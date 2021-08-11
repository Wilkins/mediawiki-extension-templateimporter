<?php

namespace TemplateImporter;

use MediaWikiUnitTestCase;
use TemplateImporter\Repository\MemoryFactoryRepository;
use TemplateImporter\Command\FakeCommand;
use TemplateImporter\Config\FakeConfig;


class BaseImporterTest extends MediaWikiUnitTestCase {

    public $fixturesDir = __DIR__.'/../../fixtures/pages';

    public function setUp() : void {
        $this->mediawikiPath = __DIR__ . "/../../../../../";
        $this->manager = new NamespaceManager( $this->mediawikiPath, 'fr' );
        $this->config = new FakeConfig(
            'fr',
            new MemoryFactoryRepository(),
            new FakeCommand()
        );

    }


    public function testListFiles() {

        $importer = new BaseImporter( 
            $this->config
		 );
        $importer->listFiles( $this->fixturesDir );

        $this->assertTrue( true );
    }


    public function testListFilesNotADirectory() {

        $importer = new BaseImporter( 
            $this->config
        );
        $this->expectException( 'TemplateImporter\Exception\Exception' );
        $importer->listFiles( 'MissingDirectory' );

        $this->assertTrue( true );
    }


    public function testConstructWithDefaultConfig() {
        $importer = new BaseImporter();
        $this->assertEquals(
            'TemplateImporter\Config\MediaWikiConfig',
            get_class( $importer->config ),
            "Default config is not a MediaWikiConfig"

         );

    }

    /*
    public function testConstructWithLang() {
        $this->expectException( 'TemplateImporter\Exception\Exception' );
        $importer = new BaseImporter( 
            $this->config
		 );

    }
     */


}
