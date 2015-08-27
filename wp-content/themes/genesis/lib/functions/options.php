<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Genesis\Options
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://my.studiopress.com/themes/genesis/
 */

/**
 * Return option from the options table and cache result.
 *
 * Applies `genesis_pre_get_option_$key` and `genesis_options` filters.
 *
 * Values pulled from the database are cached on each request, so a second request for the same value won't cause a
 * second DB interaction.
 *
 * @since 0.1.3
 *
 * @uses GENESIS_SETTINGS_FIELD
 *
 * @param string  $key        Option name.
 * @param string  $setting    Optional. Settings field name. Eventually defaults to `GENESIS_SETTINGS_FIELD` if not
 *                            passed as an argument.
 * @param boolean $use_cache  Optional. Whether to use the Genesis cache value or not. Default is true.
 *
 * @return mixed The value of this $key in the database.
 */
function genesis_get_option( $key, $setting = null, $use_cache = true ) {

	//* The default is set here, so it doesn't have to be repeated in the function arguments for genesis_option() too.
	$setting = $setting ? $setting : GENESIS_SETTINGS_FIELD;

	//* Allow child theme to short-circuit this function
	$pre = apply_filters( "genesis_pre_get_option_{$key}", null, $setting );
	if ( null !== $pre ) {
		return $pre;
	}

	//* Bypass cache if viewing site in customizer
	if ( genesis_is_customizer() ) {
		$use_cache = false;
	}

	//* If we need to bypass the cache
	if ( ! $use_cache ) {
		$options = get_option( $setting );

		if ( ! is_array( $options ) || ! array_key_exists( $key, $options ) )
			return '';

		return is_array( $options[$key] ) ? stripslashes_deep( $options[$key] ) : stripslashes( wp_kses_decode_entities( $options[$key] ) );
	}

	//* Setup caches
	static $settings_cache = array();
	static $options_cache  = array();

	//* Check options cache
	if ( isset( $options_cache[$setting][$key] ) )
		//* Option has been cached
		return $options_cache[$setting][$key];

	//* Check settings cache
	if ( isset( $settings_cache[$setting] ) )
		//* Setting has been cached
		$options = apply_filters( 'genesis_options', $settings_cache[$setting], $setting );
	else
		//* Set value and cache setting
		$options = $settings_cache[$setting] = apply_filters( 'genesis_options', get_option( $setting ), $setting );

	//* Check for non-existent option
	if ( ! is_array( $options ) || ! array_key_exists( $key, (array) $options ) )
		//* Cache non-existent option
		$options_cache[$setting][$key] = '';
	else
		//* Option has not been previously been cached, so cache now
		$options_cache[$setting][$key] = is_array( $options[$key] ) ? stripslashes_deep( $options[$key] ) : stripslashes( wp_kses_decode_entities( $options[$key] ) );

	return $options_cache[$setting][$key];

}

/**
 * Echo options from the options database.
 *
 * @since 0.1.3
 *
 * @uses genesis_get_option() Return option from the options table and cache result.
 *
 * @param string  $key       Option name.
 * @param string  $setting   Optional. Settings field name. Eventually defaults to GENESIS_SETINGS_FIELD.
 * @param boolean $use_cache Optional. Whether to use the Genesis cache value or not. Default is true.
 */
function genesis_option( $key, $setting = null, $use_cache = true ) {

	echo genesis_get_option( $key, $setting, $use_cache );

}

/**
 * Return SEO options from the SEO options database.
 *
 * @since 0.1.3
 *
 * @uses genesis_get_option() Return option from the options table and cache result.
 * @uses GENESIS_SEO_SETTINGS_FIELD
 *
 * @param string  $key       Option name.
 * @param boolean $use_cache Optional. Whether to use the Genesis cache value or not. Defaults to true.
 *
 * @return mixed The value of this $key in the database.
 */
function genesis_get_seo_option( $key, $use_cache = true ) {

	return genesis_get_option( $key, GENESIS_SEO_SETTINGS_FIELD, $use_cache );

}

/**
 * Echo an SEO option from the SEO options database.
 *
 * @since 0.1.3
 *
 * @uses genesis_option() Echo option from the options table and cache result.
 * @uses GENESIS_SEO_SETTINGS_FIELD
 *
 * @param string  $key       Option name.
 * @param boolean $use_cache Optional. Whether to use the Genesis cache value or not. Defaults to true.
 */
function genesis_seo_option( $key, $use_cache = true ) {

	genesis_option( $key, GENESIS_SEO_SETTINGS_FIELD, $use_cache );

}

/**
 * Return a CPT Archive setting value from the options table.
 *
 * @since 2.0.0
 *
 * @uses genesis_get_global_post_type_name()       Get the `post_type` from the global `$post` if supplied value is empty.
 * @uses genesis_get_option()                      Return option from the options table and cache result.
 * @uses GENESIS_CPT_ARCHIVE_SETTINGS_FIELD_PREFIX
 *
 * @param string $key            Option name.
 * @param string $post_type_name Post type name.
 * @param bool   $use_cache      Optional. Whether to use the Genesis cache value or not. Defaults to true.
 *
 * @return mixed The option value.
 */
