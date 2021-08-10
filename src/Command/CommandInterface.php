<?php

namespace TemplateImporter\Command;

interface CommandInterface {

	public function which( $binary );

	public function execute( $command );
}
