<?php

namespace TemplateImporter\Page;

use TemplateImporter\Repository\PageRepositoryInterface;
use TemplateImporter\Command\CommandInterface;

class PageFactory {

	public static function create( $basename, $pathname, PageRepositoryInterface $repository, CommandInterface $command = null ) {
		if ( PageText::match( $basename ) ) {
			return new PageText( $basename, $pathname, $repository, $command );
		} elseif ( PageImage::match( $basename ) ) {
			return new PageImage( $basename, $pathname, $repository, $command );
		}
		return null;
	}

}