function genesis_get_cpt_option( $key, $post_type_name = '', $use_cache = true ) {

	$post_type_name = genesis_get_global_post_type_name( $post_type_name );

	return genesis_get_option( $key, GENESIS_CPT_ARCHIVE_SETTINGS_FIELD_PREFIX . $post_type_name, $use_cache );

}

/**
 * Echo a CPT Archive option from the options table.
 *
 * @since 2.0.0
 *
 * @uses genesis_get_cpt_option() Return a CPT Archive setting value from the options table.
 *
 * @param string $key            Option name.
 * @param string $post_type_name Post type name.
 * @param bool   $use_cache      Optional. Whether to use the Genesis cache value or not. Defaults to true.
 */
function genesis_cpt_option( $key, $post_type_name, $use_cache = true ) {

	echo genesis_get_cpt_option( $key, $post_type_name, $use_cache );

}

/**
 * Echo data from a post or page custom field.
 *
 * Echo only the first value of custom field.
 *
 * Pass in a `printf()` pattern as the second parameter and have that wrap around the value, if the value is not falsy.
 *
 * @since 0.1.3
 *
 * @uses genesis_get_custom_field() Return custom field post meta data.
 *
 * @param string $field          Custom field key.
 * @param string $output_pattern printf() compatible output pattern.
 */
function genesis_custom_field( $field, $output_pattern = '%s' ) {

	if ( $value = genesis_get_custom_field( $field ) )
		printf( $output_pattern, $value );

}

/**
 * Return custom field post meta data.
 *
 * Return only the first value of custom field. Return false if field is blank or not set.
 *
 * @since 0.1.3
 *
 * @param string $field Custom field key.
 *
 * @return string|boolean Return value or false on failure.
 */
function genesis_get_custom_field( $field ) {

	if ( null === get_the_ID() )
		return '';

	$custom_field = get_post_meta( get_the_ID(), $field, true );

	if ( ! $custom_field )
		return '';

	//* Return custom field, slashes stripped, sanitized if string
	return is_array( $custom_field ) ? stripslashes_deep( $custom_field ) : stripslashes( wp_kses_decode_entities( $custom_field ) );

}

/**
 * Save post meta / custom field data for a post or page.
 *
 * It verifies the nonce, then checks we're not doing autosave, ajax or a future post request. It then checks the
 * current user's permissions, before finally* either updating the post meta, or deleting the field if the value was not
 * truthy.
 *
 * By passing an array of fields => values from the same metabox (and therefore same nonce) into the $data argument,
 * repeated checks against the nonce, request and permissions are avoided.
 *
 * @since 1.9.0
 *
 * @param array    $data         Key/Value pairs of data to save in '_field_name' => 'value' format.
 * @param string   $nonce_action Nonce action for use with wp_verify_nonce().
 * @param string   $nonce_name   Name of the nonce to check for permissions.
 * @param WP_Post|integer $post  Post object or ID.
 * @param integer  $deprecated   Deprecated (formerly accepted a post ID).
 *
 * @return mixed Return null if permissions incorrect, doing autosave, ajax or future post, false if update or delete
 *               failed, and true on success.
 */
function genesis_save_custom_fields( array $data, $nonce_action, $nonce_name, $post, $deprecated = null ) {

	if ( ! empty( $deprecated ) ) {
		_deprecated_argument( __FUNCTION__, '2.0.0' );
	}

	//* Verify the nonce
	if ( ! isset( $_POST[ $nonce_name ] ) || ! wp_verify_nonce( $_POST[ $nonce_name ], $nonce_action ) )
		return;

	//* Don't try to save the data under autosave, ajax, or future post.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
		return;
	if ( defined( 'DOING_CRON' ) && DOING_CRON )
		return;

	//* Grab the post object
	if ( ! is_null( $deprecated ) )
		$post = get_post( $deprecated );
	else
		$post = get_post( $post );

	//* Don't save if WP is creating a revision (same as DOING_AUTOSAVE?)
	if ( 'revision' === get_post_type( $post ) )
		return;

	//* Check that the user is allowed to edit the post
	if ( ! current_user_can( 'edit_post', $post->ID ) )
		return;

	//* Cycle through $data, insert value or delete field
	foreach ( (array) $data as $field => $value ) {
		//* Save $value, or delete if the $value is empty
		if ( $value )
			update_post_meta( $post->ID, $field, $value );
		else
			delete_post_meta( $post->ID, $field );
	}

}

/**
 * Takes an array of new settings, merges them with the old settings, and pushes them into the database.
 *
 * @since 2.1.0
 *
 * @uses GENESIS_SETTINGS_FIELD
 *
 * @param string|array $new     New settings. Can be a string, or an array.
 * @param string       $setting Optional. Settings field name. Default is GENESIS_SETTINGS_FIELD.
 */
function genesis_update_settings( $new = '', $setting = GENESIS_SETTINGS_FIELD ) {

	return update_option( $setting, wp_parse_args( $new, get_option( $setting ) ) );

}
