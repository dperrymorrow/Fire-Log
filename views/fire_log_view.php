<!DOCTYPE html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title>Fire Log Spark</title>
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.2r1/build/base/base-min.css"/>
	<style type="text/css" media="screen">

	body,html{
		background-color:#2b2b2c;
		margin:0;
		padding:0;
		font-family:"Monaco",Verdana,Arial,sans-serif;
	}

	h1{
		text-shadow: #000 -1px -1px -1px;
		font-weight:bold;
		color:#7485a4;
		padding:0px;
		font-size:28px;
		font-family:Georgia,Times,serif;
		margin:20px;
		display:block;
		background-color:#222;
		padding: 20px;
			border:1px solid #444;
			border-style:inset;
	}

	pre{
		height:auto; 
		display:block; 
		white-space:pre-wrap;
		font-size:13px;
		line-height:18px;
		overflow:auto;
		text-align:left;
		color:#8f9d6a;
		font-weight:normal;
		overflow:auto;
		padding: 10px 20px 20px 0px;
		font-family:"Monaco",Verdana,Arial,sans-serif;
		background-color:#2b2b2c;
		display:block;
		clear:both;
	}


	#nav, .paginationWrapper{
		display:inline-block;
		height:auto;
		
		padding: 20px 0px 20px 0px;
		margin: 0px 20px 20px 20px;
		background-color:#222;
		color:#c26549;
		border:1px solid #444;
		border-style:inset;
	}
	
	#nav{
		float:left;
		width:110px;
	}
	
	.paginationWrapper{
		display:block;
		margin-left:0;
		padding:0;
		font-size:12px;
	}
	
	.container{
		display:block;
		overflow:hidden;
	}

	.paginationWrapper a, #nav a{
		display:block;
		padding: 2px;
		color:#666;
		font-size:12px;
		font-weight:normal;
		text-decoration:none;
		text-align:center;
		border-bottom:1px solid #262626;
	}

	.paginationWrapper a:hover, #nav a.active, #nav a:hover{
		text-decoration:none;
		color:#c26549;
		background-color:#2b2b2c;
	}
	
	.paginationWrapper a{
		display:inline-block;
	}

	.debug{
		color:#8f9d6a;
	}
	.error{
		color:#c26549;
	}
	.info{
		color:#d8ce84;
	}

	</style>

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>	

	<script type="text/javascript" charset="utf-8">
	$( document ).ready(function(){

		$( 'h1' ).css( 'opacity', 0.8 );

		$( '#filter' ).change( function(){
			if( $( this ).val() == 'all' ){
				$( '.error,.debug,.info' ).show();
			}else if( $( this ).val() == 'debug' ){
				$( '.error,.info' ).hide();
				$( '.debug' ).show();
			}	else if( $( this ).val() == 'info' ){
				$( '.error,.debug' ).hide();
				$( '.info' ).show();
			}	else if( $( this ).val() == 'error' ){
				$( '.info,.debug' ).hide(  );
				$( '.error' ).show();
			}


		});
	});
	</script>

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
			<?php echo $this->pagination->create_links(); ?>
		<pre><?php echo $log_contents ?></pre>
		<?php echo $this->pagination->create_links(); ?>
		</div>
	</div>
</body>
</html>