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
        'name'        => 'paypal',
        'label'       => 'PayPal',
        'actionUrl'   => 'https://www.paypal.com/cgi-bin/webscr',
        'confirmText' => 'OK',
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

    public function validateConfirmation($data)
    {
    }
}
