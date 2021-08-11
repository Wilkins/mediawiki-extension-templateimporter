<?php

namespace TemplateImporter\Page;

use TemplateImporter\Command\FakeCommand;
use TemplateImporter\Config\FakeConfig;

class PageTextTest extends PageBaseTest {

	public function getRepositoryClass() {
		return $this->factory->createPageTextRepository();
	}

	public function dataProviderVersionsMatch() {
		return [
			[ 'Attribut:Longueur.txt', "1.12.1", "Update (v1.12.1)" ],
			[ 'Catégorie:Voyages.txt', "1.12.1", "(v1.12.1) Foo" ],
			[ 'Modèle:Radian.txt', "1.12.1", "Foo (v1.12.1) Bar" ],
			[ 'Fichier:Toureiffel.jpg.txt', "1.12.1", "Foo (v1.12.1) Bar" ],
			// [ 'Voyage:Tourisme.txt', "1.12.1", "Update (v1.12.1)" ],
		];
	}

	/**
	 * @dataProvider dataProviderVersionsMatch
	 */
	public function testPageDetectVersion(
		$filename, $expectedVersion, $comment ) {
		$file = $this->getFixture( $filename );
		$this->config->getFactory()->comment = $comment;

		$page = new PageText(
			$file->getBasename(),
			$file->getPathname(),
			$this->config
		);

		$this->assertEquals( $expectedVersion, $page->getVersion(),
			"Detected version does not match expected"
		);
	}

	public function dataProviderVersionsChange() {
		// Filename
		// Current comment
		// New available version
		// Expected Versiontag
		// Current content and new content are the same ?
		// Template will be updated ?
		return [
			// Not the same content
			[ 'Attribut:Longueur.txt', -1, "2.0.0", 'new', false, true ],
			[ 'Attribut:Longueur.txt', "(v1.12.1)", "2.0.0", 'willupdate' , false, true ],
			[ 'Attribut:Longueur.txt', "(v1.12.1)", "1.12.1", 'uptodate', false, false ],

			// Same Content
			[ 'Attribut:Longueur.txt', -1, "2.0.0", 'new', true, true ],
			[ 'Attribut:Longueur.txt', "(v1.12.1)", "1.12.1", 'uptodate', true, false ],
			[ 'Attribut:Longueur.txt', "(v1.12.1)", "2.0.0", 'unchanged', true, false ],

			// Undetected current content
			[ 'Attribut:Longueur.txt', "foobar", "2.0.0", 'unknown', false, false ],
			[ 'Attribut:Longueur.txt', "machinbidule", "2.0.0", 'unknown', false, false ],
			/*
			[ 'Catégorie:Voyages.txt', "1.12.1", "(v1.12.1) Foo" ],
			[ 'Modèle:Radian.txt', "1.12.1", "Foo (v1.12.1) Bar" ],
			 */
			// [ 'Voyage:Tourisme.txt', "1.12.1", "Update (v1.12.1)" ],
		];
	}

	/**
	 * @dataProvider dataProviderVersionsChange
	 */
	public function testPageDetectVersionChange(
		$filename, $currentVersion, $targetVersion, $expectedStatus, $sameContent, $needsChange ) {
		$file = $this->getFixture( $filename );
		$this->config->getFactory()->comment = $currentVersion;

		$page = new PageText(
			$file->getBasename(),
			$file->getPathname(),
			$this->config
		);
		$page->textFile = "dummy";
		$page->textBase = ( $sameContent ? "" : "no" ) . "dummy";

		$page->checkVersion( $targetVersion );
		$resultStatus = $page->getVersionTag();

		$this->assertEquals( $expectedStatus, $resultStatus,
			"Detected version does not match expected"
		);

		$this->assertSame( $needsChange, $page->needsUpdate() );
	}

	public function dataProviderPages() {
		// Filename
		// is a Category page
		// Constant namespace
		// Get Link
		return [
			[ 'Attribut:Longueur.txt', false, 'SMW_NS_PROPERTY', '[[Attribut:Longueur]]' ],
			[ 'Catégorie:Voyages.txt', true, 'NS_CATEGORY', '[[:Catégorie:Voyages]]' ],
			[ 'Modèle:Radian.txt', false, 'NS_TEMPLATE', '[[Modèle:Radian]]' ],
			[ 'Fichier:Toureiffel.jpg.txt', false, 'NS_FILE', '[[:Fichier:Toureiffel.jpg]] (Metadata)' ],
			[ 'Toureiffel.jpg', false, null, '[[Toureiffel.jpg]]' ],
			// [ 'Voyage:Tourisme.txt', false, 'NS_PROJECT' ],
		];
	}

