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
}
