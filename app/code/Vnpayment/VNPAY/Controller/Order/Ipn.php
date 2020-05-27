<?php

namespace Vnpayment\VNPAY\Controller\Order;

use Magento\Framework\App\Action\Context;

class Ipn extends \Magento\Framework\App\Action\Action {

    /** @var  \Magento\Sales\Model\Order */
    protected $order;

    /** @var  \Magento\Checkout\Model\Session */
    protected $checkoutSession;

    /** @var  \Magento\Framework\App\Config\ScopeConfigInterface */
    protected $scopeConfig;

    public function __construct(Context $context, \Magento\Sales\Model\Order $order, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->order = $order;
        $this->checkoutSession = $checkoutSession;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Order success action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute() {
        $vnp_SecureHash = $this->getRequest()->getParam('vnp_SecureHash', '');
        $SECURE_SECRET = $this->scopeConfig->getValue('payment/vnpay/hash_code');
        $responseParams = $this->getRequest()->getParams();
        $vnp_ResponseCode = $this->getRequest()->getParam('vnp_ResponseCode', '');
        $inputData = array();
        foreach ($responseParams as $key => $value) {
            $inputData[$key] = $value;
        }
        unset($inputData['vnp_SecureHashType']);
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . $key . "=" . $value;
            } else {
                $hashData = $hashData . $key . "=" . $value;
                $i = 1;
            }
        }
        $returnData = array();
        $secureHash =hash('sha256', $SECURE_SECRET . $hashData);
        try {
            if ($secureHash == $vnp_SecureHash) {
                $vnp_TxnRef = $this->getRequest()->getParam('vnp_TxnRef', '000000000');
                $order = $this->order->loadByIncrementId($vnp_TxnRef);
                if ($order->getId()) {
                    if ($order->getStatus() != NULL && $order->getStatus() == 'pending') {

                        if ($vnp_ResponseCode == '00') {
                            if ($this->checkoutSession->getLastOrderId() == $order->getId()) {
                                $amount = $this->getRequest()->getParam('vnp_Amount', '0');
                                $order->setTotalPaid(floatval($amount) / 100);
                                $orderState = $order::STATE_PROCESSING;
                                $order->setState($orderState)->setStatus($order::STATE_PROCESSING);
                                $order->save();
                                //$isSuccess = true;
                            }
                        } else {
                            if ($this->checkoutSession->getLastOrderId() == $order->getId()) {
                                $amount = $this->getRequest()->getParam('vnp_Amount', '0');
                                $order->setTotalPaid(floatval($amount) / 100);
                                $order->addStatusHistoryComment('Giao dịch thất bại');
                                $orderState = $order::STATE_CLOSED;
                                $order->setState($orderState)->setStatus($order::STATE_CLOSED);
                                $order->save();
                                //$isSuccess = false;
                            }
                        }
                        $returnData['RspCode'] = '00';
                        $returnData['Message'] = 'Confirm Success';
                    } else {
                        $returnData['RspCode'] = '02';
                        $returnData['Message'] = 'Order already confirmed';
                    }
                } else {
                    $returnData['RspCode'] = '01';
                    $returnData['Message'] = 'Order not found';
                }
            } else {
                $returnData['RspCode'] = '97';
                $returnData['Message'] = 'Chu ky khong hop le';
            }
        } catch (Exception $e) {
            $returnData['RspCode'] = '99';
            $returnData['Message'] = 'Unknow error';
        }
//Trả lại VNPAY theo định dạng JSON
        echo json_encode($returnData);
    }

}
