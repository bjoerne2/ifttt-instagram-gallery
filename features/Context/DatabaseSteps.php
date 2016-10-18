<?php

namespace Context;

use PDO;
use PHPUnit_Framework_Assert;
use Exception;

trait DatabaseSteps {

	/**
	 * @Given /^the plugin "([^"]*)" is activated$/
	 */
	public function activate_plugin( $plugin_id ) {
		if ( file_exists( $this->path( $this->webserver_dir, 'wp-content', 'plugins', $plugin_file = "$plugin_id.php" ) ) ) {
		} elseif ( file_exists( $this->path( $this->webserver_dir, 'wp-content', 'plugins', $plugin_file = "$plugin_id/$plugin_id.php" ) ) ) {
		// } else {
		// 	throw new Exception( "Plugin file '$plugin_id' not found" );			
		}
		$pdo  = $this->create_pdo();
		$stmt = $pdo->prepare( 'SELECT * FROM wp_options WHERE option_name = :option_name' );
		$stmt->execute( array( ':option_name' => 'active_plugins' ) );
		$option_value = $stmt->fetch( PDO::FETCH_ASSOC )['option_value'];
		$unserialized = unserialize( $option_value );
		foreach ( $unserialized as $active_plugin ) {
			if ( $active_plugin == $plugin_file ) {
				return;
			}
		}
		$unserialized[] = $plugin_file;
		$option_value   = serialize( $unserialized );
		$stmt = $pdo->prepare( 'UPDATE wp_options SET option_value = :option_value WHERE option_name = :option_name' );
		$stmt->execute( array( ':option_name' => 'active_plugins', ':option_value' => $option_value ) );
	}

	/**
	 * @Given /^the theme "([^"]*)" is activated$/
	 */
	public function activate_theme( $theme_id ) {
		$this->set_option( 'stylesheet', $theme_id );
		$this->set_option( 'template', $theme_id );
	}

	/**
	 * @Given /^the option "([^"]*)" has the serialized content struct$/
	 */
	public function set_serialized_content_struct( $option_name, $table ) {
		$rows_hash  = $table->getRowsHash();
		$caption    = array_key_exists( 'Caption', $rows_hash ) ? $rows_hash['Caption'] : 'A caption';
		$url        = array_key_exists( 'Url', $rows_hash ) ? $rows_hash['Url'] : '__webserver_url__';
		$source_url = $this->parameters['webserver_url'] . '/' . $rows_hash['Image'];
		$additional_tags = '';
		if ( array_key_exists( 'tags', $rows_hash ) ) {
			foreach ( array_map( 'trim', explode( ',', $rows_hash['tags'] ) ) as $tag ) {
				$additional_tags .= ',"' . $tag . '"';
			}
		}
		$option_value = '{"title":"' . $caption . '","description":"{\"Url\":\"' . $url . '\",\"SourceUrl\":\"' . $source_url . '\"}","post_status":"draft","mt_keywords":["ifttt_bridge"' . $additional_tags .']}';
		$option_value = str_replace( '__webserver_url__', $this->webserver_url, $option_value );
		$this->set_serialized_option( $option_name, $option_value );
	}

	/**
	 * @Given /^the option "([^"]*)" has the serialized value (.*)$/
	 */
	public function set_serialized_option( $option_name, $option_value ) {
		$option_value_obj = json_decode( $option_value, true );
		$serialized = serialize( $option_value_obj );
		$this->set_option( $option_name, $serialized );
	}

	/**
	 * @Given /^the option "([^"]*)" has the value "([^"]*)"$/
	 */
	public function set_option( $option_name, $option_value ) {
		$pdo  = $this->create_pdo();
		$stmt = $pdo->prepare( 'SELECT * FROM wp_options WHERE option_name = :option_name' );
		$stmt->execute( array( ':option_name' => $option_name ) );
		$result = $this->fetch_all( $stmt );
		if ( 0 == count( $result ) ) {
			$stmt = $pdo->prepare( 'INSERT INTO wp_options (option_name, option_value) VALUES (:option_name, :option_value)' );
		} else {
			$stmt = $pdo->prepare( 'UPDATE wp_options SET option_value = :option_value WHERE option_name = :option_name' );
		}
		$stmt->execute( array( ':option_name' => $option_name, ':option_value' => $option_value ) );
	}