	/**
	 * @dataProvider dataProviderPages
	 */
	public function testPageDetectPages(
		$filename, $expectedCategoryStatus, $namespaceConstant, $expectedLinkText ) {
		$file = $this->getFixture( $filename );

		$page = new PageText(
			$file->getBasename(),
			$file->getPathname(),
			$this->config
		);

		$constantValue = $namespaceConstant ? constant( $namespaceConstant ) : 0;
		$this->assertEquals( $constantValue, $page->namespaceId,
			"Detected namespace does not match expected"
		);

		$this->assertSame( $expectedCategoryStatus, $page->isCategory(),
			"Detected category status does not match expected"
		);

		$this->assertSame( $expectedLinkText, $page->getWikiText(),
			"Detected linktext does not match expected"
		);
	}

	public function dataProviderPagesIcone() {
		// Filename
		// Get Wiki Icone
		return [
			[ 'Attribut:Longueur.txt', 'TXT' ],
			[ 'Catégorie:Voyages.txt', 'CAT' ],
			[ 'Modèle:Radian.txt', 'TXT' ],
			[ 'Fichier:Toureiffel.jpg.txt', '[[Fichier:Toureiffel.jpg|20px]]' ],
			[ 'Toureiffel.jpg', 'TXT' ],
			// [ 'Voyage:Tourisme.txt', false, 'NS_PROJECT' ],
		];
	}

	/**
	 * @dataProvider dataProviderPagesIcone
	 */
	public function testPageDetectPagesIcone( $filename, $expectedIcone ) {
		$file = $this->getFixture( $filename );

		$page = new PageText(
			$file->getBasename(),
			$file->getPathname(),
			$this->config
		);

		$this->assertSame( $expectedIcone, $page->getWikiIcone(),
			"Detected icone does not match expected"
		);
	}

	public function dataProviderPagesCommand() {
		// Filename
		// Generated Command
		return [
			[
				'Attribut:Longueur.txt',
				"Executing /dev/null/php /path/to/mediawiki/maintenance/importTextFiles.php --conf=/path/to/mediawiki/LocalSettings.php  -s 'Test' --overwrite --rc \"" . $this->fixtureDir . "/Attribut:Longueur.txt\" OK",
			],
			[
				'Catégorie:Voyages.txt',
				"Executing /dev/null/php /path/to/mediawiki/maintenance/importTextFiles.php --conf=/path/to/mediawiki/LocalSettings.php  -s 'Test' --overwrite --rc \"" . $this->fixtureDir . "/Catégorie:Voyages.txt\" OK",
			],
			[
				'Modèle:Radian.txt',
				"Executing /dev/null/php /path/to/mediawiki/maintenance/importTextFiles.php --conf=/path/to/mediawiki/LocalSettings.php  -s 'Test' --overwrite --rc \"" . $this->fixtureDir . "/Modèle:Radian.txt\" OK",
			],
			[
				'Fichier:Toureiffel.jpg.txt',
				"Executing /dev/null/php /path/to/mediawiki/maintenance/importTextFiles.php --conf=/path/to/mediawiki/LocalSettings.php  -s 'Test' --overwrite --rc \"" . $this->fixtureDir . "/Fichier:Toureiffel.jpg.txt\" OK",
			],
			[
				'Toureiffel.jpg',
				"Executing /dev/null/php /path/to/mediawiki/maintenance/importImages.php --conf=/path/to/mediawiki/LocalSettings.php " . realpath($this->fixtureDir) . " --from=\"Toureiffel.jpg\" --comment-file=\"" . realpath($this->fixtureDir) . "/Fichier:Toureiffel.jpg.txt\" --extensions=jpg,png --limit=1 --overwrite  --summary=\"Test\" OK",
			],
			/*
			[ 'Modèle:Radian.txt', 'TXT' ],
			[ 'Fichier:Toureiffel.jpg.txt', '[[Fichier:Toureiffel.jpg|20px]]' ],
			[ 'Toureiffel.jpg', 'TXT' ],
			 */
		];
	}

	/**
	 * @dataProvider dataProviderPagesCommand
	 */
	public function testImport( $filename, $expectedCommand ) {
		$file = $this->getFixture( $filename );
		global $wgFileExtensions;
		$wgFileExtensions = [ 'jpg', 'png' ];

		$page = PageFactory::create(
			$file->getBasename(),
			$file->getPathname(),
			$this->config
		);

		$result = $page->import( "Test", "/path/to/mediawiki" );

		$this->assertSame( $expectedCommand, $result );
	}


	public function testImportMissing() {
		$file = $this->getFixture( 'Missing.jpg' );

		$page = PageFactory::create(
			$file->getBasename(),
			$file->getPathname(),
			$this->config
		);

        $this->expectException( "TemplateImporter\Exception\Exception" );
		$result = $page->import( "Test", "/path/to/mediawiki" );

	}

}
