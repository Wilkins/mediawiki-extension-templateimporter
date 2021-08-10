<?php

namespace TemplateImporter\Repository;

class MemoryPageTextRepository implements PageTextRepositoryInterface {

	public $comment = -1;

	public function getComment( $pageTitle, $namespaceId ) {
		return $this->comment;
	}

	public function getCurrentText( $pageTitle, $namespaceId ) {
	}

	public function getCurrentRevision( $pageTitle, $namespaceId ) {
	}

	public function setComment( $pageTitle, $namespaceId, $comment ) {
	}
}
