<?php

namespace JournalBundle\Card;

use Application\Domain\CardInterface;

/**
 * Basic card
 */
class BaseCard implements CardInterface
{
    /** @var string */
    protected $template;

    /** @var array */
    protected $parameters = [];

    /**
     * @param string $template
     * @param array  $parameters
     */
    public function __construct($template, array $parameters = [])
    {
        $this->template   = $template;
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate(array $context = [])
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(array $context = [])
    {
        return $this->parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function isVisible(array $context = [])
    {
        return true;
    }
}
