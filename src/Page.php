<?php

namespace TemplateImporter;

class Page {

	const PAGE_NEW = "NEW";
	const PAGE_UNKNOWN = "UNKNOWN";
	const PAGE_WILLUPDATE = "WILLUPDATE";
	const PAGE_UPTODATE = "UPTODATE";
	const PAGE_UNCHANGED = "UNCHANGED";
	const VERSION_REGEXP = "\(v(\d+\.\d+\.\d+)\)";

	public $pageName;
	public $pageTitle;
	public $path;
	public $namespace;
	public $namespaceId;
	public $version = '';
	public $versionTag;
	public $comment;
	public $textBase;
	public $textFile;
	public $extensionVersion;

	public function __construct( $pageName, $path, $extensionVersion ) {

		$this->pageName = $pageName;
		$this->path = $path;
		$this->extensionVersion = $extensionVersion;
		list( $this->namespace, $this->pageTitle ) = explode( ':', $this->pageName );
		$this->namespaceId = NamespaceManager::getNamespaceFromName( $this->namespace );
		$this->textBase = $this->getCurrentText();
		file_put_contents( '/tmp/base-'.$pageName, $this->textBase );
		$this->textFile = file_get_contents( $this->path );
		file_put_contents( '/tmp/file-'.$pageName, $this->textFile );
		$this->checkVersion();

	}

	/**
	 * Check if the page namespace is a Category
	 *
	 * @return boolean
	 */
	public function isCategory() {
		return $this->namespaceId == NS_CATEGORY ? true : false;
	}

	/**
	 * Check if the page namespace is a Category
	 *
	 * @return boolean
	 */
	public function hasChanged() {
		return trim( $this->textBase ) != trim( $this->textFile );
	}

	/**
	 * Get version from the pagename
	 *
	 * @return string the version
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * Get versionTag from the pagename
	 *
	 * @return string the versionTag
	 */
	public function getVersionTag() {
		return strtolower( $this->versionTag );
	}

	/**
	 * Calculate the version and the version tag
	 *
	 * @return void
	 */
	public function checkVersion() {
		$comment = $this->getComment();

		if ( $comment === -1 ) {
			$this->versionTag = static::PAGE_NEW;
		} elseif ( preg_match( "#".static::VERSION_REGEXP."#", $comment ) ) {

			$this->version = preg_replace( "#.*".static::VERSION_REGEXP.".*#", "$1", $comment );
			if ( $this->version == $this->extensionVersion ) {
				$this->versionTag = static::PAGE_UPTODATE;
			} elseif ( !$this->hasChanged() ){
				$this->versionTag = static::PAGE_UNCHANGED;
			} else {
				$this->versionTag = static::PAGE_WILLUPDATE;
			}

		} else {
			$this->versionTag = static::PAGE_UNKNOWN;
		}
	}

	/**
	 * Check wether this page needs an update
	 *
	 * @return boolean true is update is needed, false otherwise
	 */
	public function needsUpdate() {
		return ( $this->versionTag == static::PAGE_WILLUPDATE
			|| $this->versionTag == static::PAGE_NEW )
			&& $this->hasChanged();
	}

	/**
	 * Retrieve the last comment from the database for a given namespace:pagename
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Database_access
	 *
	 * @return the version number (ex: 3.0.1) or -1 if not found
	 */
	public function getComment() {
		$dbr = wfGetDb( DB_MASTER );
		$res = $dbr->select(
			[ 'revision', 'page' ],
			[ 'rev_comment' ],
				"page_title = '{$this->pageTitle}' and page_namespace={$this->namespaceId}",
				__METHOD__,
				[],
				[
					'page' => [
						'INNER JOIN',
						   [ 'rev_id=page_latest' ]
					   ]
				]
		);

		if ( $res->result->num_rows >= 0 ) {
			foreach ( $res->result as $row ) {
				return $row['rev_comment'];
			}
		}
		return -1;
		// throw new Exception( "" );
	}

	/**
	 * Retrieve the last comment from the database for a given namespace:pagename
	 *
	 * @param integer $namespace the namespace id
	 * @param string  $pagename  the pagename
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Database_access
	 */
	public function getCurrentText() {
		$dbr = wfGetDb( DB_MASTER );
		$res = $dbr->select(
			[ 'text', 'revision', 'page' ],
			[ 'old_text' ],
				"page_title = '{$this->pageTitle}' and page_namespace={$this->namespaceId}",
				__METHOD__,
				[],
				[
					'page' => [
						'INNER JOIN',
						   [ 'rev_id=page_latest' ]
					   ],
					'revision' => [
						'INNER JOIN',
						   [ 'old_id=rev_text_id' ]
					   ]
				]
		);

		if ( $res->result->num_rows >= 0 ) {
			foreach ( $res->result as $row ) {
				return $row['old_text'];
			}
		}
		return -1;
		// throw new Exception( "" );
	}

}
