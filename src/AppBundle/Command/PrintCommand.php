<?php

namespace AppBundle\Command;

use Printer\Printer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PrintCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('journal:print')
            ->setDescription('Print a journal edition')
            ->addArgument(
                'id',
                InputArgument::REQUIRED,
                'Journal id'
            )
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
            'phantomjs %s/../rasterize.js http://localhost/app.php/journal/%s/render %s',
            $this->getContainer()->getParameter('kernel.root_dir'),
            $input->getArgument('id'),
            $uri
        );
        exec($rasterizeCommand);
        $output->writeln('screenshot done');

        $convertCommand = sprintf(
            'convert %s -monochrome -crop 384x+0+0 %s.png',
            $uri,
            $uri
        );
        exec($convertCommand);
        $output->writeln('monochrome done');

        if (!$input->getOption('dry-run')) {
            $this->printImage($uri);
        }
        $output->writeln('printimage done');

        $output->writeln('Finished !');
    }

    protected function printImage($uri)
    {
        $image = new \Imagick($uri);
        $printer = new Printer(['device' => '/dev/ttyAMA0', 'baudrate' => 19200]);
        $printer->wake();
        $printer->printImage($image);
        $printer->feed();
        $printer->sleep();
        $printer->setDefault();
    }
}
