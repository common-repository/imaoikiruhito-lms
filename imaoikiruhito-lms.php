<?php
/**
 * Plugin Name: Imaoikiruhito LMS
 * Plugin URI: https://imaoikiruhito-plugin.com/
 * Description: Learning Management System Plugin
 * Version: 1.0.13
 * Author: Imaoikiruhito
 * Author URI: https://www.imaoikiruhito.com/
 * License: GPLv2
 * Text Domain: imaoikiruhitolms
 * Domain Path: /languages/
 *
 * @package Imaoikiruhito LMS
 */

define( 'IIHLMS_VERSION', '1.0.0.0' );
define( 'IIHLMS_DB_ORDER', '1.0' );
define( 'IIHLMS_DB_ORDER_META', '1.0' );
define( 'IIHLMS_DB_ORDER_CART', '1.0' );
define( 'IIHLMS_DB_ORDER_CART_META', '1.0' );
define( 'IIHLMS_DB_USER_ACTIVITY', '1.0' );
define( 'IIHLMS_DB_USER_ACTIVITY_META', '1.0' );
define( 'IIHLMS_DB_MEMBERSHIP', '1.0' );
define( 'IIHLMS_DB_PRE_USER', '1.0' );
define( 'IIHLMS_APPLYPAGE_NAME', 'iihlms-apply' );
define( 'IIHLMS_APPLYRESULTPAGE_NAME', 'iihlms-applyresult' );
define( 'IIHLMS_USERPAGE_NAME', 'iihlms-userpage' );
define( 'IIHLMS_USERREGISTRATIONPAGE_NAME', 'iihlms-user-registration' );
define( 'IIHLMS_ACCEPTINGUSERREGISTRATION_NAME', 'iihlms-accepting-user-registration' );
define( 'IIHLMS_ORDERHISTORY_NAME', 'iihlms-orderhistory' );
define( 'IIHLMS_SUBSCRIPTIONCANCELLATION_NAME', 'iihlms-subscription-cancellation' );
define( 'IIHLMS_SUBSCRIPTIONCANCELLATIONRESULT_NAME', 'iihlms-subscription-cancellationresult' );
define( 'IIHLMS_TESTRESULTLIST_NAME', 'iihlms-test-result-list' );
define( 'IIHLMS_TESTRESULT_NAME', 'iihlms-test-result' );
define( 'IIHLMS_TESTRESULT_VIEWANSWERDETAILS_NAME', 'iihlms-test-result-view-answer-details' );
define( 'IIHLMS_PLUGIN_PATH', dirname( __FILE__ ) );
define( 'IIHLMS_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'IIHLMS_PLUGIN_DIRNAME', dirname( plugin_basename( __FILE__ ) ) );
define( 'IIHLMS_PLUGIN_FILENAME', __FILE__ );
define( 'IIHLMS_CONSUMPTION_TAX_INITIAL_VALUE', '10' );

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! class_exists( 'Imaoikiruhito_LMS' ) ) {
	require_once dirname( __FILE__ ) . '/classes/class-imaoikiruhito-lms.php';
	$iihlms = new Imaoikiruhito_LMS();
}
