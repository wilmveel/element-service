<?php
	session_start();	
	header('Content-type: application/json');	

	$file = $_GET['file']; 
	$json  =file_get_contents('php://input');
	
	// Add json to history
	if (!isset($_SESSION['history'])){
		$_SESSION['history'] = array();
		if(!isset($_SESSION['history'][$file])){
			$_SESSION['history'][$file] = array();
		}
	}
	
	if(!isset($_SESSION['pointer'])){
		$_SESSION['pointer'] = array();
		if(!isset($_SESSION['pointer'][$file])){
			$_SESSION['pointer'][$file] = 0;
		}
	}
	
	$i = $_SESSION['pointer'][$file] + 1;
	$_SESSION['history'][$file][$i] = $json;
	$_SESSION['pointer'][$file] = $i;
	
	
		
	file_put_contents("./data/" . $file, prettyPrint($json));

	echo "OK";

	function prettyPrint( $json )
{
    $result = '';
    $level = 0;
    $prev_char = '';
    $in_quotes = false;
    $ends_line_level = NULL;
    $json_length = strlen( $json );

    for( $i = 0; $i < $json_length; $i++ ) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if( $ends_line_level !== NULL ) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if( $char === '"' && $prev_char != '\\' ) {
            $in_quotes = !$in_quotes;
        } else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                case '{': case '[':
                    $level++;
                case ',':
                    $ends_line_level = $level;
                    break;

                case ':':
                    $post = " ";
                    break;

                case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
            }
        }
        if( $new_line_level !== NULL ) {
            $result .= "\n".str_repeat( "\t", $new_line_level );
        }
        $result .= $char.$post;
        $prev_char = $char;
    }

    return $result;
}
?>