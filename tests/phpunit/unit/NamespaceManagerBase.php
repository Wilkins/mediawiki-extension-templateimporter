<?php

namespace TemplateImporter;

use MediaWikiUnitTestCase;


abstract class NamespaceManagerBase extends MediaWikiUnitTestCase
{

    public $mediawikiPath;
    public $manager;
    public $lang;

    /*
    public static function setUpBeforeClass() : void
    {
        echo "SetupBeforeClass\n";
    }
     */
    
    public function setUp() : void
    {
        //echo "SetUp\n";
        $this->mediawikiPath = __DIR__."/../../../../../";
        //include $this->mediawikiPath.'/LocalSettings.php';
        //print_r( $GLOBALS );
        //echo $GLOBALS['wgLanguageCode'];
        $this->manager = new NamespaceManager( $this->mediawikiPath, $this->lang );
    }

    public function loadNamespacesTravel( $lang )
    {
        $this->manager->loadCustomNamespaces( 
            __DIR__."/../../fixtures/customNamespaces.SemanticTravel.$lang.php" );
    }

    /**
     * @dataProvider dataProviderNamespaceCore
     */
    public function testNamespaceNameResolutionCore(
        $namespaceId, $namespaceConstant, $namespaceName )
    {
            $detectedId = $this->manager->getNamespaceFromName( $namespaceName );
            $this->assertequals( $namespaceId, $detectedId );
    }

    /**
     * @dataProvider dataProviderNamespaceCore
     */
    public function testNamespaceConstantResolutionCore(
        $namespaceId, $namespaceConstant, $namespaceName )
    {
        $detectedId = constant( $namespaceConstant );
        $this->assertequals( $namespaceId, $detectedId );
    }

    /**
     * @dataProvider dataProviderNamespaceCore
     */
    public function testNamespaceIdResolutionCore(
        $namespaceId, $namespaceConstant, $namespaceName )
    {
        $detectedName = $this->manager->getNamespaceName( $namespaceId );
        $this->assertequals( $namespaceName, $detectedName );
    }

    /**
     * @dataProvider dataProviderNamespaceSemantic
     */
    public function testNamespaceConstantResolutionSemantic(
        $namespaceId, $namespaceConstant, $namespaceName )
    {
        $detectedId = constant( $namespaceConstant );
        $this->assertequals( $namespaceId, $detectedId );
    }

    /**
     * @dataProvider dataProviderNamespaceSemantic
     */
    public function testNamespaceNameResolutionSemantic(
        $namespaceId, $namespaceConstant, $namespaceName )
    {
        $detectedId = $this->manager->getNamespaceFromName( $namespaceName );
        $this->assertequals( $namespaceId, $detectedId );
    }

    /**
     * @dataProvider dataProviderNamespaceSemantic
     */
    public function testNamespaceIdResolutionSemantic(
        $namespaceId, $namespaceConstant, $namespaceName )
    {
        $detectedName = $this->manager->getNamespaceName( $namespaceId );
        $this->assertequals( $namespaceName, $detectedName );
    }


    /**
     * @dataProvider dataProviderNamespaceTravel
     */
    public function testNamespaceNameResolutionExtensions(
        $namespaceId, $namespaceConstant, $namespaceName )
    {
        $this->loadNamespacesTravel( $this->lang );

        $detectedId = $this->manager->getNamespaceFromName( $namespaceName );
        $this->assertequals( $namespaceId, $detectedId );
    }

    /**
     * @dataProvider dataProviderNamespaceTravel
     */
    public function testNamespaceConstantResolutionExtensions(
        $namespaceId, $namespaceConstant, $namespaceName )
    {
        $this->loadNamespacesTravel( $this->lang );

        $detectedId = constant( $namespaceConstant );
        $this->assertequals( $namespaceId, $detectedId );
    }

    /**
     * @dataProvider dataProviderNamespaceTravel
     */
    public function testNamespaceIdResolutionExtensions(
        $namespaceId, $namespaceConstant, $namespaceName )
    {
        $this->loadNamespacesTravel( $this->lang );

        $detectedName = $this->manager->getNamespaceName( $namespaceId );
        $this->assertequals( $namespaceName, $detectedName );
    }


    public function dataProviderNamespaceCore()
    {
        throw new Exception( "Please use a specific dataProvider for dataProviderNamespaceCore" );
    }

    public function dataProviderNamespaceSemantic()
    {
        throw new Exception( "Please use a specific dataProvider for dataProviderNamespaceSemantic" );
    }

    public function dataProviderNamespaceTravel()
    {
        throw new Exception( "Please use a specific dataProvider for dataProviderNamespaceTravel" );
    }

}
