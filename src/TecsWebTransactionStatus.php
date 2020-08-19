<?php
/**
 * @author Branislav Zvolensky <branislav.zvolensky@tecs.at>
 * @copyright TECS telecommunication & e-commercesolutions GmbH
 * @license GNU-v3
 */

namespace Tecs;

use Tecs\Common\Environtment;
use Tecs\Generator\TransactionStatusSign;
use Tecs\Model\TransactionStatusResponse;

/**
 * Class TecsWebTransactionStatus
 * @package Tecs
 */
class TecsWebTransactionStatus implements Environtment
{
    private static $endpoints = [
        Environtment::MODE_PROD => 'https://prod.tecspayment.com/merchantservices/statusTransaction',
        Environtment::MODE_TEST => 'https://test.tecs.at/merchantservices/statusTransaction',
        Environtment::MODE_DEV => 'https://dev.tecs.at/merchantservices/statusTransaction'
    ];

    /**
     * @var string
     */
    private $merchantId;

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var TransactionStatusSign
     */
    private $signService;

    /**
     * @var string
     */
    private $serviceEndpoint = '';

    /**
     * TecsWebTransactionStatus constructor.
     * @param string $merchantId
     * @param string $secretKey
     */
    public function __construct(
        $merchantId,
        $secretKey,
        $mode = 'prod'
    ) {
        $this->merchantId  = $merchantId;
        $this->secretKey   = $secretKey;
        $this->serviceEndpoint = self::$endpoints[$mode];
        $this->signService = new TransactionStatusSign();
    }

    /**
     * @param string $transactionId
     * @return string
     * @throws \Exception
     */
    public function getHttpAuthToken($transactionId = '') {
        $sign = $this->signService->createSign([
            TransactionStatusSign::TRANSACTION_ID => $transactionId,
            TransactionStatusSign::MERCHANT_ID => $this->merchantId,
            TransactionStatusSign::SECRET_KEY => $this->secretKey
        ]);

        return sprintf('TecsWebToken %s', $sign);
    }

    /**
     * @param string $transactionId
     * @return TransactionStatusResponse|boolean
     */
    public function getTransactionStatus($transactionId = '') {
        $data = array(
            "sourceId" => 1,
            "terminalId" => $this->merchantId,
            'transactionId' => $transactionId
        );
        $dataString = json_encode($data);
        $authToken = $this->getHttpAuthToken($transactionId);

        $ch = curl_init($this->serviceEndpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            sprintf('Authorization: %s', $authToken),
            'accept: application/json',
            'Content-Type: application/json'
        ));

        $result = curl_exec($ch);

        if (!$result) {
            return false;
        }

        return new TransactionStatusResponse(json_decode($result, true));
    }

    /**
     * Override Default Service Endpoint URL
     *
     * @param $serviceEndpoint
     * @return $this
     */
    public function setServiceEndpoint($serviceEndpoint)
    {
        $this->serviceEndpoint = $serviceEndpoint;

        return $this;
    }
}
