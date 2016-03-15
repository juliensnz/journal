<?php

namespace Application\Domain;

/**
 * Card renderer interface
 */
interface CardRendererInterface
{
    /**
     * Render the given card
     *
     * @return string
     */
    public function render(CardInterface $card);
}
