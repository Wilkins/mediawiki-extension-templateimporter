<?php

namespace TemplateImporter\Formatter;

use MediaWikiUnitTestCase;
use TemplateImporter\Output\FakeOutput;
use TemplateImporter\Context\FakeContext;
use TemplateImporter\Page\PageBaseTest;
use TemplateImporter\Page\PageText;
use TemplateImporter\Page\PageViewModel;


class HtmlPageFormatterTest extends PageBaseTest {

	public function getRepositoryClass() {
		return $this->factory->createPageTextRepository();
	}

	public function dataProviderPagesHtml() {
		// Filename
		// Get Wiki Icone
		return [
            [ 
                '[[Attribut:Longueur.txt]]', 
                '1.2.0',
                'new',
                'TXT',
                '<tr class="status-new"><td>TXT</td><td>[[Attribut:Longueur.txt]]</td><td>2.0.0</td><td>1.2.0</td><td>templateimporter-specialimportpages-status-new</td></tr>'
            ],
            [ 
                '[[Catégorie:Voyages.txt]]', 
                '1.2.0',
                'willupdate',
                'CAT',
                '<tr class="status-willupdate"><td>CAT</td><td>[[Catégorie:Voyages.txt]]</td><td>2.0.0</td><td>1.2.0</td><td>templateimporter-specialimportpages-status-willupdate</td></tr>'
            ],
            /*
			[ 'Catégorie:Voyages.txt', 'CAT' ],
			[ 'Modèle:Radian.txt', 'TXT' ],
			[ 'Fichier:Toureiffel.jpg.txt', '[[Fichier:Toureiffel.jpg|20px]]' ],
            [ 'Toureiffel.jpg', 'TXT' ],
             */
			// [ 'Voyage:Tourisme.txt', false, 'NS_PROJECT' ],
		];
	}

	/**
	 * @dataProvider dataProviderPagesHtml
     */
    /*
     */
    public function testPageDetectPagesHtml(
        $pageName, $pageVersion, $pageStatus, $pageIcon, $expectedHtml
    ) {

        $viewmodel = new PageViewModel();
        $viewmodel->name = $pageName;
        $viewmodel->version = $pageVersion;
        $viewmodel->status = $pageStatus;
        $viewmodel->icon = $pageIcon;

        $context = new FakeContext();
        $formatter = new HtmlPageFormatter( $viewmodel, "2.0.0" );
        $html = $formatter->render( $context );

        $this->assertSame( $expectedHtml, $html,
			"Rendered html does not match expected"
		);
    }

}
