<?php

namespace TemplateImporter;

/**
 * @license GNU GPL v2+
 *
 * @author Thibault Taillandier <thibault@taillandier.name>
 */
class NamespaceManager {

	public $PageFormsLoaded = false;
	public $defaultLang = 'en';

	public function loadAll() {

		global $wgLanguageCode;
		$this->loadNamespaceData( $this->defaultLang );

		if ( $wgLanguageCode != 'en' ) {
			$this->loadNamespaceData( $wgLanguageCode );
		}
	}

	public function loadNamespacesMediawiki( $lang ) {
		global $wgNamespaceAliases, $wgExtraNamespaces, $wgCanonicalNamespaceNames,
			$wgTemplateImporterMWPath;
		$filemessages = "$wgTemplateImporterMWPath/languages/messages/Messages".ucfirst( $lang ).".php";
		// echo $filemessages;
		require_once $filemessages;
		foreach ( $namespaceNames as $nsId => $nsName ) {
			$wgCanonicalNamespaceNames[$nsId] = $nsName;
			$wgExtraNamespaces[$nsId] = $nsName;
			$wgNamespaceAliases[$nsName] = $nsId;
		}
	}

	public function loadCustomNamespaces( $filename ) {
		global $wgNamespaceAliases, $wgExtraNamespaces, $wgCanonicalNamespaceNames;
		if ( !file_exists( $filename ) ) {
			throw new Exception( "Language file $filename doesn't exist" );
		}
		require_once $filename;
		foreach ( $customExtensionNamespaces as $customNs ) {
			$nsId = $customNs[0];
			$nsConstant = $customNs[1];
			$nsName = $customNs[2];
			if ( !defined( $nsConstant ) ) {
				define( $nsConstant, $nsId );
			}
			$wgNamespaceAliases[$nsName] = $nsId;

			// If a specific lang is used, it will override the english one.
			$wgExtraNamespaces[$nsId] = $nsName;

			$wgCanonicalNamespaceNames[$nsId] = $nsName;
		}
	}

	public function loadNamespacesPageForms( $lang ) {
		global $wgNamespaceAliases, $wgExtraNamespaces, $wgCanonicalNamespaceNames,
			$wgTemplateImporterMWPath;

		$filemessages = "$wgTemplateImporterMWPath/extensions/PageForms/languages/PF_Namespaces.php";
		require $filemessages;

		foreach ( $namespaceNames[$lang] as $nsId => $nsName ) {
			$wgCanonicalNamespaceNames[$nsId] = $nsName;
			$wgExtraNamespaces[$nsId] = $nsName;
			$wgNamespaceAliases[$nsName] = $nsId;
		}
	}

	public function loadNamespacesSMW( $lang ) {
		global $wgNamespaceAliases, $wgExtraNamespaces, $wgCanonicalNamespaceNames,
			$wgTemplateImporterMWPath;
		if ( !defined( 'SMW_VERSION' ) ) {
			return;
		}
		$filemessages = "$wgTemplateImporterMWPath/extensions/SemanticMediaWiki/languages/"
			."SMW_Language".ucfirst( $lang ).".php";
		// echo $filemessages;
		require_once $filemessages;
		$className = "SMWLanguage".ucfirst( $lang );
		$c = new $className;
		$namespaceNames = $c->getNamespaces();
		foreach ( $namespaceNames as $nsId => $nsName ) {
			$wgCanonicalNamespaceNames[$nsId] = $nsName;
			$wgExtraNamespaces[$nsId] = $nsName;
			$wgNamespaceAliases[$nsName] = $nsId;
		}
	}

	public function loadNamespaceData( $lang ) {
		$this->loadNamespacesMediawiki( $lang );
		$this->loadNamespacesPageForms( $lang );
		$this->loadNamespacesSMW( $lang );
	}

	/**
	 * Get the namespace id from the namespace name
	 *
	 * @throws Exception if namespace is not found.
	 *
	 * @param string $searchName the namespace we are looking for
	 *
	 * @return integer the id of the namespace
	 */
	public static function getNamespaceFromName( $searchName ) {
		global $wgNamespaceAliases;

		if ( isset( $wgNamespaceAliases[$searchName] ) ) {
			return $wgNamespaceAliases[$searchName];
		}

		throw new Exception( "Namespace name « ${searchName} » was not found in TemplateImporter."
			." This should not happen, please contact developpers extension with tag: Error101" );
	}

	/**
	 * Get the namespace id from the namespace name
	 *
	 * @throws Exception if namespace is not found.
	 *
	 * @param string $searchName the namespace we are looking for
	 *
	 * @return integer the id of the namespace
	 */
	public static function getNamespaceName( $nsId ) {
		global $wgExtraNamespaces;

		if ( isset( $wgExtraNamespaces[$nsId] ) ) {
			return $wgExtraNamespaces[$nsId];
		}

		throw new Exception( "Namespace name « ${nsId} » was not found in TemplateImporter."
			." This should not happen, please contact developpers extension with tag: Error102" );
	}

}
