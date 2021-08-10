<?php

namespace TemplateImporter;

class NamespaceManagerEnTest extends NamespaceManagerBase {

	public $lang = 'en';

	public function dataProviderNamespaceCore() {
		return NamespaceProviderEn::providerNamespacesCore();
	}

	public function dataProviderNamespaceForms() {
		return NamespaceProviderEn::providerNamespacesForms();
	}

	public function dataProviderNamespaceSemantic() {
		return NamespaceProviderEn::providerNamespacesSemantic();
	}

	public function dataProviderNamespaceTravel() {
		return NamespaceProviderEn::providerNamespacesTravel();
	}

}
