<?php

namespace TemplateImporter\Config;

class FakeConfig extends AbstractConfig {

	public function setMetaNamespace( $metaNamespace ) {
		$this->metaNamespace = $metaNamespace;
	}

	public function setMediaWikiPath( $mediaWikiPath ) {
		$this->mediaWikiPath = $mediaWikiPath;
	}

	public function getFileExtensions() {
		return [ 'jpg', 'png' ];
	}

    public function getMetaNamespace() {
        return $this->metaNamespace;
	}

}
