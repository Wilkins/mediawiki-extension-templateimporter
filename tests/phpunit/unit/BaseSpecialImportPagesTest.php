<?php

namespace TemplateImporter;

use TemplateImporter\Context\FakeContext;

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

    public function testShowForm() {

        $specialPage = new BaseSpecialImportPages( 'TemplateImporter' );
        $importer = new BaseImporter( $this->config );
        $specialPage->setLangTemplateDir( $this->fixtureDir );
        $specialPage->initImporter( $importer );
        $context = new FakeContext();
        $generatedContent = $specialPage->generatePageTable( $context );

        $expectedContent = '<table id="templateimporter-import-form"><tr><th colspan="2">templateimporter-specialimportpages-column-pagename</th><th>templateimporter-specialimportpages-column-packageversion</th><th>templateimporter-specialimportpages-column-pageversion</th><th>templateimporter-specialimportpages-column-status</th></tr><tr class="status-"><td>TXT</td><td>[[Attribut:Longueur]]</td><td></td><td></td><td>templateimporter-specialimportpages-status-</td></tr><tr class="status-"><td>CAT</td><td>[[:Catégorie:Voyages]]</td><td></td><td></td><td>templateimporter-specialimportpages-status-</td></tr><tr class="status-"><td>[[Fichier:Toureiffel.jpg|20px]]</td><td>[[:Fichier:Toureiffel.jpg]] (Metadata)</td><td></td><td></td><td>templateimporter-specialimportpages-status-</td></tr><tr class="status-"><td>TXT</td><td>[[Formulaire:Voyage]]</td><td></td><td></td><td>templateimporter-specialimportpages-status-</td></tr><tr class="status-"><td><img src="data:image/png;base64, TG9yZW0gaXBzdW0gZmlsZSBjb250ZW50" alt="Missing.jpg" width="20px"/></td><td>Missing.jpg (contenu)</td><td></td><td></td><td>templateimporter-specialimportpages-status-</td></tr><tr class="status-"><td>TXT</td><td>[[Modèle:Radian]]</td><td></td><td></td><td>templateimporter-specialimportpages-status-</td></tr><tr class="status-"><td><img src="data:image/png;base64, TG9yZW0gaXBzdW0gZmlsZSBjb250ZW50" alt="Toureiffel.jpg" width="20px"/></td><td>Toureiffel.jpg (contenu)</td><td></td><td></td><td>templateimporter-specialimportpages-status-</td></tr></table>';

        $this->assertSame( $expectedContent, $generatedContent );
    }
/*
 */



}
