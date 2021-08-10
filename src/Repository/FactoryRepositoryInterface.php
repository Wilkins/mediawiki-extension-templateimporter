<?php

namespace TemplateImporter\Repository;

interface FactoryRepositoryInterface {

	public function createPageTextRepository(): PageTextRepositoryInterface;

	public function createPageImageRepository(): PageImageRepositoryInterface;

}
