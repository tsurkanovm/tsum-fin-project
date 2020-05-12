<?php
declare(strict_types=1);
namespace Tsum\CashFlow\Model\Source\Storage;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Data\OptionSourceInterface;
use Tsum\CashFlow\Model\Storage;

class Currency extends AbstractSource implements OptionSourceInterface
{
    public function getAllOptions() : array
    {
        return [
            ['label' => __('hrn'), 'value' => Storage::UAH_CODE],
            ['label' => __('usd'), 'value' => Storage::USD_CODE],
            ['label' => __('eur'), 'value' => Storage::EUR_CODE]
        ];
    }
}
