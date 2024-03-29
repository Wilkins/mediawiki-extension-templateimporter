<?php

namespace TemplateImporter\Formatter;

use TemplateImporter\Page\PageViewModel;

// use MediaWiki\MediaWikiServices;

class HtmlPageFormatter implements PageFormatterInterface {

	private $page;
	private $parser;
	private $extensionVersion;

	public function __construct( PageViewModel $page, $extensionVersion ) {
		$this->page = $page;
		$this->extensionVersion = $extensionVersion;
		// $this->parser = MediaWikiServices::getInstance()->getParserFactory()->create();
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
	/*
	public function addContentAutodetected( $content ) {
		if ( $this->isWikiContent( $content ) ) {
			$this->getOutput()->addWikiText( $content );
		} else {
			$this->getOutput()->addHtml( $content );
		}
	}
	 */

	public function render( $context ) {
		$status = $context->msg(
			'templateimporter-specialimportpages-status-' . $this->page->status
		)->text();
			$html = '<tr class="status-' . $this->page->status . '"><td>'
				// $this->addContentAutodetected( $icone );
			. $this->page->icon
			. '</td><td>'
			. $this->page->name
			. '</td>'

			. '<td>' . $this->extensionVersion . '</td>'
			. '<td>' . $this->page->version . '</td>'
			. '<td>' . $status . '</td></tr>';

			return $html;
	}
}
