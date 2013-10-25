<?php
/**
*
* @package Kiss Portal Engine
* @version $Id$
* @author  Jedis
* @begin   25 June 2013
* @copyright (c) Q @ http://cybercommand.net 26Dec 2012
* @home    http://www.phpbbireland.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

global $phpbb_root_path, $phpEx;

$user->add_lang('mcp');

$tapproved = $treported = $papproved = $preported = 0;


if (!$auth->acl_getf_global('m_'))
{
	@$template->assign_vars(array(
		'MOD_MCP_ALLOWED'    => false,
	));
	return;
}

$sql = 'SELECT t.topic_id, t.topic_approved, t.topic_reported
	FROM ' . TOPICS_TABLE . ' t ';
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result))
{
	if ($row['topic_approved'] == 0) $tapproved++;
	if ($row['topic_reported'] == 1) $treported++;
}

$sql = 'SELECT t.topic_id, t.topic_approved, p.topic_id, p.post_approved, p.post_reported
	FROM ' . POSTS_TABLE . ' p
	LEFT JOIN ' . TOPICS_TABLE . ' t
	ON t.topic_id = p.topic_id
	WHERE t.topic_approved=1';
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result))
{
	if ($row['post_approved'] == 0) $papproved++;
	if ($row['post_reported'] == 1) $preported++;
}

$template->assign_block_vars('moderator', array(
	'TOPIC_APPROVE'   => $tapproved,
	'TOPIC_REPORT'    => $treported,
	'POSTS_APPROVE'   => $papproved,
	'POSTS_REPORT'    => $preported,
));

$db->sql_freeresult($result);

$forum_id = request_var('f', 0);
if ($forum_id)
{
	$umcp = append_sid("{$phpbb_root_path}mcp.$phpEx", 'i=main&amp;mode=forum_view&amp;f=' . $forum_id);
}
else
{
	$umcp = "";
}


$template->assign_vars(array(
	'MOD_MCP_ALLOWED'     => true,
	'U_MOD_MCP'           => append_sid("{$phpbb_root_path}mcp.$phpEx", 'i=main&mode=front'),
	'U_MCP'               => $umcp,
	'MOD_PORTAL_DEBUG'    => sprintf($user->lang['PORTAL_DEBUG_QUERIES'], ($queries) ? $queries : '0', ($cached_queries) ? $cached_queries : '0', ($total_queries) ? $total_queries : '0'),
));


?>