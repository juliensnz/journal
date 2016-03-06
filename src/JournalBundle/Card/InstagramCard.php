<?php

namespace JournalBundle\Card;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Instagram card
 */
class InstagramCard extends BaseCard
{
    //const ACCESS_TOKEN  = 'a8306941c383455a87570aeefbb64226';
    const CLIENT_ID            = '1c45cb4ec8924441b9cf3efabaaf5ad5';
    const USER_PHOTOSTREAM_URL = 'https://api.instagram.com/v1/users/%s/media/recent/?client_id=%s';
    const MEDIA_URL            = 'https://api.instagram.com/v1/media/%s?client_id=%s';


    protected $favoriteUsers = [
        '1667424273',
        '1598842984',
        '323193898',
        '181370969'
    ];

    /**
     * {@inheritdoc}
     */
    public function getParameters(array $context = [])
    {
        return array_replace_recursive(
            $this->parameters,
            $this->getPictureContent()
        );
    }

    protected function getPictureContent()
    {
        $pictures = [];
        foreach ($this->favoriteUsers as $user) {
            $response = json_decode(file_get_contents(
                sprintf(self::USER_PHOTOSTREAM_URL, $user, self::CLIENT_ID)
            ), true);

            $pictures = $pictures + $response['data'];
        }

        $selectedPicture = $pictures[array_rand($pictures)];

        return [
            'image_url' => $selectedPicture['images']['standard_resolution']['url'],
            'comment'   => $selectedPicture['caption']['text'],
            'user'      => $selectedPicture['user']['username'],
            'likes'     => $selectedPicture['likes']['count']
        ];
    }
}
