<?php

namespace TemplateImporter\Repository;

interface PageImageRepository extends PageRepository {

    public function getCurrentSize( $pageTitle, $namespaceId );
    public function getComment( $pageTitle, $namespace );

}
