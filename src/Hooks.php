<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 * @file
 */

namespace MediaWiki\Extension\WarnNotRecentlyUpdated;

use Article;
use DateTime;
use Html;
use MediaWiki\Extension\WarnNotRecentlyUpdated\Utilities\StringOption;

class Hooks {

	///
	/// Hook implementation
	///

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ArticleViewHeader
	 * @param Article $article
	 * @param bool|ParserOutput|null &$outputDone Whether the output for this page finished or not.
	 * @param bool &$pcache Whether to try the parser cache or not
	 * @return bool True or no return value to continue or false to abort
	 */
	public static function onArticleViewHeader( $article, &$outputDone, &$pcache ) {
		if ( self::hasWarning( $article ) ) {
			self::printWarning( $article );
		}

		return true;
	}

	///
	/// Warning handling
	///

	public static function printWarning( Article $article ): void {
		$out = $article->getContext()->getOutput();
		$message = self::getMessage( $article )->get();
		$warning = wfMessage( $message )->parse();

		$out->addHtml( Html::rawElement(
			"div",
			[
				"class" => "mw-message-box-warning mw-message-box",
			],
			$warning,
		) );
	}

	private static function getMessage( Article $article ): StringOption {
		static $message;

		if ( $mesage ) {
			return $message;
		}

		$config = $article->getContext()->getOutput()->getConfig();
		$pages = $config->get( 'WarnNotRecentlyUpdatedPages' );

		$title = $article->getTitle();
		$ns = $title->getNamespace();
		if ( !array_key_exists( $ns, $pages ) ) {
			$message = StringOption::fromNone();
			return $message;
		}

		$pageName = $title->getText();
		foreach ( $pages[$ns] as $prefix => $candidateMessage ) {
			if ( str_starts_with( $pageName, $prefix ) ) {
				$message = StringOption::from( $candidateMessage );
				return $message;
			}
		}

		$message = StringOption::fromNone();
		return $message;
	}

	///
	/// Determine if we need a warning
	///

	private static function hasWarning( Article $article ): bool {
		return $article->getTitle()->exists()
				&& self::isInScope( $article )
				&& self::isOutdated( $article );
	}

	private static function isInScope( Article $article ): bool {
		return self::getMessage( $article )->isSome();
	}

	private static function isOutdated( Article $article ): bool {
		$config = $article->getContext()->getOutput()->getConfig();
		$delay = $config->get( 'WarnNotRecentlyUpdatedDelay' );

		$timestamp = $article->getPage()->getTimestamp();
		$timestamp = DateTime::createFromFormat( 'YmdHis', $timestamp )->getTimestamp();
		$age = time() - $timestamp;

		return $age >= $delay;
	}

}
