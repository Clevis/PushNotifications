<?php

namespace Clevis\PushNotifications\Service\OS;

use Clevis\PushNotifications\Message\MessageInterface;

interface OSNotificationServiceInterface
{
    /**
     * Send a notification message
     *
     * @param  \Clevis\PushNotifications\Message\MessageInterface $message
     * @return mixed
     */
    public function send(MessageInterface $message);
}
