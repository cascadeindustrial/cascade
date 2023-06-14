<?php
namespace MageMaclean\MyShipping\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use MageMaclean\MyShipping\Model\Carrier;
use MageMaclean\MyShipping\Api\Data\CourierInterface;

class Data extends AbstractHelper
{
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
    }

    public function isEnabled($storeId = null)
    {
        if(!$this->scopeConfig->isSetFlag('myshipping/general/enabled')) return false;

        return $this->scopeConfig->isSetFlag('carriers/' . Carrier::CODE . '/active');
    }

    public function getConfigData($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            'carriers/' . Carrier::CODE . '/' . $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getSystemConfigData($path, $storeId = null) {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getAccountValidationConfig() {
        $result = [
            'required-entry' => true,
            'minlength' => $this->getSystemConfigData("myshipping/account_validation/min_length"),
            'maxlength' => $this->getSystemConfigData("myshipping/account_validation/max_length"),
        ];
        if($additionalRules = $this->getSystemConfigData("myshipping/account_validation/rules")) {
            $additionalRules = explode(",", $additionalRules);
            foreach($additionalRules as $rule) {
                $result[$rule] = true;
            }
        }
        return $result;
    }

    public function getAccountValidationClasses() {
        $result = [
            'required',
            'minimum-length-' . $this->getSystemConfigData("myshipping/account_validation/min_length"),
            'maximum-length-' . $this->getSystemConfigData("myshipping/account_validation/max_length"),
            'min-text-length-' . $this->getSystemConfigData("myshipping/account_validation/min_length"),
            'max-text-length-' . $this->getSystemConfigData("myshipping/account_validation/max_length"),
        ];
        if($additionalRules = $this->getSystemConfigData("myshipping/account_validation/rules")) {
            $additionalRules = explode(",", $additionalRules);
            foreach($additionalRules as $rule) {
                $result[] = $rule;
            }
        }
        return implode(" ", $result);
    }

    public function getMyshippingInfo($myshippingType) {
        $myshippingInfo = '';
        if($myshippingType == 'new') {
            $myshippingInfo = $this->getSystemConfigData("myshipping/checkout/info_new");
        } else if($myshippingType == 'account') {
            $myshippingInfo = $this->getSystemConfigData("myshipping/checkout/info_accounts");
        }
        return $myshippingInfo;
    }

    public function isMyshippingMethod($method) {
        $carrierCode = explode("_", $method)[0];
        return $carrierCode == Carrier::CODE;
    }

    public function isShippingMethodNew($method) {
        return $method == Carrier::CODE_NEW;
    }

    public function isShippingMethodAccount($method) {
        $accountCode = Carrier::CODE . '_account_';
        return substr($method, 0, strlen($accountCode)) == $accountCode;
    }

    public function getMaskString($mask, $courier, $account, $method = false) {
        $result = $mask;
        $result = str_replace("{{courier}}", $courier, $result);
        $result = str_replace("{{account}}", $account, $result);

        if($method)
            $result = str_replace("{{method}}", $method, $result);
        
        
        return $result;
    }

    public function canUseCourier(CourierInterface $courier, $countryId) {
        if(!$courier->getSallowspecific()) {
            return true;
        }

        $availableCountries = explode(',', $courier->getSpecificcountry());
        return in_array($countryId, $availableCountries);
    }
}
