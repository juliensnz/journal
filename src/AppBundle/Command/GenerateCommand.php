<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('journal:print')
            ->setDescription('Print a journal edition')
            ->addOption(
                'dry-run',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will yell in uppercase letters'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Journal generation start');

        $edition = time();
        $uri = sprintf('%s/journals/%s.png', $this->getContainer()->getParameter('kernel.root_dir'), $edition);
        $output->writeln($uri);

        $rasterizeCommand = sprintf(
            'phantomjs %s/../rasterize.js http://journal.dev %s',
            $this->getContainer()->getParameter('kernel.root_dir'),
            $uri
        );
        exec($rasterizeCommand);

        $convertCommand = sprintf(
            'convert %s -monochrome %s',
            $uri,
            $uri
        );
        exec($convertCommand);

        exec(sprintf('open %s', $uri));

        if ($input->getOption('dry-run')) {

        }

        $output->writeln('Finished !');
    }
}
