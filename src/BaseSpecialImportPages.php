<?php

namespace TemplateImporter;

use Xml;
use Html;
use Status;
use TemplateImporter\Page;

/**
 * Special page that allow importing templates
 *
 * @file    SpecialImportFiles.php
 * @ingroup SemanticTravel
 *
 * @licence GNU GPL v2+
 * @author  Thibault Taillandier <thibault@taillandier.name>
 */
class BaseSpecialImportPages extends \SpecialPage {

	public $importer;
	public $groupName = 'templateimporter';

	/**
	 * @constructor
	 *
	 * @param string $name the name of the SpecialPage
	 *
	 * @return void
	 */
	public function __construct( $name = 'ImportTravelPages' ) {
		parent::__construct( $name, '' );
		$this->mIncludable = false;
	}

	public function initImporter( $importer ) {
		$this->importer = $importer;
	}

	/**
	 * Get the lang of templates
	 *
	 * @return the lang code
	 */
	public function getLang() {
		global $wgLang;
		// FIXME Voir aussi les namespaces
		return 'fr';
		return $wgLang->getCode();
	}

	/**
	 * Redirects to special page
	 *
	 * @return void
	 */
	public function redirect() {
		$url = $_SERVER['REQUEST_URI'];
		$url = preg_replace( '#&action=import#', '', $url );
		header( "Location: $url" );
	}

	/**
	 * Execute the Special Page
	 *
	 * @param string $par the url part
	 *
	 * @return boolean the status of the rendered page
	 */
	public function execute( $par ) {
		global $wgOut, $wgRequest;
		$this->setHeaders();

		$this->showForm();

		if ( $wgRequest->getText( 'action' ) != 'import' ) {
			return Status::newGood();
		}

		$output = $this->getOutput();

		try {
			$files = $this->importer->listFiles();
			foreach ( $files as $displayName => $page ) {
				$wgOut->addWikiText( "Import de $displayName" );
				$page->checkVersion();
				if ( $page->needsUpdate() && $page->hasChanged() ) {
					$importer->importFile( $page->path );
				}
			}
		} catch ( Exception $e ) {
			$wgOut->addWikiText( '<span class="error">' .  $e->getMessage() . '</span>' );
			return Status::newFatal( $e->getMessage() );
		} catch ( \Exception $e ) {
			$wgOut->addWikiText( '<span class="error">' .  $e->getMessage() . '</span>' );
			return Status::newFatal( $e->getMessage() );
		}
		$this->redirect();
		return Status::newGood();
	}

	/**
	 * Display the search form for a travel tree
	 *
	 * @param array $params the array of search parameters
	 *
	 * @return void
	 */
	protected function showForm() {
		global $wgScript;

		if ( $this->mIncluding ) {
			return false;
		}
		$output = $this->getOutput();
		// $output->addModuleStyles( 'ext.smg.specialtraveltree' );

		$files = $this->importer->listFiles();
		$output->addHTML( '<table id="templateimporter-import-form"><tr>' );
		$output->addHTML( '<th>'
			. $this->msg( 'templateimporter-specialimportpages-column-pagename' )->text()
			. '</th>' );
		$output->addHTML( '<th>'
			. $this->msg( 'templateimporter-specialimportpages-column-packageversion' )->text()
			. '</th>' );
		$output->addHTML( '<th>'
			. $this->msg( 'templateimporter-specialimportpages-column-pageversion' )->text()
			. '</th>' );
		$output->addHTML( '<th>'
			. $this->msg( 'templateimporter-specialimportpages-column-status' )->text()
			. '</th>' );
		$output->addHTML( '</tr>' );

		foreach ( $files as $displayName => $page ) {

			$status = $this->msg( 'templateimporter-specialimportpages-status-'
				.$page->getVersionTag() )->text();
			$output->addHTML( '<tr class="status-' . $page->getVersionTag() . '"><td>' );
			$output->addWikiText( '[[' . ( $page->isCategory() ? ':' : '' ) . $displayName . ']]' );
			$output->addHTML( '</td>' );

			$output->addHTML( '<td>' . $this->importer->getVersion() . '</td>' );
			$output->addHTML( '<td>' . $page->getVersion() . '</td>' );
			$output->addHTML( '<td>' . $status . '</td></tr>' );
		}
		$output->addHTML( '</table>' );

		$output->addHTML(
			Xml::openElement( 'form', [ 'action' => $wgScript ] ) .
			Html::hidden( 'title', $this->getPageTitle()->getPrefixedText() ) .
			Html::hidden( 'action', 'import' ) .
			Xml::openElement( 'fieldset' ) .
			Xml::openElement( 'table', [ 'id' => 'smg-importpages-form' ] ) .
			Xml::closeElement( 'table' ) .
			Xml::submitButton( $this->msg( 'templateimporter-specialimportpages-button-submit' )->text() ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' )
		);
	}

	/**
	 * Wether the page is cachable
	 *
	 * @return boolean
	 */
	public function isCacheable() {
		return false;
	}

	/**
	 * Get the description
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->msg( 'templateimporter-specialimportpages-title' )->text();
	}

	/**
	 * Get the groupe name
	 *
	 * @return string
	 */
	protected function getGroupName() {
		return $this->groupName;
	}
}

