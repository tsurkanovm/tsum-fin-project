<?php

namespace Tsum\CashFlow\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Tsum\CashFlow\Model\StorageRepository;
use Tsum\CashFlow\Model\ResourceModel\Storage as ResourceStorage;
use Tsum\CashFlow\Model\StorageFactory;
use Tsum\CashFlow\Model\Storage;
use Tsum\CashFlow\Model\Config;

class StorageRepositoryTest extends TestCase
{
    /** @var ResourceStorage|\PHPUnit\Framework\MockObject\MockObject */
    private $resourceMock;

    /** @var StorageFactory|\PHPUnit\Framework\MockObject\MockObject */
    private $storageFactoryMock;

    /** @var Storage|\PHPUnit\Framework\MockObject\MockObject */
    private $storageMock;

    /** @var StorageRepository */
    private $storageRepository;

    protected function setUp(): void
    {
        $this->resourceMock = $this->createMock(ResourceStorage::class);
        $this->storageFactoryMock = $this->createMock(StorageFactory::class);
        $this->storageMock = $this->createMock(Storage::class);

        // StorageFactory returns our Storage mock
        $this->storageFactoryMock->method('create')
            ->willReturn($this->storageMock);

        // Setup dummy stubs for other deps in the constructor
        $storageCollectionFactory = $this->createMock(\Tsum\CashFlow\Model\ResourceModel\Storage\CollectionFactory::class);
        $searchResultsFactory = $this->createMock(\Tsum\CashFlow\Api\Data\StorageSearchResultsInterfaceFactory::class);
        $collectionProcessor = $this->createMock(\Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface::class);

        $this->storageRepository = new StorageRepository(
            $this->resourceMock,
            $this->storageFactoryMock,
            $storageCollectionFactory,
            $searchResultsFactory,
            $collectionProcessor
        );
    }

    public function testGetIdByOnesIdReturnsId(): void
    {
        $onesId = 123;
        $expectedId = 456;

        // Resource loads storage with onesId and field
        $this->resourceMock->expects($this->once())
            ->method('load')
            ->with($this->storageMock, $onesId, Config::ONES_CODE_FIELD);

        // Storage should return an ID
        $this->storageMock->expects($this->once())
            ->method('getId')
            ->willReturn($expectedId);

        $result = $this->storageRepository->getIdByOnesId($onesId);
        $this->assertSame($expectedId, $result);
    }

    public function testGetIdByOnesIdReturnsNullWhenNotFound(): void
    {
        $onesId = 789;

        $this->resourceMock->expects($this->once())
            ->method('load')
            ->with($this->storageMock, $onesId, Config::ONES_CODE_FIELD);

        // Storage returns null (not found)
        $this->storageMock->expects($this->once())
            ->method('getId')
            ->willReturn(null);

        $result = $this->storageRepository->getIdByOnesId($onesId);
        $this->assertNull($result);
    }
}
