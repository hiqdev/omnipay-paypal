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

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * PayPal Complete Purchase Response.
 */
class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * @var CompletePurchaseRequest
     */
    public $request;

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        if ($this->getResult() !== 'VERIFIED') {
            throw new InvalidResponseException('Not verified');
        }

        if ($this->request->getTestMode() !== $this->getTestMode()) {
            throw new InvalidResponseException('Invalid test mode');
        }
    }

    /**
     * Whether the payment is successful
     * @return boolean
     */
    public function isSuccessful()
    {
        return true;
    }

    /**
     * Whether the payment is test
     * @return boolean
     */
    public function getTestMode()
    {
        return (bool)$this->data['test_ipn'];
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getTransactionId()
    {
        return $this->data['item_number'];
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getTransactionReference()
    {
        return $this->data['txn_id'];
    }

    /**
     * Retruns the transatcion status
     * @return string
     */
    public function getTransactionStatus()
    {
        return $this->data['payment_status'];
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getAmount()
    {
        return $this->data['payment_gross'];
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getFee()
    {
        return $this->data['payment_fee'];
    }

    /**
     * Returns the currency
     * @return string
     */
    public function getCurrency()
    {
        return strtoupper($this->data['mc_currency']);
    }

    /**
     * Returns the payer "name/email"
     * @return string
     */
    public function getPayer()
    {
        $payer = $this->data['address_name'] . '/' . $this->data['payer_email'];
        $charset = strtoupper($this->data['charset']);
        if ($charset !== 'UTF-8') {
            $payer = iconv($charset, 'UTF-8//IGNORE', $payer);
        }

        return $payer;
    }

    /**
     * Returns the payment date
     * @return string
     */
    public function getTime()
    {
        return date('c', strtotime($this->data['payment_date']));
    }
}
