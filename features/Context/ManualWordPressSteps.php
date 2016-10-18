<?php

namespace Context;

use Exception;
use PHPUnit_Framework_Assert;

trait ManualWordPressSteps {

	/**
	 * @Given /^I am logged as an administrator$/
	 */
	public function login_as_administrator() {
		$this->login( 'admin', 'admin' );
	}

	/**
	 * @Given /^I logout$/
	 */
	public function logout() {
		$this->visit( 'wp-login.php?action=logout' );
		$this->get_page()->find( 'css', '#error-page a' )->click();
	}

	/**
	 * @Given /^I activate the plugin "([^"]*)"$/
	 */
	public function activate_plugin_manually( $plugin_name ) {
		$link = $this->get_plugin_area( $plugin_name )->find( 'xpath', "//a[contains(@href, 'action=activate')]" );
		PHPUnit_Framework_Assert::assertNotNull( $link, 'Link not found' );
		$link->click();
	}

	/**
	 * @Given /^I deactivate the plugin "([^"]*)"$/
	 */
	public function deactivate_plugin_manually( $plugin_name ) {
		$link = $this->get_plugin_area( $plugin_name )->find( 'xpath', "//a[contains(@href, 'action=deactivate')]" );
		PHPUnit_Framework_Assert::assertNotNull( $link, 'Link not found' );
		$link->click();
		PHPUnit_Framework_Assert::assertNotNull( $this->get_page()->find( 'css', '.updated' ), "Can't find element" );
	}

	/**
	 * @Given /^I uninstall the plugin "([^"]*)"$/
	 */
	public function uninstall_plugin_manually( $plugin_name ) {
		$link = $this->get_plugin_area( $plugin_name )->find( 'xpath', "//a[contains(@href, 'action=delete-selected')]" );
		PHPUnit_Framework_Assert::assertNotNull( $link, 'Link not found' );
		$link->click();
		$this->getSession()->getDriver()->getWebDriverSession()->accept_alert();
		PHPUnit_Framework_Assert::assertNotNull( $this->get_page()->find( 'css', '.updated' ), "Can't find element" );
	}

	private function get_plugin_area( $plugin_name ) {
		$plugin_area = $this->get_page()->find( 'xpath', "//tr[td/strong/text() = '$plugin_name']" );
		PHPUnit_Framework_Assert::assertNotNull( $plugin_area, 'Plugin area not found' );
		return $plugin_area;
	}

	/**
	 * @Given /^I activate the widget "([^"]*)"$/
	 */
	public function activate_widget_manually( $widget_id ) {
		$widget_div = $this->get_page()->find( 'xpath', "//div[contains(@id, '$widget_id')]" );
		PHPUnit_Framework_Assert::assertNotNull( $widget_div, 'Widget area not found' );
		$h3 = $widget_div->find( 'css', 'h3' );
		PHPUnit_Framework_Assert::assertNotNull( $h3, 'h3 not found' );
		$h3->click();
		$widget_div_id = $widget_div->getAttribute( 'id' );
		$this->getSession()->wait( 5000, "jQuery('#$widget_div_id .widgets-chooser').length == 1" );
		$this->get_page()->pressButton( 'Add Widget' );
		$sidebar = $this->get_page()->find( 'css', '#sidebar-1' );
		$widget_div    = $sidebar->find( 'xpath', "//div[contains(@id, '$widget_id')]" );
		$widget_div_id = $widget_div->getAttribute( 'id' );
	}

	/**
	 * @When /^I activate permalinks$/
	 */
	public function activate_permalinks() {
		$this->visit( '/wp-admin/options-permalink.php' );
		$this->check_radio_button( 'Post name' );
		$this->pressButton( 'Save Changes' );
	}

	/**
	 * @When /^I check the "([^"]*)" radio button$/
	 */
	public function check_radio_button( $label_text ) {
		foreach ( $this->get_page()->findAll( 'css', 'label' ) as $label ) {
			if ( $label_text === $label->getText() && $label->has( 'css', 'input[type="radio"]' ) ) {
				$this->getMainContext()->fillField( $label->find( 'css', 'input[type="radio"]' )->getAttribute( 'name' ), $label->find( 'css', 'input[type="radio"]' )->getAttribute( 'value' ) );
				return;
			}
		}
		throw new Exception( 'Radio button not found' );
	}

	/**
	 * @Given /^I should see the message "([^"]*)"$/
	 */
	public function assert_message( $msg ) {
		PHPUnit_Framework_Assert::assertNotNull( $this->get_page()->find( 'css', '.updated' ), "Can't find element" );
		PHPUnit_Framework_Assert::assertTrue( $this->get_page()->hasContent( $msg ), "Can't find message" );
	}

