<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Helper;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class CfStorageHelper
{
    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * @var Config
     */
    private $config;
    /**
     * @param LocatorInterface $locator
     * @param ProductRepositoryInterface $productRepo
     * @param Config $config
     */
    public function __construct(
        LocatorInterface $locator,
        ProductRepositoryInterface $productRepo,
        Config $config
    ) {
        $this->locator = $locator;
        $this->productRepo = $productRepo;
        $this->config = $config;
    }

    /**
     * Check if requested product is a storage
     * @param int|null $id product ID
     *
     * @return bool
     */
    public function isStorage(int $id = null) : bool
    {
        if ($id) {
            try {
                $attrSetId = $this->productRepo->getById($id)->getAttributeSetId();
            } catch (NoSuchEntityException $e) {
                return false;
            }
        } else {
            $attrSetId = $this->locator->getProduct()->getAttributeSetId();
        }

        return  $attrSetId == $this->config->getStorageAttributeSetId();
    }
}
