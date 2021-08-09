<?php

namespace TemplateImporter\Page;

class PageFactoryTest extends PageBaseTest
{

    public function dataProviderPages()
    {
        return [
            [ 'Attribut:Longueur.txt',  'TemplateImporter\Page\PageText' ],
            [ 'Catégorie:Voyages.txt',  'TemplateImporter\Page\PageText' ],
            [ 'Toureiffel.jpg', 'TemplateImporter\Page\PageImage' ],
        ];
    }

    /**
     * @dataProvider dataProviderPages
     */
    /*
    public function testPageDetection( $filename, $expectedClass )
    {

        $file = $this->getFixture( $filename );

        global $wgFileExtensions;
        $wgFileExtensions = [ 'jpg', 'png'];
        $page = PageFactory::create(
            $file->getBasename(),
            $file->getPathname(),
            $this->repository
        );

        
        $this->assertEquals( $expectedClass, get_class( $page ) );
    }
     */




}
