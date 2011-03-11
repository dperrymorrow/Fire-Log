<?php


function build_log_link( $file, $cur_file ){
	
	$url = build_spark_url( array( 'file'=>$file['name'] ));
	$lbl = rtrim( $file[ 'name' ], '.php' );
	$lbl = ltrim( $lbl, 'log-' );
	$class = '';
	
	if( $file[ 'name' ] == $cur_file ){
		$class = 'active';
	}
	
	return "<a href=\"$url\" class=\"$class\">$lbl</a>\n";
}