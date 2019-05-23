<?php

namespace Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheClear extends Command
{
    protected function configure()
    {
        $this->setName('cache:clear');
        $this->setDescription('Vide le dossier cache de twig');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = dirname(__DIR__, 2).'/storage/cache/';
        $output->writeln("Traitement des fichiers de cache...");
        $this->deleteDirectory($files);
        $output->writeln("Cache vidé avec succès");
    }

    protected function deleteDirectory($dirname)
    {
        $diractory_list = [];
        if (is_dir($dirname)) {
            $dir_handle = opendir($dirname);
            if (!$dir_handle) {
                return false;
            }
            while ($file = readdir($dir_handle)) {
                if ($file != "." && $file != ".." && $file != ".gitkeep") {
                    if (!is_dir($dirname."/".$file)) {
                        unlink($dirname."/".$file);
                    } else {
                        $diractory_list[] = $dirname.'/'.$file;
                        $this->deleteDirectory($dirname.'/'.$file);
                    }
                }
            }
            closedir($dir_handle);
        }
        foreach ($diractory_list as $value) {
            rmdir($value);
        }
        return true;
    }
}
