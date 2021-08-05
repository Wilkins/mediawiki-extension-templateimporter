<?php

namespace TemplateImporter;


class NamespaceProviderFr
{

    /**
     * @dataProvider
     * @see https://www.mediawiki.org/wiki/Extension_default_namespaces
     */

    public static function providerNamespacesCore()
    {
        return [
            [ -2, 'NS_MEDIA', 'Média' ],
            [ -1, 'NS_SPECIAL', 'Spécial' ],
            [ 1, 'NS_TALK', 'Discussion' ],
            [ 2, 'NS_USER', 'Utilisateur' ],
            [ 3, 'NS_USER_TALK', 'Discussion_utilisateur' ],
            //[ 4, 'NS_PROJECT', 'Projet' ],
            //[ 5, 'NS_PROJECT_TALK', 'Discussion_projet' ],
            [ 6, 'NS_FILE', 'Fichier' ],
            [ 7, 'NS_FILE_TALK', 'Discussion_fichier' ],
            [ 8, 'NS_MEDIAWIKI', 'MediaWiki' ],
            [ 9, 'NS_MEDIAWIKI_TALK', 'Discussion_MediaWiki' ],
            [ 10, 'NS_TEMPLATE', 'Modèle' ],
            [ 11, 'NS_TEMPLATE_TALK', 'Discussion_modèle' ],
            [ 12, 'NS_HELP', 'Aide' ],
            [ 13, 'NS_HELP_TALK', 'Discussion_aide' ],
            [ 14, 'NS_CATEGORY', 'Catégorie' ],
            [ 15, 'NS_CATEGORY_TALK', 'Discussion_catégorie' ],

        ];
    }

    public static function providerNamespacesTravel()
    {
        return [
            [ 2900, 'NS_TRAVEL', 'Voyage' ],
            [ 2901, 'NS_TRAVEL_TALK', 'Discussion_voyage' ],
            [ 2902, 'NS_TRAVEL_TEMPLATE', 'Modèle_de_voyage' ],
            [ 2903, 'NS_TRAVEL_TEMPLATE_TALK', 'Discussion_modèle_de_voyage' ],
            [ 2904, 'NS_TRAVEL_FORM', 'Formulaire_de_voyage' ],
            [ 2905, 'NS_TRAVEL_FORM_TALK', 'Discussion_formulaire_de_voyage' ],
        ];
    }

    public static function providerNamespacesGenealogy()
    {
        return [
            [ 2700, 'NS_SGENEALOGY', 'Généalogie' ],
            [ 2701, 'NS_SGENEALOGY_TALK', 'Discussion_généalogie' ],
            [ 2702, 'NS_SGENEALOGY_TEMPLATE', 'Modèle_de_généalogie' ],
            [ 2703, 'NS_SGENEALOGY_TEMPLATE_TALK', 'Discussion_modèle_de_généalogie' ],
            [ 2704, 'NS_SGENEALOGY_FORM', 'Formulaire_de_généalogie' ],
            [ 2705, 'NS_SGENEALOGY_FORM_TALK', 'Discussion_formulaire_de_généalogie' ],
        ];
    }
}
