<?php
/**
 *
 * Login To Search. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, [Dimetrodon], https://phpbbforever.com/home
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dimetrodon\loginsearch\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Login To Search Event listener.
 */
class main_listener implements EventSubscriberInterface
{
//	public function __construct(auth $auth)
//	{
//		$this->auth = $auth;
//	}
	public static function getSubscribedEvents()
	{
		return [
			'core.user_setup'							=> 'load_language_on_setup',
			'core.search_auth_checks_override'			=> 'search_auth_checks_override_vars',
		];
	}

	/* @var \phpbb\language\language */
	protected $language;

	/**
	 * Constructor
	 *
	 * @param \phpbb\language\language	$language	Language object
	 */
	public function __construct(\phpbb\language\language $language)
	{
		$this->language = $language;
	}


	/**
	 * Load common language files during user setup
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'dimetrodon/loginsearch',
			'lang_set' => 'common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	 * A sample PHP event
	 * Modifies the names of the forums on index
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function search_auth_checks_override_vars($event)
	{
		$auth_check_override = true;
		
		// Is user able to search? Has search been disabled?
		if (!$auth->acl_get('u_search') || !$auth->acl_getf_global('f_search') || !$config['load_search'])
		{
			// Is the user logged in but unable to search? If so, they will get an error message.	 
			if ($user->data['user_id'] != ANONYMOUS)
			{			
			$template->assign_var('S_NO_SEARCH', true);
			trigger_error('NO_SEARCH');
			}

		// If the user is a guest and cannot search, they will recieve a login page.                      
		login_box('', ((isset($user->lang['LOGIN_EXPLAIN_' . strtoupper($mode)])) ? $user->lang['LOGIN_EXPLAIN_' . strtoupper($mode)] : $user->lang['LOGIN_EXPLAIN_SEARCH']));
		}

	}
}