	/**
	 * @Given /^the hello world post has the content "([^"]*)"$/
	 */
	public function set_hello_world_content( $content ) {
		$pdo  = $this->create_pdo();
		$stmt = $pdo->prepare( 'SELECT ID FROM wp_posts WHERE post_type = :post_type ORDER BY ID' );
		$stmt->execute( array( ':post_type' => 'post' ) );
		$result = $this->fetch_all( $stmt );
		$id     = $result[0]['ID'];
		$stmt   = $pdo->prepare( 'UPDATE wp_posts SET post_content = :post_content WHERE ID = :ID' );
		$stmt->execute( array( ':post_content' => $content, ':ID' => $id ) );
	}

	/**
	 * @Given /the widget "([^"]*)" is activated( with the options)?$/
	 */
	public function activate_widget( $widget_id, $with_options_expr = null, $table = null ) {
		$pdo  = $this->create_pdo();
		$stmt = $pdo->prepare( 'SELECT * FROM wp_options WHERE option_name = :option_name' );
		$stmt->execute( array( ':option_name' => 'sidebars_widgets' ) );
		$result = $this->fetch_all( $stmt );
		PHPUnit_Framework_Assert::assertEquals( count( $result ), 1, "Option 'sidebars_widgets' doesn't exists" );
		$unserialized_option_value = unserialize( $result[0]['option_value'] );
		$unserialized_option_value['sidebar-1'][] = "$widget_id-2";
		$serialized_option_value   = serialize( $unserialized_option_value );
		$stmt = $pdo->prepare( 'UPDATE wp_options SET option_value = :option_value WHERE option_name = :option_name' );
		$stmt->execute( array( ':option_name' => 'sidebars_widgets', ':option_value' => $serialized_option_value ) );
		$widget_options = array( '2' => array(), '_multiwidget' => 1 );
		if ( $table ) {
			$widget_options['2'] = $table->getRowsHash();
		}
		$this->set_option( "widget_$widget_id", serialize( $widget_options ) );
	}

	/**
	 * @Given /the widget "([^"]*)" should have the options$/
	 */
	public function assert_widget_options( $widget, $table ) {
		$rows_hash = $table->getRowsHash();
		$pdo  = $this->create_pdo();
		$stmt = $pdo->prepare( 'SELECT * FROM wp_options WHERE option_name = :option_name' );
		$stmt->execute( array( ':option_name' => 'widget_ifttt-instagram-gallery' ) );
		$result = $this->fetch_all( $stmt );
		PHPUnit_Framework_Assert::assertEquals( count( $result ), 1, "Option 'widget_ifttt-instagram-gallery' doesn't exists" );
		$unserialized   = unserialize( $result[0]['option_value'] );
		$widget_options = $unserialized['2'];
		foreach ( $rows_hash as $expected_key => $expected_value ) {
			foreach ( $widget_options as $widget_options_key => $widget_options_value ) {
				if ( $expected_key == $widget_options_key && $expected_value == $widget_options_value ) {
					continue 2;
				}
			}
			if ( array_key_exists( $expected_key, $widget_options) ) {
				PHPUnit_Framework_Assert::fail( "Widget option $expected_key' exists but has the value '$widget_options[$expected_key]'" );
			} else {
				PHPUnit_Framework_Assert::fail( "Widget option $expected_key' doesn't exists" );
			}
		}
	}

	/**
	 * @Given /the option "([^"]*)" should be serialized and contain (.*)$/
	 */
	public function assert_serialized_option_contains_value( $option_name, $option_value ) {
		$expected_option_values = json_decode( $option_value, true );
		$pdo  = $this->create_pdo();
		$stmt = $pdo->prepare( 'SELECT * FROM wp_options WHERE option_name = :option_name' );
		$stmt->execute( array( ':option_name' => $option_name ) );
		$result = $this->fetch_all( $stmt );
		PHPUnit_Framework_Assert::assertEquals( count( $result ), 1, "Option '$option_name' doesn't exists" );
		$unserialized = unserialize( $result[0]['option_value'] );
		foreach ( $expected_option_values as $expected_option_k => $expected_option_v ) {
			foreach ( $unserialized as $option_k => $option_v ) {
				if ( $expected_option_k == $option_k && $expected_option_v == $option_v ) {
					continue 2;
				}
			}
			PHPUnit_Framework_Assert::fail( "Option $option_value not found in " . json_encode( $unserialized ) );
		}
	}

	/**
	 * @Given /the option "([^"]*)" should have the serialized value (.*)$/
	 */
	public function assert_serialized_option_value( $option_name, $option_value ) {
		$option_value_obj = json_decode( $option_value, true );
		$serialized = serialize( $option_value_obj );
		$this->assert_option_value( $option_name, $serialized );
	}

