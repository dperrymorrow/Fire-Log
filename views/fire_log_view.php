<!DOCTYPE html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title>Fire Log Spark</title>
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.2r1/build/base/base-min.css"/>
	<style type="text/css" media="screen">
		<?php include( 'styles.css' ); ?>
	</style>


</head>
<body>

	<h1><?php echo $log_file_name ?><?php if( $today ) echo ' - ' .$this->lang->line( 'fire_log_today' ); ?></h1>
	<div class="container">
	<div id="nav">
		<?php 
		foreach ( $list as $file ){
			echo build_log_link( $file, $log_file_name );
		} 
		?>
		</div>
			<div class="container">
			<div id="filterBar">
			<?php
			echo build_filter_link( 'all', 'SHOW ALL' );
			echo build_filter_link( 'error', 'ERRORS' );
			echo build_filter_link( 'info', 'INFO' );
			echo build_filter_link( 'debug', 'DEBUG' );
			
			?>
			<a href="<?php echo build_spark_url( array( 'delete'=>$log_file_name ), TRUE )?>" onclick="return confirm('Are You Sure?');" class="deleteFile" >DELETE CURRENT FILE</a>
			</div>
			<?php echo str_replace( "&nbsp;", '', $pagination_links ); ?>
			
		<div class="logContainer"><?php echo $log_contents ?></div>
		<?php echo str_replace( "&nbsp;", '', $pagination_links ); ?>
		</div>
	</div>
</body>
</html>