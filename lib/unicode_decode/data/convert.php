<?php

require '../../sapphire/dev/Parser.php' ;

class QuoteParser extends Parser {
/* ws: /[\s]* /x */
function match_ws () {
	$result = array("name"=>"ws", "text"=>"");
	if ( ( $subres = $this->rx( '/[\s]* /x' ) ) !== FALSE ) {
		$result["text"] .= $subres;
		return $result;
	}
	else { return FALSE; }
}


/* char: /\\\\./ */
function match_char () {
	$result = array("name"=>"char", "text"=>"");
	if ( ( $subres = $this->rx( '/\\\\./' ) ) !== FALSE ) {
		$result["text"] .= $subres;
		return $result;
	}
	else { return FALSE; }
}


/* hex: /\\\\x[A-Fa-f0-9]+/ */
function match_hex () {
	$result = array("name"=>"hex", "text"=>"");
	if ( ( $subres = $this->rx( '/\\\\x[A-Fa-f0-9]+/' ) ) !== FALSE ) {
		$result["text"] .= $subres;
		return $result;
	}
	else { return FALSE; }
}


/* escaped: hex / char */
function match_escaped () {
	$result = $this->construct( "escaped" );
	$procflag = FALSE ;
	$_6 = NULL;
	do {
		$res_3 = $result;
		$pos_3 = $this->pos;
		$key = "hex:{$this->pos}";
		$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_hex() ) );
		if ( $subres !== FALSE ) {
			$procflag = $this->store( $result, $subres ) || $procflag;
			$_6 = TRUE; break;
		}
		$result = $res_3;
		$this->pos = $pos_3;
		$key = "char:{$this->pos}";
		$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_char() ) );
		if ( $subres !== FALSE ) {
			$procflag = $this->store( $result, $subres ) || $procflag;
			$_6 = TRUE; break;
		}
		$result = $res_3;
		$this->pos = $pos_3;
		$_6 = FALSE; break;
	}
	while(0);
	if( $_6 === TRUE ) {
		if ( $procflag ) { unset( $result["nodes"] ) ; }
		return $result ;
	}
	if( $_6 === FALSE) { return FALSE; }
}


/* not-curly: /[^}]/ */
function match_not_curly () {
	$result = array("name"=>"not_curly", "text"=>"");
	if ( ( $subres = $this->rx( '/[^}]/' ) ) !== FALSE ) {
		$result["text"] .= $subres;
		return $result;
	}
	else { return FALSE; }
}


/* not-double: /[^"]/ */
function match_not_double () {
	$result = array("name"=>"not_double", "text"=>"");
	if ( ( $subres = $this->rx( '/[^"]/' ) ) !== FALSE ) {
		$result["text"] .= $subres;
		return $result;
	}
	else { return FALSE; }
}


/* not-single: /[^\']/ */
function match_not_single () {
	$result = array("name"=>"not_single", "text"=>"");
	if ( ( $subres = $this->rx( '/[^\']/' ) ) !== FALSE ) {
		$result["text"] .= $subres;
		return $result;
	}
	else { return FALSE; }
}


/* qquotestr: ( escaped / not-curly )* */
function match_qquotestr () {
	$result = $this->construct( "qquotestr" );
	$procflag = FALSE ;
	while (true) {
		$res_17 = $result;
		$pos_17 = $this->pos;
		$_16 = NULL;
		do {
			$_14 = NULL;
			do {
				$res_11 = $result;
				$pos_11 = $this->pos;
				$key = "escaped:{$this->pos}";
				$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_escaped() ) );
				if ( $subres !== FALSE ) {
					$procflag = $this->store( $result, $subres ) || $procflag;
					$_14 = TRUE; break;
				}
				$result = $res_11;
				$this->pos = $pos_11;
				$key = "not_curly:{$this->pos}";
				$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_not_curly() ) );
				if ( $subres !== FALSE ) {
					$procflag = $this->store( $result, $subres ) || $procflag;
					$_14 = TRUE; break;
				}
				$result = $res_11;
				$this->pos = $pos_11;
				$_14 = FALSE; break;
			}
			while(0);
			if( $_14 === FALSE) { $_16 = FALSE; break; }
			$_16 = TRUE; break;
		}
		while(0);
		if( $_16 === FALSE) {
			$result = $res_17;
			$this->pos = $pos_17;
			unset( $res_17 );
			unset( $pos_17 );
			break;
		}
	}
	if ( $procflag ) { unset( $result["nodes"] ) ; }
	return $result ;
}


