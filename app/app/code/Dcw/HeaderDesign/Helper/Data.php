<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ShopbyBrand
 */


namespace Dcw\HeaderDesign\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;


class Data extends AbstractHelper
{
    /**
     * @return string
     */
    public function getPhoneNumberColor()
    {
        return $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/phone_number_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getPhoneNumberIconColor()
    {
        return $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/phone_number_icon_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getNavigationAnchorTagColor()
    {
        return $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/navigation_anchortag_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getSearchButtonColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/search_button_color',
            ScopeInterface::SCOPE_STORE
        );
    }


    /**
     * @return string
     */
    public function getSearchButtonLabelColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/search_button_label_color',
            ScopeInterface::SCOPE_STORE
        );
    }


    /**
     * @return string
     */
    public function getSearchInputColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/search_input_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getContactUsLinkColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/contactus_link_color',
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return string
     */
    public function getContactUsIconColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/contactus_icon_color',
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return string
     */
    public function getCurrencyLinkColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/currency_link_color',
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return string
     */
    public function getMyAccountLinkColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/myaccount_link_color',
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return string
     */
    public function getMyAccountIconColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/myaccount_icon_color',
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return string
     */
    public function getPhonenoColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/phone_number_color',
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return string
     */
    public function getMinicartColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/minicart_color',
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return string
     */
    public function getMinicartIconColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/minicart_icon_color',
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return string
     */
    public function getQuoteIconColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/quote_background_color',
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return string
     */
    public function getHeaderTopBackgroundColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/header_top_background_color',
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return string
     */
    public function getHeaderBackgroundColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/header_background_color',
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return string
     */
    public function getAccountDropdownColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/myaccount_dropdown_color',
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return string
     */
    public function getCurrencyDropdownColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/currency_dropdown_color',
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return string
     */
    public function getQuoteCartNotificationFontColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/quote_notification_font_color',
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return string
     */
    public function getQuoteCartNotificationBackgroundColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/quote_notification_background_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getSearchBarInputTextColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/search_input_text_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getMiniCartNotificationFontColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/minicart_notification_font_color',
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return string
     */
    public function getMiniCartNotificationBackgroundColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/minicart_notification_background_color',
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return string
     */
    public function getNavigationAnchorDropdownIconColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/navigation_anchor_dropdownicon_color',
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return string
     */
    public function getSearchInputDropshadowColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettings/searchinput_dropshadow_color',
            ScopeInterface::SCOPE_STORE
        );
    }
    /* Mobile header settings[starts here] */

    /**
     * @param $path
     * @return mixed
     */
    public function getNavigationMobileAnchorTagColor()
    {
        return $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/navigation_anchortag_color',
            ScopeInterface::SCOPE_STORE
        );
    }



    // /**
    //  * @return string
    //  */
    // public function getSearchBarInputTextMobileColor()
    // {
    //     return (string) $this->scopeConfig->getValue(
    //         'generalconfiguration/headerdesignsettingsmobile/quote_background_color',
    //         ScopeInterface::SCOPE_STORE
    //     );
    // }

    /**
     * @return string
     */
    public function getQuoteMobileIconColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/quote_background_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getQuoteCartMobileNotificationFontColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/quote_notification_font_color',
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return string
     */
    public function getQuoteCartMobileNotificationBackgroundColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/quote_notification_background_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getSearchInputMobileDropshadowColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/searchinput_dropshadow_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getCurrencyMobileDropdownColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/currency_dropdown_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getHeaderMobileTopBackgroundColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/header_top_background_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getHeaderMobileBackgroundColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/header_background_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getMinicartDropdownIconMobileColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/minicart_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getSearchButtonMobileColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/search_button_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getPhoneNumberMobileColor()
    {
        return $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/phone_number_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getCurrencyLinkMobileColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/currency_link_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getContactUsIconMobileColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/contactus_icon_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getMagnifyingGlassIconColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/magnifying_glassicon_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getPhoneNoIconColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/phone_number_icon_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getContactUsLinkMobileColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/contactus_link_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getSearchInputMobileColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/search_input_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getSearchBarInputTextMobileColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/search_mobile_input_text_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getSearchButtonLabelMobileColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/search_button_label_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getNavigationAnchorDropdownIconMobileColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/navigation_anchor_dropdownicon_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getMinicartNotificationBackgroundMobileColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/minicart_notification_background_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getMinicartNotificationFontMobileColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/minicart_notification_font_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getAccountLinksMobileColor()
    {
        return (string) $this->scopeConfig->getValue(
            'generalconfiguration/headerdesignsettingsmobile/account_links_color',
            ScopeInterface::SCOPE_STORE
        );
    }

    /* Mobile header settings[ends here] */
}
