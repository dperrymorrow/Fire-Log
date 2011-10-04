<?php


class Fire_log{

	public $filter = FALSE;
	public $pages = array();
	public $CI;
	public $log_file = '';
	public $today ='';
	public $url_vals = array();

	public function __construct()
	{

		$this->CI = &get_instance();
		$this->CI->load->helper( array('file','url') );	
		$this->CI->load->library( 'pagination' );


		define( 'PARAM_DILEM', $this->CI->config->item( 'fire_log_param_dilem' ));

		$this->url_vals = get_spark_params();
		$params = $this->url_vals;
		//		log_message( 'info', 'Hey there, You are running Fire Log Spark Version ' .$this->CI->config->item( 'fire_log_version') );	

		//echo getcwd();
		
		//$this->CI->load->_ci_view_path = dirname(__DIR__).DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR;
		$this->today = 'log-'.date( 'Y-m-d') . '.php';




		if( isset( $params[ 'file' ] ) and !empty( $params[ 'file' ] ))
		{
			$this->log_file = $params[ 'file' ];
			//	$this->build_heading(  );
			$this->view();

			}else if( isset( $params[ 'delete' ] ))
			{

				$this->clear_file( $params[ 'delete' ] );
				log_message( 'info', $this->CI->lang->line( 'fire_log_file_deleted' ) . $params[ 'delete'] );

				$this->log_file = $this->today;
				redirect( current_url().'/file'.PARAM_DILEM .$this->today);
			}
			else
			{
				redirect( current_url().'/file'.PARAM_DILEM .$this->today);
			}
		}



		public function view()
		{

			if( $this->log_file == $this->today )
			{
				$data[ 'today' ] = TRUE;
			}
			else
			{
				$data[ 'today' ] = FALSE;
			}

			$data[ 'list' ] = $this->list_files();
			$data[ 'log_contents' ] = $this->get_file( $this->log_file );
			$data[ 'log_file_name' ] = $this->log_file;
			$data[ 'pagination_links' ] = $this->CI->pagination->create_links();
			
			$view_dir = dirname(__FILE__).DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR;
			
			$this->CI->load->add_package_path( $view_dir );
			$this->CI->load->view( 'fire_log_view', $data );
			$this->CI->load->remove_package_path( $view_dir );
		}



		public function list_files()
		{
			$list = get_dir_file_info( APPPATH . 'logs' );
			$filtered_list = array();

			foreach ($list as $file )
			{
				$file['attrs'] = '';
				$file['suffix'] = '';

				if( $file[ 'name' ] == $this->log_file )
				{
					$file['attrs'] = 'selected="selected"';
				}

				if( $file[ 'name' ] == $this->today )
				{
					$file[ 'suffix'] = ' - (' . $this->CI->lang->line( 'fire_log_today' ) . ')';
				}

				if( $file[ 'name' ] != 'index.html' )
				{
					array_push( $filtered_list, $file ); 
				}

			}
			//Order list by file name
			usort($filtered_list, create_function('$a, $b', 'return strcmp($b["name"], $a["name"]);'));

			return $filtered_list;
		}

		public function get_file( $log_file )
		{

			$path = APPPATH . 'logs/' .$log_file;

			if( file_exists( $path ))
			{

				$this->split_files( $path );

				$config = $this->CI->config->item( 'fire_log_pagination_settings' );
				$config['base_url'] = build_spark_url( $this->url_vals );

				$config['total_rows'] = count( $this->pages );
				$config['uri_segment' ] = $this->CI->uri->total_segments();
				// this is hardcoded so it doesnt get overode from config
				$config[ 'per_page' ] = 1;

				$this->CI->pagination->initialize($config);
				$cur_page = $this->CI->uri->segment( $config[ 'uri_segment'] );

				if( !is_array( $cur_page ) )
				{
					$cur_page = intval( $cur_page );
				}
				else
				{
					$cur_page = 0;
				}

				//trace( $cur_page, TRUE );
				if( isset( $this->pages[ $cur_page ] ))
				{
					$data = $this->pages[ $cur_page ];
				}
				else
				{
					$data = $this->CI->lang->line( 'fire_log_no_results_found' );
				}


				$data = ltrim( $data, "\n" );
				$data = ltrim( $data, "\n" );

				$data = str_replace( 'DEBUG', '<div class="debug">DEBUG', $data );
				$data = str_replace( 'ERROR', '<div class="error">ERROR', $data );
				$data = str_replace( 'INFO', '<div class="info">INFO', $data );
				$data = str_replace( "<div", "</div><div", $data );
				//				$data = ltrim( $data, '<div class="logContainer"></div>' );

				$data = substr($data, 6 );

			}
			else
			{
				$msg = $this->CI->lang->line( 'fire_log_not_found' );
				$msg = str_replace( '%log_file%', $this->log_file, $msg );
				$data = $msg;
			}


			return $data;

		}

		public function clear_file( $log_file )
		{
			$file = APPPATH . 'logs/' . $log_file;
			if( file_exists( $file ))
			{
				@unlink( $file );
			}
		}


		function split_files( $source, $lines=75 )
		{

			if( param_is_valid( 'filter', array( 'debug,info,error' )) != FALSE )
			{
				$this->filter = TRUE;
			}

			$i=0;
			$buffer='';

			$handle = fopen( $source, "r" );

			while (!feof ($handle))
			{

				$content = fgets( $handle, 496);

				if( $this->CI->config->item( 'fire_log_strip_tags') )
				{
					$content = strip_tags( $content );
				}


				$buffer .= $content;
				$i++;

				if ($i >= $lines)
				{
					array_push( $this->pages, $buffer );
					$buffer='';
					$i=0;
					$lines += 75;
				}
			}
			
			if ( !empty( $buffer )) {
				array_push( $this->pages, $buffer );
			}			

			fclose ($handle);



			if( $this->filter )
			{

				$temp_pages = array();
				$temp_page = '';

				foreach ($this->pages as $page )
				{

					$page = $this->filter( $page );

					if( strlen( $temp_page ) < 5000 )
					{
						$temp_page .= $page;
					}
					else
					{
						array_push( $temp_pages, $temp_page );
						$temp_page = '';
					}
				}

				if( !empty( $temp_page ))
				{
					array_push( $temp_pages, $temp_page );
				}

				$this->pages = $temp_pages;
			}
		}


		public function filter( $str )
		{

			$content = $str;


			switch ($this->url_vals[ 'filter' ])
			{
				case 'debug':	
				$content = preg_replace( "~INFO.*\n~", '', $content );
				$content = preg_replace( "~ERROR.*\n~", '', $content );
				break;
				case 'error':
				$content = preg_replace( "~INFO.*\n~", '', $content );
				$content = preg_replace( "~DEBUG.*\n~", '', $content );
				break;
				case 'info':
				$content = preg_replace( "~DEBUG.*\n~", '', $content );
				$content = preg_replace( "~ERROR.*\n~", '', $content );
				break;
			}
			return $content;
		}

	} // end Fire_log


