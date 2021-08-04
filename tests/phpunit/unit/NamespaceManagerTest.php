<?php

namespace TemplateImporter;

use PHPUnit\FrameworkTestCase;
use MediaWikiUnitTestCase;

class NamespaceManagerTest extends MediaWikiUnitTestCase
{
    public $mediawikiPath;
    public $manager;

    public function setUp() : void
    {
        $this->mediawikiPath = __DIR__."/../../../../../";
        $this->manager = new NamespaceManager( $this->mediawikiPath, 'fr' );

    }


    /**
     * @dataProvider TemplateImporter\NamespaceProvider::providerNamespaces
     */
    public function testNamespaceNameResolutionCore( 
        $namespaceId, $namespaceConstant, $namespaceName )
    {
        $detectedId = $this->manager->getNamespaceFromName( $namespaceName );
        $this->assertequals( $namespaceId, $detectedId );
    }

    /**
     * @dataProvider TemplateImporter\NamespaceProvider::providerNamespaces
     */
    public function testNamespaceConstantResolutionCore(
        $namespaceId, $namespaceConstant, $namespaceName )
    {
        $detectedId = constant( $namespaceConstant );
        $this->assertequals( $namespaceId, $detectedId );
    }


    /**
     * @dataProvider TemplateImporter\NamespaceProvider::providerNamespacesTravel
     */
    public function testNamespaceNameResolutionExtensions(
        $namespaceId, $namespaceConstant, $namespaceName )
    {
        $this->manager->loadCustomNamespaces( 
            __DIR__.'/../../fixtures/customNamespaces.SemanticTravel.en.php' );
        $this->manager->loadCustomNamespaces( 
            __DIR__.'/../../fixtures/customNamespaces.SemanticTravel.fr.php' );

        $detectedId = $this->manager->getNamespaceFromName( $namespaceName );
        $this->assertequals( $namespaceId, $detectedId );
    }

    /**
     * @dataProvider TemplateImporter\NamespaceProvider::providerNamespacesTravel
     */
    public function testNamespaceConstantResolutionExtensions(
        $namespaceId, $namespaceConstant, $namespaceName )
    {
        $this->manager->loadCustomNamespaces( 
            __DIR__.'/../../fixtures/customNamespaces.SemanticTravel.en.php' );
        $this->manager->loadCustomNamespaces( 
            __DIR__.'/../../fixtures/customNamespaces.SemanticTravel.fr.php' );

        $detectedId = constant( $namespaceConstant );
        $this->assertequals( $namespaceId, $detectedId );
    }

    /**
     * @dataProvider TemplateImporter\NamespaceProvider::providerNamespaces
     */
    public function testNamespaceIdResolutionCore(
        $namespaceId, $namespaceConstant, $namespaceName )
    {
        $detectedName = $this->manager->getNamespaceName( $namespaceId );
        $this->assertequals( $namespaceName, $detectedName );
    }


    /**
     * @dataProvider TemplateImporter\NamespaceProvider::providerNamespacesTravelEn
     */
    public function testNamespaceIdResolutionExtensionsEn(
        $namespaceId, $namespaceConstant, $namespaceName )
    {
        $this->manager->loadCustomNamespaces( 
            __DIR__.'/../../fixtures/customNamespaces.SemanticTravel.en.php' );

        $detectedName = $this->manager->getNamespaceName( $namespaceId );
        $this->assertequals( $namespaceName, $detectedName );
    }

    /**
     * @dataProvider TemplateImporter\NamespaceProvider::providerNamespacesTravelFr
     */
    public function testNamespaceIdResolutionExtensionsFr(
        $namespaceId, $namespaceConstant, $namespaceName )
    {
        $this->manager->loadCustomNamespaces( 
            __DIR__.'/../../fixtures/customNamespaces.SemanticTravel.fr.php' );

        $detectedName = $this->manager->getNamespaceName( $namespaceId );
        $this->assertequals( $namespaceName, $detectedName );
    }



}
