<?php

namespace Tsum\CashFlowImport\Model\Source\Grid;

use Tsum\CashFlowImport\Api\Data\StagingInterface;

class MoveType extends \Tsum\CashFlow\Model\Source\CfItem\MoveType
{
    public function getOptionArray(): array
    {
        $options = parent::getOptionArray();
        $options[StagingInterface::TRANSFER_TYPE_ID] = __('TRS');

        return $options;
    }
}
