<?php

namespace Aalmond\Sdsrs\Anki;

interface AnkiApiControllerInterface
{
    /**
     * Lists all collections we know of.
     *
     * @return array
     */
    public function listCollections() : array;

    /**
     * Lists all decks in a collection. Throws a ResourceNotFoundException
     * if collection is not found.
     *
     * @return array
     * @throws \Aalmond\Sdsrs\Exceptions\ResourceNotFoundException
     */
    public function listDecks(string $collectionName) : array;
}
