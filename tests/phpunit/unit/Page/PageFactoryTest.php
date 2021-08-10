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
			[
				'Fake.truc',
				null,
				'TemplateImporter\Repository\MemoryPageTextRepository',
			],
		];
	}

	/**
	 * @dataProvider dataProviderPages
	 */
	public function testPageDetection( $filename, $expectedClass, $repoClass ) {
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
