<?php

/*
 * PayPal driver for Omnipay PHP payment library
 *
 * @link      https://github.com/hiqdev/omnipay-paypal
 * @package   omnipay-paypal
 * @license   MIT
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace Omnipay\PayPal\Message;

use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{
    private $request;

    private $purse          = 'some@company.dev';
    private $secret         = '12()&*&+_)?><';
    private $returnUrl      = 'https://www.foodstore.com/success';
    private $cancelUrl      = 'https://www.foodstore.com/failure';
    private $notifyUrl      = 'https://www.foodstore.com/notify';
    private $description    = 'Test Transaction long description';
    private $transactionId  = '12345ASD67890sd';
    private $amount         = '14.65';
    private $quantity       = '1';
    private $currency       = 'USD';
    private $testMode       = true;

    public function setUp()
    {
        parent::setUp();

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'purse'         => $this->purse,
            'secret'        => $this->secret,
            'returnUrl'     => $this->returnUrl,
            'cancelUrl'     => $this->cancelUrl,
            'notifyUrl'     => $this->notifyUrl,
            'description'   => $this->description,
            'transactionId' => $this->transactionId,
            'amount'        => $this->amount,
            'currency'      => $this->currency,
            'testMode'      => $this->testMode,
        ]);
    }

    public function testSuccess()
    {
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getCode());
        $this->assertNull($response->getMessage());
        $this->assertSame('POST', $response->getRedirectMethod());
        $this->assertStringEndsWith('paypal.com/cgi-bin/webscr', $response->getRedirectUrl());
        $this->assertSame([
            'cmd'           => '_xclick',
            'bn'            => 'PP-BuyNowBF:btn_paynowCC_LG.gif:NonHostedGuest',
            'item_name'     => $this->description,
            'amount'        => $this->amount,
            'currency_code' => strtoupper($this->currency),
            'business'      => $this->purse,
            'notify_url'    => $this->notifyUrl,
            'return'        => $this->returnUrl,
            'cancel_return' => $this->cancelUrl,
            'item_number'   => $this->transactionId,
        ], $response->getRedirectData());
    }
}
