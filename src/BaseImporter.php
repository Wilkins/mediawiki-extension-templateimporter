<?php

namespace TemplateImporter;

use DirectoryIterator;
use TemplateImporter\Config\ConfigInterface;
use TemplateImporter\Exception\Exception;
use TemplateImporter\Page\PageFactory;

class BaseImporter {

	public $config;
	public $templateDir;

	/**
	 * @param string|null $lang the 2 chars lang
	 */
	public function __construct(
		string $templateDir = null,
		ConfigInterface $config = null
	) {
		if ( !$config ) {
			$config = TemplateImporter::getDefaultConfig();
		}
		if ( !is_dir( $templateDir ) ) {
			throw new Exception( "Directory $templateDir does not exist." );
		}
		$this->config = $config;
		$this->templateDir = $templateDir;
	}

	/**
	 * List all the importable files from the given lang
	 *
	 * @return array the list of importable files
	 */
	public function listFiles() {
		$files = [];
		foreach ( new DirectoryIterator( $this->templateDir ) as $file ) {
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
