<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AdobeStockAsset\Test\Unit\Model;

use Magento\AdobeStockAsset\Model\CategoryFactory;
use Magento\AdobeStockAsset\Model\CategoryRepository;
use Magento\AdobeStockAsset\Model\ResourceModel\Category as ResourceModel;
use Magento\AdobeStockAsset\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\AdobeStockAsset\Model\ResourceModel\Category\Command\Save;
use Magento\AdobeStockAssetApi\Api\Data\CategoryInterface;
use Magento\AdobeStockAssetApi\Api\Data\CategorySearchResultsInterfaceFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Category repository test.
 */
class CategoryRepositoryTest extends TestCase
{
    /**
     * @var MockObject|ResourceModel $resourceModel
     */
    private $resourceModel;

    /**
     * @var MockObject|CategoryCollectionFactory $categoryCollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * @var MockObject|CategoryFactory
     */
    private $categoryFactory;

    /**
     * @var MockObject|JoinProcessorInterface $joinProcessorInterface
     */
    private $joinProcessorInterface;

    /**
     * @var MockObject|CollectionProcessorInterface $collectionProcessorInterface
     */
    private $collectionProcessorInterface;

    /**
     * @var MockObject|CategorySearchResultsInterfaceFactory $categorySearchResultsInterfaceFactory
     */
    private $categorySearchResultsInterfaceFactory;

    /**
     * @var CategoryRepository $categoryRepository
     */
    private $categoryRepository;

    /**
     * @var MockObject|Save $commandSave
     */
    private $commandSave;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        $this->resourceModel = $this->createMock(ResourceModel::class);
        $this->commandSave = $this->createMock(Save::class);
        $this->categoryCollectionFactory = $this->createMock(CategoryCollectionFactory::class);
        $this->categoryFactory = $this->createMock(CategoryFactory::class);
        $this->joinProcessorInterface = $this->createMock(JoinProcessorInterface::class);
        $this->collectionProcessorInterface = $this->createMock(CollectionProcessorInterface::class);
        $this->categorySearchResultsInterfaceFactory = $this->createMock(CategorySearchResultsInterfaceFactory::class);

        $this->categoryRepository = new CategoryRepository(
            $this->resourceModel,
            $this->commandSave,
            $this->categoryCollectionFactory,
            $this->categoryFactory,
            $this->joinProcessorInterface,
            $this->collectionProcessorInterface,
            $this->categorySearchResultsInterfaceFactory
        );
    }

    /**
     * Test get list
     */
    public function testGetList(): void
    {
        /** @var MockObject|SearchCriteriaInterface $searchCriteria */
        $searchCriteria = $this->createMock(\Magento\Framework\Api\SearchCriteriaInterface::class);

        $collection = $this->createMock(\Magento\AdobeStockAsset\Model\ResourceModel\Category\Collection::class);
        $this->categoryCollectionFactory->expects($this->once())
            ->method('create')
            ->willReturn($collection);

        $this->joinProcessorInterface->expects($this->once())
            ->method('process')
            ->with($collection, CategoryInterface::class)
            ->willReturn(null);
        $this->collectionProcessorInterface->expects($this->once())
            ->method('process')
            ->with($searchCriteria, $collection)
            ->willReturn(null);
        $searchResults = $this->createMock(\Magento\AdobeStockAssetApi\Api\Data\CategorySearchResultsInterface::class);
        $this->categorySearchResultsInterfaceFactory->expects($this->once())
            ->method('create')
            ->willReturn($searchResults);
        $collection->expects($this->once())
            ->method('getItems')
            ->willReturn([]);
        $searchResults->expects($this->once())
            ->method('setItems')
            ->willReturnSelf();
        $searchResults->expects($this->once())
            ->method('setSearchCriteria')
            ->willReturnSelf();
        $searchResults->expects($this->once())
            ->method('setTotalCount')
            ->willReturnSelf();
        $this->assertEquals($searchResults, $this->categoryRepository->getList($searchCriteria));
    }

    /**
     * Test get By id.
     */
    public function testGetById(): void
    {
        $categoryMock = $this->createMock(\Magento\AdobeStockAsset\Model\Category::class);
        $this->categoryFactory->expects($this->once())
            ->method('create')
            ->willReturn($categoryMock);
        $this->resourceModel->expects($this->once())
            ->method('load')
            ->willReturnSelf();
        $categoryMock->expects($this->once())
            ->method('getId')
            ->willReturn(2);
        $this->assertInstanceOf(CategoryInterface::class, $this->categoryRepository->getById(2));
    }

    /**
     * Test get By id with exception.
     *
     * @expectedException Magento\Framework\Exception\NoSuchEntityException
     * @exceptedExceptionMessage Object with id 2 does not exist
     */
    public function testGetByIdWithException(): void
    {
        $categoryMock = $this->createMock(\Magento\AdobeStockAsset\Model\Category::class);
        $this->categoryFactory->expects($this->once())
            ->method('create')
            ->willReturn($categoryMock);
        $this->resourceModel->expects($this->once())
            ->method('load')
            ->willReturnSelf();
        $categoryMock->expects($this->once())
            ->method('getId')
            ->willReturn(null);
        $this->categoryRepository->getById(2);
    }
}
