<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Dumper;

class CardController extends Controller
{
    /**
     * @Route("/cards", name="journal_card_index")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $cardRegistry = $this->container->get('journal.registry.card');

        $cards = $cardRegistry->getCards();
        $cards = array_map(function ($card, $code) {
            return array_merge($card->getProperties(), ['code' => $code]);
        }, $cards, array_keys($cards));

        return new JsonResponse(array_values($cards));
    }
}
