<?php

namespace TemplateImporter;

abstract class NamespaceManagerBase extends TemplateImporterTest {

    protected $dummyMetaNamespace = 'FooBar';

	public function loadNamespacesTravel( $lang ) {
		$this->manager->loadCustomNamespaces(
			__DIR__ . "/../../fixtures/namespaces/customNamespaces.SemanticTravel.$lang.php" );
	}

	/**
	 * @dataProvider dataProviderNamespaceCore
	 */
	public function testNamespaceNameResolutionCore(
		$namespaceId, $namespaceConstant, $namespaceName ) {
			$detectedId = $this->manager->getNamespaceFromName( $namespaceName );
			$this->assertequals( $namespaceId, $detectedId );
	}

	/**
	 * @dataProvider dataProviderNamespaceCore
	 */
	public function testNamespaceConstantResolutionCore(
		$namespaceId, $namespaceConstant, $namespaceName ) {
		$detectedId = constant( $namespaceConstant );
		$this->assertequals( $namespaceId, $detectedId );
	}

	/**
	 * @dataProvider dataProviderNamespaceCore
	 */
	public function testNamespaceIdResolutionCore(
		$namespaceId, $namespaceConstant, $namespaceName ) {
		$detectedName = $this->manager->getNamespaceName( $namespaceId );
		$this->assertequals( $namespaceName, $detectedName );
	}

	/**
	 * @dataProvider dataProviderNamespaceSemantic
	 */
	public function testNamespaceConstantResolutionSemantic(
		$namespaceId, $namespaceConstant, $namespaceName ) {
		$detectedId = constant( $namespaceConstant );
		$this->assertequals( $namespaceId, $detectedId );
	}

	/**
	 * @dataProvider dataProviderNamespaceForms
	 */
	public function testNamespaceConstantResolutionForms(
		$namespaceId, $namespaceConstant, $namespaceName ) {
		$detectedId = constant( $namespaceConstant );
		$this->assertequals( $namespaceId, $detectedId );
	}

	/**
	 * @dataProvider dataProviderNamespaceForms
	 */
	public function testNamespaceNameResolutionForms(
		$namespaceId, $namespaceConstant, $namespaceName ) {
		$detectedId = $this->manager->getNamespaceFromName( $namespaceName );
		$this->assertequals( $namespaceId, $detectedId );
	}

	/**
	 * @dataProvider dataProviderNamespaceForms
	 */
	public function testNamespaceIdResolutionForms(
		$namespaceId, $namespaceConstant, $namespaceName ) {
		$detectedName = $this->manager->getNamespaceName( $namespaceId );
		$this->assertequals( $namespaceName, $detectedName );
	}

	/**
	 * @dataProvider dataProviderNamespaceSemantic
	 */
	public function testNamespaceNameResolutionSemantic(
		$namespaceId, $namespaceConstant, $namespaceName ) {
		$detectedId = $this->manager->getNamespaceFromName( $namespaceName );
		$this->assertequals( $namespaceId, $detectedId );
	}

	/**
	 * @dataProvider dataProviderNamespaceSemantic
	 */
	public function testNamespaceIdResolutionSemantic(
		$namespaceId, $namespaceConstant, $namespaceName ) {
		$detectedName = $this->manager->getNamespaceName( $namespaceId );
		$this->assertequals( $namespaceName, $detectedName );
	}

	/**
	 * @dataProvider dataProviderNamespaceTravel
	 */
	public function testNamespaceNameResolutionExtensions(
		$namespaceId, $namespaceConstant, $namespaceName ) {
		$this->loadNamespacesTravel( $this->lang );

		$detectedId = $this->manager->getNamespaceFromName( $namespaceName );
		$this->assertequals( $namespaceId, $detectedId );
	}

	/**
	 * @dataProvider dataProviderNamespaceTravel
	 */
	public function testNamespaceConstantResolutionExtensions(
		$namespaceId, $namespaceConstant, $namespaceName ) {
		$this->loadNamespacesTravel( $this->lang );

		$detectedId = constant( $namespaceConstant );
		$this->assertequals( $namespaceId, $detectedId );
	}

	/**
	 * @dataProvider dataProviderNamespaceTravel
	 */
	public function testNamespaceIdResolutionExtensions(
		$namespaceId, $namespaceConstant, $namespaceName ) {
		$this->loadNamespacesTravel( $this->lang );

		$detectedName = $this->manager->getNamespaceName( $namespaceId );
		$this->assertequals( $namespaceName, $detectedName );
	}

	abstract public function dataProviderNamespaceCore();

	abstract public function dataProviderNamespaceSemantic();

	abstract public function dataProviderNamespaceTravel();

	/**
	 * @dataProvider dataProviderNamespaceTravel
	 */
	public function testMissingFileNamespaceIdResolutionExtensions(
		$namespaceId, $namespaceConstant, $namespaceName ) {
		$this->expectException( 'TemplateImporter\Exception\MissingFileException' );
		$this->loadNamespacesTravel( 'missing-lang' );
	}

	public function testMissingNamespaceFromName() {
		$this->expectException( 'TemplateImporter\Exception\MissingNamespaceException' );
		$detectedName = $this->manager->getNamespaceFromName( 'FakeDummyNamespace' );
	}

	public function testMissingNamespaceFromId() {
		$this->expectException( 'TemplateImporter\Exception\MissingNamespaceException' );
		$detectedName = $this->manager->getNamespaceName( 87984654 );
    }

    public function testMetaNamespaceName() {
        $this->config->setMetaNamespace( $this->dummyMetaNamespace );
        $this->manager = new NamespaceManager( $this->config );
        $name = $this->manager->getNamespaceName( 4 );
        $this->assertEquals( $name, $this->dummyMetaNamespace );
    }

    public function testMetaNamespaceId() {
        $this->config->setMetaNamespace( $this->dummyMetaNamespace );
        $this->manager = new NamespaceManager( $this->config );
        $id = $this->manager->getNamespaceFromName( $this->dummyMetaNamespace );
        $this->assertEquals( 4, $id );
    }

    public function testMetaNamespaceTalkName() {
        $this->config->setMetaNamespace( $this->dummyMetaNamespace );
        $this->manager = new NamespaceManager( $this->config );
        $name = $this->manager->getNamespaceName( 5 );
        $this->assertEquals( $name, $this->dummyMetaNamespaceTalk );
    }

    public function testMetaNamespaceTalkId() {
        $this->config->setMetaNamespace( $this->dummyMetaNamespace );
        $this->manager = new NamespaceManager( $this->config );
        $id = $this->manager->getNamespaceFromName( $this->dummyMetaNamespaceTalk );
        $this->assertEquals( 5, $id );
    }

}
