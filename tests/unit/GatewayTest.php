<?php

/*
 * PayPal driver for Omnipay PHP payment library
 *
 * @link      https://github.com/hiqdev/omnipay-paypal
 * @package   omnipay-paypal
 * @license   MIT
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace Omnipay\PayPal;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public $gateway;

    private $purse          = 'purse@company.co';
    private $secret         = 'sDf#$Sdf#$%';
    private $testMode       = true;
    private $transactionId  = 'sadf2345asf';
    private $description    = 'Test completePurchase description';
    private $currency       = 'USD';
    private $amount         = '12.46';

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setPurse($this->purse);
        $this->gateway->setSecret($this->secret);
        $this->gateway->setTestMode($this->testMode);
    }

    public function testGateway()
    {
        $this->assertSame($this->purse,     $this->gateway->getPurse());
        $this->assertSame($this->secret,    $this->gateway->getSecret());
        $this->assertSame($this->testMode,  $this->gateway->getTestMode());
    }

    public function testCompletePurchase()
    {
        $request = $this->gateway->completePurchase([
            'transactionId' => $this->transactionId,
        ]);

        $this->assertSame($this->purse,         $request->getPurse());
        $this->assertSame($this->secret,        $request->getSecret());
        $this->assertSame($this->testMode,      $request->getTestMode());
        $this->assertSame($this->transactionId, $request->getTransactionId());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase([
            'transactionId' => $this->transactionId,
            'description'   => $this->description,
            'currency'      => $this->currency,
            'amount'        => $this->amount,
        ]);

        $this->assertSame($this->transactionId, $request->getTransactionId());
        $this->assertSame($this->description,   $request->getDescription());
        $this->assertSame($this->currency,      $request->getCurrency());
        $this->assertSame($this->amount,        $request->getAmount());
    }
}
