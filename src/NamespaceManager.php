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
    private $mediawikiPath;

    public function __construct( $mediawikiPath, $lang )
    {
        $this->mediawikiPath = $mediawikiPath;
        $this->loadNamespaceData( $this->defaultLang );
        if ( $lang != $this->defaultLang ) {
            $this->loadNamespaceData( $lang );
        }
    }

	public function loadNamespacesMediawiki( $lang ) {
		global $wgNamespaceAliases, $wgExtraNamespaces, $wgCanonicalNamespaceNames,
            $wgTemplateImporterMWPath;
        $filemessages = $this->mediawikiPath
            ."/languages/messages/Messages".ucfirst( $lang ).".php";
        global $namespaceNames;
        include $filemessages;
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
		include $filename;
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

		$filemessages = $this->mediawikiPath."/extensions/PageForms/languages/PF_Namespaces.php";
		include $filemessages;

		foreach ( $namespaceNames[$lang] as $nsId => $nsName ) {
			$wgCanonicalNamespaceNames[$nsId] = $nsName;
			$wgExtraNamespaces[$nsId] = $nsName;
			$wgNamespaceAliases[$nsName] = $nsId;
		}
	}

	public function loadNamespacesSMW( $lang ) {
		global $wgNamespaceAliases, $wgExtraNamespaces, $wgCanonicalNamespaceNames,
            $wgTemplateImporterMWPath, $wgLanguageCode;;
        if ( !defined( 'SMW_VERSION' ) ) {
			return;
        }
        // We clear the \SMW\NamespaceManager::$initLanguageCode
        // to avoid the SiteLanguageChangeException from 
        // extensions/SemanticMediaWiki/src/NamespaceManager.php
        \SMW\NamespaceManager::clear();
        $lg = \SMW\Lang\Lang::getInstance();
        $lg = $lg->fetch( $lang );
        $vars = array_merge( $GLOBALS, ['wgLanguageCode' => $lang] );
		$ns = \SMW\NamespaceManager::initCustomNamespace(
            $vars,
			$lg
		);
		$namespaceNames = $lg->getNamespaces();
		foreach ( $namespaceNames as $nsId => $nsName ) {
			if ( !is_integer( $nsId ) ) {
				continue;
			}
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
