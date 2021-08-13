<?php

namespace TemplateImporter\Config;

use TemplateImporter\Command\CommandInterface;
use TemplateImporter\Repository\FactoryRepositoryInterface;

abstract class AbstractConfig implements ConfigInterface {

	private $lang;
	private $factory;
    private $command;
    private $mediaWikiPath;

	public function __construct(
		$lang,
		FactoryRepositoryInterface $factory,
        CommandInterface $command,
        string $mediaWikiPath
	) {
		$this->lang = $lang;
		$this->factory = $factory;
        $this->command = $command;
        $this->mediaWikiPath = $mediaWikiPath;
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

    public function getMediaWikiPath() {
        if ( file_exists( $this->mediaWikiPath ) ) {
            return realpath( $this->mediaWikiPath );
        }
        return $this->mediaWikiPath;
	}

	public function setMediaWikiPath( $mediaWikiPath ) {
		$this->mediaWikiPath = $mediaWikiPath;
	}

	abstract public function getFileExtensions();
}
