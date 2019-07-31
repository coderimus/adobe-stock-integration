<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\AdobeStockAsset\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Premium level (metadata) resource model
 */
class PremiumLevel extends AbstractDb
{
    /**
     * Initialize with table name and primary field
     */
    protected function _construct()
    {
        $this->_init('adobe_stock_premium_level', 'id');
    }
}