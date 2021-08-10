<?php

namespace TemplateImporter;

use DirectoryIterator;

class BaseImporter {

	public $lang;

	/**
	 * @constructor
	 *
	 * @param string $lang the 2 chars lang
	 */
	public function __construct( $lang ) {
		if ( !$lang ) {
			throw new Exception(
				"Lang parameter not defined, could not construct the Importer object"
			   );
		}
		$this->lang = $lang;
	}

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
