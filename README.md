**No longer current, depreciated**

**Security Updates**
Please use jQuery 3.5.0 or later

**working on update for phpBB 3.3.10 see branch 3.3.10**

#phpbb3-extension-portal

Fully integrated portal for phpBB 3.1.x

**Development Status:** [![Build Status](https://travis-ci.org/phpbbireland/phpbb3-extension-portal.svg)](https://travis-ci.org/phpbbireland/phpbb3-extension-portal)

Only three errors and two of these are probably all right but one doesn't provide any iformation so I can't yet find it...  

## Tested with phpBB 3.1.5
    • No file updates required for 3.1.5
    • Quite a few updates to the portal code including some additions to the code and minor fixes...
    • Note, the process of updating many files at one time, make commenting impractical.
    • Added a sample style (Olympus).  
  
*If you have already installed and want to update, please disable the extension, remove it (Delete Data) and reinstall as the latest updates add an extra database table and may changes others... A complete reinstall in the only way to ensure everything works.*
...  

Mike

## Installation:
    • Copy the entire contents from the unzipped folder to "phpBB/ext/phpbbireland/portal/".
    • Navigate in the ACP to "Customise -> Manage extensions".
    • Find "Portal" under "Disabled Extensions" and click "Enable".

## Uninstalling:
    • Navigate in the ACP to "Customise -> Manage extensions".
    • Click the "Disable" link for "Portal".
    • To permanently uninstall, click "Delete Data", then delete the "portal" folder from "phpBB/ext/phpbbireland/".

## License

[GPLv2](license.txt)
