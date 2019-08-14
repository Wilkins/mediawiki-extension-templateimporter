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
	public $extensionVersion;

	public static function match( $filename ) {
		return preg_match( static::getRegexp(), $filename );
	}

	public static function getRegexp() {
		throw new Exception( "This getRegexp static method "
		   ." should be used and defined only in subclasses of Page class" );
	}


	public function __construct( $pageName, $path ) {

		$this->pageName = $pageName;
		$this->path = $path;
		if ( preg_match( '#:#', $this->pageName ) ) {
			list( $this->namespace, $this->pageTitle ) = explode( ':', $this->pageName );
		} else {
			list( $this->namespace, $this->pageTitle ) = array( '', $this->pageName );
		}
		$this->namespaceId = NamespaceManager::getNamespaceFromName( $this->namespace );
// 		file_put_contents( '/tmp/base-'.$pageName, $this->textBase );
// 		file_put_contents( '/tmp/file-'.$pageName, $this->textFile );
		$this->detectVersion();

	}

	/**
	 * Check if the page namespace is a Category
	 *
	 * @return boolean
	 */
	public function isCategory() {
		return $this->namespaceId == NS_CATEGORY ? true : false;
	}
	public function getWikiHtml() {
		return '';
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
	 * Calculate the version and the version tag
	 *
	 * @return void
	 */
	public function detectVersion() {
		$this->comment = $this->getComment();

		if ( $this->comment != -1 && preg_match( "#".static::VERSION_REGEXP."#", $this->comment ) ) {

			$this->version = preg_replace(
				"#.*".static::VERSION_REGEXP.".*#",
				"$1",
				$this->comment
			 );
		}
	}

	/**
	 * Calculate the version and the version tag
	 *
	 * @return void
	 */
	public function checkVersion( $extensionVersion ) {
		if ( $this->comment === -1 ) {
			$this->versionTag = static::PAGE_NEW;
		} elseif ( preg_match( "#".static::VERSION_REGEXP."#", $this->comment ) ) {

			if ( $this->version == $extensionVersion ) {
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

	public function import( $comment ) {
	}



}
