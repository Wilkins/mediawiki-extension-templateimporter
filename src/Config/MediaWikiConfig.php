<?php

namespace TemplateImporter\Config;

/**
 * @codeCoverageIgnore
 */
class MediaWikiConfig extends AbstractConfig {

	public function getFileExtensions() {
		global $wgFileExtensions;
		return $wgFileExtensions;
	}

}
