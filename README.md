# dev-null-group/monolog-discord
[![Latest Stable Version](https://img.shields.io/packagist/v/unixoff/monolog-discord)](https://packagist.org/packages/unixoff/monolog-discord)
[![License](https://img.shields.io/packagist/l/unixoff/monolog-discord)](https://packagist.org/packages/unixoff/monolog-discord)
[![PHP Version Require](https://img.shields.io/packagist/php-v/unixoff/monolog-discord)](https://packagist.org/packages/unixoff/monolog-discord)

This package for sending logs of discord to a webhook channel

-------------------------------------------------
## Installation
`composer require dev-null-group/monolog-discord`

## Usage
```php
<?php

require 'vendor/autoload.php';

use DevNullGroup\MonologDiscord\DiscordWebhookHandler;

$webhook = 'Your Webhook URL';

$log = new Monolog\Logger('discord');
$log->pushHandler(new DiscordWebhookHandler($webhook));

$log->info('hello world!');
```

![Sample image](sample.png)

## Symfony setting
```yaml
monolog:
    handlers:
        main:
            ...
        discord_webhook_handler:
            type: service
            id: discord_webhook_handler

services:
    discord_webhook_handler:
        class: MonologDiscord\DiscordWebhookHandler
        arguments:
            $webhookUrl: '%env(string:DISCORD_WEBHOOK)%'
```

# License
See the [LICENSE](LICENSE) Apache License 2.0.
