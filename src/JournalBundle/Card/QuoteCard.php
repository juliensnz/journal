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
    public function getData(array $context = [])
    {
        $client = new Client();

        do {
            $quote = json_decode($client->get(static::API_URL)->getBody(), true);
        } while (null === $quote);

        return array_replace_recursive(
            $this->data,
            [
                'quote' => $quote
            ]
        );
    }
}
