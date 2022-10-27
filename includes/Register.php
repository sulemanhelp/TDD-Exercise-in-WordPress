<?php
/**
 * Add fields to wordpress registration form
 *
 * @package TDD
 */

namespace Suleman\TDD;

/**
 * Test the WordPress plugin abstraction.
 */
class Register {

	public function init()
	{
		add_action( 'register_form', [ $this, 'add_first_name' ], 10, 1 );
		add_action( 'register_form', [ $this, 'add_last_name' ], 10, 1 );
		add_action( 'user_register', [ $this, 'save_first_name' ], 10, 1 );
		add_action( 'user_register', [ $this, 'save_last_name' ], 10, 1 );
		add_filter( 'registration_errors', [ $this, 'validate_first_name' ], 10, 3 );
		add_filter( 'registration_errors', [ $this, 'validate_last_name' ], 10, 3 );
	}

	/**
	 * Add first name to registration form
	 *
	 * @return void
	 */
	public function add_first_name(){
		$first_name = ! empty( $_POST['first_name'] ) ? $_POST['first_name'] : '';
		$first_name = sanitize_text_field( $first_name );
		?>
		<p>
			<label for="first_name"><?php esc_html_e( 'First Name:', 'tdd-plugin' ); ?><br/>
				<input type="text" id="first_name" name="first_name" value="<?php echo esc_attr( $first_name ); ?>" class="input" />
			</label>
		</p>
		<?php

	}

	/**
	 * Add last name to registration form
	 *
	 * @return void
	 */
	public function add_last_name(){
		$last_name = ! empty( $_POST['last_name'] ) ? $_POST['last_name'] : '';
		$last_name = sanitize_text_field( $last_name );
		?>
		<p>
			<label for="last_name"><?php esc_html_e( 'Last Name:', 'tdd-plugin' ); ?><br/>
				<input type="text" id="last_name" name="last_name" value="<?php echo esc_attr( $last_name ); ?>" class="input" />
			</label>
		</p>
		<?php
	}

	/**
	 * Validate the value of first name.
	 *
	 * @param \WP_Error $errors               A WP_Error object containing any errors encountered during registration.
	 * @param string    $sanitized_user_login User's username after it has been sanitized.
	 * @param string    $user_email           User's email.
	 *
	 * @return \WP_Error
	 */
	public function validate_first_name( $errors, $sanitized_user_login, $user_email ) {

		$first_name = ! empty( $_POST['first_name'] ) ? $_POST['first_name'] : '';
		$first_name = preg_replace("/[^a-zA-Z\s]/", "", $first_name );
		$first_name = trim( sanitize_text_field( $first_name ) );

		if ( empty( $first_name ) || strlen( $first_name ) < 2 ) {
			$errors->add( 'first_name_error', __( '<strong>ERROR</strong>: Please enter your first name.', 'tdd-plugin' ) );
			return $errors;
		}

		return $errors;
	}

	/**
	 * Validate the value of last name.
	 *
	 * @param \WP_Error $errors               A WP_Error object containing any errors encountered during registration.
	 * @param string    $sanitized_user_login User's username after it has been sanitized.
	 * @param string    $user_email           User's email.
	 *
	 * @return \WP_Error
	 */
	public function validate_last_name( $errors, $sanitized_user_login, $user_email ) {
		$last_name = ! empty( $_POST['last_name'] ) ? $_POST['last_name'] : '';
		$last_name = preg_replace("/[^a-zA-Z\s]/", "", $last_name );
		$last_name = trim( sanitize_text_field( $last_name ) );

		if ( empty( $last_name ) || strlen( $last_name ) < 2 ) {
			$errors->add( 'last_name_error', __( '<strong>ERROR</strong>: Please enter your last name.', 'tdd-plugin' ) );
			return $errors;
		}

		return $errors;
	}

	/**
	 * Save the first name
	 *
	 * @param int $user_id User ID.
	 *
	 * @return void
	 */
	public function save_first_name( $user_id ) {
		$first_name = ! empty( $_POST['first_name'] ) ? $_POST['first_name'] : '';
		$first_name = preg_replace("/[^a-zA-Z\s]/", "", $first_name );
		$first_name = trim( sanitize_text_field( $first_name ) );

		if ( ! empty( $first_name ) && strlen( $first_name ) > 2 ) {
			update_user_meta( $user_id, 'first_name', $first_name );
		}
	}

	/**
	 * Save the last name
	 *
	 * @param int $user_id User ID.
	 *
	 * @return void
	 */
	public function save_last_name( $user_id ) {
		$last_name = ! empty( $_POST['last_name'] ) ? $_POST['last_name'] : '';
		$last_name = preg_replace("/[^a-zA-Z\s]/", "", $last_name );
		$last_name = trim( sanitize_text_field( $last_name ) );

		if ( ! empty( $last_name ) && strlen( $last_name ) > 2 ) {
			update_user_meta( $user_id, 'last_name', $last_name );
		}

	}
}
