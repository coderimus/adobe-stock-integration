<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\MediaGalleryUi\Console\Command;

use Magento\MediaGalleryUi\Model\ImagesIndexer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Scan media directory for media gallery asset and write their parameters to database
 */
class IndexAssets extends Command
{
    /**
     * @var ImagesIndexer
     */
    protected $imagesIndexer;

    /**
     * @param ImagesIndexer $imagesIndexer
     */
    public function __construct(
        ImagesIndexer $imagesIndexer
    ) {
        $this->imagesIndexer = $imagesIndexer;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('media-gallery:index');
        $this->setDescription('Scan media directory for media gallery asset and write their parameters to database');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Uploading assets information from media directory to database...');
        $this->imagesIndexer->execute();
        $output->writeln('Completed assets indexing.');
    }
}
