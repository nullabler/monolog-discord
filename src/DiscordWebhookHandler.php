<?php
namespace MonologDiscord;

use RuntimeException;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class DiscordWebhookHandler extends AbstractProcessingHandler
{
    const COLOR_RED = 16711680;
    const COLOR_PURPLE = 10027263;
    const COLOR_GREEN = 54041;

    public function __construct(
        private string $webhookUrl,
        private ?string $username = null,
        int $level = Logger::ERROR,
        bool $bubble = true,

    ) {
        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {
        $this->send($record['level'], $record['level_name'], $record['formatted']);
    }

    protected function send(int $level, string $levelName, string $formatted): void
    {
        $payload = json_encode([
            'username' => $this->username,
            'embeds' => [
                ['title' => 'Level: ' . $levelName, 'description' => $formatted, 'color' => $this->getColor($level)]
            ]
        ]);
        $this->curlExecute($payload);
    }

    protected function curlExecute(string $payload, int $retries = 5): void
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->webhookUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        while ($retries--) {
            $curlResponse = curl_exec($ch);
            if ($curlResponse === false) {
                $curlErrno = curl_errno($ch);
                if (!$retries) {
                    $curlError = curl_error($ch);
                    curl_close($ch);
                    throw new RuntimeException(sprintf('Curl error (code %d): %s', $curlErrno, $curlError));
                }

                continue;
            }
            curl_close($ch);
        }
    }

    private function getColor(int $level): int
    {
        return match ($level) {
            Logger::CRITICAL => self::COLOR_RED,
            Logger::ERROR => self::COLOR_PURPLE,
            default => self::COLOR_GREEN
        };
    }
}