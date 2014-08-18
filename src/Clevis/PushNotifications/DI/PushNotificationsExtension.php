<?php

namespace Clevis\PushNotifications\DI;

use Clevis\PushNotifications\Device\Types;
use Nette\DI\CompilerExtension;
use Nette\Utils\Validators;


class PushNotificationsExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$config = $this->getConfig([
			'android' => [
				'c2dm' => [
					'username' => NULL,
					'password' => NULL,
					'source' => NULL,
				],
				'gcm' => [
					'apiKey',
					'useMultiCurl' => TRUE,
				],
			],
			'apple' => [
				'sandbox' => NULL,
				'pem' => NULL,
				'passphrase' => NULL,
			],
		]);
		Validators::assert($config['android']['c2dm']['username'], 'string', 'android.c2dm.username');
		Validators::assert($config['android']['c2dm']['password'], 'string', 'android.c2dm.password');
		Validators::assert($config['android']['c2dm']['source'], 'string', 'android.c2dm.source');
		Validators::assert($config['android']['gcm']['apiKey'], 'string', 'android.gcm.apiKey');
		Validators::assert($config['android']['gcm']['useMultiCurl'], 'boolean', 'android.gcm.useMultiCurl');
		Validators::assert($config['apple']['sandbox'], 'boolean', 'apple.sandbox');
		Validators::assert($config['apple']['pem'], 'string', "Key 'pem' must be relative or absolute path", 'apple.pem');
		Validators::assert($config['apple']['passphrase'], 'string', 'apple.passphrase');

		$builder->addDefinition($this->prefix('android.c2dm'))
			->setClass('Clevis\PushNotifications\Service\OS\AndroidNotification')
			->setArguments(array(
				$config['android']['c2dm']['username'],
				$config['android']['c2dm']['password'],
				$config['android']['c2dm']['source'],
			));

		$builder->addDefinition($this->prefix('android.gcm'))
			->setClass('Clevis\PushNotifications\Service\OS\AndroidGCMNotification')
			->setArguments(array(
				$config['android']['gcm']['apiKey'],
				$config['android']['gcm']['useMultiCurl'],
			));

		$builder->addDefinition($this->prefix('apple'))
			->setClass('Clevis\PushNotifications\Service\OS\AppleNotification')
			->setArguments(array(
				$config['apple']['sandbox'],
				$config['apple']['pem'],
				$config['apple']['passphrase'],
			));

		$builder->addDefinition($this->prefix('microsoft'))
			->setClass('Clevis\PushNotifications\Service\OS\MicrosoftNotification');

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
