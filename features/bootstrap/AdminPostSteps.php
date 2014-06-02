<?php

trait AdminPostSteps {

	/**
	 * @Given /^the admin post action "([^"]*)" is invoked (\d+) times$/
	 */
	public function invoke_admin_post_action_n_times( $name, $n ) {
		for ( $i = 0; $i < $n; $i++ ) { 
			$this->invoke_admin_post_action( $name );
		}
	}

	/**
	 * @Given /^the admin post action "([^"]*)" is invoked$/
	 */
	public function invoke_admin_post_action( $name ) {
		$curl_handle = curl_init();
		curl_setopt_array(
			$curl_handle, array(
				CURLOPT_URL => $this->parameters['webserver_url'] . "/wp-admin/admin-post.php?action=$name",
				CURLOPT_HEADER => 0,
				CURLOPT_RETURNTRANSFER => TRUE,
			)
		); 
		curl_exec( $curl_handle );
		$response_info = curl_getinfo( $curl_handle );
		curl_close( $curl_handle );
		assertEquals( 200, $response_info['http_code'] );
	}
}
