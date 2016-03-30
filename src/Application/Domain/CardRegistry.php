<?php

namespace Application\Domain;

/**
 * Registry of card
 */
class CardRegistry
{
    /** @var CardInterface[] */
    protected $cards = [];

    /**
     * Register a view card
     *
     * @param CardInterface $card
     */
    public function add(CardInterface $card, $code)
    {
        $this->cards[$code] = $card;
    }

    /**
     * Get all the cards
     *
     * @return CardInterface[]
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * Get the view card for the given code
     *
     * @param string $code
     *
     * @return CardInterface
     */
    public function get($code)
    {
        return $this->cards[$code];
    }
}
