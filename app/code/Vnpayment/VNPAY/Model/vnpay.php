<?php

namespace Vnpayment\VNPAY\Model;

/**
 * Class VNPAY
 *
 * @method \Magento\Quote\Api\Data\PaymentMethodExtensionInterface getExtensionAttributes()
 */
class VNPAY extends \Magento\Payment\Model\Method\AbstractMethod
{
    const PAYMENT_METHOD_VNPAY_CODE = 'vnpay';

    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD_VNPAY_CODE;

    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = true;


}
