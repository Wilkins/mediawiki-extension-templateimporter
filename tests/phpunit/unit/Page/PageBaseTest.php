<?php

namespace TemplateImporter\Page;

use MediaWikiUnitTestCase;
use SplFileInfo;
use TemplateImporter\NamespaceManager;
use TemplateImporter\Repository\MemoryFactoryRepository;
use TemplateImporter\Command\FakeCommand;
use TemplateImporter\Config\FakeConfig;

abstract class PageBaseTest extends MediaWikiUnitTestCase {

	public $mediawikiPath;
	public $lang = 'fr';
	public $repository;
	public $repositoryClass;
	public $fixtureDir = __DIR__ . '/../../../fixtures/pages';

	public function setUp(): void {
		$this->mediawikiPath = __DIR__ . "/../../../../../../";
        $this->manager = new NamespaceManager( $this->mediawikiPath, $this->lang );
        $this->config = new FakeConfig(
            $this->lang,
            new MemoryFactoryRepository(),
            new FakeCommand()
        );
        $this->factory = $this->config->getFactory();

		$this->repository = $this->getRepositoryClass();
	}

	public function getFixture( $filename ) {
		return new SplFileInfo( $this->fixtureDir . '/' . $filename );
	}

}
