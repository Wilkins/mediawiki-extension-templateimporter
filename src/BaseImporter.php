<?php

namespace TemplateImporter;

use DirectoryIterator;
use TemplateImporter\Exception;

class BaseImporter {

	public $lang;

	public $version = "1.0";

	public function getVersion() {

		return $this->version;
	}

	public function getText() {

		return "Update from $this->name (v".$this->version.")";
	}

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
	public function importFile( $file ) {
		// TODO Use "where" command if windows platform ?
		$php = trim( shell_exec( "which php" ) );
		$maintenanceScript = "$IP/maintenance/importTextFiles.php";
		$config = "$IP/LocalSettings.php";
		$text = $this->getText();

		$command = "$php $maintenanceScript --conf=$config "
			." -s '$text' --overwrite --rc \"$file\"";
		// echo "$command\n";
		$res = shell_exec( $command );
		// echo $res;
	}

	public function getLangTemplateDir() {

		throw new Exception( "You must declare a getLangTemplateDir method "
			."in you SpecialPage class, where we can find the templates text files" );
	}

	/**
	 * List all the importable files from the given lang
	 *
	 * @return array the list of importable files
	 */
	public function listFiles() {
		$filesDir = $this->getLangTemplateDir();
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
			if ( !preg_match( "#.txt$#", $file->getFilename() ) ) {
				continue;
			}

			$displayName = preg_replace( "#.txt$#", "", $file->getBasename() );
			$page = new Page( $displayName, $file->getPathname(), $this->getVersion() );
			$files[ $displayName ] = $page;
			// file->getPathname();
		}
		asort( $files );
		return $files;
	}
}

