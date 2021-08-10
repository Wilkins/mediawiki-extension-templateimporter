<?php

namespace TemplateImporter\Page;

use TemplateImporter\Command\CommandInterface;
use TemplateImporter\Repository\FactoryRepositoryInterface;

class PageFactory {

	public static function create(
		$basename,
		$pathname,
		FactoryRepositoryInterface $factory,
// PageRepositoryInterface $repository,
		CommandInterface $command = null
	) {
		if ( PageText::match( $basename ) ) {
			return new PageText( $basename, $pathname, $factory, $command );
		} elseif ( PageImage::match( $basename ) ) {
			return new PageImage( $basename, $pathname, $factory, $command );
		}
		return null;
	}

}
