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

class CompletePurchaseResponseTest extends TestCase
{
    private $request;
    private $response               = 'VERIFIED';

    private $purse                  = 'some@company.business';
    private $secret                 = '22SAD#-78G8sdf$88';
    private $description            = 'Test Transaction long description';
    private $transactionId          = '1SD672345A890sd';
    private $transactionStatus      = 'Completed';
    private $transactionReference   = 'sdfa1SD672345A8';
    private $time                   = '2016-02-02T18:50:20+00:00';
    private $payer                  = 'some@payer.email';
    private $address                = 'Somewhere str, 123';
    private $fee                    = '1.01';
    private $amount                 = '1465.01';
    private $currency               = 'USD';
    private $testMode               = true;

    public function setUp()
    {
        parent::setUp();

        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'purse'         => $this->purse,
            'secret'        => $this->secret,
            'testMode'      => $this->testMode,
        ]);
    }

    public function testNotVerifiedException()
    {
        $this->expectException(\Omnipay\Common\Exception\InvalidResponseException::class);
        $this->expectExceptionMessage('Not verified');
        new CompletePurchaseResponse($this->request, [
            'description'           => $this->description,
        ]);
    }

    public function testInvalidTestModeException()
    {
        $this->expectException(\Omnipay\Common\Exception\InvalidResponseException::class);
        $this->expectExceptionMessage('Invalid test mode');
        new CompletePurchaseResponse($this->request, [
            'item_name'         => $this->description,
            '_result'           => $this->response,
            'payment_status'    => $this->transactionStatus,
        ]);
    }

    public function testInvalidPaymentStatusException()
    {
        $this->expectException(\Omnipay\Common\Exception\InvalidResponseException::class);
        $this->expectExceptionMessage('Invalid payment status');
        new CompletePurchaseResponse($this->request, [
            'item_name'         => $this->description,
            '_result'           => $this->response,
            'test_ipn'          => $this->testMode,
        ]);
    }

    public function testSuccess()
    {
        $response = new CompletePurchaseResponse($this->request, [
            'test_ipn'          => $this->testMode,
            'business'          => $this->purse,
            'payment_fee'       => $this->fee,
            'payment_gross'     => $this->amount,
            'item_name'         => $this->description,
            'mc_currency'       => $this->currency,
            'payer_email'       => $this->payer,
            'address_name'      => $this->address,
            'payment_date'      => $this->time,
            'item_number'       => $this->transactionId,
            'payment_status'    => $this->transactionStatus,
            'txn_id'            => $this->transactionReference,
            '_result'           => $this->response,
        ]);

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->getTestMode());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());
        $this->assertSame($this->transactionId,         $response->getTransactionId());
        $this->assertSame($this->transactionStatus,     $response->getTransactionStatus());
        $this->assertSame($this->transactionReference,  $response->getTransactionReference());
        $this->assertSame($this->fee,                   $response->getFee());
        $this->assertSame($this->amount,                $response->getAmount());
        $this->assertSame($this->currency,              $response->getCurrency());
        $this->assertSame($this->time,                  $response->getTime());
        $this->assertSame($this->address . '/' . $this->payer, $response->getPayer());
    }
}
