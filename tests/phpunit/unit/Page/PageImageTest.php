<?php

namespace TemplateImporter\Page;


class PageImageTest extends PageBaseTest
{

    public function dataProviderPages() {
        // Filename
        // is a Category page
        // Constant namespace
        // Get Link
        return [
            [ 'Attribut:Longueur.txt', false, 'SMW_NS_PROPERTY', '[[Attribut:Longueur]]' ],
            [ 'Catégorie:Voyages.txt', true, 'NS_CATEGORY', '[[:Catégorie:Voyages]]' ],
            [ 'Modèle:Radian.txt', false, 'NS_TEMPLATE', '[[Modèle:Radian]]' ],
            //[ 'Voyage:Tourisme.txt', false, 'NS_PROJECT' ],
        ];
    }

    /**
     * @dataProvider dataProviderPages
    public function testPageDetectPages( 
        $filename, $expectedCategoryStatus, $namespaceConstant, $expectedLinkText )
    {

        $file = $this->getFixture( $filename );

        $page = new PageImage(
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

        $this->assertSame( $expectedLinkText, $page->getWikiText(), 
            "Detected linktext does not match expected"
        );
    }
     */
}
