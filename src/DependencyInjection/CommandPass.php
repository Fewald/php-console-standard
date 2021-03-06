<?php

namespace {{project_namespace}}\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CommandPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $consoleAppId;

    /**
     * @param string $consoleName
     */
    public function __construct($consoleAppId)
    {
        $this->consoleAppId = $consoleAppId;
    }

    /**
     * Add console commands to the application.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition($this->consoleAppId)) {
            return;
        }

        $appDefinition = $container->getDefinition($this->consoleAppId);

        foreach ($container->findTaggedServiceIds($this->consoleAppId . '.command') as $id => $commands) {
            $appDefinition->addMethodCall('add', [new Reference($id)]);

            $this->addConfigurators($container, $commands, $id);
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param $commands
     * @param $id
     */
    private function addConfigurators(ContainerBuilder $container, $commands, $id)
    {
        foreach ($commands as $command) {
            if (isset($command['configurator'])) {
                $container->getDefinition($id)->addMethodCall(
                    'addCommandConfigurator',
                    [new Reference($command['configurator'])]
                );
            }
        }
    }
}
