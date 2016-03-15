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
    protected $data = [];

    /**
     * @param string $template
     * @param array  $data
     */
    public function __construct($template, array $data = [])
    {
        $this->template = $template;
        $this->data     = $data;
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
    public function getData(array $context = [])
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function isVisible(array $context = [])
    {
        return true;
    }
}
