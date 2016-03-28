<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class RenderController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $journalRenderer = $this->container->get('journal.renderer.html_journal');
        $cardRegistry = $this->container->get('journal.registry.card');

        return new Response($journalRenderer->render($cardRegistry->getCards()));
    }
}
