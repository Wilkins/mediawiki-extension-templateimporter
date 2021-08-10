<?php

namespace TemplateImporter\Page;

use TemplateImporter\Repository\PageRepositoryInterface;

class PageImage extends Page {
	public $fileSize;
	public $currentSize;

	public static function getRegexp() {
		global $wgFileExtensions;
		if ( !isset( $wgFileExtensions ) ) {
			$wgFileExtensions = [];
		}
		$ext = implode( '|', $wgFileExtensions );
		return "#\.($ext)$#";
	}

	public function __construct( $pageName, $path, PageRepositoryInterface $repository ) {
		parent::__construct( $pageName, $path, $repository );
		$this->fileSize = filesize( $this->path );
		$this->currentSize = $this->repository->getCurrentSize( $this->pageTitle, $this->namespaceId );

	}
	/**
	 * Check if the page namespace is a Category
	 *
	 * @return boolean
	 */
	public function hasChanged() {
		return trim( $this->fileSize ) != trim( $this->currentSize );
	}

	public function getWikiIcone() {
		$base64 = base64_encode( file_get_contents( $this->path ) );

		return '<img src="data:image/png;base64, '
			.$base64.'" alt="'.$this->pageName
			.'" width="20px"/>';
	}

	public function getWikiText() {
		return $this->pageName.' (contenu)';
	}



	/**
	 * Import the file into the wiki database using the maintenance/importTextFiles.php script
	 * php importImages.php  --conf=/path/to/LocalSettings.php /path/to/templates
	 *    --from=filename.png --comment-file=/path/to/File:filename.png.txt
	 *    --extensions=png --limit=1 --overwrite --summary="New version (v0.2.0)"
	 *
	 * @param string $file the full file path
	 *
	 * @return void
	 */
	public function import( $comment ) {
		global $wgTemplateImporterMWPath, $wgFileExtensions;
		// TODO Use "where" command if windows platform ?
		$php = trim( shell_exec( "which php" ) );
		$maintenanceScript = "$wgTemplateImporterMWPath/maintenance/importImages.php";
		$config = "$wgTemplateImporterMWPath/LocalSettings.php";
		$dir = dirname( $this->path );
		$path = $this->path;
		$from = $this->pageName;
		$files = glob( $dir.'/*:'.$from.'.txt' );
		if ( count( $files ) != 1 ) {
			throw new Exception(
				"Unable to find the correct metadata file for $this->pageName"
				." found multiples possibilities : <br>"
				.implode( '<br>\n', $files )
			);
		} else {
			$commentFile = $files[0];
		}
		// $commentFile = $dir.'/Fichier:'.$from.'.txt';
		$ext = implode( ',', $wgFileExtensions );

		$command = "$php $maintenanceScript --conf=$config "
			." $dir --from=\"$from\""
			." --comment-file=\"$commentFile\""
			." --extensions=$ext"
			." --limit=1 --overwrite "
			." --summary=\"$comment\" ";

		# echo "$command<br>\n";
		$res = shell_exec( $command );
		# echo $res;

		$this->updateMetadataDescription( "File:".$this->pageName, $comment );
	}


	public function updateMetadataDescription( $pageName, $comment ) {

		$pagetext = new PageText( $pageName );
		$pagetext->setComment( $comment );

	}
}
