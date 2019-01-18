<?php

/**
 * @author Branislav Zvolensky <branislav.zvolensky@tecs.at>
 * @copyright TECS telecommunication & e-commercesolutions GmbH
 * @license GNU-v3
 */

namespace Tecs\Common;

/**
 * Interface SignedUrlFactoryInterface
 * @package Tecs\Common
 */
interface SignedUrlFactoryInterface extends InputDataInterface
{
    /**
     * Creates Signed URL from data
     *
     * @param array $data
     *
     * @return string
     */
    public function createSignedUrl(array $data = array());
}