	/**
	 * @Given /^I should see the error message "([^"]*)"$/
	 */
	public function assert_error_message( $msg ) {
		PHPUnit_Framework_Assert::assertNotNull( $this->get_page()->find( 'css', '.error' ), "Can't find element" );
		PHPUnit_Framework_Assert::assertTrue( $this->get_page()->hasContent( $msg ), "Can't find message" );
	}

	/**
	* @Given /^I should see$/
	*/
	public function assert_page_contains_all( $table ) {
		$rows = $table->getRows();
		foreach ( $rows as $row ) {
			$this->assertPageContainsText( $row[0] );
		}
	}

	/**
	 * @Given /I should see an "([^"]*)" element having an attribute "([^"]*)" with value "([^"]*)"$/
	 */
	public function assert_element_having_attribute( $selector, $attribute_name, $attribute_value ) {
		$element = $this->get_page()->find( 'css', $selector );
		PHPUnit_Framework_Assert::assertEquals( $attribute_value, $element->getAttribute( $attribute_name ) );
	}

	/**
	 * @Given /I should see an "([^"]*)" element not having an attribute "([^"]*)" with value "([^"]*)"$/
	 */
	public function assert_element_not_having_attribute( $selector, $attribute_name, $attribute_value ) {
		$element = $this->get_page()->find( 'css', $selector );
		PHPUnit_Framework_Assert::assertNotEquals( $attribute_value, $element->getAttribute( $attribute_name ) );
	}

	/**
	 * @Given /I should see images with titles$/
	 */
	public function assert_image_titles( $table ) {
		$rows   = $table->getRows();
		$div    = $this->get_page()->find( 'css', '.ifttt-instagram-images' );
		PHPUnit_Framework_Assert::assertNotNull( $div, 'div .ifttt-instagram-images not found' );
		$images = $div->findAll( 'css' ,'img' );
		PHPUnit_Framework_Assert::assertEquals( count( $rows ), count( $images ) );
		for ( $i = 0;  $i < count( $rows );  $i++ ) {
			PHPUnit_Framework_Assert::assertEquals( $rows[$i][0], $images[$i]->getAttribute( 'title' ) );
		}
	}

	/**
	 * @Given /^I should see images with$/
	 */
	public function assert_images_with_preferences( $table ) {
		$rows_hash = $table->getRowsHash();
		$div       = $this->get_page()->find( 'css', '.ifttt-instagram-images' );
		PHPUnit_Framework_Assert::assertNotNull( $div, 'div .ifttt-instagram-images not found' );
		$images    = $div->findAll( 'css' ,'img' );
		if ( array_key_exists( 'number of images', $rows_hash ) ) {
			PHPUnit_Framework_Assert::assertEquals( intval( $rows_hash['number of images'] ), count( $images ) );
		}
		$js = "(function(){wrapper=jQuery('.ifttt-instagram-images');return JSON.stringify(jQuery(wrapper).find('img').map(function(){return{width:jQuery(this).innerWidth(),top:jQuery(this).position().top-jQuery(wrapper).position().top,left:jQuery(this).position().left-jQuery(wrapper).position().left};}).get())})();";
		$result     = $this->getSession()->evaluateScript( $js );
		$last_idx_in_row = -1;
		$last_top = -1;
		$last_row_width  = -1;
		foreach ( json_decode( $result, true ) as $image_info ) {
			$row_width = $image_info['left'] + $image_info['width'];
			if ( array_key_exists( 'row width <=', $rows_hash ) ) {
				PHPUnit_Framework_Assert::assertTrue( $row_width <= intval( $rows_hash['row width <='] ), "Row width $row_width not <= " . $rows_hash['row width >='] );
			}
			if ( $last_idx_in_row == -1 ) {
				// first loop
				$idx_in_row = 0;
				PHPUnit_Framework_Assert::assertEquals( 0, $image_info['top'] );
				PHPUnit_Framework_Assert::assertEquals( 0, $image_info['left'] );
			} elseif ( $last_top == $image_info['top'] ) {
				// same row as predecessor
				$idx_in_row = $last_idx_in_row + 1;
				if ( array_key_exists( 'maximum per row', $rows_hash ) ) {
					PHPUnit_Framework_Assert::assertTrue( $idx_in_row < intval( $rows_hash['maximum per row'] ), 'Too many images in one row' );
				}
			} else {
				// new row
				$idx_in_row = 0;
				if ( array_key_exists( 'maximum per row', $rows_hash ) ) {
					PHPUnit_Framework_Assert::assertTrue( $last_idx_in_row == intval( $rows_hash['maximum per row'] ) - 1, 'Too less images in one row' );
				}
				if ( array_key_exists( 'row width >=', $rows_hash ) ) {
					PHPUnit_Framework_Assert::assertTrue( $last_row_width >= intval( $rows_hash['row width >='] ), "Row width $last_row_width not >= " . $rows_hash['row width >='] );
				}
			}
			$last_idx_in_row = $idx_in_row;
			$last_top = $image_info['top'];
			$last_row_width  = $row_width;
			unset($idx_in_row);
			unset($row_width);
		}
	}

