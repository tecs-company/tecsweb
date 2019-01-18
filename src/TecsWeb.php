<?php

/**
 * @author Branislav Zvolensky <branislav.zvolensky@tecs.at>
 * @copyright TECS telecommunication & e-commercesolutions GmbH
 * @license GNU-v3
 */

namespace Tecs;

use Tecs\Common\SignedUrlFactoryInterface;
use Tecs\Common\SignFactoryInterface;
use Tecs\Generator\Sign;
use Tecs\Generator\SignedUrl;

/**
 * Class TecsWeb
 * @package Tecs
 */
class TecsWeb implements SignFactoryInterface, SignedUrlFactoryInterface
{
    /**
     * Private Secret Key
     *
     * @var string
     */
    private $privateSecretKey = 'secretMerchantKey';

    /**
     * Merchant Identifier
     *
     * @var string
     */
    private $mid = '';

    /**
     *
     * @var string
     */
    private $paygateUrl = '';

    /**
     * TecsWeb constructor.
     * @param string $privateSecretKey Private Secret Key
     * @param string $mid Merchant ID
     * @param string $paygateUrl
     */
    public function __construct(
        $privateSecretKey,
        $mid,
        $paygateUrl = ''
    ) {
        $this->privateSecretKey = $privateSecretKey;
        $this->mid = $mid;
        $this->paygateUrl = $paygateUrl;
    }

    /**
     * @param array $data
     * @return string|Sign
     * @throws \Exception
     */
    public function createSign(array $data = array())
    {
        $this->validate($data);

        return new Sign($data, $this->privateSecretKey, $this->mid);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '';
    }

    /**
     * @param array $data
     * @return string|SignedUrl
     * @throws \Exception
     */
    public function createSignedUrl(array $data = array())
    {
        return new SignedUrl(
            $this->paygateUrl,
            $data,
            $this->createSign($data),
            $this->mid
        );
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    private function validate(array $data)
    {
        $mandatoryFields = [
            self::AMOUNT, self::RECEIPT_NUMBER, self::TX_CURRENCY, self::TX_ID, self::TX_DESC, self::RETURN_URL
        ];

        foreach ($mandatoryFields as $mandatoryField) {
            if (!key_exists($mandatoryField, $data)) {
                throw new \Exception(
                    sprintf('Data Field "%s" is not defined in input data', $mandatoryField)
                );
            }
        }
    }
}
