<?php
/**
 * @author Branislav Zvolensky <branislav.zvolensky@tecs.at>
 * @copyright TECS telecommunication & e-commercesolutions GmbH
 * @license GNU-v3
 */

namespace Tecs\Generator;

use Tecs\Common\SignFactoryInterface;

/**
 * Class TransactionStatusSign
 * @package Tecs\Generator
 */
class TransactionStatusSign implements  SignFactoryInterface
{
    const TRANSACTION_ID = 'transactioId';
    const MERCHANT_ID = 'merchantId';
    const SECRET_KEY = 'secretKey';

    /**
     * @param array $data
     * @return string|void
     * @throws \Exception
     */
    public function createSign(array $data = array())
    {
        $this->checkData($data);

        return hash(
            'sha256',
            $this->prepareToHash($data)
        );
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    private function checkData(array $data) {
        $required = [self::TRANSACTION_ID, self::SECRET_KEY, self::MERCHANT_ID];

        foreach ($required as $item) {
            if (!array_key_exists($item, $data)) {
                throw new \Exception(__METHOD__ . sprintf(' Missing required parameter %s', $item));
            }
        }
    }

    /**
     * @param array $data
     * @return string
     */
    private function prepareToHash(array $data) {
        return sprintf(
            '%s|%s|%s',
            $data[self::TRANSACTION_ID],
            $data[self::MERCHANT_ID],
            $data[self::SECRET_KEY]
        );
    }
}
