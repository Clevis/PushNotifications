<?php

namespace Clevis\PushNotifications\Message;

use Clevis\PushNotifications\Device\Types;

class iOSMessage extends AppleMessage
{
    /**
     * Returns the target OS for this message
     *
     * @return string
     */
    public function getTargetOS()
    {
        return Types::OS_IOS;
    }
}
