<?php
namespace MonologDiscord;

use Dotenv\Dotenv;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class DiscordWebhookHandlerTest extends TestCase
{
    /**
     * @dataProvider getDataForSuccess
     */
    public function testSuccess(string $level, string $message, int $color)
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $handler = new DiscordWebhookHandlerMock(webhookUrl: $_ENV['WEBHOOK_URL'], level: Logger::DEBUG);
        $log = new Logger('discord');
        $log->pushHandler($handler);

        $log->{strtolower($level)}($message);
        $this->assertSame('discord', $log->getName());
        $this->assertRegExp('#"title":"Level: ' . $level
            . '","description":"\[[^\]]+\] discord\.' . $level
            . ': ' . $message
            . '[^"]+","color":' . $color
            . '#', $handler->getPayload());
    }

    public function getDataForSuccess(): array
    {
        return [
            ['CRITICAL', 'Test critical message!', DiscordWebhookHandler::COLOR_RED],
            ['ERROR', 'Test error message!', DiscordWebhookHandler::COLOR_PURPLE],
            ['ALERT', 'Test alert message!', DiscordWebhookHandler::COLOR_GREEN],
            ['NOTICE', 'Test alert message!', DiscordWebhookHandler::COLOR_GREEN],
            ['WARNING', 'Test alert message!', DiscordWebhookHandler::COLOR_GREEN],
            ['INFO', 'Test alert message!', DiscordWebhookHandler::COLOR_GREEN],
            ['DEBUG', 'Test alert message!', DiscordWebhookHandler::COLOR_GREEN],
        ];
    }

    /**
     * @dataProvider getDataForFail
     */
    public function testFail(string $level)
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $handler = new DiscordWebhookHandlerMock($_ENV['WEBHOOK_URL']);
        $log = new Logger('discord');
        $log->pushHandler($handler);

        $log->{$level}('Hello world');
        $this->assertSame('discord', $log->getName());
        $this->assertNull($handler->getPayload());
    }

    public function getDataForFail(): array
    {
        return [
            ['notice'],
            ['warning'],
            ['info'],
            ['debug'],
        ];
    }
}