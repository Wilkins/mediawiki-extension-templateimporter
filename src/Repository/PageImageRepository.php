<?php

namespace TemplateImporter\Repository;

interface PageImageRepository  {

    public function getCurrentSize( $pageTitle, $namespaceId );
    public function getComment( $pageTitle, $namespace );

}
