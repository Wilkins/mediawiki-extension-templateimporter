<?php

namespace TemplateImporter\Page;

use TemplateImporter\Repository\PageRepositoryInterface;

class PageFactory {

	public static function create( $basename, $pathname, PageRepositoryInterface $repository ) {
		if ( PageText::match( $basename ) ) {
			return new PageText( $basename, $pathname, $repository );
		} elseif ( PageImage::match( $basename ) ) {
			return new PageImage( $basename, $pathname, $repository );
		}
		return null;
	}

}
