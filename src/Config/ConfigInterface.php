<?php

namespace TemplateImporter\Config;

interface ConfigInterface {

	public function getLang();
	public function getFactory();
	public function getCommand();
	public function getMediaWikiPath();
	public function getFileExtensions();
	public function getMetaNamespace();

}
