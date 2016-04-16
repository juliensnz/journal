<?php

namespace JournalBundle\Card;

use GuzzleHttp\Client;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Weather card
 */
class WeatherCard extends BaseCard
{
    const API_URL = 'http://api.openweathermap.org/data/2.5/forecast?q=%s,fr&mode=json&appid=8c3d7e8846fbfccf9e9a15ae94481796&units=metric';

    /**
     * {@inheritdoc}
     */
    public function getData(array $options = [])
    {
        $options = $this->configureOptions($options);

        $client = new Client();
        $response = json_decode($client->get(sprintf(static::API_URL, $options['location']))->getBody(), true);

        $weather = [];
        foreach ($response['list'] as $forecast) {
            $forecast['dt'] = new \DateTime('@' . $forecast['dt']);
            $diff = $forecast['dt']->diff(new \DateTime('now'));
            if (in_array($forecast['dt']->format('G'), ['9', '18']) && ($diff->h + $diff->days * 24) < 24) {
                $weather[] = $forecast;
            }
        }

        return array_replace_recursive(
            $this->data,
            [
                'weather' => $weather
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
            'location'  => 'Nantes'
        ]);

        return $resolver->resolve($options);
    }
}
