<!DOCTYPE html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title>Fire Log Spark</title>
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.2r1/build/base/base-min.css"/>
	<style type="text/css" media="screen">
		
		body,html{
			background-color:black;
			margin:0;
			padding:0;
		}
		
		h1{
			font-family:"Monaco",Arial,sans-serif;
			color:orange;
			padding:0px;
			margin:0;
			background-color:#222;
		}
		
		pre{
			height:auto;
			display:block;
			white-space:pre-wrap;
			font-size:14px;
			line-height:18px;
			overflow:auto;
			text-align:left;
			font-weight:normal;
			overflow:auto;
			padding: 80px 20px 20px 20px;
			color:green;
			font-family:"Monaco",Arial,sans-serif;
			background-color:black;
		}
		
		#heading{
			position:fixed;
			display:block;
			top:0;
			left:0;
			width:100%;
			padding:20px;
			border-bottom:1px dashed yellow;
			margin:0;
			background-color:#222;
		}
		form{
			position:absolute;
			right:0px;
			top:0px;
			display:inline-block;
			width:400px;
			height:auto;
			padding:10px;
			background-color:#333;

		}
		
		a{
			color:#999;
			font-family:"Monaco",Arial,sans-serif;
			font-size:12px;
			font-weight:bold;
		}
		
		.debug{
			color:green;
		}
		.error{
			color:orange;
		}
		.info{
			color:yellow;
		}
		
		hr{
			border:none;
			border-bottom:1px dashed #666;
		}
	</style>
	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>	

	<script type="text/javascript" charset="utf-8">
		$( document ).ready(function(){
			$( '#methodForm').css( 'opacity', 0.5 );
			
			$( '#methodForm').mouseover( function(){
				$( this ).css( 'opacity', 1 );
			});
			
			$( '#methodForm').mouseout( function(){
				$( this ).css( 'opacity', 0.5 );
			});
			
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
<div id="heading">
<h1><?php echo $log_file_name ?><?php if( $today ) echo ' - ' .$this->lang->line( 'fire_log_today' ); ?></h1>
<form id="methodForm" action="<?php echo current_url() ?>" method="POST">
<select name="log_file">
<?php foreach ($list as $file ): ?>
	<option value="<?php echo $file['name'] .$file['attrs'] ?>"><?php echo $file['name'] . $file['suffix'] ?></option>
<?php endforeach; ?>		
</select>
<input type="submit" name="view" value="<?php echo $this->lang->line( 'fire_log_view' ) ?>"/>
<input type="submit" name="delete_file" value="<?php echo $this->lang->line( 'fire_log_delete' ) ?>"/>
<hr/>

<select id="filter">
	<option value="all"><?php echo $this->lang->line( 'fire_log_show_all' ) ?></option>
	<option value="error"><?php echo $this->lang->line( 'fire_log_show_error' ) ?></option>
	<option value="info"><?php echo $this->lang->line( 'fire_log_show_info' ) ?></option>
	<option value="debug"><?php echo $this->lang->line( 'fire_log_show_debug' ) ?></option>
</select>
<hr/>
<a href="<?php echo current_url() ?>"><?php echo $this->lang->line( 'fire_log_home' )?></a>
</form>
</div>

<pre><?php echo $log_contents ?></pre>

</body>
</html>