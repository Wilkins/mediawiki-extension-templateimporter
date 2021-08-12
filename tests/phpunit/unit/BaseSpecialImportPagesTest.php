<?php

namespace TemplateImporter;


class BaseSpecialImportPagesTest extends TemplateImporterTest {



    public function dataProviderWikiContents() {
        // Content
        // is wiki content ?
        return [
            [ '[[Link]]', true ],
            [ 'foo [[Link]] bar', true ],
            [ '[[Link|20px]]', true ],
            [ 'foo [[Link|20px]] bar', true ],
            [ 'foo [[Link|20px]] bar <a src="hello.jpg">', true ],
            [ '<img src="hello.jpg">', false ],
            [ 'Lorem ipsum', false ],
            [ '', false ],
            [ null, false ],
        ];
    }


    /**
     * @dataProvider dataProviderWikiContents
     */
    public function testCheckWikiContent( $content, $isWiki ) {


        $specialPage = new BaseSpecialImportPages( 'TemplateImporter' );
        $this->assertSame( $isWiki, $specialPage->isWikiContent( $content ) );
        //$this->assertTrue( true );

    }

/*
    public function testShowForm() {

        $this->config = new FakeConfig(
            'fr',
            new MemoryFactoryRepository(),
            new FakeCommand()
        );

        $specialPage = new BaseSpecialImportPages( 'TemplateImporter' );
        $importer = new BaseImporter( $this->config );
        $specialPage->initImporter( $importer );
        $context = new FakeContext();
        //echo $specialPage->showForm( $context );

    }
 */



}
