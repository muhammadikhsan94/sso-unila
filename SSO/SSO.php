<?php
/**
 * Single Sign-on Authentication
 *
 * @author      Muhammad Ikhsan <muhammadikhsan208@gmail.com>
 * @copyright   2021 Muhammad Ikhsan
 * @license     MIT
 * @package     SSO
*
 */
namespace SSO;

use phpCAS;

// ------------------------------------------------------------------------
//  Constants
// ------------------------------------------------------------------------

/**
 * CAS server host address
 */
define('CAS_SERVER_HOST', 'login.unila.ac.id');

/**
 * CAS server uri
 */
define('CAS_SERVER_URI', '/cas');

/**
 * CAS server port
 */
define('CAS_SERVER_PORT', 443);

// ------------------------------------------------------------------------
//  CAS Initialization
// ------------------------------------------------------------------------

// ONLY DO THIS IF phpCAS EXISTS (i.e. installing via Composer). Thanks to Fariskhi for noticing the bug.
if (class_exists('phpCAS')) {
  /**
   * Create phpCAS client
   */
  phpCAS::client(CAS_VERSION_2_0, CAS_SERVER_HOST, CAS_SERVER_PORT, CAS_SERVER_URI);

  /**
   * Set no validation.
   */
  phpCAS::setNoCasServerValidation();
}

/**
 * The SSO class is a simple phpCAS interface for authenticating using
 * SSO-UNILA CAS service.
 *
 * @class     SSO
 * @category  Authentication
 * @package   SSO 
 * @author    Muhammad Ikhsan <muhammadikhsan208@gmail.com>
 * @license   MIT
 */
class SSO
{

  /**
   * Authenticate the user.
   *
   * @return bool Authentication
   */
  public static function authenticate() {
    return phpCAS::forceAuthentication();
  }

  public static function cookieClear() {
    if (isset($_COOKIE['PHPSESSID'])) {
        unset($_COOKIE['PHPSESSID']);
        return setcookie('PHPSESSID', '', time() - 3600, '/'); // empty value and old timestamp
    }
  }

  public static function ciCookieClear() {
    if (isset($_COOKIE['ci_session'])) {
        unset($_COOKIE['ci_session']);
        return setcookie('ci_session', '', time() - 3600, '/'); // empty value and old timestamp
    }
  }

  /**
   * Check if the user is already authenticated.
   *
   * @return bool Authentication
   */
  public static function check() {
    return phpCAS::checkAuthentication();
  }

  /**
   * Logout from SSO with URL redirection options
   */
  public static function logout($url='') {
    if ($url === '')
      phpCAS::logout();
    else
      phpCAS::logoutWithRedirectService($url);
  }

  /**
   * Returns the authenticated user.
   *
   * @return Object User
   */
  public static function getUser() {
    
    // Get attribute release from CAS SERVER
    $details = phpCAS::getAttributes();
    
    // Create new user object, initially empty.
    $user = new \stdClass();
    $user->username = phpCAS::getUser();
    $user->nm_pengguna = $details['nm_pengguna'];
    $user->a_aktif = $details['a_aktif'];
    $user->last_sync = $details['last_sync'];

    return $user;
  }

  // ----------------------------------------------------------
  // Manual Installation Stuff
  // ----------------------------------------------------------

  /**
   * Sets the path to CAS.php. Use only when not installing via Composer.
   *
   * @param string $cas_path Path to CAS.php
   */
  public static function setCASPath($cas_path) {
    require $cas_path;

    // Initialize CAS client.
    self::init();
  }

  /**
   * Initialize CAS client. Called by setCASPath().
   */
  private static function init() {
    // Create CAS client.
    phpCAS::client(CAS_VERSION_2_0, CAS_SERVER_HOST, CAS_SERVER_PORT, CAS_SERVER_URI);

    // Set no validation.
    phpCAS::setNoCasServerValidation();
  }

}