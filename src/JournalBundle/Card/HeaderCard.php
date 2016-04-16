<?php

namespace JournalBundle\Card;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Basic card
 */
class HeaderCard extends BaseCard
{
    /**
     * {@inheritdoc}
     */
    public function getData(array $options = [])
    {
        $options = $this->configureOptions($options);

        return array_replace_recursive(
            $this->data,
            [
                'date'     => new \DateTime(),
                'company'  => $options['company'],
                'name'     => $options['name'],
                'location' => $options['location']
            ]
        );
    }

    /**
     * Configure the default options
     *
     * @param  array  $options
     *
     * @return array
     */
    protected function configureOptions(array $options)
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired('position');
        $resolver->setDefaults([
            'company'  => 'Akeneo',
            'name'     => 'Julien Sanchez',
            'location' => 'Nantes',
        ]);

        return $resolver->resolve($options);
    }
}
