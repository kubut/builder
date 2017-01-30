<?php
namespace BuilderBundle\Command;

use BuilderBundle\WebSocket\DataBasesChannel;
use BuilderBundle\WebSocket\Trouble;
use Ratchet\App;
use Ratchet\Http\Router;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class WebSocketServerCommand extends ContainerAwareCommand
{
    /**
     * Configure function
     */
    protected function configure()
    {
        $this
            ->setName('websocket:server')
            ->setDescription('Starting notes server');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $databasesChannel = $this->getContainer()->get('app.builder.websocket.channel.databases');
        $instancesChannel = $this->getContainer()->get('app.builder.websocket.channel.instances');
        $app = new App("builder.vagrant", 8080, '0.0.0.0');
        $app->route('/databases', $databasesChannel, array('*'));
        $app->route('/instances', $instancesChannel, array('*'));
        $app->run();
    }
}
