<?php

namespace Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Dotenv\Dotenv;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Loader;

class DataFixture extends Command
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager = null;

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        $dotenv = Dotenv::create(dirname(__DIR__, 2));
        $dotenv->load(true);

        if (getenv('ENV') == 'dev') {
            $db = "DB_DEV";
        } elseif (getenv('ENV') == 'prod') {
            $db = "DB_PROD";
        }

        $settings = [
            'devMode' => true,
            'path' => ['app/src/Entity']
        ];

        if ($this->entityManager === null) {
            $config = Setup::createAnnotationMetadataConfiguration($settings['path'], $settings['devMode']);

            // define credentials...
            $connectionOptions = [
                'url' => getenv($db)
            ];

            $driver = new AnnotationDriver(new AnnotationReader(), $settings['path']);
            AnnotationRegistry::registerLoader('class_exists');
            $config->setMetadataDriverImpl($driver);
            $this->entityManager = EntityManager::create($connectionOptions, $config);
        }

        return $this->entityManager;
    }

    protected function configure()
    {
        $this->setName('data:fixture');
        $this->addArgument("name", InputArgument::REQUIRED, 'Nom de la fixture');
        $this->setDescription('Initialiser une fixture sans purger la base');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument("name");
        $output->writeln("Traitement de la fixture...");
        $loader = new Loader();
        $loader->loadFromFile(dirname(__DIR__, 2)."/app/src/Entity/DataFixtures/".$name.".php");
        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->getEntityManager(), $purger);
        $executor->execute($loader->getFixtures(), true);
        $output->writeln("Fixture envoyé");
    }
}
