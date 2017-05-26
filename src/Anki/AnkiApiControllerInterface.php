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

    /**
     * Creates a card with the given front and back content. Returns
     * the ID of the new card.
     *
     * @param string $front
     * @param string $back
     *
     * @return int
     */
    public function addCard(string $front, string $back) : int;
}
