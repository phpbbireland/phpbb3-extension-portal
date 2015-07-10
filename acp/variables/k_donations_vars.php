<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbbireland\portal\acp;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

global $request, $phpEx, $k_config, $template;

$user->add_lang_ext('phpbbireland/portal', 'blocks/k_donations');

if ($request->is_set_post('submit'))
{
	$k_donation_years = $request->variable('k_donation_years', '2012');
	$k_donations_max  = $request->variable('k_donations_max', 100);
	$sgp_functions_admin->sgp_acp_set_config('k_donation_years', $k_donation_years);
	$sgp_functions_admin->sgp_acp_set_config('k_donations_max', $k_donations_max);
}
else
{
	$k_donation_years = $k_config['k_donation_years'];
	$k_donations_max  = $k_config['k_donations_max'];
}

$template->assign_vars(array(
	'S_K_DONATION_YEARS' => $k_donation_years,
	'S_K_DONATIONS_MAX'  => $k_donations_max,
));
