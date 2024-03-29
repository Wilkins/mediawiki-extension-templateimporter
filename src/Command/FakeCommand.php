<?php

namespace TemplateImporter\Command;

class FakeCommand implements CommandInterface {

	public function which( $binary ) {
		return "/dev/null/$binary";
	}

	public function execute( $command ) {
		return "Executing $command OK";
	}

	public function getFileSize( $filename ) {
		return "1234";
	}

	public function getFileContents( $filename ) {
		return "Lorem ipsum file content";
	}

    /**
     * We all a real glob call to be able to handle fixtures files
     */
	public function getGlob( $dir, $file ) {
        return glob( $dir . '/*:' . $file );
	}
}
