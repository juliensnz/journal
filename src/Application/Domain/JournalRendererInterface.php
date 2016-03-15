<?php

namespace Application\Domain;

/**
 * Journal renderer interface
 */
interface JournalRendererInterface
{
    /**
     * Render the given journal
     *
     * @return string
     */
    public function render(array $cards);
}
