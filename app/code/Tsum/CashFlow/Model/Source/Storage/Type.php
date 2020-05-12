<?php
declare(strict_types=1);
namespace Tsum\CashFlow\Model\Source\Storage;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Data\OptionSourceInterface;
use Tsum\CashFlow\Model\ResourceModel\StorageType\Collection as TypeCollection;
use Tsum\CashFlow\Model\ResourceModel\StorageType\CollectionFactory;

class Type extends AbstractSource implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    private $typeCollectionFactory;

    /**
     * @param CollectionFactory $typeCollectionFactory
     */
    public function __construct(CollectionFactory $typeCollectionFactory)
    {
        $this->typeCollectionFactory = $typeCollectionFactory;
    }

    /**
     * @param bool $withEmpty
     * @param bool $defaultValues
     * @return array
     */
    public function getAllOptions($withEmpty = true, $defaultValues = false) : array
    {
        if (!$this->_options) {
            /** @var  TypeCollection $collection */
            $collection = $this->typeCollectionFactory->create();
            $this->_options = $collection->load()->toOptionArray();
        }

        return $this->_options;
    }
}
