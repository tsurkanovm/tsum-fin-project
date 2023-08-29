<?php

namespace Tsum\CashFlow\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Tsum\CashFlow\Api\Data\TransferInterface;

class Transfer extends AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('tsum_cf_transfer', 'cf_transfer_id');
    }

    /**
     * @inheritDoc
     * @throws LocalizedException
     */
    protected function _beforeSave(AbstractModel $object): AbstractDb
    {
        if ($errorMessage = $this->validate($object)) {
            throw new LocalizedException(__($errorMessage));
        }

        return $this;
    }

    private function validate(AbstractModel $object): string|false
    {
        if ($object->getData(TransferInterface::TOTAL) == 0 || $object->getData(TransferInterface::IN_TOTAL) == 0) {
            return 'Total can`t be empty!';
        }

        if (($object->getData(TransferInterface::TOTAL) <> $object->getData(TransferInterface::IN_TOTAL))
            && ($object->getData(TransferInterface::CURRENCY) == $object->getData(TransferInterface::IN_CURRENCY))) {
            return 'Total should be equal!';
        }

        if (($object->getData(TransferInterface::IN_STORAGE_ID) == $object->getData(TransferInterface::STORAGE_ID))
            && ($object->getData(TransferInterface::CURRENCY) == $object->getData(TransferInterface::IN_CURRENCY))) {
            return 'Storages can`t be equal with the same currency!';
        }

        if (($object->getData(TransferInterface::TOTAL) == $object->getData(TransferInterface::IN_TOTAL))
            && ($object->getData(TransferInterface::CURRENCY) <> $object->getData(TransferInterface::IN_CURRENCY))) {
            return 'Total can`t be equal for different currencies';
        }

        return false;
    }
}
