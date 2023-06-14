<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Block\Quote\QuoteCheckout;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\App\ObjectManager;
use Magento\ReCaptchaUi\Model\IsCaptchaEnabledInterface;
use Magento\ReCaptchaUi\Model\UiConfigResolverInterface;
use MSP\ReCaptcha\Model\Config;
use MSP\ReCaptcha\Model\LayoutSettings;

/**
 * Class Captcha
 * @package Cart2Quote\Quotation\Block\Quote\QuoteCheckout
 */
class Captcha implements LayoutProcessorInterface
{
    /**
     * @var UiConfigResolverInterface
     */
    private $captchaUiConfigResolver;

    /**
     * @var IsCaptchaEnabledInterface
     */
    private $isCaptchaEnabled;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var LayoutSettings
     */
    private $layoutSettings;

    /**
     * Captcha constructor.
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        //Magento 2.4.x compatibility
        if (interface_exists(UiConfigResolverInterface::class)) {
            $this->captchaUiConfigResolver = $objectManager->get(UiConfigResolverInterface::class);
            $this->isCaptchaEnabled = $objectManager->get(IsCaptchaEnabledInterface::class);
        } else {
            //Magento 2.3 compatibility
            $this->layoutSettings = $objectManager->get(LayoutSettings::class);
            $this->config = $objectManager->get(Config::class);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param array $jsLayout
     * @return array
     * @throws InputException
     */
    public function process($jsLayout)
    {
        $key = 'customer_login';
        $enabledM24 = (isset($this->isCaptchaEnabled) && $this->isCaptchaEnabled->isCaptchaEnabledFor($key));
        $enabledM23 = (isset($this->config) && $this->config->isEnabledFrontend());

        $config = $this->getConfiguration($enabledM24, $enabledM23, $key);
        $authConfig = $this->getConfiguration($enabledM24, $enabledM23, $key, true);

        if ($config) {
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['quotation-fields']['children']['customer-email']['children']
            ['quote_recaptcha'] = $config;

            $jsLayout['components']['checkout']['children']['authentication']['children']
            ['quote_recaptcha'] = $authConfig;
        } else {
            if (isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
                ['quotation-fields']['children']['customer-email']['children']['quote_recaptcha'])) {
                unset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
                    ['quotation-fields']['children']['customer-email']['children']['quote_recaptcha']);
            }

            if (isset($jsLayout['components']['checkout']['children']['authentication']['children']['quote_recaptcha'])) {
                unset($jsLayout['components']['checkout']['children']['authentication']['children']['quote_recaptcha']);
            }
        }
        
        return $jsLayout;
    }

    /**
     * @param bool $enabledM24
     * @param bool $enabledM23
     * @param string $key
     * @return array|false
     */
    protected function getConfiguration($enabledM24, $enabledM23, $key, $auth = false)
    {
        $configuration = false;

        if ($enabledM24) {
            $configuration['component'] = "Magento_ReCaptchaFrontendUi/js/reCaptcha";
            $configuration['reCaptchaId'] = "recaptcha-checkout-inline-login";
            $configuration['displayArea'] = "additional-login-form-fields";
            $configuration['configSource'] = "checkoutConfig";
            $configuration['settings'] = $this->captchaUiConfigResolver->get($key);

            if ($auth) {
                $configuration['reCaptchaId'] = "recaptcha-checkout-login";
            }
        } elseif ($enabledM23) {
            $configuration['component'] = "MSP_ReCaptcha/js/reCaptcha";
            $configuration['reCaptchaId'] = "msp-recaptcha-checkout-inline-login";
            $configuration['displayArea'] = "additional-login-form-fields";
            $configuration['configSource'] = "checkoutConfig";
            $configuration['zone'] = "login";
            $configuration['settings'] = $this->layoutSettings->getCaptchaSettings();

            if ($auth) {
                $configuration['reCaptchaId'] = "msp-recaptcha-checkout-login";
                unset($configuration['zone']);
            }
        }

        return $configuration;
    }
}