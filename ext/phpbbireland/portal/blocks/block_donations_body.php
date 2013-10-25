<?php

if (!defined('IN_PHPBB'))
{
	exit;
}

global $k_config;

$loop_count = 0;
$donations_amount = 0.00;

$k_donations_max = $k_config['k_donations_max'];
$k_donations_years = $k_config['k_donations_years'];
$sql_in = explode(",", $k_donations_years);

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_donations.html')
	{
		$block_cache_time = $blk['block_cache_time'];
		break;
	}
}
$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['k_block_cache_time_default']);

$sql = "SELECT *
	FROM ". K_DONATIONS_TABLE . "
	WHERE donations_id <> 0 " . "
	AND " . $db->sql_in_set('donations_year', $sql_in) . "
	ORDER BY donations_id DESC";

	//ORDER BY donations_id, donations_date, donations_name ASC";

if (!$result = $db->sql_query($sql))
{
	trigger_error($user->lang['ERROR_PORTAL_MODULE'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . ', line ' . __LINE__);
}

$result = $db->sql_query_limit($sql, $k_donations_max, 0, $block_cache_time);

while ($row = $db->sql_fetchrow($result))
{
	$usr_name_full = get_user_data('full', $row['donator_id']);

	$template->assign_block_vars('donations_loop_row', array(
		'DONATIONS_ID'			=> $row['donations_id'],
		'DONATIONS_NAME'		=> $row['donations_name'],
		'DONATIONS_AMOUNT'		=> $row['donations_amount'],
		'DONATIONS_R_TOT'		=> $row['donations_r_tot'],
		'DONATIONS_PRIVATE'		=> $row['donations_private'],
		'DONATIONS_DATE'		=> $row['donations_date'],
		'DONATIONS_YEAR'        => $row['donations_year'],
		'DONATIONS_PID'			=> $usr_name_full,
		'S_DROW_COUNT'			=> $loop_count++,
	));
	$donations_amount += $row['donations_amount'];

}
$db->sql_freeresult($result);

$k_donations_years = str_replace(',', ', ', $k_donations_years);

$template->assign_vars(array(
	'TOTAL_DONATIONS' => sprintf($user->lang['TOTAL_DONATIONS'], $k_donations_years, $donations_amount),
	'DONATIONS_YEAR'  => $row['donations_year'],
));

?>