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
		FactoryRepositoryInterface $factory,
// PageRepositoryInterface $repository,
        CommandInterface $command = null,
        ConfigInterface $config = null

	) {
		if ( !$config ) {
			$config = new MediaWikiConfig();
		}
		if ( PageText::match( $basename , $config ) ) {
			return new PageText( $basename, $pathname, $factory, $command, $config );
		} elseif ( PageImage::match( $basename, $config ) ) {
			return new PageImage( $basename, $pathname, $factory, $command, $config );
		}
		return null;
	}

}
