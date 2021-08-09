<?php

namespace TemplateImporter\Page;

use MediaWikiUnitTestCase;
use TemplateImporter\Repository\MemoryPageRepository;
use TemplateImporter\NamespaceManager;
use SplFileInfo;

class PageBaseTest extends MediaWikiUnitTestCase
{

    public $mediawikiPath;
    public $lang = 'fr';
    public $repository;

    public function setUp() : void
    {

		$this->mediawikiPath = __DIR__."/../../../../../../";
		$this->manager = new NamespaceManager( $this->mediawikiPath, $this->lang );
        $this->repository = new MemoryPageRepository();
    }

    public function getFixture( $filename )
    {
        return new SplFileInfo( __DIR__.'/../../../fixtures/'.$filename );
    }




}
