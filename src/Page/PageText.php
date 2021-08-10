<?php

namespace TemplateImporter\Page;

use TemplateImporter\Command\CommandInterface;
use TemplateImporter\Repository\PageRepositoryInterface;

class PageText extends Page {
	public $textFile;
	public $textBase;

	public static function getRegexp() {
		return "#\.txt$#";
	}

	public function __construct(
		$pageName,
		$path = '/dev/null',
		PageRepositoryInterface $repository,
		CommandInterface $command = null
	) {
		$pageName = preg_replace( "#.txt$#", "", $pageName );
		parent::__construct( $pageName, $path, $repository, $command );
		$this->textFile = $this->command->getFileContents( $this->path );
		$this->textBase = $this->repository->getCurrentText( $this->pageTitle, $this->namespaceId );
	}

	/**
	 * Check if the page namespace is a Category
	 *
	 * @return bool
	 */
	public function hasChanged() {
		return trim( $this->textBase ) != trim( $this->textFile );
	}

	public function getWikiIcone() {
		// return $this->namespaceId;
		if ( $this->namespaceId == NS_CATEGORY ) {
			return 'CAT';
		} elseif ( $this->namespaceId == NS_FILE && $this->versionTag != static::PAGE_NEW ) {
			return '[[' . $this->pageName . '|20px]]';
		}
		return 'TXT';
	}

	public function getWikiText() {
		if ( $this->namespaceId == NS_CATEGORY ) {
			return '[[:' . $this->pageName . ']]';
		} elseif ( $this->namespaceId == NS_FILE && $this->versionTag != static::PAGE_NEW ) {
			return '[[:' . $this->pageName . ']] (Metadata)';
		}
		return '[[' . $this->pageName . ']]';
	}

	/**
	 * Import the file into the wiki database using the maintenance/importTextFiles.php script
	 *
	 * @param string $file the full file path
	 *
	 * @return void
	 */
	public function import( $comment, $mediawikiPath ) {
		$php = $this->command->which( 'php' );
		$maintenanceScript = "$mediawikiPath/maintenance/importTextFiles.php";
		$config = "$mediawikiPath/LocalSettings.php";
		$path = $this->path;

		$command = "$php $maintenanceScript --conf=$config "
			. " -s '$comment' --overwrite --rc \"$path\"";
		$res = $this->command->execute( $command );
		return $res;
	}
}
