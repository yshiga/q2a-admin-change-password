<?php

/*
	Plugin Name: Admin Change Password
	Plugin URI:
	Plugin Update Check URI:
	Plugin Description: An administrator can change the user's other passwords.
	Plugin Version: 0.1
	Plugin Date: 2016-07-01
	Plugin Author: 38qa.net
	Plugin Author URI:
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.7
*/


if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
}

// layer
qa_register_plugin_layer('qa-admin-change-password-layer.php', 'Admin Change Password Layer');
// page
qa_register_plugin_module('page', 'qa-admin-change-password-page.php', 'qa_change_admin_password', 'Admin Change Password');
/*
	Omit PHP closing tag to help avoid accidental output
*/
