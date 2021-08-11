<?php

namespace TemplateImporter\Page;

use TemplateImporter\Command\CommandInterface;
use TemplateImporter\Config\ConfigInterface;
use TemplateImporter\Repository\FactoryRepositoryInterface;
use TemplateImporter\Exception\Exception;

class PageImage extends Page {
	public $fileSize;
	public $currentSize;

	public static function getRegexp( ConfigInterface $config ) {
		$ext = implode( '|', $config->getFileExtensions() );
		return "#\.($ext)$#";
	}

	public function __construct(
		$pageName,
		$path,
		FactoryRepositoryInterface $factory,
		CommandInterface $command = null,
        ConfigInterface $config = null
	) {
        parent::__construct( $pageName, $path, $factory, $command, $config );
        $this->repository = $factory->createPageImageRepository();
		$this->fileSize = $this->command->getFileSize( $this->path );
		$this->currentSize = $this->repository->getCurrentSize( $this->pageTitle, $this->namespaceId );
		$this->detectVersion();
	}

	/**
	 * Check if the page namespace is a Category
	 *
	 * @return bool
	 */
	public function hasChanged() {
		return trim( $this->fileSize ) != trim( $this->currentSize );
	}

	public function getWikiIcone() {
		$base64 = base64_encode( file_get_contents( $this->path ) );

		return '<img src="data:image/png;base64, '
			. $base64 . '" alt="' . $this->pageName
			. '" width="20px"/>';
	}

	public function getWikiText() {
		return $this->pageName . ' (contenu)';
	}

	/**
	 * Import the file into the wiki database using the maintenance/importTextFiles.php script
	 * php importImages.php  --conf=/path/to/LocalSettings.php /path/to/templates
	 *	--from=filename.png --comment-file=/path/to/File:filename.png.txt
	 *	--extensions=png --limit=1 --overwrite --summary="New version (v0.2.0)"
	 *
	 * @param string $file the full file path
	 *
	 * @return void
	 */
	public function import( $comment, $mediawikiPath ) {
		$php = $this->command->which( 'php' );
		$maintenanceScript = "$mediawikiPath/maintenance/importImages.php";
		$config = "$mediawikiPath/LocalSettings.php";
		$dir = dirname( $this->path );
		$path = $this->path;
		$from = $this->pageName;
		$files = glob( $dir . '/*:' . $from . '.txt' );
		if ( count( $files ) != 1 ) {
			throw new Exception(
				"Unable to find the correct metadata file for $this->pageName"
				. " found multiples possibilities : <br>"
				. implode( '<br>\n', $files )
			);
		} else {
			$commentFile = $files[0];
		}
		$ext = implode( ',', $this->config->getFileExtensions() );

		$command = "$php $maintenanceScript --conf=$config"
			. " $dir --from=\"$from\""
			. " --comment-file=\"$commentFile\""
			. " --extensions=$ext"
			. " --limit=1 --overwrite "
			. " --summary=\"$comment\"";

        $result = $this->command->execute( $command );

        $this->updateMetadataDescription( "File:" . $this->pageName, $comment );
        return $result;
	}

	public function updateMetadataDescription( $pageName, $comment ) {
		$pagetext = new PageText(
			$pageName,
            '/dev/null',
            $this->factory
		);
        $pagetext->setComment( $comment );
	}
}
