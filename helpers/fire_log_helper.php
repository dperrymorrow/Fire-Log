<?php


function build_log_link( $file, $cur_file )
{

	$url = build_spark_url( array( 'file'=>$file['name'] ));
	$lbl = rtrim( $file[ 'name' ], '.php' );
	$lbl = ltrim( $lbl, 'log-' );
	$class = '';

	if( $file[ 'name' ] == $cur_file )
	{
		$class = 'active';
	}

	return "<a href=\"$url\" class=\"$class\">$lbl</a>\n";
}


function build_filter_link( $filter, $label )
{

	$params = get_spark_params();
	$class = '';

	if( isset( $params[ 'filter' ] ) and $filter == $params[ 'filter' ] )
	{
		$class = 'active';
	}
	else if( !isset( $params[ 'filter' ] ) and $filter == 'all' )
	{
		$class = 'active';
	}

	$params[ 'filter' ] = $filter;


	$url = build_spark_url( $params, TRUE );

	return "<a href=\"$url\" class=\"$class\">$label</a>";

}