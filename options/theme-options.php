<?php

Carbon_Container::factory('theme_options', __('Theme Options', 'crb'))
	->add_tab('Header Menu', array(
		Carbon_Field::factory('association', 'crb_header_menus', __('Header Menus', 'crb') )
			->set_types(array(
				array(
					'type' => 'term',
					'taxonomy' => 'nav_menu'
				)
			))
			->help_text('Select Menu to display in the Header. Menus will be listed by selected order in the header.'),
	))
	->add_tab('Misc', array(
		Carbon_Field::factory('header_scripts', 'crb_header_script', __('Header Script', 'crb')),
		Carbon_Field::factory('footer_scripts', 'crb_footer_script', __('Footer Script', 'crb')),
	));

if ( carbon_twitter_widget_registered() ) {
	Carbon_Container::factory('theme_options', 'Twitter Settings')
		->set_page_parent(__('Theme Options', 'crb'))
		->add_fields(array(
			Carbon_Field::factory('html', 'crb_twitter_settings_html')
				->set_html('
					<div style="position: relative; background: #fff; border: 1px solid #ccc; padding: 10px;">
						<h4><strong>' . __('Twitter API requires a Twitter application for communication with 3rd party sites. Here are the steps for creating and setting up a Twitter application:', 'crb') . '</strong></h4>
						<ol style="font-weight: normal;">
							<li>' . sprintf(__('Go to <a href="%1$s" target="_blank">%1$s</a> and log in, if necessary.', 'crb'), 'https://dev.twitter.com/apps/new') . '</li>
							<li>' . __('Supply the necessary required fields and accept the <strong>Terms of Service</strong>. <strong>Callback URL</strong> field may be left empty.', 'crb') . '</li>
							<li>' . __('Submit the form.', 'crb') . '</li>
							<li>' . __('On the next screen, click on the <strong>Keys and Access Tokens</strong> tab.', 'crb') . '</li>
							<li>' . __('Scroll down to <strong>Your access token</strong> section and click the <strong>Create my access token</strong> button.', 'crb') . '</li>
							<li>' . __('Copy the following fields: <strong>Consumer Key, Consumer Secret, Access Token, Access Token Secret</strong> to the below fields.', 'crb') . '</li>
						</ol>
					</div>
				'),
			Carbon_Field::factory('text', 'crb_twitter_consumer_key', __('Consumer Key', 'crb'))
				->set_default_value(''),
			Carbon_Field::factory('text', 'crb_twitter_consumer_secret', __('Consumer Secret', 'crb'))
				->set_default_value(''),
			Carbon_Field::factory('text', 'crb_twitter_oauth_access_token', __('Access Token', 'crb'))
				->set_default_value(''),
			Carbon_Field::factory('text', 'crb_twitter_oauth_access_token_secret', __('Access Token Secret', 'crb'))
				->set_default_value(''),
		));
}