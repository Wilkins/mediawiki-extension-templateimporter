<?php

namespace TemplateImporter\Repository;

/**
 * @codeCoverageIgnore
 */
class DbFactoryRepository implements FactoryRepositoryInterface {

	public function createPageTextRepository(): PageTextRepositoryInterface {
		return new DbPageTextRepository();
	}

	public function createPageImageRepository(): PageImageRepositoryInterface {
		return new DbPageImageRepository();
	}

}
