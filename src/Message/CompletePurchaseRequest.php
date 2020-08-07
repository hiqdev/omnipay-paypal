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
 * PayPal Complete Purchase Request.
 */
class CompletePurchaseRequest extends AbstractRequest
{
    protected $mainEndpoint = 'https://ipnpb.paypal.com/cgi-bin/webscr';
    protected $testEndpoint = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';

    /**
     * Get the data for this request.
     * @return array request data
     */
    public function getData()
    {
        $this->validate('purse');

        return array_merge([
            'cmd' => '_notify-validate',
        ], $this->httpRequest->request->all());
    }

    /**
     * Send the request with specified data.
     * @param mixed $data The data to send
     * @return CompletePurchaseResponse
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->request('POST', $this->getEndpoint(), [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ], http_build_query($data));
        $data['_result'] = $httpResponse->getBody()->getContents();

        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
