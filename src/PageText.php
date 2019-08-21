<?php

namespace TemplateImporter;

class PageText extends Page {
	public $textFile;
	public $textBase;

	public static function getRegexp() {
		return "#\.txt$#";
	}

	public function __construct( $pageName, $path = '/dev/null' ) {
		$pageName = preg_replace( "#.txt$#", "", $pageName );
		parent::__construct( $pageName, $path );
		$this->textFile = file_get_contents( $this->path );
		$this->textBase = $this->getCurrentText();
	}

	/**
	 * Check if the page namespace is a Category
	 *
	 * @return boolean
	 */
	public function hasChanged() {
		return trim( $this->textBase ) != trim( $this->textFile );
	}

	public function getWikiIcone() {
		// return $this->namespaceId;
		if ( $this->namespaceId == NS_CATEGORY ) {
			return 'CAT';
		} elseif ( $this->namespaceId == NS_FILE && $this->versionTag != static::PAGE_NEW ) {
			return '[['.$this->pageName.'|20px]]';
		}
		return 'TXT';
	}

	public function getWikiText() {
		if ( $this->namespaceId == NS_CATEGORY ) {
			return '[[:'.$this->pageName.']]';
		} elseif ( $this->namespaceId == NS_FILE && $this->versionTag != static::PAGE_NEW ) {
			return '[[:'.$this->pageName.']] (Metadata)';
		}
		return '[['.$this->pageName.']]';
	}


	/**
	 * Retrieve the last comment from the database for a given namespace:pagename
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Database_access
	 *
	 * @return the version number (ex: 3.0.1) or -1 if not found
	 */
	public function getComment() {
		$dbr = wfGetDb( DB_MASTER );
		$res = $dbr->select(
			[ 'revision', 'page' ],
			[ 'rev_comment' ],
				"page_title = '{$this->pageTitle}' and page_namespace={$this->namespaceId}",
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
	 * @param integer $namespace the namespace id
	 * @param string  $pagename  the pagename
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Database_access
	 */
	public function getCurrentText() {
		$dbr = wfGetDb( DB_MASTER );
		$res = $dbr->select(
			[ 'text', 'revision', 'page' ],
			[ 'old_text' ],
				"page_title = '{$this->pageTitle}' and page_namespace={$this->namespaceId}",
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
	 * @param integer $namespace the namespace id
	 * @param string  $pagename  the pagename
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Database_access
	 */
	public function getCurrentRevision() {
		$dbr = wfGetDb( DB_MASTER );

		error_log( "page_title = '{$this->pageTitle}' and page_namespace={$this->namespaceId}" );
		$res = $dbr->select(
			[ 'page' ],
			[ 'page_latest' ],
				"page_title = '{$this->pageTitle}' and page_namespace={$this->namespaceId}",
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

	public function setComment( $comment ) {
		$dbr = wfGetDb( DB_MASTER );

		$rev_id = $this->getCurrentRevision();

		error_log( "setComment : ".$this->pageName );
		error_log( "mettre $comment sur revision $rev_id\n" );

		$dbr->update( 'revision',
			[ 'rev_comment' => $comment ],
			[ 'rev_id' => $rev_id+1 ],
			__METHOD__
		);

		$success = ( $dbr->affectedRows() > 0 );
		/*
        $success = 1;
         */

		if ( $success ) {
			error_log( "Commentaire changé avec succès" );
		} else {
			error_log( "Erreur" );
		}

	}

	/**
	 * Import the file into the wiki database using the maintenance/importTextFiles.php script
	 *
	 * @param string $file the full file path
	 *
	 * @return void
	 */
	public function import( $comment ) {
		global $wgTemplateImporterMWPath;
		// TODO Use "where" command if windows platform ?
		$php = trim( shell_exec( "which php" ) );
		$maintenanceScript = "$wgTemplateImporterMWPath/maintenance/importTextFiles.php";
		$config = "$wgTemplateImporterMWPath/LocalSettings.php";
		$path = $this->path;

		$command = "$php $maintenanceScript --conf=$config "
			." -s '$comment' --overwrite --rc \"$path\"";
		# echo "$command<br>\n";
		$res = shell_exec( $command );
		# echo $res;
	}
}
