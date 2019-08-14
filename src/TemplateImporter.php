<?php

namespace TemplateImporter;

class TemplateImporter {
	public static function addExtensionCSS( &$parser, &$text ) {
		global $wgTemplateImporterCSSLoaded;
		if ( $wgTemplateImporterCSSLoaded === true ) {
			return true;
		}

		$parser->mOutput->addHeadItem(
			'<link rel="stylesheet" href="/load.php?debug=false&amp;lang=en'
			.'&amp;modules=ext.ti.templateimporter&amp;only=styles'
			.'&amp;skin=templateimporter"/>'
		);

		$wgTemplateImporterCSSLoaded = true;

		return true;
	}
}
