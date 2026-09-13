<?php

namespace Trust\SMS;

require_once TRUST_SMS . 'hook.php';

use Trust\SMS\Hook\SendSMS;

class InitTrustSMSIntegration
{
    /**
     * Call initiators
     */
    function __construct()
    {
        SendSMS::init();
    }
}