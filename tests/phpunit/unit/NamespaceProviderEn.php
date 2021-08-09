<?php

namespace TemplateImporter;

class NamespaceProviderEn
{

	public static function providerNamespacesCore() {

		return [
			[ -2, 'NS_MEDIA', 'Media' ],
			[ -1, 'NS_SPECIAL', 'Special' ],
			[ 1, 'NS_TALK', 'Talk' ],
			[ 2, 'NS_USER', 'User' ],
			[ 3, 'NS_USER_TALK', 'User_talk' ],
			// [ 4, 'NS_PROJECT', 'Project' ],
			// [ 5, 'NS_PROJECT_TALK', 'Project_talk' ],
			[ 6, 'NS_FILE', 'File' ],
			[ 7, 'NS_FILE_TALK', 'File_talk' ],
			[ 8, 'NS_MEDIAWIKI', 'MediaWiki' ],
			[ 9, 'NS_MEDIAWIKI_TALK', 'MediaWiki_talk' ],
			[ 10, 'NS_TEMPLATE', 'Template' ],
			[ 11, 'NS_TEMPLATE_TALK', 'Template_talk' ],
			[ 12, 'NS_HELP', 'Help' ],
			[ 13, 'NS_HELP_TALK', 'Help_talk' ],
			[ 14, 'NS_CATEGORY', 'Category' ],
			[ 15, 'NS_CATEGORY_TALK', 'Category_talk' ],
		];
	}

	public static function providerNamespacesForms() {

		return [
			[ 106, 'PF_NS_FORM', 'Form' ],
			[ 107, 'PF_NS_FORM_TALK', 'Form_talk' ],
		];
	}


	public static function providerNamespacesSemantic() {

		return [
			/*
            [ 100, 'SMW_NS_RELATION', 'Relation' ],
            [ 101, 'SMW_NS_RELATION_TALK', 'Relation_talk' ],
             */
			[ 102, 'SMW_NS_PROPERTY', 'Property' ],
			[ 103, 'SMW_NS_PROPERTY_TALK', 'Property_talk' ],
			/*
            [ 104, 'SMW_NS_TYPE', 'Type' ],
            [ 105, 'SMW_NS_TYPE_TALK', 'Type_talk' ],
             */
			[ 108, 'SMW_NS_CONCEPT', 'Concept' ],
			[ 109, 'SMW_NS_CONCEPT_TALK', 'Concept_talk' ],
			[ 112, 'SMW_NS_SCHEMA', 'smw/schema' ],
			[ 113, 'SMW_NS_SCHEMA_TALK', 'smw/schema_talk' ],
			[ 114, 'SMW_NS_RULE', 'Rule' ],
			[ 115, 'SMW_NS_RULE_TALK', 'Rule_talk' ],

		];
	}
	public static function providerNamespacesTravel() {

		return [
			[ 2900, 'NS_TRAVEL', 'Travel' ],
			[ 2901, 'NS_TRAVEL_TALK', 'Travel_talk' ],
			[ 2902, 'NS_TRAVEL_TEMPLATE', 'Travel_template' ],
			[ 2903, 'NS_TRAVEL_TEMPLATE_TALK', 'Travel_template_talk' ],
			[ 2904, 'NS_TRAVEL_FORM', 'Travel_form' ],
			[ 2905, 'NS_TRAVEL_FORM_TALK', 'Travel_form_talk' ],
		];
	}

	public static function providerNamespacesGenealogy() {

		return [
			[ 2700, 'NS_SGENEALOGY', 'Genealogy' ],
			[ 2701, 'NS_SGENEALOGY_TALK', 'Genealogy_talk' ],
			[ 2702, 'NS_SGENEALOGY_TEMPLATE', 'Genealogy_template' ],
			[ 2703, 'NS_SGENEALOGY_TEMPLATE_TALK', 'Genealogy_template_talk' ],
			[ 2704, 'NS_SGENEALOGY_FORM', 'Genealogy_form' ],
			[ 2705, 'NS_SGENEALOGY_FORM_TALK', 'Genealogy_form_talk' ],
		];
	}
}
