<?php

namespace TemplateImporter;

use TemplateImporter\Exception\MissingFileException;
use TemplateImporter\Exception\MissingNamespaceException;
use TemplateImporter\Config\ConfigInterface;

/**
 * @license GPL-2.0-or-later
 *
 * @author Thibault Taillandier <thibault@taillandier.name>
 */
class NamespaceManager {

	public $defaultLang = 'en';
    private $mediawikiPath;
    protected $config;

    public function __construct( ConfigInterface $config ) {
        $this->config = $config;
		$this->mediawikiPath = $config->getMediaWikiPath();
		$this->loadNamespaceData( $this->defaultLang );
		if ( $config->getLang() != $this->defaultLang ) {
			$this->loadNamespaceData( $config->getLang() );
		}
	}

	public function checkFileExists( $filename ) {
		if ( !file_exists( $filename ) ) {
			throw new MissingFileException( "File $filename does not exist." );
		}
	}

	public function loadNamespacesMediawiki( $lang ) {
		global $wgNamespaceAliases, $wgExtraNamespaces, $wgCanonicalNamespaceNames;
		$filemessages = $this->mediawikiPath
			. "/languages/messages/Messages" . ucfirst( $lang ) . ".php";
		$this->checkFileExists( $filemessages );
		include $filemessages;
		foreach ( $namespaceNames as $nsId => $nsName ) {
			$wgCanonicalNamespaceNames[$nsId] = $nsName;
			$wgExtraNamespaces[$nsId] = $nsName;
			$wgNamespaceAliases[$nsName] = $nsId;
        }
	}

    public function loadMetaNamespaces() {
        global $wgNamespaceAliases, $wgExtraNamespaces, $wgCanonicalNamespaceNames;
        $name = $this->config->getMetaNamespace();
        if ( $name ) {
            $genericMetaNamespaceTalk = $wgExtraNamespaces[5];
            $metaNamespaces = [
                '4' => $name,
                '5' => str_replace( '$1', $name, $genericMetaNamespaceTalk ),
            ];
            foreach ( $metaNamespaces as $nsId => $nsName ) {
                $wgCanonicalNamespaceNames[$nsId] = $nsName;
                $wgExtraNamespaces[$nsId] = $nsName;
                $wgNamespaceAliases[$nsName] = $nsId;
            }
        }
	}

	public function loadCustomNamespaces( $filename ) {
		global $wgNamespaceAliases, $wgExtraNamespaces, $wgCanonicalNamespaceNames;
		$this->checkFileExists( $filename );
		include $filename;
		foreach ( $customExtensionNamespaces as $customNs ) {
			list( $nsId, $nsConstant, $nsName ) = $customNs;
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
		global $wgNamespaceAliases, $wgExtraNamespaces, $wgCanonicalNamespaceNames;
		if ( defined( 'PF_VERSION' ) ) {

			$filemessages = $this->mediawikiPath . "/extensions/PageForms/languages/PF_Namespaces.php";
			$this->checkFileExists( $filemessages );
			include $filemessages;

			foreach ( $namespaceNames[$lang] as $nsId => $nsName ) {
				$wgCanonicalNamespaceNames[$nsId] = $nsName;
				$wgExtraNamespaces[$nsId] = $nsName;
				$wgNamespaceAliases[$nsName] = $nsId;
			}
		}
	}

	public function loadNamespacesSMW( $lang ) {
		global $wgNamespaceAliases, $wgExtraNamespaces, $wgCanonicalNamespaceNames;
		if ( defined( 'SMW_VERSION' ) ) {
			// We clear the \SMW\NamespaceManager::$initLanguageCode
			// to avoid the SiteLanguageChangeException from
			// extensions/SemanticMediaWiki/src/NamespaceManager.php
			\SMW\NamespaceManager::clear();
			$lg = \SMW\Lang\Lang::getInstance();
			$lg = $lg->fetch( $lang );
			$vars = array_merge( $GLOBALS, [ 'wgLanguageCode' => $lang ] );
			$ns = \SMW\NamespaceManager::initCustomNamespace(
				$vars,
				$lg
			);
			$namespaceNames = $lg->getNamespaces();
			foreach ( $namespaceNames as $nsId => $nsName ) {
				if ( is_int( $nsId ) ) {
					$wgCanonicalNamespaceNames[$nsId] = $nsName;
					$wgExtraNamespaces[$nsId] = $nsName;
					$wgNamespaceAliases[$nsName] = $nsId;
				}
			}
		}
	}

	public function loadNamespaceData( $lang ) {
		$this->loadNamespacesMediawiki( $lang );
        $this->loadMetaNamespaces();
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
	 * @return int the id of the namespace
	 */
	public static function getNamespaceFromName( $searchName ) {
		global $wgNamespaceAliases;

		if ( isset( $wgNamespaceAliases[$searchName] ) ) {
			return $wgNamespaceAliases[$searchName];
		}

		throw new MissingNamespaceException(
			"Namespace name « ${searchName} » was not found in TemplateImporter."
			. " This should not happen, please contact developpers extension with tag: Error101"
		);
	}

	/**
	 * Get the namespace id from the namespace name
	 *
	 * @throws Exception if namespace is not found.
	 *
	 * @param string $searchName the namespace we are looking for
	 *
	 * @return int the id of the namespace
	 */
	public static function getNamespaceName( $nsId ) {
		global $wgExtraNamespaces;

		if ( isset( $wgExtraNamespaces[$nsId] ) ) {
			return $wgExtraNamespaces[$nsId];
		}

		throw new MissingNamespaceException(
			"Namespace name « ${nsId} » was not found in TemplateImporter."
			. " This should not happen, please contact developpers extension with tag: Error102"
		);
	}
}
