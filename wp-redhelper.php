<?php
/*
Plugin Name: WP Redhelper
Plugin URI: http://redhelper.ru
Description: This plugin allows you to add RedHelper Live Chat to your WordPress site
Version: 1.0
Author: zlebnik
Author URI: http://zlebnik.ru
License: GPL2
*/
class WPRedhelper {
	public function echoScript() {
		$options = get_option('redhelper_options');
		if (!$options['login']) return;
		echo '
		<!-- RedHelper -->
		<script id="rhlpscrtg" type="text/javascript" charset="utf-8" async="async"
			src="http://web.redhelper.ru/service/main.js?c='.$options['login'].'"></script>
		<!--/Redhelper -->';
		return;
	}

	public function optionsPage() {
		echo '
			<div class="wrap">
			<h2>RedHelper settings</h2>
				<form action="options.php" method="post">';
		settings_fields('redhelper_options');
		do_settings_sections('redhelper');
		echo '<input name="Submit" type="submit" value="';
		esc_attr_e('Save Changes');
		echo '" />
				</form>
			</div>';
	}

	public function registerOptions() {
		register_setting('redhelper_options', 'redhelper_options', array('WPRedhelper', 'validateOptions'));
		add_settings_section('redhelper_main', 'Main Settings', array('WPRedhelper', 'optionsText'), 'redhelper');
		add_settings_field('redhelper_login', 'Your RedHelper login', array('WPRedhelper', 'optionLogin'), 'redhelper', 'redhelper_main');
	}

	public function optionLogin() {
		$options = get_option('redhelper_options');
		echo "<input id='redhelper_login' name='redhelper_options[login]' size='40' type='text' value='{$options['login']}' />";
	}

	public function validateOptions($input) {
		$newinput['login'] = trim($input['login']);
		return $newinput;
	}

	public function addOptionsPage() {
		add_options_page('RedHelper', 'RedHelper', 'manage_options', 'redhelper', array('WPRedhelper', 'optionsPage'));	
	}

	public function optionsText() {
		return "<p>Your login for RedHelper</p>";
	}
}

add_action('admin_menu', array('WPRedhelper', 'addOptionsPage'));
add_action('wp_head', array('WPRedhelper', 'echoScript'));
add_action('admin_init', array('WPRedhelper', 'registerOptions'));

?>