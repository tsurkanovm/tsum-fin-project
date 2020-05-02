<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model\Source;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;
use Tsum\CashFlow\Helper\Config;

class Storage implements OptionSourceInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteria;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param ProductRepositoryInterface $productRepo
     * @param SearchCriteriaBuilder $searchCriteria
     * @param Config $config
     */
    public function __construct(
        ProductRepositoryInterface $productRepo,
        SearchCriteriaBuilder $searchCriteria,
        Config $config
    ) {
        $this->productRepo = $productRepo;
        $this->searchCriteria = $searchCriteria;
        $this->config = $config;
    }

    /**
     * @return array
     */
    private function getOptions() : array
    {
        $res = [];
        /** @var ProductInterface $item */
        foreach ($this->getStorages() as $item) {
            $res[] = ['value' => $item->getId(), 'label' => $item->getName()];
        }

        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray() : array
    {
        return $this->getOptions();
    }

    public function getStorages() : array
    {
        $this->searchCriteria->addFilter('attribute_set_id', $this->config->getStorageAttributeSetId());

        return $this->productRepo->getList($this->searchCriteria->create())->getItems();
    }
}
