<?php

namespace TemplateImporter\Page;

use TemplateImporter\Config\ConfigInterface;

class PageFactory {

	public static function create(
		$basename,
		$pathname,
		ConfigInterface $config

	) {
		if ( PageText::match( $basename, $config ) ) {
			return new PageText( $basename, $pathname, $config );
		} elseif ( PageImage::match( $basename, $config ) ) {
			return new PageImage( $basename, $pathname, $config );
		}
		return null;
	}

}
