# sdsrs
Spoken Dialog Spaced-Repetition System

This is the front-end client, designed for use with [sdsrs-anki-server](https://github.com/austin226/sdsrs-anki-server) which in turn uses [anki-sync-server](https://github.com/dsnopek/anki-sync-server).

# API
| action            | method | example URL                      | data                    | success response | failure response |
|-------------------|--------|----------------------------------|-------------------------|------------------|------------------|
| list_collections  | GET    | api.php?action=list_collections  |                         | 200              |                  |
| create_collection | POST   | api.php?action=create_collection | {"name": "collection1"} | 201              | 422              |
| list_decks        | GET    | api.php?action=list_decks&collection={$collectionName}        |                         | 200              | 404              |
| create_deck       | POST   | api.php?action=create_deck       | {"collection": "collection1", "name": "deck1"}       | 201              | 404,422               |
| add_card          |   POST   |  api.php?action=add_card       | {"collection": "collection1", "deck": "deck1", "front": "front1", "back": "back1"} | 201 {"card_name": "card1"} | 404,422 |
| next_card | POST | api.php?action=next_card | {"collection": "collection1", "deck": "deck1"} | 200 | 404 |
| answer_card      | POST | api.php?action=answer_card | {"collection": "collection1", "deck": "deck1", "answer": "hard"} | 200 | 404,422 |
