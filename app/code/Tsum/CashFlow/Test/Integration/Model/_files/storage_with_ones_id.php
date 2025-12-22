<?php
/** @var \Magento\Framework\ObjectManagerInterface $objectManager */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
/** @var \Tsum\CashFlow\Model\StorageFactory $storageFactory */
$storageFactory = $objectManager->get(\Tsum\CashFlow\Model\StorageFactory::class);

$storage = $storageFactory->create();
$storage->setData(\Tsum\CashFlow\Model\ConfigProvider::ONES_CODE_FIELD, 555);
$storage->setData('name', 'Test Storage');
$objectManager->get(\Tsum\CashFlow\Model\StorageRepository::class)->save($storage);
