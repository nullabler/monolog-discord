# unixoff/monolog-discord

## Installation
`composer require unixoff/monolog-discord`

## Usage
```php
<?php

require 'vendor/autoload.php';

use unixoff\MonologDiscord\DiscordWebhookHandler;

$webhook = 'Your Webhook URL';

$log = new Monolog\Logger('discord');
$log->pushHandler(new DiscordWebhookHandler($webhook));

$log->info('hello world !');
```

![Sample image](sample.png)

# License
This tool in Licensed under MIT, so feel free to fork it and make it better that it is !