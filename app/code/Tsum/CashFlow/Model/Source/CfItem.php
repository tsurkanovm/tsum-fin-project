<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Tsum\CashFlow\Model\ResourceModel\CfItem\Collection;
use Tsum\CashFlow\Model\ResourceModel\CfItem\CollectionFactory;

class CfItem implements OptionSourceInterface
{
    const ALL_TYPES = 'ALL';
    const IN_TYPE = 'IN';
    const OUT_TYPE = 'OUT';

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var string
     */
    private $cfType;

    /**
     * @param CollectionFactory $collectionFactory
     * @param string $cfType
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        string $cfType = self::ALL_TYPES
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->cfType = $cfType;
    }

    /**
     * Get Grid row type array for option element.
     * @return array
     */
    private function getOptions() : array
    {
        $res = [];
        foreach ($this->getCfItems() as $item) {
            $res[] = ['value' => $item['cf_item_id'], 'label' => $item['title']];
        }

        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getOptions();
    }

    public function getCfItems() : array
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFilter('is_active', 1);
        switch ($this->cfType) {
            case self::IN_TYPE:
                $collection->addFilter('type_id', 0);
                break;
            case self::OUT_TYPE:
                $collection->addFilter('type_id', 1);
                break;
        }

        return $collection->getData();
    }
}
