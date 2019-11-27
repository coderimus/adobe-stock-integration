<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\AdobeStockImageAdminUi\Plugin;

use Magento\AdobeStockAdminUi\Model\IsAdobeStockIntegrationEnabledInterface;
use Magento\Backend\Block\Widget\Container;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\LayoutInterface;

/**
 * Plugin for media gallery block adding button to the toolbar.
 */
class AddSearchButton
{
    /**
     * Acl for images preview
     */
    private const ACL_SAVE_PREVIEW_IMAGES = 'Magento_AdobeStockImageAdminUi::save_preview_images';

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @var IsAdobeStockIntegrationEnabledInterface
     */
    private $isAdobeStockInterfaceEnabled;

    /**
     * AddSearchButton constructor.
     *
     * @param IsAdobeStockIntegrationEnabledInterface $isAdobeStockInterfaceEnabled
     * @param AuthorizationInterface $authorization
     */
    public function __construct(
        IsAdobeStockIntegrationEnabledInterface $isAdobeStockInterfaceEnabled,
        AuthorizationInterface $authorization
    ) {
        $this->isAdobeStockInterfaceEnabled = $isAdobeStockInterfaceEnabled;
        $this->authorization = $authorization;
    }

    /**
     * Add Adobe Stock Search button to the toolbar
     *
     * @param Container $subject
     * @param LayoutInterface $layout
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSetLayout(Container $subject, LayoutInterface $layout): void
    {
        if ($this->authorization->isAllowed(self::ACL_SAVE_PREVIEW_IMAGES)
            && $this->isAdobeStockInterfaceEnabled->checkStatus()
        ) {
            $subject->addButton(
                'search_adobe_stock',
                [
                    'class' => 'action-secondary',
                    'label' => __('Search Adobe Stock'),
                    'type' => 'button',
                    'onclick' => 'jQuery("#adobe-stock-images-search-modal").trigger("openModal");'
                ],
                0,
                0,
                'header'
            );
        }
    }
}
