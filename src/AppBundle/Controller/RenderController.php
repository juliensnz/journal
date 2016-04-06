<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Parser;

class RenderController extends Controller
{
    /**
     * @Route("/{id}/render", name="homepage")
     */
    public function renderAction($id)
    {
        $yaml = new Parser();
        $config = file_get_contents($this->container->getParameter('kernel.root_dir') . '/journals/config/' . $id . '.yml');
        $config = $yaml->parse($config);

        $journalRenderer = $this->container->get('journal.renderer.html_journal');
        $cardRegistry = $this->container->get('journal.registry.card');

        return new Response($journalRenderer->render($config));
    }

    /**
     * @Route("/{id}/print", name="print")
     */
    public function printAction($id)
    {
        $kernel = $this->container->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
           'command' => 'journal:print',
           'id'      => $id
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $content = $output->fetch();

        return new Response($content);
    }
}
