<?php

namespace TemplateImporter\Page;

use TemplateImporter\Command\CommandInterface;
use TemplateImporter\Command\ShellCommand;
use TemplateImporter\Config\ConfigInterface;
use TemplateImporter\Config\MediaWikiConfig;
use TemplateImporter\NamespaceManager;
use TemplateImporter\Repository\FactoryRepositoryInterface;
use TemplateImporter\Repository\DbFactoryRepository;

abstract class Page {

	public const PAGE_NEW = "NEW";
	public const PAGE_UNKNOWN = "UNKNOWN";
	public const PAGE_WILLUPDATE = "WILLUPDATE";
	public const PAGE_UPTODATE = "UPTODATE";
	public const PAGE_UNCHANGED = "UNCHANGED";
	public const VERSION_REGEXP = "\(v(\d+\.\d+\.\d+)\)";

	public $pageName;
	public $pageTitle;
	public $path;
	public $namespace;
	public $namespaceId;
	public $version = '';
	public $versionTag;
	public $comment;
	public $extensionVersion;
	/**
	 * @var FactoryRepositoryInterface
	 */
	public $factory;
	/**
	 * @var PageRepositoryInterface
	 */
	public $repository;
	/**
	 * @var CommandInterface
	 */
	public $command;

	public static function match( $filename, ConfigInterface $config ) {
		return preg_match( static::getRegexp( $config ), $filename );
	}

	abstract public static function getRegexp( ConfigInterface $config );

	public function __construct(
		$pageName,
		$path,
		FactoryRepositoryInterface $factory = null,
		CommandInterface $command = null,
        ConfigInterface $config = null
	) {
		$this->pageName = $pageName;
		$this->path = $path;
        //$this->factory = $factory;
		if ( $factory ) {
			$this->factory = $factory;
        } else {
            $this->factory = new DbFactoryRepository();
		}
		if ( preg_match( '#:#', $this->pageName ) ) {
			list( $this->namespace, $this->pageTitle ) = explode( ':', $this->pageName );
		} else {
			list( $this->namespace, $this->pageTitle ) = [ '', $this->pageName ];
		}
		if ( $command ) {
			$this->command = $command;
		} else {
			$this->command = new ShellCommand();
		}
		if ( $config ) {
			$this->config = $config;
		} else {
			$this->config = new MediaWikiConfig();
		}
		$this->namespaceId = NamespaceManager::getNamespaceFromName( $this->namespace );
// file_put_contents( '/tmp/base-'.$pageName, $this->textBase );
// 		file_put_contents( '/tmp/file-'.$pageName, $this->textFile );
	}

	/**
	 * Check if the page namespace is a Category
	 *
	 * @return bool
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
	 * @return bool true is update is needed, false otherwise
	 */
	public function needsUpdate() {
		return ( $this->versionTag == static::PAGE_WILLUPDATE
			&& $this->hasChanged()
			)
			|| $this->versionTag == static::PAGE_NEW;
	}

	/**
	 * Calculate the version and the version tag
	 *
	 * @return void
	 */
	public function detectVersion() {
		$this->comment = $this->repository->getComment( $this->pageTitle, $this->namespaceId );

		if ( $this->comment != -1 && preg_match( "#" . static::VERSION_REGEXP . "#", $this->comment ) ) {
			$this->version = preg_replace(
				"#.*" . static::VERSION_REGEXP . ".*#",
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
		} elseif ( preg_match( "#" . static::VERSION_REGEXP . "#", $this->comment ) ) {
			if ( $this->version == $extensionVersion ) {
				$this->versionTag = static::PAGE_UPTODATE;
			} elseif ( !$this->hasChanged() ) {
				$this->versionTag = static::PAGE_UNCHANGED;
			} else {
				$this->versionTag = static::PAGE_WILLUPDATE;
			}
		} else {
			// Basically, the page exists without having been imported by TemplateImporter
			$this->versionTag = static::PAGE_UNKNOWN;
		}
	}

	abstract public function import( $comment, $mediawikiPath );
}
