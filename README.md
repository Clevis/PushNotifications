# Clevis/PushNotifications

A bundle to allow sending of push notifications to mobile devices.  Currently supports Android (C2DM, GCM), Blackberry and iOS devices.

## Installation

To use this bundle in your Nette project add the following to your `composer.json`:

```json
{
    "require": {
        "clevis/push-notifications": "~0.1-beta"
    }
}
```

and enable it in your config:

```yml
extensions:
	pushNotifications: Clevis\PushNotifications\DI\PushNotificationsExtension

pushNotifications:
    android:
        c2dm:
            username:
            password:
            source:
        gcm:
            apiKey:
    apple:
        sandbox: # boolean
        pem: # path to certificate
        passphrase:
```


NOTE: If you are using Windows, you may need to set the Android GCM `use_multi_curl` flag to false for GCM messages to be sent correctly.

## Usage

A little example of how to push your first message to an iOS device, we'll assume that you've set up the configuration correctly:

    use Clevis\PushNotifications\Message\iOSMessage;
    use Clevis\PushNotifications\Notifications;

    class Service
    {

	    /**
	     * @var Notifications
	     */
	    protected $sender;

        public function __construct(Notifications $sender)
        {
            $this->sender = $sender;
        }

        public function sendPushNotificationExample()
        {
            $message = new iOSMessage();
            $message->setMessage('Oh my! A push notification!');
            $message->setDeviceIdentifier('test012fasdf482asdfd63f6d7bc6d4293aedd5fb448fe505eb4asdfef8595a7');

            $this->sender->send($message);
        }
    }

The send method will detect the type of message so if you'll pass it an `AndroidMessage` it will automatically send it through the C2DM/GCM servers, and likewise for Mac and Blackberry.

## Android messages

Since both C2DM and GCM are still available, the `AndroidMessage` class has a small flag on it to toggle which service to send it to.  Use as follows:

```php
use Clevis\PushNotificationsBundle\Message\AndroidMessage;

$message = new AndroidMessage();
$message->setGCM(true);
```

to send as a GCM message rather than C2DM.

## iOS Feedback service

The Apple Push Notification service also exposes a Feedback service where you can get information about failed push notifications - see [here](https://developer.apple.com/library/ios/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/Chapters/CommunicatingWIthAPS.html#//apple_ref/doc/uid/TP40008194-CH101-SW3) for further details.

This service is available within the bundle.  The following code demonstrates how you can retrieve data from the service:

```php
$feedbackService = $container->get("rms_push_notifications.ios.feedback");
$uuids = $feedbackService->getDeviceUUIDs();
```

Here, `$uuids` contains an array of [Feedback](https://github.com/richsage/RMSPushNotificationsBundle/blob/master/Device/iOS/Feedback.php) objects, with timestamp, token length and the device UUID all populated.

Apple recommend you poll this service daily.
