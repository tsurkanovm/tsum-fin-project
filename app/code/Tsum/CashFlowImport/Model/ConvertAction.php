<?php

namespace Tsum\CashFlowImport\Model;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Tsum\CashFlow\Api\Data\IncomesInterface;
use Tsum\CashFlow\Api\IncomesRepositoryInterface;
use Tsum\CashFlow\Api\TransferRepositoryInterface;
use Tsum\CashFlow\Model\IncomesFactory;
use Tsum\CashFlow\Model\TransferFactory;
use Tsum\CashFlowImport\Api\Data\StagingInterface;
use Tsum\CashFlowImport\Api\StagingRepositoryInterface;

class ConvertAction
{
    public const array COMMON_REQUIRED_FIELDS = [
        'storage_id',
        'type_id',
        'total',
        'currency',
        'registration_time',
    ];

    public const array INCOMES_REQUIRED_FIELDS = [
        'cf_item_id',
    ];

    public const array TRANSFERS_REQUIRED_FIELDS = [
        'storage_id_in',
        'total_in',
        'currency_in',
    ];

    public function __construct(
        private readonly StagingRepositoryInterface $stagingRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly FilterBuilder $filterBuilder,
        private readonly FilterGroupBuilder $filterGroupBuilder,
        private readonly SortOrderBuilder $sortOrderBuilder,
        private readonly IncomesFactory $incomesFactory,
        private readonly TransferFactory $transferFactory,
        private readonly IncomesRepositoryInterface $incomesRepository,
        private readonly TransferRepositoryInterface $transferRepository,
    ) {
    }

    /**
     * @throws LocalizedException
     */
    public function convert(): int
    {
        $this->validate();
        $amount = $this->convertToIncomes();
        $amount += $this->convertToTransfers();

        return $amount;
    }

    /**
     * @throws LocalizedException
     */
    private function validate(): void
    {
        $requiredIncomesFields = array_merge(self::COMMON_REQUIRED_FIELDS, self::INCOMES_REQUIRED_FIELDS);
        $requiredTransferFields = array_merge(self::COMMON_REQUIRED_FIELDS, self::TRANSFERS_REQUIRED_FIELDS);

        $incomeDocTypeFilter = $this->getTypeFilter(StagingInterface::TRANSFER_TYPE_ID, 'neq');
        $transDocTypeFilter = $this->getTypeFilter(StagingInterface::TRANSFER_TYPE_ID);

        $this->validateRequiredFields($requiredIncomesFields, $incomeDocTypeFilter);
       // $this->validateRequiredFields($requiredTransferFields, $transDocTypeFilter);
    }

    /**
     * @param array<string> $requiredFields
     * @throws LocalizedException
     */
    private function validateRequiredFields(array $requiredFields, Filter $docTypeFilter): void
    {
        $errors = [];
        $filterGroups = $emptyFilter = [];

        $filterGroups[] = $this->filterGroupBuilder->setFilters([$this->getIsActiveFilter()])->create();
        $filterGroups[] = $this->filterGroupBuilder->setFilters([$docTypeFilter])->create();

        foreach ($requiredFields as $field) {
            $emptyFilter[] = $this->filterBuilder->setField($field)->setConditionType('null')->create();

            if ($field !== 'type_id') { // type_id can be 0 that convert to ''
                $emptyFilter[] = $this->filterBuilder
                    ->setField($field)
                    ->setValue('')
                    ->setConditionType('eq')
                    ->create();
            }

            if ($field === 'total' || $field === 'total_in') {
                $emptyFilter[] = $this->filterBuilder
                    ->setField($field)
                    ->setValue('0')
                    ->setConditionType('lt')
                    ->create();
            }
        }

        $emptyFilterGroup = $this->filterGroupBuilder->setFilters($emptyFilter)->create();
        $filterGroups[] = $emptyFilterGroup;

        $searchCriteria = $this->searchCriteriaBuilder
            // @phpstan-ignore-next-line
            ->setFilterGroups($filterGroups)
            ->create();

        $validationResult = $this->stagingRepository->getList($searchCriteria);

        foreach ($validationResult->getItems() as $item) {
            $errors[] = $item->getId();
        }

        if ($errors) {
            throw new LocalizedException(
                __('Validation failed. Please check next items - ' . implode(', ', $errors))
            );
        }
    }

    public function getIsActiveFilter(): Filter
    {
        return $this->filterBuilder
            ->setField(StagingInterface::IS_ACTIVE)
            ->setValue('1')
            ->setConditionType('eq')
            ->create();
    }

    private function getTypeFilter(int $type, string $condition = 'eq'): Filter
    {
        return $this->filterBuilder
            ->setField(StagingInterface::TYPE_ID)
            ->setValue((string)$type)
            ->setConditionType($condition)
            ->create();
    }

