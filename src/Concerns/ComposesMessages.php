<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Telegraph;

trait ComposesMessages
{
    public function message(string $message): Telegraph
    {
        $telegraph = clone $this;

        return match (config('telegraph.default_parse_mode')) {
            self::PARSE_MARKDOWN => $telegraph->markdown($message),
            default => $telegraph->html($message)
        };
    }

    private function setMessageText(string $message): void
    {
        $this->endpoint ??= self::ENDPOINT_MESSAGE;

        $this->data['text'] = $message;
        $this->data['chat_id'] = $this->getChat()->chat_id;
    }

    public function html(string $message = null): Telegraph
    {
        $telegraph = clone $this;

        if ($message !== null) {
            $telegraph->setMessageText($message);
        }

        $telegraph->data['parse_mode'] = 'html';

        return $telegraph;
    }

    public function markdown(string $message = null): Telegraph
    {
        $telegraph = clone $this;

        if ($message !== null) {
            $telegraph->setMessageText($message);
        }

        $telegraph->data['parse_mode'] = 'markdown';

        return $telegraph;
    }

    public function reply(int $messageId): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->data['reply_to_message_id'] = $messageId;

        return $telegraph;
    }

    public function protected(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->data['protect_content'] = true;

        return $telegraph;
    }

    public function silent(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->data['disable_notification'] = true;

        return $telegraph;
    }

    public function withoutPreview(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->data['disable_web_page_preview'] = true;

        return $telegraph;
    }

    public function deleteMessage(int $messageId): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_DELETE_MESSAGE;
        $telegraph->data = [
            'chat_id' => $telegraph->getChat()->chat_id,
            'message_id' => $messageId,
        ];

        return $telegraph;
    }

    public function edit(int $messageId): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_EDIT_MESSAGE;
        $telegraph->data['message_id'] = $messageId;

        return $telegraph;
    }

    public function location(float $latitude, float $longitude): Telegraph
    {
        $this->endpoint = self::ENDPOINT_SEND_LOCATION;
        $this->data['latitude'] = $latitude;
        $this->data['longitude'] = $longitude;
        $this->data['chat_id'] = $this->getChat()->chat_id;

        return $this;
    }
}
