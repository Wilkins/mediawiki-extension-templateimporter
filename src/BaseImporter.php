<?php

namespace TemplateImporter;

use DirectoryIterator;
use TemplateImporter\Exception;
use TemplateImporter\PageFactory;

class BaseImporter {

	public $lang;

	/**
	 * @constructor
	 *
	 * @param string $lang the 2 chars lang
	 */
	public function __construct( $lang ) {
		if ( ! $lang ) {
			throw new Exception(
				"Lang parameter not defined, could not construct the Importer object"
			   );
		}
		$this->lang = $lang;
	}

	/**
	 * Import the file into the wiki database using the maintenance/importTextFiles.php script
	 *
	 * @param string $file the full file path
	 *
	 * @return void
	 */
	/*
	public function importFile( $file ) {
		global $wgTemplateImporterMWPath;
		// TODO Use "where" command if windows platform ?
		$php = trim( shell_exec( "which php" ) );
		$maintenanceScript = "$wgTemplateImporterMWPath/maintenance/importTextFiles.php";
		$config = "$wgTemplateImporterMWPath/LocalSettings.php";
		$text = $this->getText();

		$command = "$php $maintenanceScript --conf=$config "
			." -s '$text' --overwrite --rc \"$file\"";
		// echo "$command\n";
		$res = shell_exec( $command );
		// echo $res;
    }
     */

	/**
	 * List all the importable files from the given lang
	 *
	 * @return array the list of importable files
	 */
	public function listFiles( $filesDir ) {
		if ( !is_dir( $filesDir ) ) {
			throw new Exception( "Directory $filesDir does not exist." );
		}
		$files = [];
		foreach ( new DirectoryIterator( $filesDir ) as $file ) {
			if ( $file->isDot() ) {
				continue;
			}
			if ( !$file->isFile() ) {
				continue;
			}

			// FIXME
			/*
            if ( $file->getBasename() != 'Pin-village.png'
                && $file->getBasename() != 'Fichier:Pin-village.png.txt' ) {
                continue;
            }
             */

			$page = PageFactory::create(
				$file->getBasename(),
				$file->getPathname()
			);
			if ( !$page ) {
				continue;
			}

			$files[ $page->pageName ] = $page;
		}

		ksort( $files );
		return $files;
	}
}

