<?php

namespace JournalBundle\Card;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Flickr card
 */
class FlickrCard extends BaseCard
{
    const API_KEY  = '291dc5838e9645cf6d056bc3ea8af49d';
    const API_URL  = 'https://api.flickr.com/services/rest/';

    const FIND_BY_USERNAME_METHOD = 'flickr.people.findByUsername';

    const POPULAR_LIMIT = 1000;

    const PICTURE_SCHEME = 'https://farm%s.staticflickr.com/%s/%s_%s.jpg';

    /**
     * {@inheritdoc}
     */
    public function getData(array $options = [])
    {
        $options = $this->configureOptions($options);

        return array_replace_recursive(
            $this->data,
            $this->getPictureContent($options['username'])
        );
    }

    protected function getPictureContent($username)
    {
        if (false === strstr($username, '@')) {
            $userId = $this->getUserId($username);
        } else {
            $userId = $username;
        }

        $pictures = json_decode(file_get_contents($this->buildGetUrl(static::API_URL, [
            'api_key'        => static::API_KEY,
            'method'         => 'flickr.people.getPublicPhotos',
            'user_id'        => $userId,
            'per_page'       => 1000,
            'format'         => 'json',
            'nojsoncallback' => 1
        ])), true);

        $photos = $pictures['photos']['photo'];
        $photo = $photos[array_rand($photos)];

        return ['image_url' => $this->buildPictureUrl($photo)];
    }

    protected function getUserId($username)
    {
        $response = file_get_contents(
            $this->buildGetUrl(
                static::API_URL,
                [
                    'method'   => static::FIND_BY_USERNAME_METHOD,
                    'api_key'  => static::API_KEY,
                    'username' => $username,
                    'format'   => 'json',
                    'nojsoncallback' => 1
                ]
            )
        );

        $response = json_decode($response, true);

        return $response['user']['id'];
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
            static::PICTURE_SCHEME,
            $pictureInfos['farm'],
            $pictureInfos['server'],
            $pictureInfos['id'],
            $pictureInfos['secret']
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
            'username' => '92650625@N06'
        ]);

        return $resolver->resolve($options);
    }
}
