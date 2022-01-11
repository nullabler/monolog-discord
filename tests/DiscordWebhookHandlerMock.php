<?php
namespace MonologDiscord;

class DiscordWebhookHandlerMock extends DiscordWebhookHandler
{
    private ?string $payload = null;

    public function getPayload(): ?string
    {
        return $this->payload;
    }

    protected function curlExecute(string $payload, int $retries = 5): void
    {
        $this->payload = $payload;
    }
}