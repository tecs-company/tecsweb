<?php

/**
 * @author Branislav Zvolensky <branislav.zvolensky@tecs.at>
 * @copyright TECS telecommunication & e-commercesolutions GmbH
 * @license GNU-v3
 */

namespace Tecs\Common;

/**
 * Interface SignFactoryInterface
 * @package Tecs\Common
 */
interface SignFactoryInterface extends RequestDataInterface
{
    /**
     * Creates SHA1 HASH in uppercase from input data
     *
     * @param array $data
     * @return string
     */
    public function createSign(array $data = array());
}
