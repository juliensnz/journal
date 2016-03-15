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
     * @param string        $type
     * @param integer       $position
     */
    public function add(CardInterface $card, $type, $position)
    {
        if (!isset($this->cards[$type][$position])) {
            $this->cards[$type][$position] = $card;
        } else {
            $this->add($card, $type, ++$position);
        }
    }

    public function getCards()
    {
        $selection = [];

        foreach ($this->cards as $type => $card) {
            $position = array_rand($card);
            $card = $card[$position];

            while (isset($selection[$position])) {
                $position++;
            }

            $selection[$position] = $card;
        }

        return $selection;
    }

    /**
     * Get the view cards for the given type
     *
     * @param string $type
     *
     * @return CardInterface[]
     */
    public function get($type)
    {
        $cards = isset($this->cards[$type]) ? $this->cards[$type] : [];
        ksort($cards);

        return $cards;
    }
}