/* qquoted: "qq{" qquotestr "}" */
function match_qquoted () {
	$result = $this->construct( "qquoted" );
	$procflag = FALSE ;
	$_21 = NULL;
	do {
		if ( ( $subres = $this->literal( "qq{" ) ) !== FALSE ) { $result["text"] .= $subres; }
		else { $_21 = FALSE; break; }
		$key = "qquotestr:{$this->pos}";
		$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_qquotestr() ) );
		if ( $subres !== FALSE ) {
			$procflag = $this->store( $result, $subres ) || $procflag;
		}
		else { $_21 = FALSE; break; }
		if ( ( $subres = $this->literal( "}" ) ) !== FALSE ) { $result["text"] .= $subres; }
		else { $_21 = FALSE; break; }
		$_21 = TRUE; break;
	}
	while(0);
	if( $_21 === TRUE ) {
		if ( $procflag ) { unset( $result["nodes"] ) ; }
		return $result ;
	}
	if( $_21 === FALSE) { return FALSE; }
}

function qquoted_qquotestr (  &$self, $sub  ) { 
		$self['string'] = str_replace( '"', '\"', $sub['text'] ) ;
	}

/* dquotestr: ( escaped / not-double )* */
function match_dquotestr () {
	$result = $this->construct( "dquotestr" );
	$procflag = FALSE ;
	while (true) {
		$res_29 = $result;
		$pos_29 = $this->pos;
		$_28 = NULL;
		do {
			$_26 = NULL;
			do {
				$res_23 = $result;
				$pos_23 = $this->pos;
				$key = "escaped:{$this->pos}";
				$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_escaped() ) );
				if ( $subres !== FALSE ) {
					$procflag = $this->store( $result, $subres ) || $procflag;
					$_26 = TRUE; break;
				}
				$result = $res_23;
				$this->pos = $pos_23;
				$key = "not_double:{$this->pos}";
				$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_not_double() ) );
				if ( $subres !== FALSE ) {
					$procflag = $this->store( $result, $subres ) || $procflag;
					$_26 = TRUE; break;
				}
				$result = $res_23;
				$this->pos = $pos_23;
				$_26 = FALSE; break;
			}
			while(0);
			if( $_26 === FALSE) { $_28 = FALSE; break; }
			$_28 = TRUE; break;
		}
		while(0);
		if( $_28 === FALSE) {
			$result = $res_29;
			$this->pos = $pos_29;
			unset( $res_29 );
			unset( $pos_29 );
			break;
		}
	}
	if ( $procflag ) { unset( $result["nodes"] ) ; }
	return $result ;
}


/* dquoted: '"' dquotestr '"' */
function match_dquoted () {
	$result = $this->construct( "dquoted" );
	$procflag = FALSE ;
	$_33 = NULL;
	do {
		if ( ( $subres = $this->literal( '"' ) ) !== FALSE ) { $result["text"] .= $subres; }
		else { $_33 = FALSE; break; }
		$key = "dquotestr:{$this->pos}";
		$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_dquotestr() ) );
		if ( $subres !== FALSE ) {
			$procflag = $this->store( $result, $subres ) || $procflag;
		}
		else { $_33 = FALSE; break; }
		if ( ( $subres = $this->literal( '"' ) ) !== FALSE ) { $result["text"] .= $subres; }
		else { $_33 = FALSE; break; }
		$_33 = TRUE; break;
	}
	while(0);
	if( $_33 === TRUE ) {
		if ( $procflag ) { unset( $result["nodes"] ) ; }
		return $result ;
	}
	if( $_33 === FALSE) { return FALSE; }
}

function dquoted_dquotestr (  &$self, $sub  ) { 
		$self['string'] = $sub['text'] ;
	}

/* squotestr: ( escaped / not-single )* */
function match_squotestr () {
	$result = $this->construct( "squotestr" );
	$procflag = FALSE ;
	while (true) {
		$res_41 = $result;
		$pos_41 = $this->pos;
		$_40 = NULL;
		do {
			$_38 = NULL;
			do {
				$res_35 = $result;
				$pos_35 = $this->pos;
				$key = "escaped:{$this->pos}";
				$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_escaped() ) );
				if ( $subres !== FALSE ) {
					$procflag = $this->store( $result, $subres ) || $procflag;
					$_38 = TRUE; break;
				}
				$result = $res_35;
				$this->pos = $pos_35;
				$key = "not_single:{$this->pos}";
				$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_not_single() ) );
				if ( $subres !== FALSE ) {
					$procflag = $this->store( $result, $subres ) || $procflag;
					$_38 = TRUE; break;
				}
				$result = $res_35;
				$this->pos = $pos_35;
				$_38 = FALSE; break;
			}
			while(0);
			if( $_38 === FALSE) { $_40 = FALSE; break; }
			$_40 = TRUE; break;
		}
		while(0);
		if( $_40 === FALSE) {
			$result = $res_41;
			$this->pos = $pos_41;
			unset( $res_41 );
			unset( $pos_41 );
			break;
		}
	}
	if ( $procflag ) { unset( $result["nodes"] ) ; }
	return $result ;
}


