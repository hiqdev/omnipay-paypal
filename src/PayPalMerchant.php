<?php

/*
 * PHP merchant
 *
 * @link      https://github.com/hiqdev/php-merchant
 * @package   php-merchant
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015, HiQDev (https://hiqdev.com/)
 */

namespace hiqdev\php\merchant;

class PayPalMerchant extends Merchant
{
    protected static $_defaults = [
        'name'          => 'paypal',
        'label'         => 'PayPal',
        #'actionUrl'    => 'https://sandbox.paypal.com/cgi-bin/webscr',
        'actionUrl'     => 'https://www.paypal.com/cgi-bin/webscr',
        'confirmText'   => 'OK'
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
