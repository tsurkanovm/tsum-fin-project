<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tsum\CashFlow\UI\DataProvider;

use Magento\Framework\App\Request\DataPersistorInterface;
use Tsum\CashFlow\Model\ResourceModel\Storage\CollectionFactory;

/**
 * Class DataProvider
 */
class StorageDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $storageItem) {
            $this->loadedData[$storageItem->getId()] = $storageItem->getData();
        }

        $data = $this->dataPersistor->get('tsum_cash_storage');
        if (!empty($data)) {
            $storage = $this->collection->getNewEmptyItem();
            $storage->setData($data);
            $this->loadedData[$storage->getId()] = $storage->getData();
            $this->dataPersistor->clear('tsum_cash_storage');
        }

        return $this->loadedData;
    }
}
