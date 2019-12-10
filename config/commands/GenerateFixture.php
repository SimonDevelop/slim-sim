<?php

namespace Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateFixture extends Command
{
    protected function configure()
    {
        $this->setName('generate:fixture');
        $this->addArgument("name", InputArgument::REQUIRED, 'Nom de la fixture');
        $this->setDescription('génère une classe fixture');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $text = file_get_contents(__DIR__.'/templates/fixture.template.php');
        file_put_contents(
            dirname(__DIR__, 2).'/app/src/Entity/DataFixtures/'.$name.'.php',
            preg_replace('/PregReplace/', "$name", $text)
        );
        $output->writeln("Fixture générée");
    }
}