	/**
	 * @Given /the option "([^"]*)" should have the value "([^"]*)"$/
	 */
	public function assert_option_value( $option_name, $option_value ) {
		$pdo  = $this->create_pdo();
		$stmt = $pdo->prepare( 'SELECT * FROM wp_options WHERE option_name = :option_name' );
		$stmt->execute( array( ':option_name' => $option_name ) );
		$result = $this->fetch_all( $stmt );
		PHPUnit_Framework_Assert::assertEquals( count( $result ), 1, "Option '$option_name' doesn't exists" );
		PHPUnit_Framework_Assert::assertEquals( $option_value, $result[0]['option_value'], "Option '$option_name' should have value '$option_value' but has value '".$result[0]['option_value']."'" );
	}

	/**
	 * @Given /^the option "([^"]*)" should not exist$/
	 */
	public function assert_option_not_exists( $option_name ) {
		$pdo  = $this->create_pdo();
		$stmt = $pdo->prepare( 'SELECT * FROM wp_options WHERE option_name = :option_name' );
		$stmt->execute( array( ':option_name' => $option_name ) );
		$result = $this->fetch_all( $stmt );
		PHPUnit_Framework_Assert::assertEquals( count( $result ), 0, "The option '$option_name' was found but should not exist" );
	}

	/**
	 * @Given /^a post exists with$/
	 */
	public function assert_post_exists( $table ) {
		$rows_hash = $table->getRowsHash();
		$pdo       = $this->create_pdo();
		if ( array_key_exists( 'metadata', $rows_hash ) ) {
			$metadata = $rows_hash['metadata'];
			unset( $rows_hash['metadata'] );
		}
		$where = array();
		foreach ( $rows_hash as $key => $value ) {
			$where[] = "$key = :$key";
		}
		$stmt = $pdo->prepare( 'SELECT * FROM wp_posts WHERE ' . implode( ' AND ', $where ) );
		$stmt->execute( $rows_hash );
		$result = $this->fetch_all( $stmt );
		PHPUnit_Framework_Assert::assertEquals( count( $result ), 1, 'The post was not found' );
		if ( $metadata ) {
			$metadata = str_replace( '__webserver_url__', $this->webserver_url, $metadata );
			$post_id = $result[0]['ID'];
			$metadata_parts = array_map( 'trim', explode( '=>', $metadata ) );
			$meta_key       = $metadata_parts[0];
			$meta_value     = serialize( json_decode( $metadata_parts[1], true ) );
			$stmt           = $pdo->prepare( 'SELECT * FROM wp_postmeta WHERE post_id = :post_id AND meta_key = :meta_key' );
			$stmt->execute( array( 'post_id' => $post_id, 'meta_key' => $meta_key ) );
			$result = $this->fetch_all( $stmt );
			PHPUnit_Framework_Assert::assertEquals( count( $result ), 1, "Metadata with key '$meta_key' was not found" );
			PHPUnit_Framework_Assert::assertEquals( $meta_value, $result[0]['meta_value'] );
		}
	}

	/**
	 * @Given /the widget "([^"]*)" should be activated$/
	 */
	public function assert_widget_activated( $widget_id ) {
		$pdo  = $this->create_pdo();
		$stmt = $pdo->prepare( 'SELECT * FROM wp_options WHERE option_name = :option_name' );
		$stmt->execute( array( ':option_name' => 'sidebars_widgets' ) );
		$result = $this->fetch_all( $stmt );
		PHPUnit_Framework_Assert::assertEquals( count( $result ), 1, "Option 'sidebars_widgets' doesn't exists" );
		$unserialized_option_value = unserialize( $result[0]['option_value'] );
		$active_widgets = $unserialized_option_value['sidebar-1'];
		foreach ( $active_widgets as $active_widget ) {
			if ( strpos( $active_widget, $widget_id ) !== false ) {
				return;
			}
		}
		PHPUnit_Framework_Assert::fail( "Widget '$widget_id' is not contained in '" . implode(', ', $active_widgets ) . "'" );
	}

	/**
	 * @Given /^the file "([^"]*)" exists in the upload folder$/
	 */
	public function assert_image_exists_in_upload_folder( $image ) {
		PHPUnit_Framework_Assert::assertTrue( file_exists( $this->path( $this->webserver_dir, 'wp-content', 'uploads', $image ) ), "Image '$image' not found in upload folder" );
	}

	private function create_pdo() {
		$pdo = new PDO( 'sqlite:'.$this->path( $this->temp_dir, $this->database_file ) );
		$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		return $pdo;
	}

	private function fetch_all( $stmt ) {
		$result = array();
		while ( $row = $stmt->fetch( PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT ) )  {
			$result[] = $row;
		}
		return $result;
	}
}