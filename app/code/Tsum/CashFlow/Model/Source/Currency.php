<?php

namespace Tsum\CashFlow\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Currency implements OptionSourceInterface
{
    /**
     * @return array
     */
    public function getOptionArray(): array
    {
        return ['UAH' => __('UAH'),
            'USD' => __('USD'),
            'EUR' => __('EUR')
        ];
    }

    /**
     * @return array
     */
    public function getAllOptions(): array
    {
        $res = $this->getOptions();
        array_unshift($res, ['value' => '', 'label' => '']);

        return $res;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        $res = [];
        foreach ($this->getOptionArray() as $index => $value) {
            $res[] = ['value' => $index, 'label' => $value];
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
}
