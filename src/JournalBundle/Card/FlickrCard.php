<?php

namespace JournalBundle\Card;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Flickr card
 */
class FlickrCard extends BaseCard
{
    const API_KEY  = 'ac106666edbf68e3d9b0f4869d7ded64';
    const FEED_URL = 'https://api.flickr.com/services/feeds/photos_friends.gne?user_id=128809835@N06&format=rss2';
    const API_URL  = 'https://api.flickr.com/services/rest/';

    const FIND_BY_USERNAME_METHOD = 'flickr.people.findByUsername';
    const GET_PHOTOS_INFO_METHOD  = 'flickr.photos.getInfo';

    const POPULAR_LIMIT = 1000;

    const PICTURE_SCHEME = 'https://farm%s.staticflickr.com/%s/%s_%s.jpg';

    /**
     * {@inheritdoc}
     */
    public function getData(array $context = [])
    {
        return array_replace_recursive(
            $this->data,
            $this->getPictureContent('ossdoubleonesix')
        );
    }

    protected function getPictureContent($username)
    {
        $userId = $this->getUserId($username);

        $client = new Client();
        $client->followRedirects(true);

        $crawler = $client->request(
            'GET',
            $this->buildGetUrl(self::FEED_URL, ['user_id' => $userId, 'format' => 'rss2'])
        );

        $items = $crawler->filter('item');

        $shulledItems = [];
        foreach ($items as $item) {
            $shulledItems[] = new Crawler($item);
        }
        shuffle($shulledItems);

        $selectedPicture = null;
        $i = 0;
        while ($i < count($shulledItems) && null === $selectedPicture) {
            $pictureId = explode('/', $shulledItems[$i]->filter('guid')->text())[2];

            if ($this->getPictureInfo($pictureId)['views'] > self::POPULAR_LIMIT) {
                $selectedPicture =  $this->getPictureInfo($pictureId);
            }

            $i++;
        }

        return [
            'image_url' => $this->buildPictureUrl($selectedPicture),
            'title'     => $selectedPicture['title']['_content'],
            'views'     => $selectedPicture['views']
        ];
    }

    protected function getUserId($username)
    {
        $response = file_get_contents(
            $this->buildGetUrl(
                self::API_URL,
                [
                    'method'   => self::FIND_BY_USERNAME_METHOD,
                    'api_key'  => self::API_KEY,
                    'username' => $username,
                    'format'   => 'json',
                    'nojsoncallback' => 1
                ]
            )
        );

        $response = json_decode($response, true);

        return $response['user']['id'];
    }

    protected function getPictureInfo($pictureId)
    {
        $response = file_get_contents(
            $this->buildGetUrl(
                self::API_URL,
                [
                    'method'   => self::GET_PHOTOS_INFO_METHOD,
                    'api_key'  => self::API_KEY,
                    'photo_id' => $pictureId,
                    'format'   => 'json',
                    'nojsoncallback' => 1,
                    'count_faves' => 1
                ]
            )
        );

        $response = json_decode($response, true);

        return $response['photo'];
    }

    protected function buildGetUrl($url, array $params = [])
    {
        return sprintf(
            '%s?%s',
            $url,
            http_build_query($params)
        );
    }

    protected function buildPictureUrl($pictureInfos)
    {
        return sprintf(
            self::PICTURE_SCHEME,
            $pictureInfos['farm'],
            $pictureInfos['server'],
            $pictureInfos['id'],
            $pictureInfos['secret']
        );
    }
}
