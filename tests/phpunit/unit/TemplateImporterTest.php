<?php

namespace TemplateImporter;

use MediaWikiUnitTestCase;
use SplFileInfo;
use TemplateImporter\Command\FakeCommand;
use TemplateImporter\Config\FakeConfig;
use TemplateImporter\Repository\MemoryFactoryRepository;

abstract class TemplateImporterTest extends MediaWikiUnitTestCase {
	public $mediawikiPath;
	public $lang = 'fr';
	public $repository;
	public $repositoryClass;
	public $fixtureDir = __DIR__ . '/../../fixtures/pages';

	public function setUp(): void {
		$this->mediawikiPath = __DIR__ . "/../../../../../";
		$this->config = new FakeConfig(
			$this->lang,
			new MemoryFactoryRepository(),
            new FakeCommand(),
            $this->mediawikiPath
		);
        $this->manager = new NamespaceManager( $this->config );
		$this->factory = $this->config->getFactory();
	}

	public function getFixture( $filename ) {
		return new SplFileInfo( $this->fixtureDir . '/' . $filename );
	}

}
