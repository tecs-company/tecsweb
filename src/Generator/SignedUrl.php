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
class SignedUrl implements InputDataInterface
{
    /**
     * @var array
     */
    private $data = array();

    /**
     * @var string
     */
    private $merchantId;

    /**
     * @var Sign
     */
    private $sign;
    private $gateUrl;

    /**
     * Sign constructor.
     * @param string $gateUrl URL to payment gate without GET params
     * @param array $data
     * @param Sign $sign
     * @param string $merchantId
     */
    public function __construct($gateUrl, array $data, Sign $sign, $merchantId)
    {
        $this->gateUrl = $gateUrl;
        $this->data = $data;
        $this->sign = $sign;
        $this->merchantId = $merchantId;
    }

    /**
     * @return string
     */
    public function getSignedUrl()
    {
        $toImplode = array();
        $toImplode[] = sprintf('mid=%s', $this->merchantId);
        $toImplode[] = sprintf('sign=%s', $this->sign);

        foreach ($this->data as $key => $value) {
            $toImplode[] = sprintf('%s=%s', $key, urlencode($value));
        }

        return $this->gateUrl . '?' . implode('&', $toImplode);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getSignedUrl();
    }
}
