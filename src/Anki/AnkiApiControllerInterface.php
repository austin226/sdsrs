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
     * @param string $collectionName
     *
     * @return array
     * @throws \Aalmond\Sdsrs\Exceptions\ResourceNotFoundException
     */
    public function listDecks(string $collectionName) : array;

    /**
     * Creates a card with the given front and back content. Returns
     * the ID of the new card.
     *
     * @param string $collectionName
     * @param string $front
     * @param string $back
     *
     * @return int
     * @throws \Aalmond\Sdsrs\Exceptions\ResourceNotFoundException
     */
    public function addCard(string $collectionName, string $front, string $back) : int;

    /**
     * Reads out the next card in the deck.
     *
     * @param string $collectionName
     * @param string $deckName
     *
     * @return array
     * @throws \Aalmond\Sdsrs\Exceptions\ResourceNotFoundException
     */
    public function nextCard(string $collectionName, string $deckName) : array;
}
