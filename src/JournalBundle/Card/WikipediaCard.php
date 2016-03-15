<?php

namespace JournalBundle\Card;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Wikipedia card
 */
class WikipediaCard extends BaseCard
{
    const RANDOM_ARTICLE = 'http://en.wikipedia.org/wiki/Special:Random';
    const PRINTER_WIDTH  = 384;

    /**
     * {@inheritdoc}
     */
    public function getData(array $context = [])
    {
        return array_replace_recursive(
            $this->data,
            $this->getArticleContent(self::RANDOM_ARTICLE)
        );
    }

    protected function getArticleContent($url)
    {
        $client = new Client();
        $client->followRedirects(true);

        $valid = false;

        while (!$valid) {
            $infobox = null;
            $img = null;

            $crawler = $client->request('GET', $url);
            $infobox = $crawler->filter('#bodyContent table.infobox:not(.geography)')->count() > 0 ?
                $crawler->filter('#bodyContent table.infobox:not(.geography)')->first() :
                null;

            if ($infobox !== null && $infobox->filter('td > a > img')->count() > 0) {
                $img = $infobox->filter('img')->first();
            }

            if ($img &&
                (int) $img->attr('data-file-width') >= self::PRINTER_WIDTH &&
                (int) $img->attr('data-file-width') > (int) $img->attr('data-file-height') &&
                strlen($crawler->filter('#bodyContent .mw-content-ltr > p')->first()->text()) > 150) {
                $valid = true;
            }
        }

        return [
            'image_url'   => $img->attr('src'),
            'content'     => $crawler->filter('#bodyContent .mw-content-ltr > p')->first()->text(),
            'title'       => $crawler->filter('#firstHeading')->text()
        ];
    }
}
