<?php

namespace TemplateImporter\Page;

class PageFactoryTest extends PageBaseTest
{

	public function dataProviderPages() {

		return [
			[
				'Attribut:Longueur.txt',
				'TemplateImporter\Page\PageText',
				'TemplateImporter\Repository\MemoryPageTextRepository',
		],

			[
				'Catégorie:Voyages.txt',
				'TemplateImporter\Page\PageText',
				'TemplateImporter\Repository\MemoryPageTextRepository',
			],
			[
				'Toureiffel.jpg',
				'TemplateImporter\Page\PageImage',
				'TemplateImporter\Repository\MemoryPageImageRepository',
			],
		];
	}

	/**
	 * @dataProvider dataProviderPages
	 */
	public function testPageDetection( $filename, $expectedClass, $repoClass ) {

		$file = $this->getFixture( $filename );

		$repo = new $repoClass();

		global $wgFileExtensions;
		$wgFileExtensions = [ 'jpg', 'png'];
		$page = PageFactory::create(
			$file->getBasename(),
			$file->getPathname(),
			$repo
		);

		$this->assertEquals( $expectedClass, get_class( $page ) );
	}
}
