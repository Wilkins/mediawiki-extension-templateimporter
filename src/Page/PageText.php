<?php

namespace TemplateImporter\Page;

use TemplateImporter\Repository\PageRepositoryInterface;

class PageText extends Page {
	public $textFile;
	public $textBase;

	public static function getRegexp() {
		return "#\.txt$#";
	}

	public function __construct( $pageName, $path = '/dev/null', PageRepositoryInterface $repository ) {
		$pageName = preg_replace( "#.txt$#", "", $pageName );
		parent::__construct( $pageName, $path, $repository );
		$this->textFile = file_get_contents( $this->path );
		$this->textBase = $this->repository->getCurrentText( $this->pageTitle, $this->namespaceId );
	}

	/**
	 * Check if the page namespace is a Category
	 *
	 * @return boolean
	 */
	public function hasChanged() {
		return trim( $this->textBase ) != trim( $this->textFile );
	}

	public function getWikiIcone() {
		// return $this->namespaceId;
		if ( $this->namespaceId == NS_CATEGORY ) {
			return 'CAT';
		} elseif ( $this->namespaceId == NS_FILE && $this->versionTag != static::PAGE_NEW ) {
			return '[['.$this->pageName.'|20px]]';
		}
		return 'TXT';
	}

	public function getWikiText() {
		if ( $this->namespaceId == NS_CATEGORY ) {
			return '[[:'.$this->pageName.']]';
		} elseif ( $this->namespaceId == NS_FILE && $this->versionTag != static::PAGE_NEW ) {
			return '[[:'.$this->pageName.']] (Metadata)';
		}
		return '[['.$this->pageName.']]';
	}


	/**
	 * Import the file into the wiki database using the maintenance/importTextFiles.php script
	 *
	 * @param string $file the full file path
	 *
	 * @return void
	 */
	public function import( $comment ) {
		global $wgTemplateImporterMWPath;
		// TODO Use "where" command if windows platform ?
		$php = trim( shell_exec( "which php" ) );
		$maintenanceScript = "$wgTemplateImporterMWPath/maintenance/importTextFiles.php";
		$config = "$wgTemplateImporterMWPath/LocalSettings.php";
		$path = $this->path;

		$command = "$php $maintenanceScript --conf=$config "
			." -s '$comment' --overwrite --rc \"$path\"";
		# echo "$command<br>\n";
		$res = shell_exec( $command );
		# echo $res;
	}
}
