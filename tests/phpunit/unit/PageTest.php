<?php

namespace TemplateImporter;

use MediaWikiUnitTestCase;
use SplFileInfo;

class PageTest extends MediaWikiUnitTestCase
{
    public $mediawikiPath;
    public $lang = 'fr';
    public $repository;

    public function setUp() : void
    {

		$this->mediawikiPath = __DIR__."/../../../../../";
		$this->manager = new NamespaceManager( $this->mediawikiPath, $this->lang );
        $this->repository = new MemoryPageRepository();
	}



    public function dataProviderVersions() {
        return [
            [ 'Attribut:Longueur.txt', "1.12.1", "Update (v1.12.1)" ],
            [ 'Catégorie:Voyages.txt', "1.12.1", "Update (v1.12.1)" ],
            [ 'Modèle:Radian.txt', "1.12.1", "Update (v1.12.1)" ],
            //[ 'Voyage:Tourisme.txt', "1.12.1", "Update (v1.12.1)" ],
        ];
    }

    public function dataProviderPages() {
        return [
            [ 'Attribut:Longueur.txt', false, 'SMW_NS_PROPERTY' ],
            [ 'Catégorie:Voyages.txt', true, 'NS_CATEGORY' ],
            [ 'Modèle:Radian.txt', false, 'NS_TEMPLATE' ],
            //[ 'Voyage:Tourisme.txt', false, 'NS_PROJECT' ],
        ];
    }

    /**
     * @dataProvider dataProviderVersions
     */
    public function testPageDetectVersion( 
        $filename, $expectedVersion, $comment )
    {

        $file = new SplFileInfo( __DIR__.'/../../fixtures/'.$filename );
        $this->repository->comment = $comment;;

        $page = new Page(
            $file->getBasename(),
            $file->getPathname(),
            $this->repository
        );

        $this->assertEquals( $expectedVersion, $page->getVersion(),
            "Detected version does not match expected"
        );
        
    }


    /**
     * @dataProvider dataProviderPages
     */
    public function testPageDetectPages( 
        $filename, $expectedCategoryStatus, $namespaceConstant )
    {

        $file = new SplFileInfo( __DIR__.'/../../fixtures/'.$filename );

        $page = new Page(
            $file->getBasename(),
            $file->getPathname(),
            $this->repository
        );

        $this->assertEquals( constant( $namespaceConstant ), $page->namespaceId,
            "Detected namespace does not match expected"
        );
        
        $this->assertSame( $expectedCategoryStatus, $page->isCategory(), 
            "Detected category status does not match expected"
        );
    }


}

