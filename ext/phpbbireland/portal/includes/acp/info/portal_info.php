    <?php
    /**
    *
    * @package Kiss portal Extension
    * @copyright (c) 2013 phpbbireland
    * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
    *
    */

    namespace phpbbireland\portal\acp;

    class portal_info
    {
       function module()
       {
          return array(
             'filename'   => '\phpbbireland\portal\acp\main_module',
             'title'      => 'ACP_PORTAL_TITLE',
             'version'   => '1.0.1',
             'modes'      => array(
                'config_portal'   => array('title' => 'ACP_PORTAl_CONFIG', 'auth' => 'acl_a_portal', 'cat' => array('ACP_PORTAL_TITLE')),
             ),
          );
       }
    }