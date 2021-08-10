<?php

namespace TemplateImporter\Repository;

interface PageImageRepositoryInterface extends PageRepositoryInterface {

	public function getCurrentSize( $pageTitle, $namespaceId );

	public function getComment( $pageTitle, $namespace );

}
