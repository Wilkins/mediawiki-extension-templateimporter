<?php

namespace TemplateImporter\Config;

class FakeConfig extends AbstractConfig {

	public function getFileExtensions() {
		return [ 'jpg', 'png' ];
	}

}
