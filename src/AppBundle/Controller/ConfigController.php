<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

class ConfigController extends Controller
{
    /**
     * @Route("/{id}/config", name="journal_config_get")
     * @Method({"GET"})
     */
    public function getAction($id)
    {
        $yaml = new Parser();
        $config = file_get_contents($this->container->getParameter('kernel.root_dir') . '/journals/config/' . $id . '.yml');
        $config = $yaml->parse($config);

        return new JsonResponse($config);
    }

    /**
     * @Route("/{id}/config", name="journal_config_post")
     * @Method({"POST"})
     */
    public function postAction(Request $request, $id)
    {
        $journal = json_decode($request->getContent(), true);
        $dumper = new Dumper();

        $yaml = $dumper->dump(['cards' => $journal], 100);
        file_put_contents($this->container->getParameter('kernel.root_dir') . '/journals/config/' . $id . '.yml', $yaml);

        return new JsonResponse($journal);
    }
}
