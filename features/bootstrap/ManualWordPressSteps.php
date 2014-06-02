<?php

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
	public function activate_plugin_manually( $plugin_id ) {
		$page = $this->get_page();
		$plugin_area = $page->find( 'css', "#$plugin_id" );
		$plugin_area->find( 'xpath', "//a[contains(@href, 'action=activate')]" )->click();
	}

	/**
	 * @Given /^I deactivate the plugin "([^"]*)"$/
	 */
	public function deactivate_plugin_manually( $plugin_id ) {
		$page = $this->get_page();
		$plugin_area = $page->find( 'css', "#$plugin_id" );
		$plugin_area->find( 'xpath', "//a[contains(@href, 'action=deactivate')]" )->click();
	}

	/**
	 * @Given /^I uninstall the plugin "([^"]*)"$/
	 */
	public function uninstall_plugin_manually( $plugin_id ) {
		$plugin_area = $this->get_page()->find( 'css', "#$plugin_id" );
		$plugin_area->find( 'xpath', "//a[contains(@href, 'action=delete-selected')]" )->click();
		$form = $this->get_page()->find( 'xpath', "//form[contains(@action, 'action=delete-selected')]" );
		$form->find( 'css', '#submit' )->press();
	}

	/**
	 * @Given /^I should see the message "([^"]*)"$/
	 */
	public function assert_message( $msg ) {
		assertNotNull( $this->get_page()->find( 'css', '.updated' ), "Can't find element" );
		assertTrue( $this->get_page()->hasContent( $msg ), "Can't find message" );
	}

	/**
	 * @Given /^I should see the error message "([^"]*)"$/
	 */
	public function assert_error_message( $msg ) {
		assertNotNull( $this->get_page()->find( 'css', '.error' ), "Can't find element" );
		assertTrue( $this->get_page()->hasContent( $msg ), "Can't find message" );
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
		assertEquals( $attribute_value, $element->getAttribute( $attribute_name ) );
	}

	/**
	 * @Given /I should see an "([^"]*)" element not having an attribute "([^"]*)" with value "([^"]*)"$/
	 */
	public function assert_element_not_having_attribute( $selector, $attribute_name, $attribute_value ) {
		$element = $this->get_page()->find( 'css', $selector );
		assertNotEquals( $attribute_value, $element->getAttribute( $attribute_name ) );
	}

	/**
	 * @Given /I should see images in section "([^"]*)" with titles$/
	 */
	public function assert_image_titles_in_section( $heading, $table ) {
		$rows   = $table->getRows();
		$div    = $this->get_page()->find( 'xpath', '//h1[text()="' . $heading . '"]/..' );
		$images = $div->findAll( 'css' ,'img' );
		assertEquals( count( $rows ), count( $images ) );
		for ( $i = 0;  $i < count( $rows );  $i++ ) {
			assertEquals( $rows[$i][0], $images[$i]->getAttribute( 'title' ) );
		}
	}

	/**
	 * @Given /^I should see images in section "([^"]*)" with$/
	 */
	public function assert_image_width_in_section_and_rows( $heading, $table ) {
		$rows_hash = $table->getRowsHash();
		$div       = $this->get_page()->find( 'xpath', '//h1[text()="' . $heading . '"]/..' );
		$images    = $div->findAll( 'css' ,'img' );
		if ( array_key_exists( 'number of images', $rows_hash ) ) {
			assertEquals( intval( $rows_hash['number of images'] ), count( $images ) );
		}
		$heading_encoded = str_replace( "'", "\\'", $heading );
		$js = "(function(){div=$('h1:contains(\"$heading_encoded\")').parent();return JSON.stringify($(div).find('img').map(function(){return{width:$(this).innerWidth(),top:$(this).position().top-$(div).position().top,left:$(this).position().left-$(div).position().left};}).get())})();";
		$result     = $this->getSession()->evaluateScript( $js );
		$idx_in_row = -1;
		$last_top   = -1;
		foreach ( json_decode( $result, true ) as $image_info ) {
			if ( $last_top == $image_info['top'] ) {
				$idx_in_row++;
				if ( array_key_exists( 'maximum per row', $rows_hash ) ) {
					assertTrue( $idx_in_row < intval( $rows_hash['maximum per row'] ), 'Too many images in one row' );
					if ( $idx_in_row == intval( $rows_hash['maximum per row'] ) - 1 ) {
						$row_width = $image_info['left'] + $image_info['width'];
						if ( array_key_exists( 'row width >=', $rows_hash ) ) {
							assertTrue( $row_width >= intval( $rows_hash['row width >='] ), "Row width $row_width not >= " . $rows_hash['row width >='] );
						}
						if ( array_key_exists( 'row width <=', $rows_hash ) ) {
							assertTrue( $row_width <= intval( $rows_hash['row width <='] ), "Row width $row_width not <= " . $rows_hash['row width >='] );
						}
					}
				}
			} else {
				if ( array_key_exists( 'maximum per row', $rows_hash ) ) {
					assertTrue( $idx_in_row == -1 || $idx_in_row == intval( $rows_hash['maximum per row'] ) - 1, 'Too less images in one row' );
				}
				$idx_in_row = 0;
				$last_top   = $image_info['top'];
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
		$this->visit( 'wp-admin' );
		$page = $this->get_page();
		$page->fillField( 'user_login', $username );
		$page->fillField( 'user_pass', $password );
		$page->findButton( 'wp-submit' )->click();
		assertTrue( $page->hasContent( 'Dashboard' ) );
	}

	private function get_page() {
		return $this->getSession()->getPage();
	}
}