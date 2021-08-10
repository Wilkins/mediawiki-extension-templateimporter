<?php

namespace TemplateImporter\Repository;

/**
 * @codeCoverageIgnore
 */
class DbPageImageRepository implements PageImageRepositoryInterface {

	private $dbr;

	public function __construct() {
		$this->dbr = wfGetDb( DB_MASTER );
	}

	/**
	 * Retrieve the last comment from the database for a given image name
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Database_access
	 *
	 * @return the version number (ex: 3.0.1) or -1 if not found
	 */
	public function getComment( $pageTitle, $namespaceId ) {
		$res = $this->dbr->select(
			[ 'image' ],
			[ 'img_description' ],
				"img_name = '{$this->pageTitle}' ",
				__METHOD__
		);

		if ( $res->result->num_rows >= 0 ) {
			foreach ( $res->result as $row ) {
				return $row['img_description'];
			}
		}
		return -1;
	}

	/**
	 * Retrieve the current size of the image from the database
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Database_access
	 *
	 * @return the size in bytes or -1 if unknown
	 */
	public function getCurrentSize( $pageTitle, $namespaceId ) {
		$res = $this->dbr->select(
			[ 'image' ],
			[ 'img_size' ],
				"img_name = '{$pageTitle}' ",
				__METHOD__
		);

		if ( $res->result->num_rows >= 0 ) {
			foreach ( $res->result as $row ) {
				return $row['img_size'];
			}
		}
		return -1;
	}

}
