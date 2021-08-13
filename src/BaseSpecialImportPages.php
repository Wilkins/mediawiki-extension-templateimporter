<?php

namespace TemplateImporter;

use Html;
use Status;
use TemplateImporter\Formatter\HtmlPageFormatter;
use TemplateImporter\Exception\MissingConfigurationParameterException;
use Xml;

/**
 * Special page that allow importing templates
 *
 * @file    SpecialImportFiles.php
 * @ingroup TemplateImporter
 *
 * @license GPL-2.0-or-later
 * @author  Thibault Taillandier <thibault@taillandier.name>
 */
class BaseSpecialImportPages extends \SpecialPage {

	public $importer;
	public $version;
    public $groupName = 'templateimporter';
    public $name;

	/**
	 * @param string $name the name of the SpecialPage
	 *
	 * @return void
	 */
	public function __construct( $name ) {
		parent::__construct( $name, '' );
		$this->mIncludable = false;
	}

	public function setImporter( $importer ) {
		$this->importer = $importer;
	}

	public function getExtensionVersion() {
		return $this->version;
	}

	/**
	 * Get the lang of templates
	 *
	 * @return the lang code
     */
    /*
	public function getLang() {
		// FIXME Voir aussi les namespaces
		return 'fr';
		// return $this->getLanguage()->getCode();
    }
     */

	/**
	 * Redirects to special page
	 *
	 * @return void
	 * @codeCoverageIgnore
	 */
	public function redirect() {
		$url = $_SERVER['REQUEST_URI'];
		$url = preg_replace( '#&action=import#', '', $url );
		header( "Location: $url" );
    }

    public function getExtensionName() {
        if ( ! $this->name ) {
            throw new MissingConfigurationParameterException(
                "The SpecialImportPages attribute \$name must be defined"
                ." in the subclass extending BaseSpecialImportPages."
            );
        }
        return $this->name;
    }

	/**
	 * Execute the Special Page
	 *
	 * @param string $par the url part
	 *
	 * @return bool the status of the rendered page
	 */
	public function executeAction() {
		$html = "";
        foreach ( $this->importer->listFiles() as $displayName => $page ) {
            $html .= "* Import de ".$page->getWikiText()."\n";
			$page->checkVersion( $this->getExtensionVersion() );
            if ( $page->needsUpdate() && $page->hasChanged() ) {
                $comment = $this->importer->getNewComment(
                    $this->getExtensionName(), $this->getExtensionVersion()
                );
				$page->import( $comment );
			}
        }
        $html .= "\n";
		return $html;
	}

	/**
	 * Execute the Special Page
	 *
	 * @param string $par the url part
	 *
	 * @return bool the status of the rendered page
	 * @codeCoverageIgnore
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->showPageTable( $this );

		if ( $this->getRequest()->getText( 'action' ) != 'import' ) {
			return Status::newGood();
		}

		try {
			$generatedHtml = $this->executeAction();
			$this->getOutput()->addWikiText( $generatedHtml );
		} catch ( \Exception $e ) {
			$this->getOutput()->addWikiText( '<span class="error">' . $e->getMessage() . '</span>' );
			return Status::newFatal( $e->getMessage() );
		}
		$this->redirect();
		return Status::newGood();
	}

	/**
	 * Display the templates status page
	 *
	 * @return void
	 */
	public function generatePageTable( $context ) {
		$html = $this->generatePageTableHeader( $context );

		foreach ( $this->importer->listFiles() as $displayName => $page ) {
			$formatter = new HtmlPageFormatter( $page->getViewModel(), $this->getExtensionVersion() );
			$page->checkVersion( $this->getExtensionVersion() );
			$html .= $formatter->render( $context );
		}

		$html .= $this->generatePageTableFooter( $context );

		return $html;
	}

	/**
	 * Display the templates status page
	 *
	 * @return void
	 * @codeCoverageIgnore
	 */
	public function showPageTable( $context ) {
		if ( $this->mIncluding ) {
			return false;
		}

		$output = $this->getOutput();
		$output->addHTML( $this->generatePageTable( $context ) );
	}

	protected function generatePageTableHeader( $context ) {
		$html = '<table id="templateimporter-import-form"><tr>'
		. '<th colspan="2">'
			. $context->msg( 'templateimporter-specialimportpages-column-pagename' )->text()
		. '</th>'
		. '<th>'
			. $context->msg( 'templateimporter-specialimportpages-column-packageversion' )->text()
		. '</th>'
		. '<th>'
			. $context->msg( 'templateimporter-specialimportpages-column-pageversion' )->text()
		. '</th>'
		. '<th>'
			. $context->msg( 'templateimporter-specialimportpages-column-status' )->text()
		. '</th>'
		. '</tr>';
		return $html;
	}

	protected function generatePageTableFooter() {
		return "</table>";
	}

	/**
	 * @codeCoverageIgnore
	 */
	protected function showForm() {
		$output = $this->getOutput();
		$output->addHTML(
			Xml::openElement( 'form', [ 'action' => $this->getConfig()->get() ] ) .
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
	 * @codeCoverageIgnore
	 * @return bool
	 */
	public function isCacheable() {
		return false;
	}

	/**
	 * Get the description
	 *
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getDescription() {
		return $this->msg( 'templateimporter-specialimportpages-title' )->text();
	}

	/**
	 * Get the groupe name
	 *
	 * @codeCoverageIgnore
	 * @return string
	 */
	protected function getGroupName() {
		return $this->groupName;
	}
}
