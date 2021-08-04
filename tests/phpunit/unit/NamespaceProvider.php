<?php

namespace TemplateImporter;


class NamespaceProvider
{

    /**
     * @dataProvider
     * @see https://www.mediawiki.org/wiki/Extension_default_namespaces
     */
    public static function providerNamespaces()
    {
        return array_merge(
            #$this->providerNamespacesEn(),
            static::providerNamespacesFr(),
        );
    }

    public static function providerNamespacesTravel()
    {
        return array_merge(
            static::providerNamespacesTravelEn(),
            static::providerNamespacesTravelFr(),
        );
    }

    public static function providerNamespacesEn()
    {
        return [
            [ -2, 'NS_MEDIA', 'Média' ],
            [ 2, 'User', 2 ],
            [ 'File', 6 ],
            [ 'Template', 10 ],
            [ 'Special', -1 ],

        ];
    }
    public static function providerNamespacesFr()
    {
        return [
            [ -2, 'NS_MEDIA', 'Média' ],
            [ -1, 'NS_SPECIAL', 'Spécial' ],
            [ 1, 'NS_TALK', 'Discussion' ],
            [ 2, 'NS_USER', 'Utilisateur' ],
            [ 3, 'NS_USER_TALK', 'Discussion_utilisateur' ],
  //          [ 4, 'NS_PROJECT', 'Projet' ],
//            [ 5, 'NS_PROJECT_TALK', 'Discussion_projet' ],
            [ 6, 'NS_FILE', 'Fichier' ],
            [ 7, 'NS_FILE_TALK', 'Discussion_fichier' ],
//            [ 8, 'NS_MEDIAWIKI', 'Mediawiki' ],
//            [ 9, 'NS_MEDIAWIKI_TALK', 'Discussion_Mediawiki' ],
            [ 10, 'NS_TEMPLATE', 'Modèle' ],
            [ 11, 'NS_TEMPLATE_TALK', 'Discussion_modèle' ],
            [ 12, 'NS_HELP', 'Aide' ],
            [ 13, 'NS_HELP_TALK', 'Discussion_aide' ],
            [ 14, 'NS_CATEGORY', 'Catégorie' ],
            [ 15, 'NS_CATEGORY_TALK', 'Discussion_catégorie' ],

/*
            [ 2700, 'NS_SGENEALOGY', 'Généalogie' ],
            [ 2701, 'NS_SGENEALOGY_TALK', 'Discussion_généalogie' ],
            [ 2702, 'NS_SGENEALOGY_TEMPLATE', 'Modèle_de_généalogie' ],
            [ 2703, 'NS_SGENEALOGY_TEMPLATE_TALK', 'Discussion_modèle_de_généalogie' ],
            [ 2704, 'NS_SGENEALOGY_FORM', 'Formulaire_de_généalogie' ],
            [ 2705, 'NS_SGENEALOGY_FORM_TALK', 'Discussion_formulaire_de_généalogie' ],

 */
        ];
    }
    public static function providerNamespacesTravelEn()
    {
        return [
            [ 2900, 'NS_TRAVEL', 'Travel' ],
            [ 2901, 'NS_TRAVEL_TALK', 'Travel_talk' ],
            [ 2902, 'NS_TRAVEL_TEMPLATE', 'Travel_template' ],
            [ 2903, 'NS_TRAVEL_TEMPLATE_TALK', 'Travel_template_talk' ],
            [ 2904, 'NS_TRAVEL_FORM', 'Travel_form' ],
            [ 2905, 'NS_TRAVEL_FORM_TALK', 'Travel_form_talk' ],
        ];
    }
    public static function providerNamespacesTravelFr()
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




}

