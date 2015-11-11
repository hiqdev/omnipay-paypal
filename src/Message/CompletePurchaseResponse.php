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
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data    = $data;

        if ($this->getHash() !== $this->calculateHash()) {
            throw new InvalidResponseException('Invalid hash');
        }

        if ($this->request->getTestMode() !== $this->getTestMode()) {
            throw new InvalidResponseException('Invalid test mode');
        }
    }

    public function isSuccessful()
    {
        return false;
    }
}
