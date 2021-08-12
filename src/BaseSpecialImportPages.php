<?php

namespace TemplateImporter;

use Html;
use Status;
use Xml;
use TemplateImporter\Exception\Exception;
use TemplateImporter\Formatter\HtmlPageFormatter;

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

	public function getVersion() {
		return $this->version;
	}

	/**
	 * Get the lang of templates
	 *
	 * @return the lang code
	 */
	public function getLang() {
		// FIXME Voir aussi les namespaces
		return 'fr';
		// return $this->getLanguage()->getCode();
	}

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

	public function getNewComment() {
		return "Update from $this->name (v" . $this->getVersion() . ")";
    }

    public function isWikiContent( $content ) {
        if ( preg_match( '#\[\[#', $content ) ) {
            return true;
        }
        return false;
    }

    /**
     * Decide wether we display HTML or WikiText
     */
    public function addContentAutodetected( $content ) {
        if ( $this->isWikiContent( $content ) ) {
            $this->getOutput()->addWikiText( $content );
        } else {
            $this->getOutput()->addHtml( $content );
        }
    }

	/**
	 * Execute the Special Page
	 *
	 * @param string $par the url part
	 *
	 * @return bool the status of the rendered page
	 */
	public function execute( $par ) {
		$this->setHeaders();

		$this->showPageTable( $this );

		if ( $this->getRequest()->getText( 'action' ) != 'import' ) {
			return Status::newGood();
		}

		$output = $this->getOutput();

		try {
			$files = $this->importer->listFiles();
			foreach ( $files as $displayName => $page ) {
				$this->getOutput()->addWikiText( "Import de $displayName" );
				$page->checkVersion( $this->getVersion() );
				if ( $page->needsUpdate() && $page->hasChanged() ) {
					$page->import( $this->getNewComment() );
				}
			}
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

		$files = $this->importer->listFiles();
        foreach ( $files as $displayName => $page ) {
            $formatter = new HtmlPageFormatter( $page->getViewModel(), $this->getVersion() );
            $page->checkVersion( $this->getVersion() );
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
