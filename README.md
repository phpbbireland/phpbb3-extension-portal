**Have an issue updating from remote, have to do it manually, so files are not all updated yet, this will take so time**  

Fully integrated portal for phpBB 3.3.x

## Tested with phpBB 3.3.10
    • Quite a few updates to the portal code including some additions to the code and minor fixes...
    • Note, the process of updating many files at one time, make commenting impractical.
  
**Tutorials:**  (there are some old youtube videos that may still be useful / relavent).  

    • Managing Portal Block in ACP (TBA)  
    • Managing Menus in ACP (TBA)  
    • Block visibility (group assess) TBA      
    • Creating Custom Blocks (TBA)  
    • Arrange Blocks (yet to be implemented for 3.1)
Video - [Arranging Blocks](https://www.youtube.com/watch?v=pkEWG6yq4as)

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

**2023**
Updated the code to work with phpBB 3.3.10...  
Locally tested using: Apache/2.4.56 (Unix) OpenSSL/1.1.1t PHP/8.2.4 mod_perl/2.0.12 Perl/v5.34.1
One minor issue... When selecting portal tab in ACP, it takes about 10 seconds to display... investigating...
