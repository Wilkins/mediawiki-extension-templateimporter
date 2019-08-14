<?php
/**
 * Initialization file for the Template Importer extension.
 *
 * On MediaWiki.org: https://www.mediawiki.org/wiki/Extension:Template_Importer
 *
 * @file    TemplateImporter.php
 * @ingroup TemplateImporter
 *
 * @licence GNU GPL v2+
 * @author  Thibault Taillandier <thibault@taillandier.name>
 */

/**
 * This documentation group collects source code files belonging to Template Importer
 *
 * Please do not use this group name for other code.
 * If you have an extension, please use your own group definition.
 *
 * @defgroup TemplateImporter Template Importer
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

if ( version_compare( $wgVersion, '1.23', '<' ) ) {
	die( '<b>Error:</b> This version of Template Importer requires MediaWiki 1.23 or above.' );
}

if ( !defined( 'TI_VERSION' ) ) {
	define( 'TI_VERSION', '0.1.0' );
}

$wgExtensionCredits['semantic'][] = [
	'path' => __FILE__,
	'name' => 'Template Importer',
	'version' => TI_VERSION,
	'author' => [
		'[https://www.mediawiki.org/wiki/User:Thibault_Taillandier Thibault Taillandier]',
	],
	'url' => 'https://www.mediawiki.org/wiki/Extension:Template_Importer',
	'descriptionmsg' => 'templateimporter-desc',
	'license-name' => 'GPL-2.0+'
];

$wgMessagesDirs['TemplateImporter'] = __DIR__ . '/i18n';
// Quite dumb, but php code sniffer yells about importing global variables with "$IP" name...
// So we copy it into a $wg variable with an extension name
$wgTemplateImporterMWPath = $IP;

/*
$autoloadFile = __DIR__.'/vendor/autoload.php';
if ( file_exists( $autoloadFile ) ) {
	require_once $autoloadFile;
} else {
    throw new Exception( "File $autoloadFile is missing."
       ." It is required to load all the TemplateImporter classes." );
}
*/

$templateImporterClasses = array(
	'TemplateImporter\BaseImporter'           => __DIR__ . '/src/BaseImporter.php',
	'TemplateImporter\BaseImporter'           => __DIR__ . '/src/BaseImporter.php',
	'TemplateImporter\BaseSpecialImportPages' => __DIR__ . '/src/BaseSpecialImportPages.php',
	'TemplateImporter\Exception'              => __DIR__ . '/src/Exception.php',
	'TemplateImporter\NamespaceManager'       => __DIR__ . '/src/NamespaceManager.php',
	'TemplateImporter\PageFactory'            => __DIR__ . '/src/PageFactory.php',
	'TemplateImporter\Page'                   => __DIR__ . '/src/Page.php',
	'TemplateImporter\PageText'               => __DIR__ . '/src/PageText.php',
	'TemplateImporter\PageImage'              => __DIR__ . '/src/PageImage.php',
	'TemplateImporter\TemplateImporter'       => __DIR__ . '/src/TemplateImporter.php',
);

$GLOBALS['wgAutoloadClasses'] = array_merge(
	$GLOBALS['wgAutoloadClasses'],
	$templateImporterClasses
);


$moduleTemplate = [
	'localBasePath' => __DIR__,
	'remoteBasePath' => ( $wgExtensionAssetsPath === false ? $wgScriptPath
		. '/extensions' : $wgExtensionAssetsPath ) . '/TemplateImporter',
	'group' => 'ext.ti'
];
$wgResourceModules['ext.ti.templateimporter'] = $moduleTemplate + [
	// 'scripts' => 'modules/specialFamilyTree.js',
	'styles' => 'modules/styles.css',
	/*
	'dependencies' => [
		'jquery.ui.autocomplete'
    ],
     */
	'messages' => [
	]
];

$wgHooks['ParserBeforeTidy'][] = '\TemplateImporter\TemplateImporter::addExtensionCSS';

