<?php

namespace TemplateImporter;

use Html;
use Status;
use Xml;
use TemplateImporter\Exception\Exception;

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
    public $templateDir;

	/**
	 * @param string $name the name of the SpecialPage
	 *
	 * @return void
	 */
	public function __construct( $name ) {
		parent::__construct( $name, '' );
		$this->mIncludable = false;
	}

	public function initImporter( $importer ) {
		$this->importer = $importer;
	}

    public function setLangTemplateDir( $templateDir ) {
        $this->templateDir = $templateDir;
    }
    public function getLangTemplateDir() {
        if ( !$this->templateDir ) {
    		throw new Exception( "You must declare a templateDir with setLangTemplateDir method " );
        }
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

		$this->showForm( $this );

		if ( $this->getRequest()->getText( 'action' ) != 'import' ) {
			return Status::newGood();
		}

		$output = $this->getOutput();

		try {
			$files = $this->importer->listFiles( $this->getLangTemplateDir() );
			foreach ( $files as $displayName => $page ) {
				$this->getOutput()->addWikiText( "Import de $displayName" );
				$page->checkVersion( $this->getVersion() );
				if ( $page->needsUpdate() && $page->hasChanged() ) {
					$page->import( $this->getNewComment() );
				}
			}
		} catch ( Exception $e ) {
			$this->getOutput()->addWikiText( '<span class="error">' . $e->getMessage() . '</span>' );
			return Status::newFatal( $e->getMessage() );
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
	public function showForm( $context ) {
		if ( $this->mIncluding ) {
			return false;
        }

        //$this->showFormHeader( $context );


		$output = $this->getOutput();
		$files = $this->importer->listFiles( $this->getLangTemplateDir() );
        foreach ( $files as $displayName => $page ) {
            $formatter = new HtmlFormatter( $page );
            $page->checkVersion( $this->getVersion() );


			$status = $context->msg( 'templateimporter-specialimportpages-status-'
				. $page->getVersionTag() )->text();
			$output->addHTML( '<tr class="status-' . $page->getVersionTag() . '"><td>' );
			$icone = $page->getWikiIcone();
            $this->addContentAutodetected( $icone );
			$output->addHTML( '</td><td>' );
			$output->addWikiText( $page->getWikiText() );
			$output->addHTML( '</td>' );

			$output->addHTML( '<td>' . $this->getVersion() . '</td>' );
			$output->addHTML( '<td>' . $page->getVersion() . '</td>' );
			$output->addHTML( '<td>' . $status . '</td></tr>' );
		}
		$output->addHTML( '</table>' );

        //$this->showFormFooter( $context );

    }

    /**
     * @codeCoverageIgnore
     */
    protected function showFormHeader( $context ) {
		$output = $this->getOutput();

		$output->addHTML( '<table id="templateimporter-import-form"><tr>' );
		$output->addHTML( '<th colspan="2">'
			. $context->msg( 'templateimporter-specialimportpages-column-pagename' )->text()
			. '</th>' );
		$output->addHTML( '<th>'
			. $context->msg( 'templateimporter-specialimportpages-column-packageversion' )->text()
			. '</th>' );
		$output->addHTML( '<th>'
			. $context->msg( 'templateimporter-specialimportpages-column-pageversion' )->text()
			. '</th>' );
		$output->addHTML( '<th>'
			. $context->msg( 'templateimporter-specialimportpages-column-status' )->text()
			. '</th>' );
		$output->addHTML( '</tr>' );
    }

    /**
     * @codeCoverageIgnore
     */
    protected function showFormFooter() {
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
