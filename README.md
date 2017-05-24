# sdsrs
Spoken Dialog Spaced-Repetition System

This is the front-end client, designed for use with [sdsrs-anki-server](https://github.com/austin226/sdsrs-anki-server) which in turn uses [anki-sync-server](https://github.com/dsnopek/anki-sync-server).

# API
| action            | method | example URL                      | data                    | success response | failure response |
|-------------------|--------|----------------------------------|-------------------------|------------------|------------------|
| list_collections  | GET    | api.php?action=list_collections  |                         | 200              |                  |
| select_collection | POST   | api.php?action=select_collection | {"name": "collection1"} | 200              | 404              |
| create_collection | POST   | api.php?action=create_collection | {"name": "collection1"} | 201              | 422              |
