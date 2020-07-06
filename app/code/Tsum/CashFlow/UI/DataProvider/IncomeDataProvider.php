<?php
declare(strict_types=1);

namespace Tsum\CashFlow\UI\DataProvider;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Tsum\CashFlow\Model\ResourceModel\Incomes\CollectionFactory;

class IncomeDataProvider extends AbstractDataProvider
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
        foreach ($items as $incomeItems) {
            $this->loadedData[$incomeItems->getId()] = $incomeItems->getData();
        }

        $data = $this->dataPersistor->get('tsum_incomes');
        if (!empty($data)) {
            $storage = $this->collection->getNewEmptyItem();
            $storage->setData($data);
            $this->loadedData[$storage->getId()] = $storage->getData();
            $this->dataPersistor->clear('tsum_incomes');
        }

        return $this->loadedData;
    }
}
