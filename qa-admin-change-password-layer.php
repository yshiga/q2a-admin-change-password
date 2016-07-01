<?php

class qa_html_theme_layer extends qa_html_theme_base
{

	function main()
	{
		if ($this->template == 'user') {
			if (qa_get_state() == 'edit' && qa_get_logged_in_level() >= QA_USER_LEVEL_SUPER ) {
				// print_r($this->content);
				$this->content['form_password'] = array(
					'tags' => 'method="post" action="'.qa_opt('site_url').'test-plugin"',

					'style' => 'wide',

					'title' => qa_lang_html('users/change_password'),

					'fields' => array(
						'old' => array(
							'label' => qa_lang_html('users/old_password'),
							'tags' => 'name="oldpassword"',
							'value' => qa_html(@$inoldpassword),
							'type' => 'password',
							'error' => qa_html(@$errors['oldpassword']),
						),

						'new_1' => array(
							'label' => qa_lang_html('users/new_password_1'),
							'tags' => 'name="newpassword1"',
							'type' => 'password',
							'error' => qa_html(@$errors['password']),
						),

						'new_2' => array(
							'label' => qa_lang_html('users/new_password_2'),
							'tags' => 'name="newpassword2"',
							'type' => 'password',
							'error' => qa_html(@$errors['newpassword2']),
						),
					),

					'buttons' => array(
						'change' => array(
							'label' => qa_lang_html('users/change_password'),
						),
					),

					'hidden' => array(
						'dochangepassword' => '1',
						'code' => qa_get_form_security_code('password'),
						'userid' => $this->content['raw']['userid'],
					),
				);
			}
			if (qa_get_state() == 'password-changed') {
				$this->content['form_profile']['ok'] = qa_lang_html('users/password_changed');
			}
		}
		qa_html_theme_base::main();
	}

}
