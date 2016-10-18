<?php

namespace Context;

use Behat\Behat\Exception\PendingException;
use Behat\MinkExtension\Context\MinkContext;
use PHPUnit_Framework_Assert;

require_once 'ManualWordPressSteps.php';
require_once 'DatabaseSteps.php';
require_once 'InstallationSteps.php';
require_once 'AdminPostSteps.php';

/**
 * Feature context.
 */
class FeatureContext extends MinkContext {

	use ManualWordPressSteps;
	use DatabaseSteps;
	use InstallationSteps;
	use AdminPostSteps;

	/**
	 * Initializes context with parameters from behat.yml.
	 *
	 * @param array $parameters
	 */
	public function __construct( array $parameters ) {
		$this->parameters    = $parameters;
		$this->install_dir   = $this->path( dirname( dirname( dirname( __FILE__ ) ) ), 'install' );
		$this->webserver_dir = $this->parameters['webserver_dir'];
		$this->webserver_url = $this->parameters['webserver_url'];
		$this->database_file = $this->parameters['database_file'];
		$this->create_wp_config_replacements();
	}

	/**
	 * @BeforeScenario
	 */
	public function set_implicit_timeout( $event ) {
		if ( array_key_exists( 'selenium_implicit_timeout', $this->parameters ) && $this->parameters['selenium_implicit_timeout'] >= 0 ) {
			$this->getSession()->getDriver()->setTimeouts( array( 'implicit' => $this->parameters['selenium_implicit_timeout'] ) );
		}
	}

	private function path() {
		return implode( func_get_args(), DIRECTORY_SEPARATOR );
	}
}
