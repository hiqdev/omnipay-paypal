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

/**
 * PayPal Abstract Request.
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $zeroAmountAllowed = false;

    protected $mainEndpoint = 'https://www.paypal.com/cgi-bin/webscr';
    protected $testEndpoint = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->mainEndpoint;
    }

    /**
     * Get the merchant purse.
     *
     * @return string merchant purse - email associated with the merchant account.
     */
    public function getPurse()
    {
        return $this->getParameter('purse');
    }

    /**
     * Set the purse.
     *
     * @param string $value merchant purse - email associated with the merchant account.
     * @return self
     */
    public function setPurse($value)
    {
        return $this->setParameter('purse', $value);
    }

    /**
     * Get the merchant secret. Actually not used.
     *
     * @return string merchant secret
     */
    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    /**
     * Set the merchant secret. Actually not used.
     *
     * @param string $value merchant secret
     * @return self
     */
    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    /**
     * Get the sum excluding fee.
     *
     * @return string sum excluding fee
     */
    public function getSum()
    {
        return $this->getParameter('sum') ?: $this->getAmount();
    }

    /**
     * Set the sum excluding fee.
     *
     * @param string $value sum excluding fee
     * @return self
     */
    public function setSum($value)
    {
        return $this->setParameter('sum', $value);
    }
}
