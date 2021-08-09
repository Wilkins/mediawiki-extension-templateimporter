<?php

namespace TemplateImporter\Page;

use MediaWikiUnitTestCase;
use TemplateImporter\Repository\MemoryPageTextRepository;
use TemplateImporter\NamespaceManager;
use SplFileInfo;

class PageBaseTest extends MediaWikiUnitTestCase
{

    public $mediawikiPath;
    public $lang = 'fr';
    public $repository;
    public $repositoryClass;

    public function setUp() : void
    {

		$this->mediawikiPath = __DIR__."/../../../../../../";
        $this->manager = new NamespaceManager( $this->mediawikiPath, $this->lang );
        if ( $this->repositoryClass ) {
            $this->repository = new $this->repositoryClass();
        }
    }

    public function getFixture( $filename )
    {
        return new SplFileInfo( __DIR__.'/../../../fixtures/'.$filename );
    }




}
