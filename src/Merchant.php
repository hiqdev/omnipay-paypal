<?php

/*
 * PayPal driver for Omnipay PHP payment library
 *
 * @link      https://github.com/hiqdev/omnipay-paypal
 * @package   omnipay-paypal
 * @license   MIT
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\php\merchant\paypal;

class Merchant extends \hiqdev\php\merchant\Merchant
{
    protected static $_defaults = [
        'system'    => 'paypal',
        'label'     => 'PayPal',
        'actionUrl' => 'https://www.paypal.com/cgi-bin/webscr',
    #   'actionUrl' => 'https://sandbox.paypal.com/cgi-bin/webscr',
    ];

    public function getInputs()
    {
        return [
            'cmd'         => '_xclick',
            'bn'          => 'PP-BuyNowBF:btn_paynowCC_LG.gif:NonHostedGuest',
            'item_name'   => $this->invoiceDescription,
            'amount'      => $this->invoiceTotal,
            'business'    => $this->purse,
            'notify_url'  => $this->confirmUrl,
            'return'      => $this->successUrl,
            'item_number' => 1,
        ];
    }

    public function prepareFrom($data)
    {
        $from    = "$data[address_name]/$data[payer_email]";
        $charset = strtoupper($data['charset']);
        if ($charset !== 'UTF-8') {
            $from = iconv($charset, 'UTF-8//IGNORE', $from);
        }

        return $from;
    }

    public function validateConfirmation($data)
    {
        if ($data['payment_status'] !== 'Completed') {
            return 'Not Completed';
        }
        $raw_post_data = 'cmd=_notify-validate&' . file_get_contents('php://input');
        $result        = static::curl($this->actionUrl, $raw_post_data);
        if ($result !== 'VERIFIED') {
            return "Not VERIFIED: $result";
        }
        $this->mset([
            'paymentTotal'    => $data['payment_gross'],
            'paymentFee'      => $data['payment_fee'],
            'paymentId'       => $data['txn_id'],
            'paymentFrom'     => $this->prepareFrom($data),
            'paymentTime'     => $this->formatDatetime($data['payment_date']),
            'paymentCurrency' => strtolower($data['mc_currency']),
        ]);

        return;
    }
}
