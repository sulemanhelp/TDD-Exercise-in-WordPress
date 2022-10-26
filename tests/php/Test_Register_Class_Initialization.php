<?php
/**
 * Tests for TDD Class Initialization
 *
 * @package PrimaryCategory
 */

namespace Suleman\TDD;

use WP_Mock;

/**
 * Test the WordPress plugin abstraction.
 */
class Test_Register_Class_Initialization extends TestCase {

	/**
	 * The instance to test.
	 *
	 * @var Register
	 */
	public $instance;

	/**
	 * Sets up test and register classes.
	 *
	 * @covers \Suleman\TDD\Register
	 */
	public function setUp() : void {
		parent::setUp();
		$this->instance = new Register();
	}

	/**
	 * Actions Data provider for init test.
	 *
	 * @return array[]
	 */
	public function registered_action_hooks_on_init()
	{
		return [
			[
				'register_form',
				'add_first_name',
				10,
				1
			],
			[
				'register_form',
				'add_last_name',
				10,
				1
			],
			[
				'user_register',
				'save_first_name',
				10,
				1
			],
			[
				'user_register',
				'save_last_name',
				10,
				1
			],
		];
	}

	/**
	 * Filters Data provider for init test.
	 *
	 * @return array[]
	 */
	public function registered_filter_hooks_on_init()
	{
		return [
			[
				'registration_errors',
				'validate_first_name',
				10,
				3
			],
			[
				'registration_errors',
				'validate_last_name',
				10,
				3
			],
		];
	}

	/**
	 * Filters Data provider for init test.
	 *
	 * @return array[]
	 */
	public function name_data()
	{
		return [
			[
				'',
				'',
				'',
				'',
			],
			[
				'Suleman',
				'Suleman',
				'Suleman',
				'Suleman',
			],
			[
				'Muhammad Suleman',
				'Muhammad Suleman',
				'Muhammad Suleman',
				'Muhammad Suleman',
			],
			[
				'Suleman      ',
				'Suleman',
				'Suleman',
				'Suleman',
			],
			[
				'Suleman <, >, &, ", \'',
				'Suleman , &amp;, \&quot;, \&#039;',
				'Suleman , &, \\", \\\'',
				'Suleman',
			],
			[
				'Suleman 65:-',
				'Suleman 65:-',
				'Suleman 65:-',
				'Suleman',
			],
		];
	}

	/**
	 * Test registered actions on init function call.
	 *
	 * @param string $hook_name     Name of the action hook to check.
	 * @param string $function_name Name of the function which will be hooked.
	 * @param int    $priority      Priority at which function will be hooked.
	 * @param int    $args          Number of args which hooked function will accept.
	 *
	 * @covers \Suleman\TDD\Register::init()
	 *
	 * @dataProvider registered_action_hooks_on_init
	 */
	public function test_registered_actions_init( $hook_name, $function_name, $priority, $args ) {
		WP_Mock::expectActionAdded( $hook_name, [ $this->instance, $function_name ], $priority, $args );
		$this->instance->init();
	}

	/**
	 * Test registered filters on init function call.
	 *
	 * @param string $hook_name     Name of the filter hook to check.
	 * @param string $function_name Name of the function which will be hooked.
	 * @param int    $priority      Priority at which function will be hooked.
	 * @param int    $args          Number of args which hooked function will accept.
	 *
	 * @covers \Suleman\TDD\Register::init()
	 *
	 * @dataProvider registered_filter_hooks_on_init
	 */
	public function test_registered_filters_init( $hook_name, $function_name, $priority, $args ) {
		WP_Mock::expectFilterAdded( $hook_name, [ $this->instance, $function_name ], $priority, $args );
		$this->instance->init();
	}

	/**
	 * Test add first name function with some data.
	 *
	 * @param string $unescaped     Name of the filter hook to check.
	 * @param string $esc_attr_name Name of the function which will be hooked.
	 * @param string $sanitized_name Name of the function which will be hooked.
	 * @param string $should_be     What name should be after all the sanitizing.
	 *
	 * @covers \Suleman\TDD\Register::add_first_name()
	 *
	 * @dataProvider name_data
	 */
	public function test_add_first_name( $unescaped, $esc_attr_name, $sanitized_name, $should_be ) {

		// set post method var for function
		$_POST['first_name'] = $unescaped;

		// Mock the escaping function
		WP_Mock::userFunction( 'esc_html_e' )
			->once()
			->with( 'First Name:', 'tdd-plugin' )
			->andReturn( 'First Name:' );

		// Mock the sanitize function
		WP_Mock::userFunction( 'sanitize_text_field' )
			->once()
			->with( $unescaped )
			->andReturn( $sanitized_name );

		// Mock the escaping function
		WP_Mock::userFunction( 'esc_attr' )
			->once()
			->with( $sanitized_name )
			->andReturn( $esc_attr_name );

		ob_start();
		$this->instance->add_first_name();
		$actual = ob_get_clean();
		$actual = preg_replace( '/(?<=>)\s+/', '', $actual );
		$actual = preg_replace( '/\s+(?=<)/', '', $actual );

		$expected = '<p><label for="first_name"><br/><input type="text" id="first_name" name="first_name" value="' . $esc_attr_name . '" class="input" /></label></p>';

		$this->assertEquals( $expected, $actual );
	}

	/**
	 * Test add first name function with some data.
	 *
	 * @param string $unescaped      Name of the filter hook to check.
	 * @param string $esc_attr_name  Name of the function which will be hooked.
	 * @param string $sanitized_name Name of the function which will be hooked.
	 * @param string $should_be      What name should be after all the sanitizing.
	 *
	 * @covers \Suleman\TDD\Register::add_last_name()
	 *
	 * @dataProvider name_data
	 */
	public function test_add_last_name( $unescaped, $esc_attr_name, $sanitized_name, $should_be ) {

		// set post method var for function
		$_POST['last_name'] = $unescaped;

		// Mock the escaping function
		WP_Mock::userFunction( 'esc_html_e' )
			->once()
			->with( 'Last Name:', 'tdd-plugin' )
			->andReturn( 'Last Name:' );

		// Mock the sanitize function
		WP_Mock::userFunction( 'sanitize_text_field' )
			->once()
			->with( $unescaped )
			->andReturn( $sanitized_name );

		// Mock the escaping function
		WP_Mock::userFunction( 'esc_attr' )
			->once()
			->with( $sanitized_name )
			->andReturn( $esc_attr_name );

		ob_start();
		$this->instance->add_last_name();
		$actual = ob_get_clean();
		$actual = preg_replace( '/(?<=>)\s+/', '', $actual );
		$actual = preg_replace( '/\s+(?=<)/', '', $actual );

		$expected = '<p><label for="last_name"><br/><input type="text" id="last_name" name="last_name" value="' . $esc_attr_name . '" class="input" /></label></p>';

		$this->assertEquals( $expected, $actual );
	}
}
