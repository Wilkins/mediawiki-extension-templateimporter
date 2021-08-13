<?php

namespace TemplateImporter\Page;

use TemplateImporter\TemplateImporterTest;

abstract class PageBaseTest extends TemplateImporterTest {

	public $repositoryClass;

	public function setUp(): void {
		parent::setUp();

		$this->repository = $this->getRepositoryClass();
	}

}
