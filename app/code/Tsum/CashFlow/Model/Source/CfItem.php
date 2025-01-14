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


    public function __construct(
        private readonly CollectionFactory $collectionFactory,
        private readonly string            $cfType = self::ALL_TYPES,
        private readonly bool              $onlyActive = false,
    ) {
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
    public function toOptionArray(): array
    {
        return $this->getOptions();
    }

    public function getCfItems() : array
    {
        $collection = $this->collectionFactory->create();
        if ($this->onlyActive) {
            $collection->addFilter('is_active', 1);
        }

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
