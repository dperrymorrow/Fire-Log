<?php


function build_spark_url( $params, $prepend_base=TRUE ){
	
	$CI = &get_instance();
	$segs = $CI->uri->segment_array();
	
	
	$base = '';
	$torch_url = '';
	foreach ($segs as $seg) {
		if( strpos( $seg, PARAM_DILEM ) === FALSE ){
			$base .= $seg.'/';
		}else{
			break;
		}
	}
	
	if( substr_count( $base, '/' ) != 2 ){
		$base = $CI->router->fetch_class() . '/' . $CI->router->fetch_method() .'/';
	}
	
	foreach ($params as $key => $value) {
		if( !empty( $value )){
			$torch_url .= $key.PARAM_DILEM.$value.'/';
		}
	}
	
	
	if( $prepend_base ){
		$url = site_url( $base . $torch_url);
	}else{
		$url =	$base . $torch_url;
	}
	
	return $url;
	
}


function get_spark_params(){
	$CI = &get_instance();
	$segs = $CI->uri->segment_array();
	$params = array();

	foreach( $segs as $segment){
		
		if( strpos( $segment, PARAM_DILEM ) !== FALSE ){
			$arr = explode( PARAM_DILEM, $segment );
			$params[ $arr[ 0 ] ] = $arr[ 1 ];
		}
	}
	
	return $params;
}