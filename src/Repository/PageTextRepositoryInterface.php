<?php

namespace TemplateImporter\Repository;

interface PageTextRepositoryInterface extends PageRepositoryInterface {

    public function getCurrentText( $pageTitle, $namespaceId );
    public function getCurrentRevision( $pageTitle, $namespaceId );
    public function getComment( $pageTitle, $namespace );
    public function setComment( $pageTitle, $namespaceId, $comment );

}
