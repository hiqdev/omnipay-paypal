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

/**
 * PayPal Abstract Request.
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $zeroAmountAllowed = false;

    /**
     * Get the username.
     *
     * @return string username
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * Set the username.
     *
     * @param string $purse username
     *
     * @return self
     */
    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    /**
     * Get the password.
     *
     * @return string password
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * Set the password.
     *
     * @param string $key password
     *
     * @return self
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }
}
