<?php

class qa_change_admin_password {

	var $directory;
	var $urltoroot;


	function load_module($directory, $urltoroot)
	{
		$this->directory=$directory;
		$this->urltoroot=$urltoroot;
	}


	function suggest_requests() // for display in admin interface
	{
		return array(
			array(
				'title' => 'Admin Change Password',
				'request' => 'change-password',
				'nav' => 'none', // 'M'=main, 'F'=footer, 'B'=before main, 'O'=opposite main, null=none
			),
		);
	}


	function match_request($request)
	{
		if ($request=='change-password')
			return true;

		return false;
	}


	function process_request($request)
	{
		if (!qa_is_http_post()) {
			qa_redirect();
		};
		$qa_content = qa_content_prepare();
		$errors = array();
		$dochangepassword = qa_post_text('dochangepassword');

		if ($dochangepassword) {
			require_once QA_INCLUDE_DIR.'app/users-edit.php';

			$inoldpassword = qa_post_text('oldpassword');
			$innewpassword1 = qa_post_text('newpassword1');
			$innewpassword2 = qa_post_text('newpassword2');
			$userid = qa_post_text('userid');
			$useraccount = qa_db_select_with_pending(qa_db_user_account_selectspec($userid, true));
			$haspassword = isset($useraccount['passsalt']) && isset($useraccount['passcheck']);
			$code = qa_post_text('code');

			if (!qa_check_form_security_code('password', $code))
				$errors['page'] = qa_lang_html('misc/form_security_again');

			else {
				$errors = array();

				if ($haspassword && (strtolower(qa_db_calc_passcheck($inoldpassword, $useraccount['passsalt'])) != strtolower($useraccount['passcheck'])))
					$errors['oldpassword'] = qa_lang('users/password_wrong');

				$useraccount['password'] = $inoldpassword;
				$errors = $errors + qa_password_validate($innewpassword1, $useraccount); // array union

				if ($innewpassword1 != $innewpassword2)
					$errors['newpassword2'] = qa_lang('users/password_mismatch');

				if (empty($errors)) {
					qa_db_user_set_password($userid, $innewpassword1);
					// qa_db_user_set($userid, 'sessioncode', ''); // stop old 'Remember me' style logins from still working
					// qa_set_logged_in_user($userid, $useraccount['handle'], false, $useraccount['sessionsource']); // reinstate this specific session

					qa_report_event('au_password', $userid, $useraccount['handle'], qa_cookie_get());

					qa_redirect('user/'.$useraccount['handle'], array('state' => 'password-changed'));
				}
			}

		}

		$qa_content['title'] = 'Test Plugin';
		// $qa_content['error'] = '';
		if (!empty($errors)) {
			$qa_content['custom'] .= '<ul>';
			foreach ($errors as $error) {
				$qa_content['custom'] .= '<li>'.$error.'</li>';
			}
			$qa_content['custom'] .= '</ul>';
			$qa_content['custom'] .= '変更できませんでした。<br>';
			$qa_content['custom'] .= '<a href="user/'.$useraccount['handle'].'">戻る</a>';
		}
		return $qa_content;
	}

}


/*
	Omit PHP closing tag to help avoid accidental output
*/
