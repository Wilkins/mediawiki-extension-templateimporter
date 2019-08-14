<?php

namespace TemplateImporter;

use TemplateImporter\PageText;
use TemplateImporter\PageImage;

class PageFactory {

	public static function create( $basename, $pathname ) {

		if ( PageText::match( $basename ) ) {
			return new PageText( $basename, $pathname );
		} elseif ( PageImage::match( $basename ) ) {
			return new PageImage( $basename, $pathname );
		}
		return null;
	}

}
