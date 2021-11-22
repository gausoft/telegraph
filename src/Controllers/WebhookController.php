<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Controllers;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymphonyResponse;

class WebhookController
{
    public function handle(Request $request, TelegraphBot $telegraph_bot): Response
    {
        /** @var class-string $handler */
        $handler = config('telegraph.webhook_handler');

        /** @var WebhookHandler $handler */
        $handler = app($handler);

        $handler->handle($request, $telegraph_bot);

        return \response()->noContent();
    }
}
