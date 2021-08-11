<?php

namespace TemplateImporter\Config;

use TemplateImporter\Repository\FactoryRepositoryInterface;
use TemplateImporter\Command\CommandInterface;

abstract class AbstractConfig implements ConfigInterface {

    private $lang;
    private $factory;
    private $command;

    public function __construct(
        $lang,
        FactoryRepositoryInterface $factory,
        CommandInterface $command
    ) {
        $this->lang = $lang;
        $this->factory = $factory;
        $this->command = $command;
    }

    /*
    public function getLang() {
        return $this->lang;
    }
    */

    public function getFactory() {
        return $this->factory;
    }

    public function getCommand() {
        return $this->command;
    }

    abstract public function getFileExtensions();
}
