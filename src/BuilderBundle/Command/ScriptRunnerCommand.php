<?php

namespace BuilderBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScriptRunnerCommand extends ContainerAwareCommand
{
    private $_Socket;
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('builder:script_runner_command')
            ->setDescription('Hello PhpStorm')
            ->addArgument('aaa', InputArgument::REQUIRED, 'Your last name?');

    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = base64_decode($input->getArgument('aaa'));
        $output = [];
        $return_var = -1;

//        exec($command, $output, $return_var);
//
//        if ($return_var !== 0) {
//            throw new \Exception(implode("\n", $output));
//        }
    }
}
