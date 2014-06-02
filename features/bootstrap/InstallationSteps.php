<?php

trait InstallationSteps {

	private function create_wp_config_replacements() {
		$this->wp_config_replacements = array();
		$this->wp_config_replacements['AUTH_KEY']         = '9Hw0Kk}&5c%YigU#p8c@:/6$MZo[f@u:F6M=v=}!v;fr^W32!/h&*Mo ~92E.C}C';
		$this->wp_config_replacements['SECURE_AUTH_KEY']  = '?<,9^IG-c)HG.JPc #v#E/IBs5J=LK/D0&0Q-BY0dW|55YZ dzuxAsDpS=CO,aN&';
		$this->wp_config_replacements['LOGGED_IN_KEY']    = '/0,Ue5fMnZ8%vE&AokeWl$p5P`y$^~v:%u!H!Gn1NH]|Ko/!zE=7F^z,[7{JW0xN';
		$this->wp_config_replacements['NONCE_KEY']        = '}vDmbs}$q5R64&q`UZg#fE_a*uJD3:/^m/q]GNY~|&)vMd#|$v.p<~#VTC.^Rkh3';
		$this->wp_config_replacements['AUTH_SALT']        = '-}H 7a0)V,kX|#a%:F;UQ+tZK0V9{@_1<B5[V/o6g]3a]EA%,s=)=~@`$U9I~Wgf';
		$this->wp_config_replacements['SECURE_AUTH_SALT'] = '6f=C`:P ?#fes])N`kct`Z+ :1Ty`lAt&AJuQT&.2ZB+o2%WUQ#P_]78lWL1m`8&';
		$this->wp_config_replacements['LOGGED_IN_SALT']   = 'z%vk: dd+>FKGFJ:6Z4c(<JnHZL6%i=tSO%=^+rHtPi<&WAr@2Cl67Jqo:7MKtOE';
		$this->wp_config_replacements['NONCE_SALT']       = '/2K@9/*3M&;.2[RJ8$V0L[MmId.<x}R< 7/0 K=mgy=:89],Z2<~LE4(Cs%?!sjd';
		$this->wp_config_replacements['WPLANG']           = '';
		$this->wp_config_replacements['WP_DEBUG']         = 'true';
	}

	/**
	 * @Given /^the blog language is "([^"]*)"$/
	 */
	public function set_blog_language( $language ) {
		$this->wp_config_replacements['WPLANG'] = $language;
	}

	/**
	 * @Given /^a fresh WordPress is installed$/
	 */
	public function install_fresh_wordress( $language_expr = null, $locale = '' ) {
		$this->create_temp_dir();
		$this->prepare_wp_in_webserver();
		$this->prepare_sqlite_integration_in_webserver();
		$this->prepare_sqlite_database();
		$this->install_plugin( 'disable-google-fonts' );
		$this->activate_plugin( 'disable-google-fonts' );   
		$this->create_wp_config_file();
	}

	/**
	 * @Given /^the plugin "([^"]*)" is installed \(from ([^\)]*)\)$/
	 */
	public function install_plugin_from_src( $plugin_id, $source ) {
		$source_path = $this->path( dirname( dirname( dirname( __FILE__ ) ) ), $source );
		$source_pathinfo = pathinfo( $source_path );
		$target_file_or_dir_name = $source_pathinfo['basename'];
		if ( strpos( $target_file_or_dir_name, $plugin_id ) === false ) {
			$target_file_or_dir_name = $plugin_id;
		}
		$this->copy_file_or_dir( $source_path, $this->path( $this->webserver_dir, 'wp-content', 'plugins', $target_file_or_dir_name ) );
	}

	/**
	 * @Given /^the plugin "([^"]*)" is installed$/
	 */
	public function install_plugin( $plugin_id ) {
		$install_name = str_replace( '-', '_', $plugin_id );
		$install_file = $this->install_file( $install_name );
		$this->extract_zip_to_dir( $install_file, $this->temp_dir );
		$this->move_file_or_dir( $this->path( $this->temp_dir, $plugin_id ), $this->path( $this->webserver_dir, 'wp-content', 'plugins', $plugin_id ) );
	}

	/**
	 * @Given /^the image "([^"]*)" is copied to the webserver$/
	 */
	public function copy_image_to_webserver( $image ) {
		$this->copy_file_or_dir( $this->path( dirname( dirname( __FILE__ ) ), 'resources', $image ), $this->path( $this->webserver_dir, $image ) );
	}


	private function create_temp_dir() {
		$tempfile = tempnam( sys_get_temp_dir(), '' );
		if ( ! file_exists( $tempfile ) ) {
			throw new Exception( 'Could not create temp file' );
		}
		$this->delete_file_or_dir( $tempfile );
		$this->mkdir( $tempfile );
		if ( ! is_dir( $tempfile ) ) {
			throw new Exception( 'Could not create temp dir' );
		}
		$this->temp_dir = $tempfile;
	}

