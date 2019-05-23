<?php

namespace Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateEntity extends Command
{
    protected function configure()
    {
        $this->setName('generate:entity');
        $this->addArgument("name", InputArgument::REQUIRED, 'Nom de l\'entité');
        $this->setDescription('génère une classe entité');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        // Entity
        $text = file_get_contents(__DIR__.'/templates/entity.template.php');
        file_put_contents(
            dirname(__DIR__, 2).'/app/src/Entity/'.$name.'.php',
            preg_replace('/PregReplace/', "$name", $text)
        );
        // Repository
        $text = file_get_contents(__DIR__.'/templates/repository.template.php');
        file_put_contents(
            dirname(__DIR__, 2).'/app/src/Entity/'.$name.'Repository.php',
            preg_replace('/PregReplace/', "$name", $text)
        );
        $output->writeln("Entité générée");
    }
}
