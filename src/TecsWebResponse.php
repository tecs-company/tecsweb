<?php

/**
 * @author Branislav Zvolensky <branislav.zvolensky@tecs.at>
 * @copyright TECS telecommunication & e-commercesolutions GmbH
 * @license GNU-v3
 */

namespace Tecs;

use Exception;
use Tecs\Common\ResponseSignCheckInterface;
use Tecs\Generator\ResponseSign;

/**
 * Class TecsWebResponse
 * @package Tecs
 */
class TecsWebResponse implements ResponseSignCheckInterface
{

    /**
     * Private Secret Key
     *
     * @var string
     */
    protected $privateSecretKey = 'secretMerchantKey';

    /**
     * GET DATA
     *
     * @var array
     */
    protected $data = [];

    /**
     * TecsWebResponse constructor.
     * @param string $privateSecretKey
     * @param array $data
     * @throws Exception
     */
    public function __construct(
        $privateSecretKey,
        array $data
    ) {
        $this->privateSecretKey = $privateSecretKey;
        $this->data = $data;
    }

    /**
     * Response sign check
     *
     * @return bool
     */
    public function isSignedCorrectly()
    {
        $responseSign = new ResponseSign($this->privateSecretKey, $this->data);

        return $this->data[self::SIGN] === $responseSign->getSign();
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return ((int) $this->data[self::RESPONSE_CODE] !== 0);
    }

    /**
     * @return string|null
     */
    public function getResponseText()
    {
        return $this->data[self::RESPONSE_TEXT];
    }

    /**
     * @return string|null
     */
    public function getResponseCode()
    {
        return $this->get(self::RESPONSE_CODE);
    }

    /**
     * @return string|null
     */
    public function getTXID()
    {
        return $this->get(self::TX_ID);
    }

    /**
     * @return string|null
     */
    public function getTXDateTime()
    {
        return $this->get(self::TX_DATE_TIME);
    }

    /**
     * @return string|null
     */
    public function getAuthorizationNumber()
    {
        return $this->get(self::AUTHORIZATION_NUMBER);
    }

    /**
     * @return string|null
     */
    public function getVUNumber()
    {
        return $this->get(self::VU_NUMBER);
    }

    /**
     * @return string|null
     */
    public function getOperatorID()
    {
        return $this->get(self::OPERATOR_ID);
    }

    /**
     * @return string|null
     */
    public function getSerieNumber()
    {
        return $this->get(self::SERIE_NR);
    }

    /**
     * @return string|null
     */
    public function getOriginalTXID()
    {
        return $this->get(self::ORIG_TX_ID);
    }

    /**
     * @return string|null
     */
    public function getSTAN()
    {
        return $this->get(self::STAN);
    }

    /**
     * @return string|null
     */
    public function getOriginalSTAN()
    {
        return $this->get(self::ORIG_STAN);
    }

    /**
     * @return string|null
     */
    public function getSVC()
    {
        return $this->get(self::SVC);
    }

    /**
     * @return string|null
     */
    public function getUserData()
    {
        return $this->get(self::USER_DATA);
    }

    /**
     * @return string|null
     */
    public function getSign()
    {
        return $this->get(self::SIGN);
    }

    /**
     * @return string|null
     */
    public function getAcquirerName()
    {
        return $this->get(self::ACQUIRER_NAME);
    }

    /**
     * @return string|null
     */
    public function getCardType()
    {
        return $this->get(self::CARD_TYPE);
    }

    /**
     * @return string|null
     */
    public function getCardReferenceNumber()
    {
        return $this->get(self::CARD_REF_NUMBER);
    }

    /**
     * @return array
     */
    public function getAllData()
    {
        return $this->data;
    }

    /**
     * @param $key
     * @return string|null
     */
    private function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }
}
