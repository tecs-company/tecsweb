<?php
/**
 * @author Branislav Zvolensky <branislav.zvolensky@tecs.at>
 * @copyright TECS telecommunication & e-commercesolutions GmbH
 * @license GNU-v3
 */

namespace Tecs\Common;

/**
 * Interface ResponseDataInterface
 * @package Tecs\Common
 */
interface ResponseDataInterface
{
    const RESPONSE_CODE = 'responsecode';
    const RESPONSE_TEXT = 'responsetext';
    const TX_ID = 'txid';
    const TX_DATE_TIME = 'Date-Time-TX';
    const AUTHORIZATION_NUMBER = 'Authorization-number';
    const VU_NUMBER = 'VU-NUMMER';
    const OPERATOR_ID = 'Operator-ID';
    const SERIE_NR = 'SERIEN-NR';
    const ORIG_TX_ID = 'Orig-TX-ID';
    const STAN = 'STAN';
    const ORIG_STAN = 'Orig-STAN';
    const SVC = 'SVC';
    const USER_DATA = 'User-Data';
    const SIGN = 'sign';
    const ACQUIRER_NAME = 'AcquirerName';
    const CARD_TYPE = 'CardType';
    const CARD_REF_NUMBER = 'CardReferenceNumber';
}
