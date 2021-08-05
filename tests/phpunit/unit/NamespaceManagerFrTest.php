<?php

namespace TemplateImporter;

class NamespaceManagerFrTest extends NamespaceManagerBase
{

    public $lang = 'fr';

    public function dataProviderNamespaceCore()
    {
        return NamespaceProviderFr::providerNamespacesCore();
    }

    public function dataProviderNamespaceTravel()
    {
        return NamespaceProviderFr::providerNamespacesTravel();
    }

}
