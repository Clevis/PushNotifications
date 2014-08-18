<?php

namespace Clevis\PushNotifications;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Clevis\PushNotifications\DependencyInjection\Compiler\AddHandlerPass;

class RMSPushNotificationsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddHandlerPass());
    }
}
