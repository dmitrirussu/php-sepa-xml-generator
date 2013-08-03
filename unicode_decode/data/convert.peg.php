<?php

require '../../sapphire/dev/Parser.php' ;

class QuoteParser extends Parser {
/*Parser:QuoteParser

ws: /[\s]* /x

char: /\\\\./
hex: /\\\\x[A-Fa-f0-9]+/

escaped: hex / char

not-curly: /[^}]/
not-double: /[^"]/
not-single: /[^\']/

qquotestr: ( escaped / not-curly )*
qquoted: "qq{" qquotestr "}"
	function qquotestr( &$self, $sub ) {
		$self['string'] = str_replace( '"', '\"', $sub['text'] ) ;
	}

dquotestr: ( escaped / not-double )*
dquoted: '"' dquotestr '"'
	function dquotestr( &$self, $sub ) {
		$self['string'] = $sub['text'] ;
	}

squotestr: ( escaped / not-single )*
squoted: "'" squotestr "'"
	function squotestr( &$self, $sub ) {
		$self['string'] = $sub['text'] ;
	}

member: qquoted / dquoted / squoted
array: "[" ws member ws ( "," ws member ws )*
	function __construct( &$self ) {
		$self['strings'] = array() ;
	}
	function member( &$self, $sub ) {
		$self['strings'][] = $sub['nodes'][0]['string'] ;
	}

not-square: /[^\[]+/

start_garbage: not-square "[" not-square

definition: start_garbage array
	function array( &$self, $sub ) {
		$self['array'] = $sub ;
	}

*/

}

foreach( glob('perl_source/*.pm') as $fname ) {
	preg_match( '!perl_source/(.*)\.pm!', $fname, $mtch ) ; $c = $mtch[1] ;
	print "$c\n" ;

	$string = file_get_contents( $fname ) ;
	$p = new QuoteParser( $string ) ;
	$r = $p->match_definition() ;

	$out = "<?php \n" . 'Unidecode::$tr[0'.$c.'] = array( "' . implode( '","', $r['array']['strings'] ) . '" );' ;
	file_put_contents( $c . '.php', $out ) ;
}

