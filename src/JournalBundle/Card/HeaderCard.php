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
    public function getData(array $context = [])
    {
        return array_replace_recursive(
            $this->data,
            [
                'date'     => new \DateTime(),
                'company'  => 'Akeneo',
                'name'     => 'Julien Sanchez',
                'location' => 'Nantes'
            ]
        );
    }
}
