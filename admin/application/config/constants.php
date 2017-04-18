<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('SITE_NM','Dragonframe');
//define('FROM_EMAIL','sanjay.bhatiya@indianic.com');
define('FROM_EMAIL','support@dragonframe.com');
define('SITE_ASSETS', 'assets/');
define('SITE_CSS', SITE_ASSETS.'css/');
define('SITE_JS', SITE_ASSETS.'js/');
define('SITE_IMG', SITE_ASSETS.'img/');
define('SITE_FONT', SITE_ASSETS.'fonts/');
define('DIR_ASSETS', FCPATH.'assets/');
define('SITE_UPLOADS', SITE_ASSETS.'uploads/');
define('WEB_URL','http://'.$_SERVER['HTTP_HOST'].'/');
define('APPLICATION_BASE_PATH',$_SERVER['DOCUMENT_ROOT'].'/meramsg/application/');
//define('IOS_PEM_PATH_ADMIN',$_SERVER['DOCUMENT_ROOT'].'/assets/apns-dev.pem');
define('IOS_PEM_PATH_ADMIN',$_SERVER['DOCUMENT_ROOT'].'/assets/apns-dist.pem');
define('SITE_URL',WEB_URL.'dragonframe');
/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
define('ACTIVE', '1');
define('VERIFIED', '1');
define('ADMIN', '1');
define('DELETED', '0');
define('DELETED_YES', '1');
define("GOOGLE_API_KEY", "AIzaSyBlp1Zh-LqKwA7NeFKJMXCiz46KOKzQals");


/* User Profile Path */

define('USERPROFILE',FCPATH.'../uploads/UserProfile/');
define('PHOTOIMAGE',FCPATH.'../uploads/PhotoImages/');
define('PHOTOIMAGE2',FCPATH.'../uploads/PhotoImages2/');

define('ABSUSERPROFILE','../uploads/UserProfile/');
define('ABSPHOTOIMAGE','../uploads/PhotoImages/');
define('ABSPHOTOIMAGE2','../uploads/PhotoImages2');
define('DEFAULT_PROFILE_WIDTH','1900');
define('DEFAULT_PROFILE_HEIGHT','1900');
define('FRAMES_UPLOAD_PATH','../uploads/frames/');
define('FRAMES_PATH','/uploads/frames/');