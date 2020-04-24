<?php

namespace Tsum\CashFlow\Plugin\Quote\Model;

use Magento\Catalog\Model\Product;

class Item
{
    public function afterRepresentProduct(
        \Magento\Quote\Model\Quote\Item $subject,
        bool $result,
        Product $product
    ) {
        if ($result) {
            if ($subject->getCfItem() != $product->getExtensionAttributes()->getCfItem()) {
                return false;
            }
        }

        return $result;
    }
}
