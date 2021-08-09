<?php

namespace TemplateImporter\Repository;

interface PageTextRepository  {

    public function getCurrentText( $pageTitle, $namespaceId );
    public function getCurrentRevision( $pageTitle, $namespaceId );
    public function getComment( $pageTitle, $namespace );
    public function setComment( $pageTitle, $namespaceId, $comment );

}
