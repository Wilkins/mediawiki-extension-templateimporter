<?php

namespace TemplateImporter;

class NamespaceManagerEnTest extends NamespaceManagerBase
{

    public $lang = 'en';

    public function dataProviderNamespaceCore()
    {
        return NamespaceProviderEn::providerNamespacesCore();
    }

    public function dataProviderNamespaceTravel()
    {
        return NamespaceProviderEn::providerNamespacesTravel();
    }

}
