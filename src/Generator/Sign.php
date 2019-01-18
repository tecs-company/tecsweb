<?php

/**
 * @author Branislav Zvolensky <branislav.zvolensky@tecs.at>
 * @copyright TECS telecommunication & e-commercesolutions GmbH
 * @license GNU-v3
 */

namespace Tecs\Generator;

use Tecs\Common\InputDataInterface;

/**
 * Class Sign
 * @package Tecs\Generator
 */
class Sign implements InputDataInterface
{
    /**
     * @var array
     */
    private $data = array();

    /**
     * @var string
     */
    private $secret;

    /**
     * @var string
     */
    private $merchantId;

    /**
     * Sign constructor.
     * @param array $data
     * @param string $secret
     * @param string $merchantId
     */
    public function __construct(array $data, $secret, $merchantId)
    {
        $this->data = $data;
        $this->secret = $secret;
        $this->merchantId = $merchantId;
    }

    /**
     * @return string
     */
    public function getSign()
    {
        $toImplode = array();
        $toImplode[] = $this->data[self::AMOUNT];
        $toImplode[] = $this->data[self::TX_ID];
        $toImplode[] = $this->data[self::TX_CURRENCY];
        $toImplode[] = $this->data[self::TX_DESC];
        $toImplode[] = $this->merchantId;
        $toImplode[] = $this->data[self::RETURN_URL];
        if (isset($this->data[self::USER_DATA])) {
            $toImplode[] = $this->data[self::USER_DATA];
        }

        $toSign = implode('|', $toImplode) . $this->secret;

        return strtoupper(sha1($toSign));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getSign();
    }
}
