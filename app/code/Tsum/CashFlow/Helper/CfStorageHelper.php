<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Helper;


use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class CfStorageHelper
{
    const STORAGE_TYPE = 'Storage';
    const STORAGE_ATTRIBUTE_SET_ID = 10;

    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * CfStorageHelper constructor.
     * @param LocatorInterface $locator
     * @param ProductRepositoryInterface $productRepo
     */
    public function __construct(LocatorInterface $locator, ProductRepositoryInterface $productRepo)
    {
        $this->locator = $locator;
        $this->productRepo = $productRepo;
    }

    public function isStorage(int $id = null) : bool
    {
        $attrSet = '';
        if ($id) {
            try {
                $attrSet = $this->productRepo->getById($id)->getAttributeSetId();
            } catch (NoSuchEntityException $e) {
                return false;
            }
        } else {
            $attrSet = $this->locator->getProduct()->getAttributeSetId();
        }

        return  $attrSet == self::STORAGE_ATTRIBUTE_SET_ID;
    }
}