    public function getCurrentKey(StagingInterface $item): string
    {
        return
            $item->getTypeId() . '_' . $item->getStorageId() . '_' . $item->getCfItemId() .
            '_' . $item->getProjectId() . '_' . $item->getCurrency();
    }

    /**
     * @throws CouldNotSaveException
     */
    private function saveAsIncomeDocument(?StagingInterface $currentItem, float|int $total): void
    {
        if (!$currentItem || !$total) {
            return;
        }

        /* @var IncomesInterface $incomes **/
        $incomes = $this->incomesFactory->create(['data' => $currentItem->getData()]);
        $incomes->setTotal(round($total));
        $incomes->setCommentary('');

        // @todo possibly we need to catch exception to allow go further and show errors after
        $this->incomesRepository->save($incomes);
    }

    /**
     * @return int
     * @throws CouldNotSaveException
     * @throws CouldNotDeleteException
     */
    public function convertToIncomes(): int
    {
        $filterGroups = [];
        // @todo include project to key
        $docTypeFilter = $this->getTypeFilter(StagingInterface::TRANSFER_TYPE_ID, 'neq');
        $filterGroups[] = $this->filterGroupBuilder->setFilters([$this->getIsActiveFilter()])->create();
        $filterGroups[] = $this->filterGroupBuilder->setFilters([$docTypeFilter])->create();

        $typeSortOrder = $this->sortOrderBuilder
            ->setField(StagingInterface::TYPE_ID)
            ->setAscendingDirection()
            ->create();
        $storageSortOrder = $this->sortOrderBuilder
            ->setField(StagingInterface::STORAGE_ID)
            ->setAscendingDirection()
            ->create();
        $cfItemSortOrder = $this->sortOrderBuilder
            ->setField(StagingInterface::CF_ITEM_ID)
            ->setAscendingDirection()
            ->create();
        $currencySortOrder = $this->sortOrderBuilder
            ->setField(StagingInterface::CURRENCY)
            ->setAscendingDirection()
            ->create();

        // @phpstan-ignore-next-line
        $this->searchCriteriaBuilder->setFilterGroups($filterGroups);
        $this->searchCriteriaBuilder->setSortOrders(
            [$typeSortOrder, $storageSortOrder, $cfItemSortOrder, $currencySortOrder]
        );

        $result = $this->stagingRepository->getList($this->searchCriteriaBuilder->create());

        $key = '';
        $currentItem = null;
        $total = $amount = 0;
        /** @var StagingInterface $item */
        foreach ($result->getItems() as $item) {
            $currentKey = $this->getCurrentKey($item);
            if (!$key) {
                $key = $currentKey;
            }

            if ($key != $currentKey && $total) {
                $this->saveAsIncomeDocument($currentItem, $total);
                $key = $currentKey;
                $total = 0;
                $amount++;
            }

            $total += $item->getTotal();
            $currentItem = $item;

            $this->stagingRepository->delete($item);
        }

        return $amount;
    }

    /**
     * @throws CouldNotDeleteException
     * @throws CouldNotSaveException
     */
    private function convertToTransfers(): int
    {
        $amount = 0;
        $filterGroups = [];
        $docTypeFilter = $this->getTypeFilter(StagingInterface::TRANSFER_TYPE_ID);
        $filterGroups[] = $this->filterGroupBuilder->setFilters([$this->getIsActiveFilter()])->create();
        $filterGroups[] = $this->filterGroupBuilder->setFilters([$docTypeFilter])->create();

        // @phpstan-ignore-next-line
        $this->searchCriteriaBuilder->setFilterGroups($filterGroups);

        $result = $this->stagingRepository->getList($this->searchCriteriaBuilder->create());
        /** @var StagingInterface $item */
        foreach ($result->getItems() as $item) {
            $this->saveAsTransferDocument($item);
            $this->stagingRepository->delete($item);
            $amount++;
        }

        return $amount;
    }

    /**
     * @throws CouldNotSaveException
     */
    private function saveAsTransferDocument(StagingInterface $stageItem): void
    {
        $stageData =  $stageItem->getData();

        // if set by this way Abstract Model won`t save it, cause it think that data has not changed,
        // needs to change something manually in model
        $transfer = $this->transferFactory->create(['data' => $stageData]);
        $transfer->setStorage((int)$stageItem->getStorageId());
        $transfer->setTotal((float)$stageItem->getTotal());
        $transfer->setCurrency((string)$stageItem->getCurrency());

        // @todo possibly we need to catch exception to allow go further and show errors after
        $this->transferRepository->save($transfer);
    }
}
