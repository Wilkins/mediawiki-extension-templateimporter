<?php

namespace TemplateImporter;

use DirectoryIterator;
use TemplateImporter\Page\PageFactory;
use TemplateImporter\Repository\FactoryRepositoryInterface;
use TemplateImporter\Command\CommandInterface;
use TemplateImporter\Config\ConfigInterface;
use TemplateImporter\Exception\Exception;


class BaseImporter {

    public $lang;
    public $factory;
    public $command;
    public $config;

	/**
	 * @param string $lang the 2 chars lang
	 */
    public function __construct( $lang,
        FactoryRepositoryInterface $factory,
        CommandInterface $command,
        ConfigInterface $config
    ) {
		if ( !$lang ) {
			throw new Exception(
				"Lang parameter not defined, could not construct the Importer object"
			   );
		}
        $this->lang = $lang;
        $this->factory = $factory;
        $this->command = $command;
        $this->config = $config;
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
                $file->getPathname(),
                $this->factory,
                $this->command,
                $this->config
			);
			if ( $page ) {
			    $files[ $page->pageName ] = $page;
			}

		}

		ksort( $files );
		return $files;
	}
}
