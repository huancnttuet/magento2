<?php


namespace THP\Chat\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const FACEBOOK_MESSENGER_ENABLE = 'thp_chat/general/facebook_messenger_enable';
    const FACEBOOK_MESSENGER_CUSTOMER_CHAT_CODE = 'thp_chat/general/facebook_messenger_customer_chat_code';
    const TAWK_TO_WIDGET_ENABLE = 'thp_chat/general/tawk_enable';
    const TAWK_TO_WIDGET_CODE = 'thp_chat/general/tawk_widget_code';

    /**
     * Retrieve the facebook messenger status
     *
     * @return boolean
     */
    public function getFacebookMessengerStatus()
    {
        return $this->scopeConfig->isSetFlag(
            self::FACEBOOK_MESSENGER_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve the facebook messenger customer chat code
     *
     * @return string
     */
    public function getFacebookMessengerChatCode()
    {
        return $this->scopeConfig->getValue(
            self::FACEBOOK_MESSENGER_CUSTOMER_CHAT_CODE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve the tawk.to widget status
     *
     * @return boolean
     */
    public function getTawkToWidgetStatus()
    {
        return $this->scopeConfig->isSetFlag(
            self::TAWK_TO_WIDGET_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve the Tawk.to widget code
     *
     * @return string
     */
    public function getTawkToWidgetCode()
    {
        return $this->scopeConfig->getValue(
            self::TAWK_TO_WIDGET_CODE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}