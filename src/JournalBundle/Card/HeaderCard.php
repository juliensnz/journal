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
                'company'  => $context['company'],
                'name'     => $context['name'],
                'location' => $context['location']
            ]
        );
    }
}
