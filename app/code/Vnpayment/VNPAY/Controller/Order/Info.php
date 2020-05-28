<?php

namespace Vnpayment\VNPAY\Controller\Order;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\ScopeInterface;

class Info extends \Magento\Framework\App\Action\Action {

    protected $resultPageFactory;
    protected $jsonFac;

    /** @var  \Magento\Sales\Model\Order */
    protected $order;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig */
    protected $scopeConfig;

    /** @var  \Magento\Store\Model\StoreManagerInterface */
    protected $storeManager;

    /** @var  \Magento\Checkout\Model\Session */
    protected $checkoutSession;

    public function __construct(
    Context $context, PageFactory $resultPageFactory, \Magento\Framework\Controller\Result\Json $json, \Magento\Sales\Model\Order $order, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Checkout\Model\Session $checkoutSession
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonFac = $json;
        $this->order = $order;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->checkoutSession = $checkoutSession;
    }

    public function execute() {
        $id = $this->getRequest()->getParam('order_id', 0);
        $order = $this->order->load(intval($id));
        $url = $this->scopeConfig->getValue('payment/vnpay/payment_url');
        if ($order->getId()) {
            $incrementID = $order->getIncrementId();


            $returnUrl = $this->storeManager->getStore()->getBaseUrl();
            $returnUrl = rtrim($returnUrl, "/");
            $returnUrl .= "/paymentvnpay/order/pay";
            $inputData = array(
                "vnp_Version" => "2.0.0",
                "vnp_TmnCode" => $this->scopeConfig->getValue('payment/vnpay/tmn_code'),
                "vnp_Amount" => round($order->getTotalDue() * 100, 0),
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],
                "vnp_Locale" => 'vn',
                "vnp_OrderInfo" => $incrementID,
                "vnp_OrderType" => 'other',
                "vnp_ReturnUrl" => $returnUrl,
                "vnp_TxnRef" => $incrementID,
            );
            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . $key . "=" . $value;
                } else {
                    $hashdata .= $key . "=" . $value;
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $url . "?" . $query;
            $SECURE_SECRET = $this->scopeConfig->getValue('payment/vnpay/hash_code');
            if (isset($SECURE_SECRET)) {
                $vnpSecureHash = hash('sha256', $SECURE_SECRET . $hashdata);
                $vnp_Url .= 'vnp_SecureHashType=SHA256&vnp_SecureHash=' . $vnpSecureHash;
            }
        }
        $this->jsonFac->setData($vnp_Url);
        return $this->jsonFac;
    }

}
