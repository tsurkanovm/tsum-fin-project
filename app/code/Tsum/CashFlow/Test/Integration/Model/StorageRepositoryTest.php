<?php

declare(strict_types=1);

namespace Tsum\CashFlow\Test\Integration\Model;

use PHPUnit\Framework\TestCase;
use Tsum\CashFlow\Model\StorageRepository;
use Magento\TestFramework\Helper\Bootstrap;

class StorageRepositoryTest extends TestCase
{
    /**
     * @var StorageRepository
     */
    private $storageRepository;

    protected function setUp(): void
    {
        $objectManager = Bootstrap::getObjectManager();
        $this->storageRepository = $objectManager->get(StorageRepository::class);
    }

    /**
     * @magentoDataFixture Tsum/CashFlow/Test/Integration/_files/storage_with_ones_id.php
     */
    public function testGetIdByOnesIdReturnsId()
    {
        $uniqueOnesId = 555;
        // This storage was created in the fixture
        $foundId = $this->storageRepository->getIdByOnesId($uniqueOnesId);

        $this->assertNotNull($foundId, 'A storage ID should be found from fixture');
        // Optionally, check that returned ID is as expected (e.g. match DB via direct read if needed for strictness)
    }

    public function testGetIdByOnesIdReturnsNullIfNotExists()
    {
        $nonExistingOnesId = 555;
        $foundId = $this->storageRepository->getIdByOnesId($nonExistingOnesId);
        $this->assertNull($foundId, 'Method should return null when storage is not found by ONES_CODE_FIELD');
    }
}
