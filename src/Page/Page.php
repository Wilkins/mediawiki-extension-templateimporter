<?php

namespace TemplateImporter\Page;

use TemplateImporter\NamespaceManager;
use TemplateImporter\Repository\PageRepositoryInterface;

abstract class Page {

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
    public $repository;

	public static function match( $filename ) {
		return preg_match( static::getRegexp(), $filename );
	}

	public abstract static function getRegexp();


	public function __construct( $pageName, $path, PageRepositoryInterface $repository ) {

		$this->pageName = $pageName;
        $this->path = $path;
        $this->repository = $repository;
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
            && $this->hasChanged()
            )
			|| $this->versionTag == static::PAGE_NEW ;
	}

	/**
	 * Calculate the version and the version tag
	 *
	 * @return void
	 */
	public function detectVersion() {
        $this->comment = $this->repository->getComment( $this->pageTitle, $this->namespaceId );

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
            // Basically, the page exists without having been imported by TemplateImporter
			$this->versionTag = static::PAGE_UNKNOWN;
		}
	}

	public abstract function import( $comment );



}
