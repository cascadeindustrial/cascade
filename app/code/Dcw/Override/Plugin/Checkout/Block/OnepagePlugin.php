<?php

declare(strict_types=1);

namespace Dcw\Override\Plugin\Checkout\Block;

use Amasty\Checkout\Model\Quote\CheckoutInitialization;
use Magento\Checkout\Block\Onepage;
use Magento\Checkout\Model\Session;

/**
 * @since 3.0.5
 */
class OnepagePlugin extends \Amasty\Checkout\Plugin\Checkout\Block\OnepagePlugin
{
    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var CheckoutInitialization
     */
    private $checkoutInitialization;

    public function __construct(
        Session $checkoutSession,
        CheckoutInitialization $checkoutInitialization
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->checkoutInitialization = $checkoutInitialization;
    }

    /**
     * Set initial quote value
     *
     * @param Onepage $subject
     */
    public function beforeGetJsLayout(Onepage $subject)
    {
         $logFile='/var/log/onepagepluginoverride.log';
    $writer = new \Zend\Log\Writer\Stream(BP . $logFile);
    $logger = new \Zend\Log\Logger();
    $logger->addWriter($writer);
    $logger->info("in overrided file frontend");
        if ($this->checkoutSession->getQuote()->getId()) {
            $this->checkoutInitialization->initializeShipping($this->checkoutSession->getQuote());
        }

    }
}