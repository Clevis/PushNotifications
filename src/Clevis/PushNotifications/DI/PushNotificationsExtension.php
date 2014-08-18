<?php

namespace Clevis\PushNotifications\DI;

use Clevis\PushNotifications\Device\Types;
use Nette\DI\CompilerExtension;


class PushNotificationsExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$config = $this->getConfig($this->defaults);

		$builder->addDefinition($this->prefix('android.c2dm'))
			->setClass('Clevis\PushNotifications\Services\OS\AndroidNotification')
			->setArguments(array(
				$config['android.c2dm.username'],
				$config['android.c2dm.password'],
				$config['android.c2dm.source'],
			));

		$builder->addDefinition($this->prefix('android.gcm'))
			->setClass('Clevis\PushNotifications\Services\OS\AndroidGCMNotification')
			->setArguments(array(
				$config['android.gcm.apiKey'],
				$config['android.gcm.useMultiCurl'],
			));

		$builder->addDefinition($this->prefix('apple'))
			->setClass('Clevis\PushNotifications\Services\OS\AppleNotification')
			->setArguments(array(
				$config['android.apple.sandbox'],
				$config['android.apple.pem'],
				$config['android.apple.passphrase'],
			));

		$builder->addDefinition($this->prefix('microsoft'))
			->setClass('Clevis\PushNotifications\Services\OS\MicrosoftNotification');

		$builder->addDefinition($this->prefix('service'))
			->setClass('Clevis\PushNotifications\Service\Notifications')
			->addSetup('addHandler', array(Types::OS_ANDROID_C2DM, $this->prefix('@android.c2dm')))
			->addSetup('addHandler', array(Types::OS_ANDROID_C2DM, $this->prefix('@android.gcm')))
			->addSetup('addHandler', array(Types::OS_IOS, $this->prefix('@apple')))
			->addSetup('addHandler', array(Types::OS_MAC, $this->prefix('@apple')))
			->addSetup('addHandler', array(Types::OS_WINDOWSMOBILE, $this->prefix('@microsoft')))
			->addSetup('addHandler', array(Types::OS_WINDOWSPHONE, $this->prefix('@microsoft')));

	}

}
