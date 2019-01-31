<?php

/*
  |--------------------------------------------------------------------------
  | Base Site URL
  |--------------------------------------------------------------------------
  |
  | URL to your CodeIgniter root. Typically this will be your base URL,
  | WITH a trailing slash:
  |
  |	http://example.com/
  |
  | WARNING: You MUST set this value!
  |
  | If it is not set, then CodeIgniter will try guess the protocol and path
  | your installation, but due to security concerns the hostname will be set
  | to $_SERVER['SERVER_ADDR'] if available, or localhost otherwise.
  | The auto-detection mechanism exists only for convenience during
  | development and MUST NOT be used in production!
  |
  | If you need to allow multiple domains, remember that this file is still
  | a PHP script and you can easily do that on your own.
  |
 */
$config['base_url'] = 'http://localhost';

/*
  |--------------------------------------------------------------------------
  | Default Language
  |--------------------------------------------------------------------------
  |
  | This determines which set of language files should be used. Make sure
  | there is an available translation if you intend to use something other
  | than english.
  |
 */
$config['language'] = 'english';

/*
  |--------------------------------------------------------------------------
  | Default Character Set
  |--------------------------------------------------------------------------
  |
  | This determines which character set is used by default in various methods
  | that require a character set to be provided.
  |
 */
$config['charset'] = 'utf8';

/*
  |--------------------------------------------------------------------------
  | Enable/Disable System Hooks
  |--------------------------------------------------------------------------
  |
  | If you would like to use the 'hooks' feature you must enable it by
  | setting this variable to TRUE (boolean).  See the user guide for details.
  |
 */
$config['enable_hook'] = FALSE;

/*
  |--------------------------------------------------------------------------
  | Allowed URL Characters
  |--------------------------------------------------------------------------
  |
  | This lets you specify with a regular expression which characters are permitted
  | within your URLs.  When someone tries to submit a URL with disallowed
  | characters they will get a warning message.
  |
  | As a security measure you are STRONGLY encouraged to restrict URLs to
  | as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
  |
  | Leave blank to allow all characters -- but only if you are insane.
  |
  | DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
  |
 */
$config['permitted_uri_chars'] = 'آ-یa-z0-9-_.';

/*
  |--------------------------------------------------------------------------
  | Error Logging Threshold     (((((DISABLE)))))
  |--------------------------------------------------------------------------
  |
  | If you have enabled error logging, you can set an error threshold to
  | determine what gets logged. Threshold options are:
  | You can enable error logging by setting a threshold over zero. The
  | threshold determines what gets logged. Threshold options are:
  |
  |	0 = Disables logging, Error logging TURNED OFF
  |	1 = Error Messages (including PHP errors)
  |	2 = Debug Messages
  |	3 = Informational Messages
  |	4 = All Messages
  |
  | For a live site you'll usually only enable Errors (1) to be logged otherwise
  | your log files will fill up very fast.
  |
 */

// $config['log_threshold'] = 0;

/*
  |--------------------------------------------------------------------------
  | Error Logging Directory Path
  |--------------------------------------------------------------------------
  |
  | Leave this BLANK unless you would like to set something other than the default
  | app/logs/ folder. Use a full server path with trailing slash.
  |
 */
$config['log_path'] = '';

/*
  |--------------------------------------------------------------------------
  | Date Format for Logs
  |--------------------------------------------------------------------------
  |
  | Each item that is logged has an associated date. You can use PHP date
  | codes to set your own date formatting
  |
 */
$config['log_date_format'] = 'Y-m-d H:i:s';



/*
  |--------------------------------------------------------------------------
  | Cookie Related Variables
  |--------------------------------------------------------------------------
  |
  | 'cookie_prefix' =  Set a prefix if you need to avoid collisions
  | 'cookie_domain' =  Set to .your-domain.com for site-wide cookies
  | 'cookie_path'   =  Typically will be a forward slash
  | 'cookie_secure' =  Cookies will only be set if a secure HTTPS connection exists.
  |
 */
$config['cookie_prefix']    = "CONFIG_";    // myprefix_
$config['cookie_domain']    = "";
$config['cookie_path']      = "/";
$config['cookie_expire']    = 3600;  // 1 hour



/*
 * Enable/Disable System Plugins
 * 
 * True  -> System Plugin is Enable
 * False -> System Plugin is Disable
 * 
 * 
 * 
 */

$config['enable_plugin'] = FALSE;

