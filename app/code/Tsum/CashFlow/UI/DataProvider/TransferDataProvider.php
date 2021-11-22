<?php
declare(strict_types=1);

namespace Tsum\CashFlow\UI\DataProvider;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Tsum\CashFlow\Model\ResourceModel\Transfer\CollectionFactory;

class TransferDataProvider extends AbstractDataProvider
{
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var array
     */
    private $loadedData;

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

    public function getData() : ? array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $transferItems) {
            $this->loadedData[$transferItems->getId()] = $transferItems->getData();
        }

        $data = $this->dataPersistor->get('tsum_transfer');
        if (!empty($data)) {
            $storage = $this->collection->getNewEmptyItem();
            $storage->setData($data);
            $this->loadedData[$storage->getId()] = $storage->getData();
            $this->dataPersistor->clear('tsum_transfer');
        }

        return $this->loadedData;
    }
}
