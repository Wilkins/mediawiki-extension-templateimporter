<?php

namespace TemplateImporter\Command;

interface CommandInterface {

	public function which( $binary );
    public function execute( $command );
    public function getFileSize( $filename );
    public function getFileContents( $filename );

}
