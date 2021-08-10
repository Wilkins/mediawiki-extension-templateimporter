<?php

namespace TemplateImporter\Command;

class FakeCommand implements CommandInterface {

	public function which( $binary ) {
		return "/dev/null/$binary";
	}

	public function execute( $command ) {
		return "Executing $command OK";
	}
}