/* squoted: "'" squotestr "'" */
function match_squoted () {
	$result = $this->construct( "squoted" );
	$procflag = FALSE ;
	$_45 = NULL;
	do {
		if ( ( $subres = $this->literal( "'" ) ) !== FALSE ) { $result["text"] .= $subres; }
		else { $_45 = FALSE; break; }
		$key = "squotestr:{$this->pos}";
		$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_squotestr() ) );
		if ( $subres !== FALSE ) {
			$procflag = $this->store( $result, $subres ) || $procflag;
		}
		else { $_45 = FALSE; break; }
		if ( ( $subres = $this->literal( "'" ) ) !== FALSE ) { $result["text"] .= $subres; }
		else { $_45 = FALSE; break; }
		$_45 = TRUE; break;
	}
	while(0);
	if( $_45 === TRUE ) {
		if ( $procflag ) { unset( $result["nodes"] ) ; }
		return $result ;
	}
	if( $_45 === FALSE) { return FALSE; }
}

function squoted_squotestr (  &$self, $sub  ) { 
		$self['string'] = $sub['text'] ;
	}

/* member: qquoted / dquoted / squoted */
function match_member () {
	$result = $this->construct( "member" );
	$procflag = FALSE ;
	$_54 = NULL;
	do {
		$res_47 = $result;
		$pos_47 = $this->pos;
		$key = "qquoted:{$this->pos}";
		$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_qquoted() ) );
		if ( $subres !== FALSE ) {
			$procflag = $this->store( $result, $subres ) || $procflag;
			$_54 = TRUE; break;
		}
		$result = $res_47;
		$this->pos = $pos_47;
		$_52 = NULL;
		do {
			$res_49 = $result;
			$pos_49 = $this->pos;
			$key = "dquoted:{$this->pos}";
			$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_dquoted() ) );
			if ( $subres !== FALSE ) {
				$procflag = $this->store( $result, $subres ) || $procflag;
				$_52 = TRUE; break;
			}
			$result = $res_49;
			$this->pos = $pos_49;
			$key = "squoted:{$this->pos}";
			$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_squoted() ) );
			if ( $subres !== FALSE ) {
				$procflag = $this->store( $result, $subres ) || $procflag;
				$_52 = TRUE; break;
			}
			$result = $res_49;
			$this->pos = $pos_49;
			$_52 = FALSE; break;
		}
		while(0);
		if( $_52 === TRUE ) { $_54 = TRUE; break; }
		$result = $res_47;
		$this->pos = $pos_47;
		$_54 = FALSE; break;
	}
	while(0);
	if( $_54 === TRUE ) {
		if ( $procflag ) { unset( $result["nodes"] ) ; }
		return $result ;
	}
	if( $_54 === FALSE) { return FALSE; }
}


