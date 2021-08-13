<?php

namespace TemplateImporter;

use TemplateImporter\Command\ShellCommand;
use TemplateImporter\Config\ConfigInterface;
use TemplateImporter\Config\MediaWikiConfig;
use TemplateImporter\Repository\DbFactoryRepository;
use MediaWiki\MediaWikiServices;

class TemplateImporter {
	/**
	 * @codeCoverageIgnore Nothing testable here
	 */
	public static function addExtensionCSS( &$parser, &$text ) {
		global $wgTemplateImporterCSSLoaded;
		if ( $wgTemplateImporterCSSLoaded === true ) {
			return true;
		}

		$parser->mOutput->addHeadItem(
			'<link rel="stylesheet" href="/load.php?debug=false&amp;lang=en'
			. '&amp;modules=ext.ti.templateimporter&amp;only=styles'
			. '&amp;skin=templateimporter"/>'
		);

		$wgTemplateImporterCSSLoaded = true;

		return true;
	}

	/**
     * @codeCoverageIgnore
     * Unable to test this, because in the PhpUnit env, the extension is already loaded
	 */
	public static function initExtension( $credits = [] ) {
		global $wgTemplateImporterMWPath;

		// We create a $IP variable with this extension name
		// We recreate with the same value as includes/WebStart.php:60
		$wgTemplateImporterMWPath = realpath( '.' );

		define( 'TI_VERSION', isset( $credits['version'] ) ? $credits['version'] : 'N/A' );
	}

    public static function getDefaultConfig(): ConfigInterface {
        global $IP;
        return new MediaWikiConfig(
            MediaWikiServices::getInstance()->getContentLanguage(),
			//'fr',
			new DbFactoryRepository(),
            new ShellCommand(),
            $IP
		);
	}
}
