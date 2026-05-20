<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Session Type
|--------------------------------------------------------------------------
*/
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session_smp';
$config['sess_expiration'] = 7200; // 2 hours
$config['sess_save_path'] = APPPATH . 'cache/sessions';
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = TRUE;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
*/
$config['cookie_prefix']   = "smp_";
$config['cookie_domain']   = "";
$config['cookie_path']     = "/";
$config['cookie_secure']   = FALSE;
$config['cookie_httponly'] = TRUE;

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
*/
$config['encryption_key'] = 'SMPTK_GALANG_KASIH_UBUNG_2025_SECRET_KEY';
$config['cipher'] = 'aes-256-cbc';
