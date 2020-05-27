define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'vnpay',
                component: 'Vnpayment_VNPAY/js/view/payment/method-renderer/vnpay-method'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);
