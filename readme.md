[Mandrill](http://mandrill.com) API library with Message class implementation like in Nette framework.

Requirements
------------

* PHP 5.3
* [Mandrill API key](https://mandrillapp.com/settings/index)
* [Nette framework](http://nette.org)


Installation
------------

The best way to install is using  [Composer](http://getcomposer.org/):

```sh
$ composer require fabian/mandrill:@dev
```

Usage
-----

Add Mandrill API key to your parameters in your config.neon:

```neon
parameters:
  mandrill:
    apiKey: yourApiKey
```

Then you can use MandrillMailer:

```php
$mail = new \Fabian\Mandrill\Message();
$mail->addTo('joe@example.com', 'John Doe')
   ->setSubject('First email')
   ->setBody("Hi,\n\nthis is first email using Mandrill.")
   ->setFrom('noreplay@yourdomain.com', 'Your Name')
   ->addTag('test-emails');
$mailer = new \Fabian\Mandrill\MandrillMailer(
    $this->context->parameters['mandrill']['apiKey']
);
$mailer->send($mail);
```

You can use \Nette\Mail\Message too:

```php
$mail = new \Nette\Mail\Message;
$mail->addTo('joe@example.com', 'John Doe')
   ->setSubject('First email')
   ->setBody("Hi,\n\nthis is first email using Mandrill.")
   ->setFrom('noreplay@yourdomain.com', 'Your Name')
$mailer = new \Fabian\Mandrill\MandrillMailer(
    $this->context->parameters['mandrill']['apiKey']
);
$mailer->send($mail);
```
