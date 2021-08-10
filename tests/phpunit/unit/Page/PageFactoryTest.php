<?php

namespace TemplateImporter\Page;

use TemplateImporter\Repository\MemoryFactoryRepository;

class PageFactoryTest extends PageBaseTest {

	public function getRepositoryClass() {
		return $this->factory->createPageTextRepository();
	}

	public function dataProviderPages() {
		return [
			[
				'Attribut:Longueur.txt',
				'TemplateImporter\Page\PageText',
			],
			[
				'Catégorie:Voyages.txt',
				'TemplateImporter\Page\PageText',
			],
			[
				'Toureiffel.jpg',
				'TemplateImporter\Page\PageImage',
			],
			[
				'Fake.truc',
				null,
			],
		];
	}

	/**
	 * @dataProvider dataProviderPages
	 */
	public function testPageDetection( $filename, $expectedClass ) {
		$file = $this->getFixture( $filename );

		$factory = new MemoryFactoryRepository();

		global $wgFileExtensions;
		$wgFileExtensions = [ 'jpg', 'png' ];
		$page = PageFactory::create(
			$file->getBasename(),
			$file->getPathname(),
			$factory
		);

		if ( $expectedClass !== null ) {
			$this->assertEquals( $expectedClass, get_class( $page ) );
		} else {
			$this->assertNull( $page );
		}
	}
}
