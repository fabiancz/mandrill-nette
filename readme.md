[Mandrill](http://mandrill.com) API library with Message class implementation like in Nette framework.

Requirements
------------

* PHP 5.3
* [Mandrill API key](https://mandrillapp.com/settings/index)

Best used with [Nette framework](http://nette.org)


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
		apiKey: s-sUxBibVE0a0auzVK2bXw
```

Register new service in your config.neon:
```neon
services:
	mandrill:
		class: Fabian\Mandrill\Mandrill(%mandrill.apiKey%)
```

Then you can simply use exactly like \Nette\Mail\Message:

```php
$m = new \Fabian\Mandrill\Message($this->context->mandrill);
$m->addTo('joe@example.com', 'John Doe')
   ->setSubject('First email')
   ->setBody("Hi,\n\nthis is first email using Mandrill.")
   ->setFrom('noreplay@yourdomain.com', 'Your Name')
   ->send();
```

For next methods you can see doc/.

__If you want to use some other Mandrill API method, that is not implemented, you'r welcome to fork and send pull requests!;)__ 