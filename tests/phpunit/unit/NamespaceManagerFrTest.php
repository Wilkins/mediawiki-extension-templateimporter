<?php

namespace TemplateImporter;

class NamespaceManagerFrTest extends NamespaceManagerBase {

	public $lang = 'fr';

	public function dataProviderNamespaceCore() {
		return NamespaceProviderFr::providerNamespacesCore();
	}

	public function dataProviderNamespaceForms() {
		return NamespaceProviderFr::providerNamespacesForms();
	}

	public function dataProviderNamespaceSemantic() {
		return NamespaceProviderFr::providerNamespacesSemantic();
	}

	public function dataProviderNamespaceTravel() {
		return NamespaceProviderFr::providerNamespacesTravel();
	}

}
