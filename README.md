#phpbb3-extension-portal

Fully integrated portal for phpBB 3.1.x

**Development Status:** [![Build Status](https://travis-ci.org/phpbbireland/phpbb3-extension-portal.svg)](https://travis-ci.org/phpbbireland/phpbb3-extension-portal)

**Due to illness updates may be delayed**  
Updated for RC3 please use this repository for testing only,  

* Migration complete, only remains to seed the database tables (currently done manually)... 
* ACP: Complete and fully working...
* The portal Page complete and working (a little more to do)...
...  

Test migration added...  
Please be aware this is for testing only... there's a lot more work to be done...

To avail of blocks on index/viewtopics/viewforums or any other phpBB pages, you need to manually copy the block folder from phpbbireland/portal/styles/common/template/ to phpBB root /styles/prosilver/template folder...
This is a temporary fix till I find a solution...
Mike

## Installation:
    • Copy the entire contents from the unzipped folder to "phpBB/ext/phpbbireland/portal/".
    • Navigate in the ACP to "Customise -> Manage extensions".
    • Find "Portal" under "Disabled Extensions" and click "Enable".

## Uninstallation:
    • Navigate in the ACP to "Customise -> Manage extensions".
    • Click the "Disable" link for "Portal".
    • To permanently uninstall, click "Delete Data", then delete the "portal" folder from "phpBB/ext/phpbbireland/".

## License

[GPLv2](license.txt)
