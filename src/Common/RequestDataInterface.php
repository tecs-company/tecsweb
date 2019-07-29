<?php

/**
 * @author Branislav Zvolensky <branislav.zvolensky@tecs.at>
 * @copyright TECS telecommunication & e-commercesolutions GmbH
 * @license GNU-v3
 */

namespace Tecs\Common;

/**
 * Interface InputDataInterface
 * @package Tecs\Common
 */
interface RequestDataInterface
{
    const AMOUNT = 'amt';
    const TX_ID = 'txid';
    const TX_DESC = 'txdesc';
    const TX_CURRENCY = 'txcur';
    const RECEIPT_NUMBER = 'receiptnumber';
    const RETURN_URL = 'rurl';
    const USER_DATA = 'User-data';
    const LANG = 'lang';
    const TX_DATE_TIME = 'Date-Time-TX';
    const TX_SOURCE_ID = 'TX-Source-Id';
    const TX_PLACE = 'Transaction-Place';
    const MESSAGE_TYPE = 'Message-Type';
    const TX_ORIG_ID = 'Txorigid';
    const CARD_NUMBER_POST = 'CardNumberPost';
    const ORIG_TX_ID = 'origTRXNum';
}
