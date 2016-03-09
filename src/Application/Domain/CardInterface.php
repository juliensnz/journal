<?php

namespace Application\Domain;

/**
 * Card interface
 */
interface CardInterface
{
    /**
     * Get the card template
     *
     * @return string
     */
    public function getTemplate(array $context = []);

    /**
     * Get additional template parameters
     *
     * @param array $context The twig context
     *
     * @return array
     */
    public function getParameters(array $context = []);

    /**
     * Indicates whether the view element should be displayed in the given context
     *
     * @param array $context The twig context
     *
     * @return boolean
     */
    public function isVisible(array $context = []);
}
