<?php

namespace TemplateImporter\Repository;

class MemoryFactoryRepository implements FactoryRepositoryInterface {

    public $comment;

    /*
    public function injectComment( $comment ) {
        $this->comment;
    }
     */

    public function createPageTextRepository() : PageTextRepositoryInterface {
        $repository = new MemoryPageTextRepository();
        $repository->comment = $this->comment;
        return $repository;
    }
    public function createPageImageRepository() : PageImageRepositoryInterface {
        $repository = new MemoryPageImageRepository();
        $repository->comment = $this->comment;
        return $repository;
        //return new MemoryPageImageRepository();
    }

}
