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

use Guzzle\Http\Message\Response;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class CompletePurchaseRequestTest extends TestCase
{
    private $request;

    private $purse                  = 'some@enterprise.inc';
    private $secret                 = '*&^^&$sdf&(23';
    private $hash                   = '33bfff79d7eeffdca9a9ac7b34a78dfc';
    private $description            = 'Test Transaction long description';
    private $transactionId          = '12345ASD67890sd';
    private $transactionReference   = '12345678';
    private $timestamp              = '2016-02-02 16:56:56 UTC';
    private $payer                  = 'somebody@some.mail';
    private $amount                 = '1465.01';
    private $testMode               = false;
    private $response               = 'VERIFIED';

    public function setUp()
    {
        parent::setUp();

        $httpResponse = new Response(200, null, $this->response);

        $tmpRequest = $this->getMock('Guzzle\Http\Message\Request', ['send'], ['POST', '']);
        $tmpRequest->expects($this->any())->method('send')->will($this->returnValue($httpResponse));

        $httpClient = $this->getMock('Guzzle\Http\Client', ['setConfig', 'post']);
        $httpClient->expects($this->any())->method('post')->will($this->returnValue($tmpRequest));

        $httpRequest = new HttpRequest([], [
            'business'          => $this->purse,
            'payment_gross'     => $this->amount,
            'item_name'         => $this->description,
            'mc_currency'       => $this->currency,
            'payer_email'       => $this->payer,
            'payment_date'      => $this->time,
            'item_number'       => $this->transactionId,
            'txn_id'            => $this->transactionReference,
        ]);

        $this->request = new CompletePurchaseRequest($httpClient, $httpRequest);
        $this->request->initialize([
            'purse'     => $this->purse,
            'secret'    => $this->secret,
            'testMode'  => $this->testMode,
        ]);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame($this->purse,         $data['business']);
        $this->assertSame($this->description,   $data['item_name']);
        $this->assertSame($this->transactionId, $data['item_number']);
        $this->assertSame($this->amount,        $data['payment_gross']);
        $this->assertSame($this->time,          $data['payment_date']);
        $this->assertSame($this->payer,         $data['payer_email']);
    }

    public function testSendData()
    {
        $data = $this->request->getData();
        $response = $this->request->sendData($data);
        $this->assertInstanceOf('Omnipay\PayPal\Message\CompletePurchaseResponse', $response);
    }
}
