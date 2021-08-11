<?php

namespace TemplateImporter\Page;

use TemplateImporter\Command\CommandInterface;
use TemplateImporter\Config\ConfigInterface;
use TemplateImporter\Config\MediaWikiConfig;
use TemplateImporter\Repository\FactoryRepositoryInterface;

class PageFactory {

	public static function create(
		$basename,
		$pathname,
		//FactoryRepositoryInterface $factory = null,
// PageRepositoryInterface $repository,
        //CommandInterface $command = null,
        ConfigInterface $config

	) {
		if ( !$config ) {
			//$config = new MediaWikiConfig();
		}
		if ( PageText::match( $basename , $config ) ) {
			return new PageText( $basename, $pathname, $config );
		} elseif ( PageImage::match( $basename, $config ) ) {
			return new PageImage( $basename, $pathname, $config );
		}
		return null;
	}

}
