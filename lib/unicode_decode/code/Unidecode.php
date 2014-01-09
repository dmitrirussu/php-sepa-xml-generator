<?php

/**
 * Unidecode takes UTF-8 data and tries to represent it in US-ASCII characters (i.e., the universally displayable characters between 0x00 and 0x7F).
 * The representation is almost always an attempt at transliteration -- i.e., conveying, in Roman letters, the pronunciation expressed by the text in
 * some other writing system.
 *
 * The tables used (in data) are converted from the tables provided in the perl library Text::Unidecode (http://search.cpan.org/dist/Text-Unidecode/lib/Text/Unidecode.pm)
 * and are distributed under the perl license
 *
 * This file and Convert.peg.php are original works distributed under the SilverStripe license
 *
 * TODO:
 * Alter conversion to produce tables organised in UTF-8 byte arrangement, not UTF-16 byte arrangement, in order to eliminate the need for utf8_to_utf16
 * Possibily use a DB file rather than included php files.
 *
 * @author Hamish Friedlander
 */
class Unidecode {

	static $tr = array() ;
	static $utf8_rx = '/([\xC0-\xDF][\x80-\xBF])|([\xE0-\xEF][\x80-\xBF]{2})|([\xF0-\xF4][\x80-\xBF]{3})/' ;

	static $containing_dir ; // Dynamically set at the bottom of this file

	private static function utf8_to_utf16( $raw ) {
		while ( is_array( $raw ) ) $raw = $raw[0] ;

		switch( strlen( $raw ) ) {
			case 1:
				return ord( $raw ) ;

			// http://en.wikipedia.org/wiki/UTF-8
			case 2:
				$b1 = ord( substr( $raw, 0, 1 ) ) ;
				$b2 = ord( substr( $raw, 1, 1 ) ) ;

				$x = ( ( $b1 & 0x03 ) << 6 ) | ( $b2 & 0x3F ) ;
				$y = ( $b1 & 0x1C ) >> 2 ;

				return ( $y << 8 ) | $x ;

			case 3:
				$b1 = ord( substr( $raw, 0, 1 ) ) ;
				$b2 = ord( substr( $raw, 1, 1 ) ) ;
				$b3 = ord( substr( $raw, 2, 1 ) ) ;

				$x = ( ( $b2 & 0x03 ) << 6 ) | ( $b3 & 0x3F ) ;
				$y = ( ( $b1 & 0x0F ) << 4 ) | ( ( $b2 & 0x3C ) >> 2 ) ;

				return ( $y << 8 ) | $x ;

			default:
				$b1 = ord( substr( $raw, 0, 1 ) ) ;
				$b2 = ord( substr( $raw, 1, 1 ) ) ;
				$b3 = ord( substr( $raw, 2, 1 ) ) ;
				$b4 = ord( substr( $raw, 3, 1 ) ) ;

				$x = ( ( $b3 & 0x03 ) << 6 ) | ( $b4 & 0x3F ) ;
				$y = ( ( $b2 & 0x0F ) << 4 ) | ( ( $b3 & 0x3C ) >> 2 ) ;
				$z = ( ( $b1 & 0x07 ) << 5 ) | ( ( $b2 & 0x30 ) >> 4 ) ;

				return ( $z << 16 ) | ( $y << 8 ) | $x ;
		}
	}

	private static function unidecode_internal_replace( $match ) {
		$utf16 = self::utf8_to_utf16( $match ) ;

		if ( $utf16 > 0xFFFF ) {
			return '_' ;
		}
		else {
			$h = $utf16 >> 8 ;
			$l = $utf16 & 0xFF ;

			if ( !isset( self::$tr[$h] ) ) {
				$fname = sprintf( 'x%02x.php', $h ) ;
				include self::$containing_dir . "/data/$fname" ;
			}

			return Unidecode::$tr[$h][$l] ;
		}
	}

	private static function unidecode_mb_replace( $match ) {
		$utf16 = mb_convert_encoding( $match[0], 'UTF-16', 'UTF-8') ;
		if ( strlen( $utf16 ) > 2 ) {
			return '_' ;
		}
		else {
			$h = ord( substr( $utf16, 0, 1 ) ) ;
			$l = ord( substr( $utf16, 1, 1 ) ) ;

			if ( !isset( self::$tr[$h] ) ) {
				$fname = sprintf( 'x%02x.php', $h ) ;
				include self::$containing_dir . "/data/$fname" ;
			}

			return Unidecode::$tr[$h][$l] ;
		}
	}

	static function decode( $str ) {
		$callback = array( 'Unidecode', function_exists( 'mb_convert_encoding' ) ? 'unidecode_mb_replace' : 'unidecode_internal_replace' ) ;
		return preg_replace_callback( self::$utf8_rx, $callback, $str ) ;
	}
}
