<?php
namespace MonologDiscord;

use Dotenv\Dotenv;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class DiscordWebhookHandlerTest extends TestCase
{
    public function testSuccess()
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $log = new Logger('discord');
        $log->pushHandler(new DiscordWebhookHandler($_ENV['WEBHOOK_URL']));
        $log->critical('Hello world!');
        $this->assertSame('discord', $log->getName());
    }
}