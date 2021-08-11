<?php

namespace TemplateImporter\Page;

class PageImageTest extends PageBaseTest {

	public function getRepositoryClass() {
		return $this->factory->createPageImageRepository();
	}

	public function dataProviderPages() {
		// Filename
		// is a Category page
		// Constant namespace
		// Get Link
		return [
			[ 'Toureiffel.jpg', false, 0, 'Toureiffel.jpg (contenu)' ],
			[ 'Missing.jpg', false, 0, 'Missing.jpg (contenu)' ],
			// [ 'Voyage:Tourisme.txt', false, 'NS_PROJECT' ],
		];
	}

	/**
	 * @dataProvider dataProviderPages
	 */
	public function testPageDetectPages(
		$filename, $expectedCategoryStatus, $namespaceConstant, $expectedLinkText ) {
		$file = $this->getFixture( $filename );

		$page = new PageImage(
			$file->getBasename(),
			$file->getPathname(),
			$this->factory
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

	public function dataProviderVersionsMatch() {
		return [
			[ 'Toureiffel.jpg', "1.12.1", "Update (v1.12.1)" ],
		];
	}

	/**
	 * @dataProvider dataProviderVersionsMatch
	 */
	public function testPageDetectVersion(
		$filename, $expectedVersion, $comment ) {
		$file = $this->getFixture( $filename );
		$this->factory->comment = $comment;

		$page = new PageImage(
			$file->getBasename(),
			$file->getPathname(),
			$this->factory
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
            [ 'Toureiffel.jpg', -1, "2.0.0", 'new', false, true ],
            [ 'Toureiffel.jpg', "(v2.0.0)", "2.0.0", 'uptodate', false, false ],
            [ 'Toureiffel.jpg', "(v1.3.0)", "2.0.0", 'willupdate', false, true ],
			// Same Content
            [ 'Toureiffel.jpg', -1, "2.0.0", 'new', true, true ],
            [ 'Toureiffel.jpg', "(v1.2.0)", "2.0.0", 'unchanged', true, false ],
			// Undetected current content
            [ 'Toureiffel.jpg', "foobar", "2.0.0", 'unknown', true, false ],
            [ 'Toureiffel.jpg', "foobar", "2.0.0", 'unknown', false, false ],
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
		$this->factory->comment = $currentVersion;

		$page = new PageImage(
			$file->getBasename(),
			$file->getPathname(),
			$this->factory
		);
        $page->fileSize = 1234;
        $page->currentSize = ( $sameContent ? 0 : 1000 ) + 1234;

		$page->checkVersion( $targetVersion );
		$resultStatus = $page->getVersionTag();

		$this->assertEquals( $expectedStatus, $resultStatus,
			"Detected version does not match expected"
		);

        $this->assertSame( $needsChange, $page->needsUpdate(),
			"Detected update change does not match expected"
        );
    }

	public function dataProviderPagesIcone() {
		// Filename
		// Get Wiki Icone
		return [
			[ 'Missing.jpg', '<img src="data:image/png;base64, /9j/4AAQSkZJRgABAQEASABIAMx5fG3IPqLPLhy/2LdNm1wV4X0W7cRTRTG2tO8o/iTg8v8A0deAf+Zjhp/ZzgfoO8I/81nDn+bWmf2c4H6D3CT/ADW8Of5taZ/UHB7uPwv4aYlE28Xh3sbGtzPWbdrQcC3T1+vpTZgHn/Q54e/5CbN/8Fwv6oH6Wm7V2vol3x9G23oGk347ul7D0/Hxa/nR0n51qimfOAfQgAAAAAAAAAAAAAAAAA//2Qo=" alt="Missing.jpg" width="20px"/>' ],
			// [ 'Voyage:Tourisme.txt', false, 'NS_PROJECT' ],
		];
	}

	/**
	 * @dataProvider dataProviderPagesIcone
	 */
	public function testPageDetectPagesIcone( $filename, $expectedIcone ) {
		$file = $this->getFixture( $filename );

		$page = new PageImage(
			$file->getBasename(),
			$file->getPathname(),
			$this->factory
		);

		$this->assertSame( $expectedIcone, $page->getWikiIcone(),
			"Detected icone does not match expected"
		);
	}

}
