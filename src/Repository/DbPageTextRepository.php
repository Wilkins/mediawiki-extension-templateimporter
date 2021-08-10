<?php

namespace TemplateImporter\Repository;

/**
 * @codeCoverageIgnore
 */
class DbPageTextRepository implements PageTextRepositoryInterface {

    private $dbr;

    public function __construct()
    {
        $this->dbr = wfGetDb( DB_MASTER );
    }

	/**
	 * Retrieve the last comment from the database for a given namespace:pagename
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Database_access
	 *
	 * @return the version number (ex: 3.0.1) or -1 if not found
	 */
	public function getComment( $pageTitle, $namespaceId ) {
		$res = $this->dbr->select(
			[ 'revision', 'page' ],
			[ 'rev_comment' ],
				"page_title = '{$pageTitle}' and page_namespace={$namespaceId}",
				__METHOD__,
				[],
				[
					'page' => [
						'INNER JOIN',
						   [ 'rev_id=page_latest' ]
					   ]
				]
		);

		if ( $res->result->num_rows >= 0 ) {
			foreach ( $res->result as $row ) {
				return $row['rev_comment'];
			}
		}
		return -1;
	}

	/**
	 * Retrieve the last comment from the database for a given namespace:pagename
	 *
	 * @param int $namespace the namespace id
	 * @param string $pagename the pagename
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Database_access
	 */
	public function getCurrentText( $pageTitle, $namespaceId ) {
		$res = $this->dbr->select(
			[ 'text', 'revision', 'page' ],
			[ 'old_text' ],
				"page_title = '{$pageTitle}' and page_namespace={$namespaceId}",
				__METHOD__,
				[],
				[
					'page' => [
						'INNER JOIN',
						   [ 'rev_id=page_latest' ]
					   ],
					'revision' => [
						'INNER JOIN',
						   [ 'old_id=rev_text_id' ]
					   ]
				]
		);

		if ( $res->result->num_rows >= 0 ) {
			foreach ( $res->result as $row ) {
				return $row['old_text'];
			}
		}
		return -1;
		// throw new Exception( "" );
	}

	/**
	 * Retrieve the last comment from the database for a given namespace:pagename
	 *
	 * @param int $namespace the namespace id
	 * @param string $pagename the pagename
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Database_access
	 */
	private function getCurrentRevision( $pageTitle, $namespaceId ) {

		// error_log( "page_title = '{$pageTitle}' and page_namespace={$namespaceId}" );
		$res = $this->dbr->select(
			[ 'page' ],
			[ 'page_latest' ],
				"page_title = '{$pageTitle}' and page_namespace={$namespaceId}",
				__METHOD__
		);

		if ( $res->result->num_rows >= 0 ) {
			foreach ( $res->result as $row ) {
				return $row['page_latest'];
			}
		}
		return -1;
		// throw new Exception( "" );
	}

	public function setComment( $pageTitle, $namespaceId, $comment ) {

		$rev_id = $this->getCurrentRevision( $pageTitle, $namespaceId );

		// error_log( "setComment : ".$this->pageName );
		// error_log( "mettre $comment sur revision $rev_id\n" );

		$this->dbr->update( 'revision',
			[ 'rev_comment' => $comment ],
			[ 'rev_id' => $rev_id + 1 ],
			__METHOD__
		);

		$success = ( $this->dbr->affectedRows() > 0 );
		/*
		$success = 1;
		 */

		if ( $success ) {
			error_log( "Commentaire changé avec succès" );
		} else {
			error_log( "Erreur" );
		}
	}

}
