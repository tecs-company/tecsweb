<?php

/**
 * @author Branislav Zvolensky <branislav.zvolensky@tecs.at>
 * @copyright TECS telecommunication & e-commercesolutions GmbH
 * @license GNU-v3
 */

namespace Tecs\Common;

/**
 * Interface ResponseSignCheckInterface
 * @package Tecs\Common
 */
interface ResponseSignCheckInterface extends ResponseDataInterface
{
    /**
     * @return bool
     */
    public function isSignedCorrectly();
}
