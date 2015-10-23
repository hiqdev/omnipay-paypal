<?php

/*
 * PayPal plugin for PHP merchant library
 *
 * @link      https://github.com/hiqdev/php-merchant-paypal
 * @package   php-merchant-paypal
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015, HiQDev (https://hiqdev.com/)
 */

namespace hiqdev\php\merchant\paypal;

class Merchant extends \hiqdev\php\merchant\Merchant
{
    protected static $_defaults = [
        #'actionUrl'  => 'https://sandbox.paypal.com/cgi-bin/webscr',
        'name'      => 'paypal',
        'label'     => 'PayPal',
        'actionUrl' => 'https://www.paypal.com/cgi-bin/webscr',
    ];

    public function getInputs()
    {
        return [
            'cmd'         => '_xclick',
            'bn'          => 'PP-BuyNowBF:btn_paynowCC_LG.gif:NonHostedGuest',
            'item_name'   => $this->description,
            'amount'      => $this->total,
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
            'from'     => $this->prepareFrom($data),
            'txn'      => $data['txn_id'],
            'currency' => strtolower($data['mc_currency']),
            'sum'      => $data['payment_gross'],
            'fee'      => $data['payment_fee'],
            'time'     => $this->formatDatetime($data['payment_date']),
        ]);

        return;
    }
}
