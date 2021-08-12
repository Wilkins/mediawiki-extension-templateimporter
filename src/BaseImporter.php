<?php

namespace TemplateImporter;

use DirectoryIterator;
use TemplateImporter\Page\PageFactory;
use TemplateImporter\Repository\FactoryRepositoryInterface;
use TemplateImporter\Command\CommandInterface;
use TemplateImporter\Config\ConfigInterface;
use TemplateImporter\Exception\Exception;


class BaseImporter {

    public $config;

	/**
	 * @param string $lang the 2 chars lang
	 */
    public function __construct( ConfigInterface $config = null ) {
        if ( !$config ){
            $config = TemplateImporter::getDefaultConfig();
        }
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

			$page = PageFactory::create(
				$file->getBasename(),
                $file->getPathname(),
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
