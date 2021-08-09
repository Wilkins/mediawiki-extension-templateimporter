<?php

namespace TemplateImporter;

use MediaWikiUnitTestCase;
use SplFileInfo;

class PageFactoryTest extends MediaWikiUnitTestCase
{
    public $mediawikiPath;
    public $lang = 'fr';

    public function setUp() : void
    {

		$this->mediawikiPath = __DIR__."/../../../../../";
		$this->manager = new NamespaceManager( $this->mediawikiPath, $this->lang );
	}


    public function testDummy()
    {
        $this->assertTrue( true );
    }


    public function testPage()
    {

        $file = new SplFileInfo( __DIR__.'/../../fixtures/Attribut:Longueur.txt' );
        $repo = new MemoryPageRepository();



        $page = PageFactory::create(
            $file->getBasename(),
            $file->getPathname(),
            $repo
        );
        
        $this->assertTrue( true );
    }




}
