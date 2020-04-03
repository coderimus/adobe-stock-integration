<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AdobeStockImage\Model;

use Magento\AdobeStockImage\Model\Storage\Delete as StorageDelete;
use Magento\AdobeStockImage\Model\Storage\Save as StorageSave;
use Magento\Framework\Api\Search\Document;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\FileSystemException;

/**
 * Save asset file and retrieve its path.
 */
class RetrieveFilePathFromDocument implements RetrieveFilePathFromDocumentInterface
{
    /**
     * @var StorageSave
     */
    private $storageSave;

    /**
     * @var StorageDelete
     */
    private $storageDelete;

    /**
     * RetrieveFilePathFromDocument constructor.
     *
     * @param StorageSave $storageSave
     * @param StorageDelete $storageDelete
     */
    public function __construct(
        StorageSave $storageSave,
        StorageDelete $storageDelete
    ) {
        $this->storageSave = $storageSave;
        $this->storageDelete = $storageDelete;
    }

    /**
     * Save asset file to filesystem and return its path.
     *
     * @param Document $document
     * @param string $url
     * @param string $destinationPath
     *
     * @return string
     * @throws AlreadyExistsException
     * @throws CouldNotDeleteException
     * @throws FileSystemException
     */
    public function execute(Document $document, string $url, string $destinationPath): string
    {
        $pathAttribute = $document->getCustomAttribute('path');
        $pathValue = $pathAttribute->getValue();
        /* If the asset has been already saved, delete the previous version */
        if (null !== $pathAttribute && $pathValue) {
            $this->storageDelete->execute($pathValue);
        }

        $path = $this->storageSave->execute($url, $destinationPath);

        return $path;
    }
}