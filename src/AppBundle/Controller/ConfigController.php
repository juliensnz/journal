<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ConfigController extends Controller
{
    /**
     * @Route("/cards", name="cards")
     */
    public function indexAction()
    {
        $cardRegistry = $this->container->get('journal.registry.card');

        $cards = $cardRegistry->getCards();
        $cards = array_map(function ($card) {
            return $card->getProperties();
        }, $cards);

        return new JsonResponse(array_values($cards));
    }
}
