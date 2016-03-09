<?php

namespace JournalBundle\Card;

use GuzzleHttp\Client;

/**
 * Quote card
 */
class QuoteCard extends BaseCard
{
    const API_URL = 'http://api.forismatic.com/api/1.0/?method=getQuote&format=json&lang=en';

    /**
     * {@inheritdoc}
     */
    public function getParameters(array $context = [])
    {
        $client = new Client();
        $quote = json_decode($client->get(static::API_URL)->getBody(), true);
        return array_replace_recursive(
            $this->parameters,
            [
                'quote' => $quote
            ]
        );
    }
}
