<?php

namespace TemplateImporter\Config;

class MediaWikiConfig implements ConfigInterface {

    public function getFileExtensions() {
        global $wgFileExtensions;
        return $wgFileExtensions;
    }



}
