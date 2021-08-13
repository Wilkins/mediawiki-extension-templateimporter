<?php

namespace TemplateImporter\Command;

/**
 * @codeCoverageIgnore
 */
class ShellCommand implements CommandInterface {

	/**
	 * TODO: Use where is windows platform
	 */
	public function which( $binary ) {
		return trim( shell_exec( "which $binary" ) );
	}

	public function execute( $command ) {
		return shell_exec( $command );
	}

	public function getFileSize( $filename ) {
		return filesize( $filename );
	}

	public function getFileContents( $filename ) {
		return file_get_contents( $filename );
	}

	public function getGlob( $dir, $file ) {
		return glob( $dir . '/*:' . $file );
	}
}
