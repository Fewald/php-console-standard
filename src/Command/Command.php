<?php

namespace {{project_namespace}}\Command;

use {{project_namespace}}\CommandConfigurator\CommandConfigurator;

abstract class Command extends \Symfony\Component\Console\Command\Command
{
    public function addCommandConfigurator(CommandConfigurator $commandConfigurator)
    {
        $commandConfigurator->configure($this);
    }
}
