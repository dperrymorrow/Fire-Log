<?php


class Fire_log{

	public $pages = array();
	public $CI;
	public $log_file = '';
	public $today ='';
	public $url_vals = array();
	
	public function __construct(){
		
		$this->CI = &get_instance();
		$this->CI->load->helper( array('file','url') );	
		$this->CI->load->library( 'pagination' );
		
		
		define( 'PARAM_DILEM', $this->CI->config->item( 'fire_log_param_dilem' ));
		
		$this->url_vals = get_spark_params();
		$params = $this->url_vals;
		
//		log_message( 'info', 'Hey there, You are running Fire Log Spark Version ' .$this->CI->config->item( 'fire_log_version') );	

		//echo getcwd();
		$this->CI->load->_ci_view_path = 'sparks/fire_log/'.$this->CI->config->item( 'fire_log_version') .'/views/';
		$this->today = 'log-'.date( 'Y-m-d') . '.php';
	
		
		if( !isset( $params[ 'file' ] )){
			$this->log_file = $this->today;
			$this->view();
		}
			
			if( isset( $params[ 'file' ] ) and !empty( $params[ 'file' ] )){
				$this->log_file = $params[ 'file' ];
			//	$this->build_heading(  );
				$this->view();
				
			}else if( isset( $params[ 'delete' ] )){
				
				$this->clear_file( $params[ 'delete' ] );
				log_message( 'info', $this->CI->lang->line( 'fire_log_file_deleted' ) . $_POST[ 'log_file'] );
				
				$this->log_file = $this->today;
				redirect( build_spark_url( array(), FALSE ) );
			}
			
			
		
	}
	


	public function view(){
		
		if( $this->log_file == $this->today ){
			$data[ 'today' ] = TRUE;
		}else{
			$data[ 'today' ] = FALSE;
		}
		
		$data[ 'list' ] = $this->list_files();
		$data[ 'log_contents' ] = $this->get_file( $this->log_file );
		$data[ 'log_file_name' ] = $this->log_file;
		$this->CI->load->view( 'fire_log_view', $data );
	}
	


	public function list_files(){
		$list = get_dir_file_info( APPPATH . 'logs' );
		$filtered_list = array();
		
		foreach ($list as $file ) {
			$file['attrs'] = '';
			$file['suffix'] = '';
			
			if( $file[ 'name' ] == $this->log_file ){
				$file['attrs'] = 'selected="selected"';
			}
			
			if( $file[ 'name' ] == $this->today ){
				$file[ 'suffix'] = ' - (' . $this->CI->lang->line( 'fire_log_today' ) . ')';
			}
			
			array_push( $filtered_list, $file ); 
			
		}
		
		return $filtered_list;
		
	}

	public function get_file( $log_file ){
		
		$path = APPPATH . 'logs/' .$log_file;
		
		if( file_exists( $path )){
			
			$this->split_files( $path );
			
				$config = $this->CI->config->item( 'fire_log_pagination_settings' );
				$config['base_url'] = build_spark_url( $this->url_vals );
				
				$config['total_rows'] = count( $this->pages );
				$config['uri_segment' ] = $this->CI->uri->total_segments();
				$config[ 'per_page' ] = 1;

				$this->CI->pagination->initialize($config);
				$cur_page = $this->CI->uri->segment( $config[ 'uri_segment'] );
				
				if( strpos( $cur_page, 'php' ) === FALSE ){
					$cur_page = intval( $cur_page );
				}else{
					$cur_page = 0;
				}
				
				//trace( $cur_page, TRUE );
				$data = $this->pages[ $cur_page ];
		
			
			if( $this->CI->config->item( 'fire_log_strip_tags') ){
				$data = strip_tags( $data );
			}
			
				$data = str_replace( 'ERROR', '</span><span class="error">ERROR', $data );
				$data = str_replace( 'DEBUG', '</span><span class="debug">DEBUG', $data );
				$data = str_replace( 'INFO', '</span><span class="info">INFO', $data );
			
			
			return $data;
		}else{
			$msg = $this->CI->lang->line( 'fire_log_not_found' );
			$msg = str_replace( '%log_file%', $this->log_file, $msg );
			return $msg;
		}
		
		
	}

	public function clear_file( $log_file ){
		$file = APPPATH . 'logs/' . $log_file;
		if( file_exists( $file )){
			@unlink( $file );
		}
	}
	
	
	function split_files( $source, $lines=100 ){

	    $i=0;
	    $buffer='';

	    $handle = fopen( $source, "r" );
	
	    while (!feof ($handle)) {
	        $buffer .= fgets( $handle, 496);
	        $i++;
	
	        if ($i >= $lines) {
				array_push( $this->pages, $buffer );
	            $buffer='';
	            $i=0;
	            $lines += 100;
	        }
	    }
	
	    fclose ($handle);
		array_shift( $this->pages );
	//	trace( $this->pages, true );
	}




}


