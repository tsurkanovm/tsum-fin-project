<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
/** @var \Tsum\CashFlow\Model\ResourceModel\Storage\CollectionFactory $collectionFactory */
$collectionFactory = $objectManager->get(\Tsum\CashFlow\Model\ResourceModel\Storage\CollectionFactory::class);
$collection = $collectionFactory->create();

foreach ($collection as $storage) {
    $storage->delete();
}
