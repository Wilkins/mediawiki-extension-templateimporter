<?php

namespace TemplateImporter\Config;

class MediaWikiConfig extends AbstractConfig {

    public function getFileExtensions() {
        global $wgFileExtensions;
        return $wgFileExtensions;
    }



}
