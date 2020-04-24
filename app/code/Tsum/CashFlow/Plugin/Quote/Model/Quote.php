<?php

namespace Tsum\CashFlow\Plugin\Quote\Model;

use Magento\Catalog\Model\Product;
use Magento\Quote\Model\Quote\Item;

class Quote
{
    public function beforeAddProduct(
        \Magento\Quote\Model\Quote $subject,
        Product $product,
        $request
    ) {
        if ($request instanceof \Magento\Framework\DataObject
            && $request->hasData('cf_item')) {
            $extensionAttributes = $product->getExtensionAttributes();
            $extensionAttributes->setCfItem($request->getCfItem());
            $product->setExtensionAttributes($extensionAttributes);
        }

        return [$product, $request];
    }

    public function afterAddProduct(
        \Magento\Quote\Model\Quote $subject,
        Item $result,
        Product $product,
        $request
    ) {
        if ($request instanceof \Magento\Framework\DataObject
        && $request->hasData('cf_item')) {
            $result->setCfItem($request->getCfItem());
        }

        return $result;
    }
}
