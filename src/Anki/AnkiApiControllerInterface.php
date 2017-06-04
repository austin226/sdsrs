<?php

namespace Aalmond\Sdsrs\Anki;

use Aalmond\Sdsrs\ApiAi\SpeechResponse;

interface AnkiApiControllerInterface
{
    /**
     * Lists all collections we know of.
     *
     * @return SpeechResponse
     */
    public function listCollections() : SpeechResponse;

    /**
     * Lists all decks in a collection. Throws a ResourceNotFoundException
     * if collection is not found.
     *
     * @param string $collectionName
     *
     * @return array
     * @throws \Aalmond\Sdsrs\Exceptions\ResourceNotFoundException
     */
    public function listDecks(string $collectionName) : SpeechResponse;

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
    public function addCard(string $collectionName, string $front, string $back) : SpeechResponse;

    /**
     * Reads out the next card in the deck, or [] if the end of the deck was reached.
     *
     * @param string $collectionName
     * @param string $deckName
     *
     * @return array
     * @throws \Aalmond\Sdsrs\Exceptions\ResourceNotFoundException
     */
    public function nextCard(string $collectionName, string $deckName) : SpeechResponse;

    /**
     * Resets the scheduler, returning an array representing how many cards are left now.
     *
     * @param string $collectionName
     * @param string $deckName
     *
     * @return array
     * @throws \Aalmond\Sdsrs\Exceptions\ResourceNotFoundException
     */
    public function resetScheduler(string $collectionName, string $deckName) : SpeechResponse;

    /**
     * @param string $collectionName
     * @param string $cardID
     * @param string $answer
     *
     * @return array
     * @throws \Aalmond\Sdsrs\Exceptions\ResourceNotFoundException
     */
    public function answerCard(string $collectionName, string $cardID, string $answer) : SpeechResponse;
}
