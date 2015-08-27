<?php
/*
Plugin Name: Disable Password Change Email
Version: 1.0.0
Plugin URI: http://wordpress.org/extend/plugins/disable-password-change-email/
Description: Turns off the automatic "Password Lost/Changed" email messages sent to the admin when a user resets password.
Author: Andrej Pavlovic
Author URI: http://www.pokret.org/
*/

/*
	This is free and unencumbered software released into the public domain.

	Anyone is free to copy, modify, publish, use, compile, sell, or
	distribute this software, either in source code form or as a compiled
	binary, for any purpose, commercial or non-commercial, and by any
	means.

	In jurisdictions that recognize copyright laws, the author or authors
	of this software dedicate any and all copyright interest in the
	software to the public domain. We make this dedication for the benefit
	of the public at large and to the detriment of our heirs and
	successors. We intend this dedication to be an overt act of
	relinquishment in perpetuity of all present and future rights to this
	software under copyright law.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
	EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
	MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
	IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
	OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
	ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
	OTHER DEALINGS IN THE SOFTWARE.

	For more information, please refer to <http://unlicense.org/>
*/

define('DISABLE_PASSWORD_CHANGE_EMAIL_VERSION', '1.0.0');

$pluginDirName = basename(dirname(__FILE__));
$pluginName = $pluginDirName . '/' . basename(__FILE__);

// do not initialize plugin if not active
if (in_array($pluginName, get_option('active_plugins')))
{
	if ( !function_exists( 'wp_password_change_notification' ) ) {
		function wp_password_change_notification() {}
	}
}
