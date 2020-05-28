<?php

namespace Vnpayment\VNPAY\Controller\Order;

use Magento\Framework\App\Action\Context;

class Pay extends \Magento\Framework\App\Action\Action {

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

        $secureHash = hash('sha256', $SECURE_SECRET . $hashData);
        if ($secureHash == $vnp_SecureHash) {
            if ($vnp_ResponseCode == '00') {
                $this->messageManager->addSuccess('Thanh toán thành công qua VNPAY');
                return $this->resultRedirectFactory->create()->setPath('checkout/onepage/success');
            } else {
                $this->messageManager->addError('Thanh toán qua VNPAY thất bại. ' . $this->getResponseDescription($vnp_ResponseCode));
                return $this->resultRedirectFactory->create()->setPath('checkout/onepage/failure');
            }
        } else {
             $this->messageManager->addError('Thanh toán qua VNPAY thất bại.');
             return $this->resultRedirectFactory->create()->setPath('checkout/onepage/failure');
        }
    }

    public function getResponseDescription($responseCode) {

        switch ($responseCode) {
            case "00" :
                $result = "Giao dịch thành công";
                break;
            case "01" :
                $result = "Giao dịch đã tồn tại";
                break;
            case "02" :
                $result = "Merchant không hợp lệ (kiểm tra lại vnp_TmnCode)";
                break;
            case "03" :
                $result = "Dữ liệu gửi sang không đúng định dạng";
                break;
            case "04" :
                $result = "Khởi tạo GD không thành công do Website đang bị tạm khóa";
                break;
            case "05" :
                $result = "Giao dịch không thành công do: Quý khách nhập sai mật khẩu quá số lần quy định. Xin quý khách vui lòng thực hiện lại giao dịch";
                break;
            case "06" :
                $result = "Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP). Xin quý khách vui lòng thực hiện lại giao dịch.";
                break;
            case "07" :
                $result = "Giao dịch bị nghi ngờ là giao dịch gian lận";
                break;
            case "09" :
                $result = "Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.";
                break;
            case "10" :
                $result = "Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần";
                break;
            case "11" :
                $result = "Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch.";
                break;
            case "12" :
                $result = "Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa.";
                break;
            case "51" :
                $result = "Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.";
                break;
            case "65" :
                $result = "Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày.";
                break;
            case "08" :
                $result = "Giao dịch không thành công do: Hệ thống Ngân hàng đang bảo trì. Xin quý khách tạm thời không thực hiện giao dịch bằng thẻ/tài khoản của Ngân hàng này.";
                break;
            case "99" :
                $result = "Có lỗi sảy ra trong quá trình thực hiện giao dịch";
                break;
            default :
                $result = "Giao dịch thất bại - Failured";
        }
        return $result;
    }

}
