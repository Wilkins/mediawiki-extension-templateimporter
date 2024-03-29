<?php

namespace TemplateImporter;

class NamespaceManagerFrTest extends NamespaceManagerBase {

	public $lang = 'fr';
    protected $dummyMetaNamespaceTalk = 'Discussion_FooBar';

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
