<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/


/**
* sgp_local_acronyms()
* phpbb_preg_quote()
* truncate_post()
* add_smilies_count()
* word_replace()
*/

if (!defined('IN_PHPBB'))
{
   exit;
}

/***
* phpbb pregs quote reused
*/

if (!function_exists('phpbb_preg_quote'))
{
	function phpbb_preg_quote($str, $delimiter)
	{
		$text = preg_quote($str);
		$text = str_replace($delimiter, '\\' . $delimiter, $text);

		return $text;
	}
}


/**
* @param: $post_text, $text_limit, $bbcode_uid
*
* Truncates and return post text while retaining special characters
*
* $show_info: optional hard coded variable to include some information
*
* If the text limit falls within a standard bbcode, return text to end of bbcode...
* If the text limit falls within a list bbcode, return the full list...
*
* Updated: 19 June 2013 Mike
*/

if (!function_exists('truncate_post'))
{
	function truncate_post($post_text, $text_limit, $bbcode_uid = '')
	{
		global $user;

		$show_info = true;
		$text_limitn = $post_length = $position = $offset = $tmp = $m = $count = $pos = $in_list = 0;
		$bbcodes_start_count = $bbcodes_end_count = 0;

		$bbcodes_start = $bbcodes_end = array();
		$bbocde_start_array = $bbocde_end_array = array();

		$buffer = '';
		$post_length = strlen($post_text);
		$bbcode_uid_length = strlen($bbcode_uid);

		if ($text_limit > $post_length)
		{
			return($post_text);
		}

		// grab and store starting bbcodes in $bbocde_start_array[] and position in position $bbcodes_start[] //
		if ($bbcode_uid)
		{
			while ($position = strpos($post_text, $bbcode_uid, $offset))
			{
				$k = $j = $position;

				while ($post_text[$j] != '[' && $j > 0)
				{
					$j--;
				}

				if ($post_text[++$j] === '/')
				{
					;
				}
				else
				{
					// back to start //
					while ($post_text[$k] != '[' && $k > 0)
					{
						$k--;
						$m++;

						if ($post_text[$k] === '[')
						{
							// store bbcode found position //
							$bbcodes_start[] = ($position - $m);
							$m = 0;
						}
					}

					while ($post_text[$k] != ']' && $k < $post_length)
					{
						$buffer .= $post_text[$k];
						$k++;
					}
					$buffer .= $post_text[$k];

					$bbocde_start_array[] = $buffer;
					$buffer = '';
				}
				$offset = $position + $bbcode_uid_length;
			}
		}

		$bbcodes_start_count = count($bbcodes_start);

		// no bbcodes, so truncate normally and return //
		if ($text_limit < $post_length && $bbcodes_start_count == 0)
		{
			for ($i = 0; $i < $text_limit; $i++)
			{
				$buffer .= $post_text[$i];
			}

			if ($show_info)
			{
				if (strlen($buffer) < $post_length)
				{
					$buffer .=  sprintf($user->lang['POST_LIMITED_TO'], $i);
				}
			}
			return($buffer);
		}

		// generate bbcodes end arrays (deal with all bbcodes including user added bbcodes) //
		foreach ($bbocde_start_array as $item)
		{

			if ($item[0] == '[' && $item[1] == 'u' && $item[2] == 'r' && $item[3] == 'l') //url
			{
				$bbocde_end_array[$count++] = '[/url:' . $bbcode_uid . ']';
			}

			else if ($item[0] == '[' && $item[1] == 'l' && $item[2] == 'i' && $item[3] == 's' && $item[4] == 't' && $item[5] == ':') //list end
			{
				$bbocde_end_array[$count++] = '[/list:u:' . $bbcode_uid . ']';
			}
			else if ($item[0] == '[' && $item[1] == '*' && $item[2] == ':') //list item
			{
				$bbocde_end_array[$count++] = '[/*:m:' . $bbcode_uid . ']';
			}

			else if ($item[0] == '[' && $item[1] == 'l' && $item[2] == 'i' && $item[3] == 's' && $item[4] == 't' && $item[5] == '=') //list end
			{
				$bbocde_end_array[$count++] = '[/list:o:' . $bbcode_uid . ']';
			}

			else if ($item[0] == '[' && $item[1] == 'q' && $item[2] == 'u' && $item[3] == 'o' && $item[4] == 't' && $item[5] == 'e' &&  $item[6] == ':') //quote
			{
				$bbocde_end_array[$count++] = '[/quote:' . $bbcode_uid . ']';
			}
			else if ($item[0] == '[' && $item[1] == 'c' && $item[2] == 'o' && $item[3] == 'd' && $item[4] == 'e' && $item[5] == ':') //code
			{
				$bbocde_end_array[$count++] = '[/code:' . $bbcode_uid . ']';
			}
			else if ($item[0] == '[' && $item[1] == 's' && $item[2] == 'i' && $item[3] == 'z' && $item[4] == 'e' && $item[5] == '=') //size
			{
				$bbocde_end_array[$count++] = '[/size:' . $bbcode_uid . ']';
			}
			else
			{
				$t = str_replace(':' . $bbcode_uid . ']', '', $item);
				$t = str_replace('[', '', $t);
				$bbocde_end_array[$count] = '[/' . $t . ':' . $bbcode_uid . ']';
				$count++;
			}
		}

		// reset some vars //
		$i = $position = $bb = 0;

		// get bbcode end position in to $bbocde_end_array //
		foreach ($bbocde_end_array as $item)
		{
			if ($position <= $post_length)
			{
				$position = strpos($post_text, $item, $position);

				if ($position !== 0)
				{
					if ($bbocde_end_array[$i] == '[/*:m:' . $bbcode_uid . ']')
					{
						if ($in_list == 0)
						{
							$in_list_end = $i - 1;
							$in_list++;
						}

						$bbcodes_end[$i] = $bbcodes_end[$in_list_end];
					}
					else
					{
						$in_list = 0;
						$bbcodes_end[$i] = $position + strlen($item);
					}
					$position += strlen($item);
				}

			}
			$i++;
		}
		$bbcodes_end_count = count($bbcodes_end);

		// get $bbcodes_end to use //
		for ($i = 0; $i < $bbcodes_start_count; $i++)
		{
			if ($text_limit >= $bbcodes_start[$i])
			{
				$tmp = $i;
				//echo '[' . $tmp . ']<br />';
			}
		}

		// process up to end bbcode //
		for ($i = 0; $i < $bbcodes_end[$tmp]; $i++)
		{
			$buffer .= $post_text[$i];
		}

		// if $text_limit is more than claculated $bbcodes_end and less than next $bbcodes_start //
		if (isset($bbcodes_start[$tmp + 1]) && $i < $text_limit && $i < $bbcodes_start[$tmp + 1])
		{
			while ($i < $text_limit)
			{
				$buffer .= $post_text[$i++];
			}
		}

		if ($show_info)
		{
			if (strlen($buffer) < $post_length)
			{
				$buffer .=  sprintf($user->lang['POST_LIMITED_TO'], $i);
			}
		}

		return($buffer);
	}
}


if (!function_exists('add_smilies_count'))
{
	function add_smilies_count($pos, $txt)
	{
		$post_length = strlen($txt);

		if ($txt[$pos] == '<' && $txt[$pos + 5] == 's' && $txt[$pos + 6] == ':')
		{
			while ($txt[$pos] != '>' && $pos < $post_length)
			{
				$pos++;
			}
			while ($txt[$pos] != '>' && $pos < $post_length)
			{
				$pos++;
			}
			return($pos);
		}
		else
		{
			return($pos);
		}
	}
}


/**
 * Written by Rowan Lewis
 * $search(string), the string to be searched for
 * $replace(string), the string to replace $search
 * $subject(string), the string to be searched in
 */
function word_replace($search, $replace, $subject)
{
	return preg_replace('/[a-zA-Z]+/e', '\'\0\' == \'' . $search . '\' ? \'' . $replace . '\': \'\0\';', $subject);
}