	/**
	 * @Given /^I should see image file "([^"]*)"$/
	 */
	public function assert_image_file( $expected_file ) {
		$div        = $this->get_page()->find( 'css', '.ifttt-instagram-images' );
		$image      = $div->find( 'css' ,'img' );
		PHPUnit_Framework_Assert::assertNotNull( $image, 'Image not found' );
		$image_src  = $image->getAttribute( 'src' );
		$image_file = substr( $image_src, strrpos( $image_src, '/' ) + 1 );
		PHPUnit_Framework_Assert::assertEquals( $expected_file, $image_file );
	}

	/**
	 * @Given /^I should see image files$/
	 */
	public function assert_image_files( $table ) {
		$rows   = $table->getRows();
		$div    = $this->get_page()->find( 'css', '.ifttt-instagram-images' );
		$images = $div->findAll( 'css' ,'img' );
		PHPUnit_Framework_Assert::assertEquals( count( $rows ) , count( $images ) );
		for ( $i = 0; $i < count( $rows ); $i++ ) {
			$image = $images[$i];
			$image_src  = $image->getAttribute( 'src' );
			$image_file = substr( $image_src, strrpos( $image_src, '/' ) + 1 );
			PHPUnit_Framework_Assert::assertEquals( $rows[$i][0], $image_file );			
		}
	}

	/**
	 * @Given /^I should not see image files$/
	 */
	public function assert_image_files_different_order( $table ) {
		$rows   = $table->getRows();
		$div    = $this->get_page()->find( 'css', '.ifttt-instagram-images' );
		$images = $div->findAll( 'css' ,'img' );
		PHPUnit_Framework_Assert::assertEquals( count( $rows ) , count( $images ) );
		for ( $i = 0; $i < count( $rows ); $i++ ) {
			$image = $images[$i];
			$image_src  = $image->getAttribute( 'src' );
			$image_file = substr( $image_src, strrpos( $image_src, '/' ) + 1 );
			if ( $rows[$i][0] != $image_file ) {
				return;
			}
		}
		PHPUnit_Framework_Assert::fail( 'All images are found in the exact order' );
	}

	/**
	 * @Given /^the css file "([^"]*)" should be loaded$/
	 */
	public function assert_css_file_loaded( $rel_file_path ) {
		$links = $this->get_page()->findAll( 'css' ,'link' );
		foreach ( $links as $link ) {
			if ( strpos( $href = $link->getAttribute( 'href' ), $rel_file_path ) !== false ) {
				return;
			}
		}
	}

	/**
	 * @Given /^the css file "([^"]*)" should not be loaded$/
	 */
	public function assert_css_file_not_loaded( $rel_file_path ) {
		$links = $this->get_page()->findAll( 'css' ,'link' );
		foreach ( $links as $link ) {
			if ( strpos( $href = $link->getAttribute( 'href' ), $rel_file_path ) !== false ) {
				PHPUnit_Framework_Assert::fail( "Css file $rel_file_path has been loaded" );
			}
		}
	}

	/**
	 * @Given /^I wait for ([\d\.]*) second[s]?$/
	 */
	public function wait( $seconds ) {
		sleep( intval( $seconds ) );
	}

	/**
	 * Makes sure the current user is logged out, and then logs in with
	 * the given username and password.
	 *
	 * @param string $username
	 * @param string $password
	 * @author Maarten Jacobs
	 **/
	private function login( $username, $password ) {
		$this->visit( 'wp-login.php' );
		$page = $this->get_page();
		for ( $i = 0; $i < 5; $i++ ) { 
			$page->fillField( 'user_login', $username );
			$page->fillField( 'user_pass', $password );
			if ( $this->getSession()->evaluateScript( "(function () { if (document.getElementById('user_pass').value == '') { return false; } else { document.getElementById('wp-submit').click(); return true; } })();" ) ) {
				break;
			}
		}
		PHPUnit_Framework_Assert::assertTrue( $page->hasContent( 'Dashboard' ) );
	}

	private function get_page() {
		return $this->getSession()->getPage();
	}
}