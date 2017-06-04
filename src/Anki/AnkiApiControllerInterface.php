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
     * Creates a card with the given front and back content. Returns
     * the ID of the new card.
     *
     * @param string $collectionName
     * @param string $front
     * @param string $back
     *
     * @return \Aalmond\Sdsrs\ApiAi\SpeechResponse
     * @throws \Aalmond\Sdsrs\Exceptions\HttpException
     */
    public function addCard(string $collectionName, string $front, string $back) : SpeechResponse;

    /**
     * Reads out the next card in the deck, or [] if the end of the deck was reached.
     *
     * @param string $collectionName
     *
     * @return \Aalmond\Sdsrs\ApiAi\SpeechResponse
     * @throws \Aalmond\Sdsrs\Exceptions\HttpException
     */
    public function nextCard(string $collectionName) : SpeechResponse;

    /**
     * Resets the scheduler, returning an array representing how many cards are left now.
     *
     * @param string $collectionName
     *
     * @return \Aalmond\Sdsrs\ApiAi\SpeechResponse
     * @throws \Aalmond\Sdsrs\Exceptions\HttpException
     */
    public function resetScheduler(string $collectionName) : SpeechResponse;

    /**
     * @param string $collectionName
     * @param string $cardID
     * @param string $answer
     *
     * @return \Aalmond\Sdsrs\ApiAi\SpeechResponse
     * @throws \Aalmond\Sdsrs\Exceptions\HttpException
     */
    public function answerCard(string $collectionName, string $cardID, string $answer) : SpeechResponse;
}
