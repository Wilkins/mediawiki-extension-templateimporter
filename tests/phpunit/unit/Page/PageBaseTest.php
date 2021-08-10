<?php

namespace TemplateImporter\Page;

use MediaWikiUnitTestCase;
use SplFileInfo;
use TemplateImporter\NamespaceManager;

class PageBaseTest extends MediaWikiUnitTestCase {

	public $mediawikiPath;
	public $lang = 'fr';
	public $repository;
    public $repositoryClass;
    public $fixtureDir = __DIR__ . '/../../../fixtures';

	public function setUp(): void {
		$this->mediawikiPath = __DIR__ . "/../../../../../../";
		$this->manager = new NamespaceManager( $this->mediawikiPath, $this->lang );
		if ( $this->repositoryClass ) {
			$this->repository = new $this->repositoryClass();
		}
	}

	public function getFixture( $filename ) {
		return new SplFileInfo( $this->fixtureDir . '/' . $filename );
	}

}
