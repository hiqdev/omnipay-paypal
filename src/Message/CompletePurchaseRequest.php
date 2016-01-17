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
        $this->httpClient->setConfig([
            'curl.options' => [
                CURLOPT_SSLVERSION => 1,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_SSL_VERIFYPEER => 1,
            ],
        ]);

        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $data)->send();
        $data['_result'] = $httpResponse->getBody(1);

        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
