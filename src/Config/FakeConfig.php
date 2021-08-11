<?php

namespace TemplateImporter\Config;

class FakeConfig implements ConfigInterface {

    public function getFileExtensions() {
        return ['jpg', 'png'];
    }



}
