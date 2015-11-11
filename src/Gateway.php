<?php

/*
 * PayPal driver for Omnipay PHP payment library
 *
 * @link      https://github.com/hiqdev/omnipay-paypal
 * @package   omnipay-paypal
 * @license   MIT
 * @copyright Copyright (c) 2015, HiQDev (http://hiqdev.com/)
 */

namespace Omnipay\PayPal;

use Omnipay\Common\AbstractGateway;

/**
 * Gateway for PayPal.
 */
class Gateway extends AbstractGateway
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'PayPal';
    }

    public function getAssetDir()
    {
        return dirname(__DIR__) . '/assets';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters()
    {
        return [
            'merchantPurse' => '',
            'testMode'      => false,
        ];
    }

    /**
     * Get the unified purse.
     *
     * @return string merchant purse
     */
    public function getPurse()
    {
        return $this->getUsername();
    }

    /**
     * Set the unified purse.
     *
     * @param string $purse merchant purse
     *
     * @return self
     */
    public function setPurse($value)
    {
        return $this->setUsername($value);
    }

    /**
     * Get the merchant purse.
     *
     * @return string merchant purse
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * Set the merchant purse.
     *
     * @param string $value merchant purse
     *
     * @return self
     */
    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    /**
     * Get the unified secret key.
     *
     * @return string secret key
     */
    public function getSecretKey()
    {
        return $this->getPassword();
    }

    /**
     * Set the unified secret key.
     *
     * @param string $value secret key
     *
     * @return self
     */
    public function setSecretKey($value)
    {
        return $this->setPassword($value);
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
     * @param string $value password
     *
     * @return self
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getSignature()
    {
        return $this->getParameter('signature');
    }

    public function setSignature($value)
    {
        return $this->setParameter('signature', $value);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\PayPal\Message\AuthorizeRequest
     */
    public function authorize(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\PayPal\Message\AuthorizeRequest', $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\PayPal\Message\PurchaseRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\PayPal\Message\PurchaseRequest', $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\PayPal\Message\CaptureRequest
     */
    public function capture(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\PayPal\Message\CaptureRequest', $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\PayPal\Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\PayPal\Message\CompletePurchaseRequest', $parameters);
    }
}
