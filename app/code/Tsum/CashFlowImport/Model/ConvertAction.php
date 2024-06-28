<?php

namespace Tsum\CashFlowImport\Model;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Tsum\CashFlow\Api\Data\CfItemInterface;
use Tsum\CashFlow\Api\Data\IncomesInterface;
use Tsum\CashFlow\Api\IncomesRepositoryInterface;
use Tsum\CashFlow\Model\Incomes;
use Tsum\CashFlow\Model\IncomesFactory;
use Tsum\CashFlowImport\Api\Data\StagingInterface;
use Tsum\CashFlowImport\Api\StagingRepositoryInterface;
use Tsum\CashFlowImport\Model\ResourceModel\Staging\CollectionFactory;

class ConvertAction
{
    public const COMMON_REQUIRED_FIELDS = [
        'storage_id',
        'type_id',
        'total',
        'currency',
        'registration_time',
    ];

    public const INCOMES_REQUIRED_FIELDS = [
        'cf_item_id',
    ];

    public const TRANSFERS_REQUIRED_FIELDS = [
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
        // @phpstan-ignore-next-line
        private readonly IncomesRepositoryInterface $incomesRepository,
    ) {
    }

    /**
     * @throws LocalizedException
     */
    public function convert(): void
    {
        $this->validate();

        // @todo include project to key
        $activeFilter = $this->getIsActiveFilter();
        $docTypeFilter = $this->getTypeFilter(StagingInterface::TRANSFER_TYPE_ID, 'neq');
        $filterGroup = $this->filterGroupBuilder->setFilters([$activeFilter, $docTypeFilter])->create();

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
        $this->searchCriteriaBuilder->setFilterGroups([$filterGroup])->create();
        $this->searchCriteriaBuilder->setSortOrders(
            [$typeSortOrder, $storageSortOrder, $cfItemSortOrder, $currencySortOrder]
        );

        $result = $this->stagingRepository->getList($this->searchCriteriaBuilder->create());

        $key = '';
        $currentItem = null;
        $total = 0;
        /** @var StagingInterface $item*/
        foreach ($result->getItems() as $item) {
            $currentKey = $this->getCurrentKey($item);
            if (!$key) {
                $key = $currentKey;
            }

            if ($key != $currentKey && $total) {
                //$this->convertToIncomes($currentItem, $total);
                $key = $currentKey;
            } else {
                $total += $item->getTotal();
            }

            $currentItem = $item;
        }

        //@todo DELETE
    }

    /**
     * @throws LocalizedException
     */
    private function validate(): void
    {
        // @todo add checkup on total and total_in > 0
        $requiredIncomesFields = array_merge(self::COMMON_REQUIRED_FIELDS, self::INCOMES_REQUIRED_FIELDS);
        $requiredTransferFields = array_merge(self::COMMON_REQUIRED_FIELDS, self::TRANSFERS_REQUIRED_FIELDS);

        $incomeDocTypeFilter = $this->getTypeFilter(StagingInterface::TRANSFER_TYPE_ID, 'neq');
        $transDocTypeFilter = $this->getTypeFilter(StagingInterface::TRANSFER_TYPE_ID);

        $this->validateRequiredFields($requiredIncomesFields, $incomeDocTypeFilter);
        $this->validateRequiredFields($requiredTransferFields, $transDocTypeFilter);
    }

    /**
     * @param array<string> $requiredFields
     * @throws LocalizedException
     */
    private function validateRequiredFields(array $requiredFields, Filter $docTypeFilter): void
    {
        $errors = [];
        $filterGroups = [];

        $commonFilters = [$this->getIsActiveFilter(), $docTypeFilter];

        foreach ($requiredFields as $field) {
            $nullFilter = $this->filterBuilder->setField($field)->setConditionType('null')->create();
            $nullFilterGroup = $this->filterGroupBuilder->setFilters([...$commonFilters, $nullFilter])->create();

            $emptyFilter = $this->filterBuilder
                ->setField($field)
                ->setValue('')
                ->setConditionType('eq')
                ->create();
            $emptyFilterGroup = $this->filterGroupBuilder->setFilters([...$commonFilters, $emptyFilter])->create();

            $filterGroups[] = $nullFilterGroup;
            $filterGroups[] = $emptyFilterGroup;
        }

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
                __('Validation failed. Please check next items - ' . implode($errors))
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
            $item->getTypeId() . '_' . $item->getStorageId() . '_' . $item->getCfItemId() . '_' . $item->getCurrency();
    }

//    /**
//     * @throws CouldNotSaveException
//     */
//    private function convertToIncomes(?StagingInterface $currentItem, float|int $total): void
//    {
//        if (!$currentItem || !$total) {
//            return;
//        }
//
//        /* @var IncomesInterface $incomes **/
//        $incomes = $this->incomesFactory->create(['data' => $currentItem->getData()]);
//        $incomes->setTotal($total);
//        // @todo possibly we need to catch exception to allow go further and show errors after
//        $this->incomesRepository->save($incomes);
//    }
}
