<?php

namespace Clevis\PushNotifications\Message;

use Clevis\PushNotifications\Device\Types;

class MacMessage extends AppleMessage
{
    /**
     * Returns the target OS for this message
     *
     * @return string
     */
    public function getTargetOS()
    {
        return Types::OS_MAC;
    }
}
