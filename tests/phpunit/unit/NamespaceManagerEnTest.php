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

    /*
    public function testMetaNamespaceTalkName() {
        $dummyMetaNamespace = 'FooBar';
        $this->config->setMetaNamespace( $dummyMetaNamespace );
        $this->manager = new NamespaceManager( $this->config );
        $name = $this->manager->getNamespaceName( 4 );
        $this->assertEquals( $name, $dummyMetaNamespace );
    }

    public function testMetaNamespaceTalkId() {
        $dummyMetaNamespace = 'FooBar';
        $this->config->setMetaNamespace( $dummyMetaNamespace );
        $this->manager = new NamespaceManager( $this->config );
        $id = $this->manager->getNamespaceFromName( $dummyMetaNamespace );
        $this->assertEquals( 4, $id );
        $this->assertEquals( 5, $id );
    }
*/
}
