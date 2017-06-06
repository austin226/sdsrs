# sdsrs
Spoken Dialog Spaced-Repetition System

This server is designed for use with [sdsrs-anki-server](https://github.com/austin226/sdsrs-anki-server) which in turn uses [anki-sync-server](https://github.com/dsnopek/anki-sync-server). It responds to intents from [sdsrs-api.ai](https://github.com/austin226/sdsrs-api.ai).

# Config
## Server Configuration
You need to input an sdsrs-anki-server base URI (IP and port) into a file called `api_config.json`. Check `api_config.example.json` for an example.

## Composer
This repository uses [Composer](https://getcomposer.org/) for dependency management. You must install the composer dependencies by calling `composer install` from within the project's base directory.

# API
The API expects calls from an <a href="https://docs.api.ai/docs/webhook">api.ai Webhook</a>.
* <a href="https://docs.api.ai/docs/webhook#section-format-of-request-to-the-service">Format of Request to the Service</a>
* <a href="https://docs.api.ai/docs/webhook#section-format-of-response-from-the-service">Format of Response to the Service</a>

## Valid requests
The following POST requests formatted in <a href="https://docs.api.ai/docs/webhook#section-format-of-request-to-the-service">api.ai style</a> are valid:

| intent                | parameters                                |
|-----------------------|-------------------------------------------|
| `list_collections`    | None                                      |
| `add_card`            | `collection`, `front`, `back`             |
| `next_card`           | `collection`                              |
| `reset_scheduler`     | `collection`                              |
| `answer_card`         | `collection`, `card_id`, `answer_ease`    |
