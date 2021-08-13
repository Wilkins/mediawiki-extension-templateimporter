<?php

namespace TemplateImporter;

class BaseImporterTest extends TemplateImporterTest {

	public function testListFiles() {
		$importer = new BaseImporter(
			$this->fixtureDir,
			$this->config
		 );
		$importer->listFiles();

		$this->assertTrue( true );
	}

	public function testListFilesNotADirectory() {
		$this->expectException( 'TemplateImporter\Exception\Exception' );
		$importer = new BaseImporter(
			'MissingDirectory',
			$this->config
		);
	}

	public function testConstructWithDefaultConfig() {
		$importer = new BaseImporter( $this->fixtureDir );
		$this->assertEquals(
			'TemplateImporter\Config\MediaWikiConfig',
			get_class( $importer->config ),
			"Default config is not a MediaWikiConfig"

		 );
	}

	/*
	public function testConstructWithLang() {
		$this->expectException( 'TemplateImporter\Exception\Exception' );
		$importer = new BaseImporter(
			$this->config
		 );

	}
	 */

}
