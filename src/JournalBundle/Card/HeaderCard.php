<?php

namespace JournalBundle\Card;

/**
 * Basic card
 */
class HeaderCard extends BaseCard
{
    /**
     * {@inheritdoc}
     */
    public function getParameters(array $context = [])
    {
        return array_replace_recursive(
            $this->parameters,
            [
                'date'     => new \DateTime(),
                'company'  => 'Akeneo',
                'name'     => 'Julien Sanchez',
                'location' => 'Nantes'
            ]
        );
    }
}
