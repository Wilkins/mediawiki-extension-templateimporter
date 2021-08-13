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

	public function getGlob( $dir, $file ) {
		if ( $file == 'Toureiffel.jpg.txt' ) {
			return [
				realpath( __DIR__ . "/../../tests/fixtures/pages/Fichier:$file" )
			];
		}
		return [];
	}
}
