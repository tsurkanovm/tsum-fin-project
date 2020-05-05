<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Observer\Incomes;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\InventoryApi\Api\Data\SourceItemInterface;
use Magento\InventoryApi\Api\Data\SourceItemInterfaceFactory;
use Magento\InventoryApi\Api\GetSourceItemsBySkuInterface;
use Magento\InventoryApi\Api\SourceItemsSaveInterface;
use Magento\InventoryApi\Api\SourceRepositoryInterface;
use Magento\InventoryCatalogApi\Api\DefaultSourceProviderInterface;
use Tsum\CashFlow\Model\Incomes;

class SetQty implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var SourceItemInterfaceFactory
     */
    private $stockItemFactory;

    /**
     * @var SourceItemsSaveInterface
     */
    private $stockSaver;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * @var DefaultSourceProviderInterface
     */
    private $defaultSourceProvider;

    /**
     * @var GetSourceItemsBySkuInterface
     */
    private $stockSkuRepo;

    public function __construct(
        SourceItemInterfaceFactory $stockItemFactory,
        SourceItemsSaveInterface $stockSaver,
        DefaultSourceProviderInterface $defaultSourceProvider,
        GetSourceItemsBySkuInterface $stockSkuRepo,
        ProductRepositoryInterface $productRepo
    ) {
        $this->stockItemFactory = $stockItemFactory;
        $this->stockSaver = $stockSaver;
        $this->defaultSourceProvider = $defaultSourceProvider;
        $this->productRepo = $productRepo;
        $this->stockSkuRepo = $stockSkuRepo;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        // temporary disabled this observer
        return $this;
        /** @var Incomes $incomes */
        $incomes = $observer->getDataObject();
        if ($incomes) {
            try {
                // @todo add Incomes interface
                $product = $this->productRepo->getById($incomes->getData('storage_id'));
                if ($stockItems = $this->stockSkuRepo->execute($product->getSku())) {
                    if ($stock = array_pop($stockItems)) {
                        $stock->setQuantity((float) $incomes->getData('total') + $stock->getQuantity());
                        $stock->setStatus(1);
                        $this->stockSaver->execute([$stock]);
                    }
                }
            } catch (\Exception $e) {
                // @todo add logger
                return $this;
            }
        }

        return $this;
    }
}