/* array: "[" ws member ws ( "," ws member ws )* */
function match_array () {
	$result = $this->construct( "array" );
	$procflag = FALSE ;
	$_66 = NULL;
	do {
		if ( ( $subres = $this->literal( "[" ) ) !== FALSE ) { $result["text"] .= $subres; }
		else { $_66 = FALSE; break; }
		$key = "ws:{$this->pos}";
		$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_ws() ) );
		if ( $subres !== FALSE ) {
			$procflag = $this->store( $result, $subres ) || $procflag;
		}
		else { $_66 = FALSE; break; }
		$key = "member:{$this->pos}";
		$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_member() ) );
		if ( $subres !== FALSE ) {
			$procflag = $this->store( $result, $subres ) || $procflag;
		}
		else { $_66 = FALSE; break; }
		$key = "ws:{$this->pos}";
		$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_ws() ) );
		if ( $subres !== FALSE ) {
			$procflag = $this->store( $result, $subres ) || $procflag;
		}
		else { $_66 = FALSE; break; }
		while (true) {
			$res_65 = $result;
			$pos_65 = $this->pos;
			$_64 = NULL;
			do {
				if ( ( $subres = $this->literal( "," ) ) !== FALSE ) { $result["text"] .= $subres; }
				else { $_64 = FALSE; break; }
				$key = "ws:{$this->pos}";
				$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_ws() ) );
				if ( $subres !== FALSE ) {
					$procflag = $this->store( $result, $subres ) || $procflag;
				}
				else { $_64 = FALSE; break; }
				$key = "member:{$this->pos}";
				$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_member() ) );
				if ( $subres !== FALSE ) {
					$procflag = $this->store( $result, $subres ) || $procflag;
				}
				else { $_64 = FALSE; break; }
				$key = "ws:{$this->pos}";
				$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_ws() ) );
				if ( $subres !== FALSE ) {
					$procflag = $this->store( $result, $subres ) || $procflag;
				}
				else { $_64 = FALSE; break; }
				$_64 = TRUE; break;
			}
			while(0);
			if( $_64 === FALSE) {
				$result = $res_65;
				$this->pos = $pos_65;
				unset( $res_65 );
				unset( $pos_65 );
				break;
			}
		}
		$_66 = TRUE; break;
	}
	while(0);
	if( $_66 === TRUE ) {
		if ( $procflag ) { unset( $result["nodes"] ) ; }
		return $result ;
	}
	if( $_66 === FALSE) { return FALSE; }
}

function array___construct (  &$self  ) { 
		$self['strings'] = array() ;
	}

function array_member (  &$self, $sub  ) { 
		$self['strings'][] = $sub['nodes'][0]['string'] ;
	}

/* not-square: /[^\[]+/ */
function match_not_square () {
	$result = array("name"=>"not_square", "text"=>"");
	if ( ( $subres = $this->rx( '/[^\[]+/' ) ) !== FALSE ) {
		$result["text"] .= $subres;
		return $result;
	}
	else { return FALSE; }
}


/* start_garbage: not-square "[" not-square */
function match_start_garbage () {
	$result = $this->construct( "start_garbage" );
	$procflag = FALSE ;
	$_72 = NULL;
	do {
		$key = "not_square:{$this->pos}";
		$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_not_square() ) );
		if ( $subres !== FALSE ) {
			$procflag = $this->store( $result, $subres ) || $procflag;
		}
		else { $_72 = FALSE; break; }
		if ( ( $subres = $this->literal( "[" ) ) !== FALSE ) { $result["text"] .= $subres; }
		else { $_72 = FALSE; break; }
		$key = "not_square:{$this->pos}";
		$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_not_square() ) );
		if ( $subres !== FALSE ) {
			$procflag = $this->store( $result, $subres ) || $procflag;
		}
		else { $_72 = FALSE; break; }
		$_72 = TRUE; break;
	}
	while(0);
	if( $_72 === TRUE ) {
		if ( $procflag ) { unset( $result["nodes"] ) ; }
		return $result ;
	}
	if( $_72 === FALSE) { return FALSE; }
}


/* definition: start_garbage array */
function match_definition () {
	$result = $this->construct( "definition" );
	$procflag = FALSE ;
	$_76 = NULL;
	do {
		$key = "start_garbage:{$this->pos}";
		$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_start_garbage() ) );
		if ( $subres !== FALSE ) {
			$procflag = $this->store( $result, $subres ) || $procflag;
		}
		else { $_76 = FALSE; break; }
		$key = "array:{$this->pos}";
		$subres = ( $this->packhas( $key ) ? $this->packread( $key ) : $this->packwrite( $key, $this->match_array() ) );
		if ( $subres !== FALSE ) {
			$procflag = $this->store( $result, $subres ) || $procflag;
		}
		else { $_76 = FALSE; break; }
		$_76 = TRUE; break;
	}
	while(0);
	if( $_76 === TRUE ) {
		if ( $procflag ) { unset( $result["nodes"] ) ; }
		return $result ;
	}
	if( $_76 === FALSE) { return FALSE; }
}

function definition_array (  &$self, $sub  ) { 
		$self['array'] = $sub ;
	}



}

foreach( glob('perl_source/*.pm') as $fname ) {
	preg_match( '!perl_source/(.*)\.pm!', $fname, $mtch ) ; $c = $mtch[1] ;
	print "$c\n" ;

	$string = file_get_contents( $fname ) ;
	$p = new QuoteParser( $string ) ;
	$r = $p->match_definition() ;

	$out = "<?php \n" . 'Unidecode::$tr[0'.$c.'] = array( "' . implode( '","', $r['array']['strings'] ) . '" );' ;
	// file_put_contents( $c . '.php', $out ) ;
}