	private function prepare_wp_in_webserver() {
		$this->extract_zip_to_dir( $this->install_file( 'wordpress' ), $this->temp_dir );
		if ( is_dir( $this->webserver_dir ) ) {
			$this->delete_file_or_dir( $this->webserver_dir );
		}
		$this->move_file_or_dir( $this->path( $this->temp_dir, 'wordpress' ), $this->webserver_dir );
	}

	private function prepare_sqlite_integration_in_webserver() {
		$this->install_plugin( 'sqlite-integration' );
		$this->copy_file_or_dir( $this->path( $this->webserver_dir, 'wp-content', 'plugins', 'sqlite-integration', 'db.php' ), $this->path( $this->webserver_dir, 'wp-content', 'db.php' ) );
	}

	private function prepare_sqlite_database() {
		$this->copy_file_or_dir( $this->path( $this->install_dir, $this->database_file ), $this->path( $this->temp_dir, $this->database_file ) );
	}

	private function install_file( $install_name ) {
		return $this->path( $this->install_dir, $this->parameters['install_files'][$install_name] );
	}

	private function create_wp_config_file() {
		$source_handle = fopen( $this->path( $this->webserver_dir, 'wp-config-sample.php' ), 'r' );
		$target_handle = fopen( $this->path( $this->webserver_dir, 'wp-config.php' ), 'w' );
		try {
			if ( ! $source_handle ) {
				throw new Exception( 'Can\'t read wp-config-sample.php' );
			} 
			if ( ! $source_handle ) {
				throw new Exception( 'Can\'t write wp-config.php' );
			} 
			$db_config_started = false;
			while ( ($line = fgets( $source_handle ) ) !== false ) {
				$db_config_started = $db_config_started || preg_match( '/^define\(\'DB_[^\']*\',[ ]*\'[^\']*\'\);/', $line );
				$line = $this->replace_config_value( $line );
				if ( $db_config_started && preg_match( '/^\/\*\*#@\+/', $line ) ) {
					$this->write_to_file( $target_handle, "define('DB_FILE', '".$this->database_file."');\r\n" );
					$this->write_to_file( $target_handle, "define('DB_DIR', '".$this->temp_dir."');\r\n" );
					$this->write_to_file( $target_handle, "\r\n" );
				}
				$this->write_to_file( $target_handle, $line );
				if ( preg_match( "/define\\('WP_DEBUG', \w*\\);/", $line ) ) {
					$this->write_to_file( $target_handle, "define('WP_DEBUG_LOG', true);\n" );
				}
			} 
		} finally {
			fclose( $source_handle );
			fclose( $target_handle );
		}
	}

	private function replace_config_value( $line ) {
		if ( ! preg_match( '/^define\(\'([^\']*)\',[ ]*\'?([^\']*)\'?\);/', $line, $matches ) ) {
			return $line;
		}
		$key   = $matches[1];
		$value = $matches[2];
		if ( ! array_key_exists( $key, $this->wp_config_replacements ) ) {
			return $line;
		}
		return preg_replace( '/'.$value.'/', $this->wp_config_replacements[$key], $line );
	}

	private function extract_zip_to_dir( $zip_file, $dir ) {
		$zip = new ZipArchive;
		$res = $zip->open( $zip_file );
		if ( $res === TRUE ) {
			$zip->extractTo( $dir );
			$zip->close();
		} else {
			throw new Exception( 'Unable to open zip file '.$zip_file );
		}		
	}

	private function move_file_or_dir( $source, $target ) {
		if ( ! rename( $source, $target ) ) {
			throw new Exception( 'Can\'t move '.$source.' to '.$target );
		}
	}

	private function copy_file_or_dir( $source, $target ) {
		if ( is_file( $source ) ) {
			if ( ! copy( $source, $target ) ) {
				throw new Exception( 'Can\'t copy file '.$source.' to '.$target );
			}
		} else {
			$this->mkdir( $target );
			foreach ( scandir( $source ) as $found ) {
				if ( $found == '.' || $found == '..' ) {
					continue;
				}
				$this->copy_file_or_dir( $this->path( $source, $found ), $this->path( $target, $found ) );
			}
		}
	}

	private function mkdir( $dir ) {
		if ( ! mkdir( $dir ) ) {
			throw new Exception( 'Can\'t create directory '.$dir );
		}
	}

	private function delete_file_or_dir( $file_or_dir ) {
		if ( is_file( $file_or_dir ) ) {
			if ( ! unlink( $file_or_dir ) ) {
				throw new Exception( 'Can\'t delete file '.$file_or_dir );
			}
		} else {
			foreach ( scandir( $file_or_dir ) as $found ) {
				if ( $found == '.' || $found == '..' ) {
					continue;
				}
				$this->delete_file_or_dir( $this->path( $file_or_dir, $found ) );
			}
			if ( ! rmdir( $file_or_dir ) ) {
				throw new Exception( 'Can\'t delete directory '.$file_or_dir );
			}
		}
	}

	private function write_to_file( $handle, $string ) {
		if ( ! fwrite( $handle, $string ) ) {
			throw new Exception( 'Can\'t write to file' );
		}
	}
}
