<?php

namespace TemplateImporter\Repository;

class MemoryPageImageRepository implements PageImageRepositoryInterface {

	public $comment = -1;

	public function getComment( $pageTitle, $namespaceId ) {
		return $this->comment;
	}

	public function getCurrentSize( $pageTitle, $namespaceId ) {
	}
}
