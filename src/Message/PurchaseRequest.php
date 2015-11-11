<?php

/*
 * PayPal driver for Omnipay PHP payment library
 *
 * @link      https://github.com/hiqdev/omnipay-paypal
 * @package   omnipay-paypal
 * @license   MIT
 * @copyright Copyright (c) 2015, HiQDev (http://hiqdev.com/)
 */

namespace Omnipay\PayPal\Message;

class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate(
            'username',
            'amount', 'currency', 'description',
            'returnUrl', 'cancelUrl', 'notifyUrl'
        );

        return [
            'cmd'           => '_xclick',
            'bn'            => 'PP-BuyNowBF:btn_paynowCC_LG.gif:NonHostedGuest',
            'item_name'     => $this->getDescription(),
            'amount'        => $this->getAmount(),
            'currency_code' => strtoupper($this->getCurrency()),
            'business'      => $this->getUsername(),
            'notify_url'    => $this->getNotifyUrl(),
            'return'        => $this->getReturnUrl(),
            'cancel_return' => $this->getCancelUrl(),
            'item_number'   => 1,
        ];
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
