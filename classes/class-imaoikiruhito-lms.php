<?php
/**
 * Plugin Name: Imaoikiruhito LMS
 * Plugin URI: https://www.imaoikiruhito.com/
 * Description: LMS
 * Author: Imaoikiruhito
 * Author URI: https://www.imaoikiruhito.com/
 * License: GPLv2
 * Text Domain: imaoikiruhitolms
 *
 * @package Imaoikiruhito LMS
 */

/**
 * LMS
 */
final class Imaoikiruhito_LMS {
	/**
	 * 管理者権限
	 *
	 * @var string CAPABILITY_ADMIN
	 */
	const CAPABILITY_ADMIN = 'manage_options';

	/**
	 * プラグインスラッグ
	 *
	 * @var string DOMAIN
	 */
	const DOMAIN = 'imaoikiruhitolms';

	/**
	 * プラグイン名称
	 *
	 * @var string NAME
	 */
	const NAME = 'Imaoikiruhito_LMS';

	/**
	 * プラグインで使用する管理画面一覧
	 *
	 * @var array $plugin_screen_hook_suffix
	 */
	private $plugin_screen_hook_suffix = array();

	/**
	 * 日付フォーマット
	 *
	 * @var string $specify_date_format
	 */
	public $specify_date_format;

	/**
	 * 日付フォーマット
	 *
	 * @var string $specify_date_format_hyphen
	 */
	public $specify_date_format_hyphen;

	/**
	 * 日時フォーマット
	 *
	 * @var string $specify_date_time_format
	 */
	public $specify_date_time_format;

	/**
	 * 日時フォーマット
	 *
	 * @var string $specify_date_time_format_hyphen
	 */
	public $specify_date_time_format_hyphen;

	/**
	 * コンストラクタ
	 *
	 * @return void
	 */
	public function __construct() {
		load_plugin_textdomain( self::DOMAIN );

		register_activation_hook( IIHLMS_PLUGIN_FILENAME, array( $this, 'activate_plugin' ) );

		add_action( 'init', array( $this, 'register_post_type' ) );

		add_action( 'add_meta_boxes', array( $this, 'register_custom_fields' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		add_action( 'admin_notices', array( $this, 'my_admin_notices' ) );
		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		add_action( 'auth_redirect', array( $this, 'disable_admin_pages' ) );
		add_action( 'edit_form_after_title', array( $this, 'iihlms_disp_after_title' ) );
		add_action( 'edit_user_profile', array( $this, 'iihlms_on_load_user_profile' ) );
		add_action( 'edit_user_profile_update', array( $this, 'iihlms_save_user_profile' ) );
		add_action( 'iihlms_accepting_userregistpage_content', array( $this, 'iihlms_accepting_userregistpage_content' ) );
		add_action( 'iihlms_apply_page_content', array( $this, 'iihlms_apply_page_content' ) );
		add_action( 'iihlms_applyresult_page_content', array( $this, 'iihlms_applyresult_page_content' ) );
		add_action( 'iihlms_courses_page_content', array( $this, 'iihlms_courses_page_content' ) );
		add_action( 'iihlms_header_learnmore', array( $this, 'iihlms_header_learnmore' ) );
		add_action( 'iihlms_header_mylearning', array( $this, 'iihlms_header_mylearning' ) );
		add_action( 'iihlms_items_page_content', array( $this, 'iihlms_items_page_content' ) );
		add_action( 'iihlms_lessons_page_content', array( $this, 'iihlms_lessons_page_content' ) );
		add_action( 'iihlms_homepage_content', array( $this, 'iihlms_homepage_content' ) );
		add_action( 'iihlms_orderhistory_content', array( $this, 'iihlms_orderhistory_content' ) );
		add_action( 'iihlms_subscriptioncancellation_content', array( $this, 'iihlms_subscriptioncancellation_content' ) );
		add_action( 'iihlms_subscriptioncancellationresult_content', array( $this, 'iihlms_subscriptioncancellationresult_content' ) );
		add_action( 'iihlms_tests_page_content', array( $this, 'iihlms_tests_page_content' ) );
		add_action( 'iihlms_test_result_content', array( $this, 'iihlms_test_result_content' ) );
		add_action( 'iihlms_test_result_view_answer_details_content', array( $this, 'iihlms_test_result_view_answer_details_content' ) );
		add_action( 'iihlms_test_result_list_content', array( $this, 'iihlms_test_result_list_content' ) );
		add_action( 'iihlms_userpage_content', array( $this, 'iihlms_userpage_content' ) );
		add_action( 'iihlms_userregistpage_content', array( $this, 'iihlms_userregistpage_content' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'custom_login' ) );
		add_action( 'manage_iihlms_test_results_posts_custom_column', array( $this, 'custom_iihlms_test_results_posts_column' ), 10, 2 );
		add_action( 'manage_iihlms_wh_stripe_posts_custom_column', array( $this, 'custom_iihlms_wh_stripe_posts_column' ), 10, 2 );
		add_action( 'manage_iihlms_wh_stripe_sp_posts_custom_column', array( $this, 'custom_iihlms_wh_stripe_sp_posts_column' ), 10, 2 );
		add_action( 'manage_iihlms_wh_paypal_posts_custom_column', array( $this, 'custom_iihlms_wh_paypal_posts_column' ), 10, 2 );
		add_action( 'manage_iihlms_wh_paypal_sp_posts_custom_column', array( $this, 'custom_iihlms_wh_paypal_sp_posts_column' ), 10, 2 );
		add_action( 'personal_options_update', array( $this, 'iihlms_save_user_profile' ) );
		add_action( 'plugins_loaded', array( $this, 'load_plugin' ) );
		add_action( 'pre_get_posts', array( $this, 'add_iihlms_test_results_posts_sort' ), 1 );
		add_action( 'pre_get_posts', array( $this, 'add_iihlms_wh_stripe_posts_sort' ), 1 );
		add_action( 'pre_get_posts', array( $this, 'add_iihlms_wh_stripe_sp_posts_sort' ), 1 );
		add_action( 'pre_get_posts', array( $this, 'add_iihlms_wh_paypal_posts_sort' ), 1 );
		add_action( 'pre_get_posts', array( $this, 'add_iihlms_wh_paypal_sp_posts_sort' ), 1 );
		add_action( 'save_post', array( $this, 'save_custom_fields' ) );
		add_action( 'send_headers', array( $this, 'iihlms_session_start' ) );
		add_action( 'show_user_profile', array( $this, 'iihlms_on_load_user_profile' ) );
		add_action( 'template_redirect', array( $this, 'page_controller' ) );
		add_action( 'user_new_form', array( $this, 'iihlms_new_user_profile' ) );
		add_action( 'user_register', array( $this, 'iihlms_save_user_profile' ) );
		add_action( 'wp', array( $this, 'disable_page_wpautop' ) );
		add_action( 'wp_ajax_check_signup_user_name', array( $this, 'check_signup_user_name_func_ajax' ) );
		add_action( 'wp_ajax_nopriv_check_signup_user_name', array( $this, 'check_signup_user_name_func_ajax' ) );
		add_action( 'wp_ajax_checkout_paypal_func', array( $this, 'checkout_paypal_func_ajax' ) );
		add_action( 'wp_ajax_create_order_paypal_func', array( $this, 'create_order_paypal_func_ajax' ) );
		add_action( 'wp_ajax_create_order_stripe_func', array( $this, 'create_order_stripe_func_ajax' ) );
		add_action( 'wp_ajax_create_subscription_paypal_func', array( $this, 'create_subscription_paypal_func_ajax' ) );
		add_action( 'wp_ajax_create_subscription_stripe_func', array( $this, 'create_subscription_stripe_func_ajax' ) );
		add_action( 'wp_ajax_subscription_approve_paypal_func', array( $this, 'subscription_approve_paypal_func_ajax' ) );
		add_action( 'wp_ajax_update_lesseon_complete', array( $this, 'update_lesseon_complete_func_ajax' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'iihlms_load_scripts' ) );
		add_action( 'wp_head', array( $this, 'get_wp_ajax_root' ) );
		add_action( 'wp_loaded', array( $this, 'iihlms_wp_loaded_functions' ) );

		add_filter( 'email_change_email', array( $this, 'iihlms_change_email_change_email' ), 10, 3 );
		add_filter( 'gettext', array( $this, 'change_login_text' ) );
		add_filter( 'login_title', array( $this, 'iihlms_change_title_tag' ) );
		add_filter( 'login_redirect', array( $this, 'iihlms_admin_login_page' ) );
		add_filter( 'manage_iihlms_test_results_posts_columns', array( $this, 'add_iihlms_test_results_posts_columns' ) );
		add_filter( 'manage_iihlms_wh_stripe_posts_columns', array( $this, 'add_iihlms_wh_stripe_posts_columns' ) );
		add_filter( 'manage_iihlms_wh_stripe_sp_posts_columns', array( $this, 'add_iihlms_wh_stripe_sp_posts_columns' ) );
		add_filter( 'manage_iihlms_wh_paypal_posts_columns', array( $this, 'add_iihlms_wh_paypal_posts_columns' ) );
		add_filter( 'manage_iihlms_wh_paypal_sp_posts_columns', array( $this, 'add_iihlms_wh_paypal_sp_posts_columns' ) );
		add_filter( 'post_row_actions', array( $this, 'lmsjp_hide_quickedit' ), 10, 2 );
		add_filter( 'query_vars', array( $this, 'ext_query_vars' ) );
		add_filter( 'retrieve_password_title', array( $this, 'iihlms_change_mail_password_reset_user_subject' ), 10, 3 );
		add_filter( 'retrieve_password_message', array( $this, 'iihlms_change_mail_password_reset_user_message' ), 10, 4 );
		add_filter( 'show_admin_bar', array( $this, 'hide_admin_bar' ) );
		add_filter( 'manage_edit-iihlms_test_results_sortable_columns', array( $this, 'iihlms_test_results_sortable_columns' ) );
		add_filter( 'manage_edit-iihlms_wh_stripe_sortable_columns', array( $this, 'iihlms_wh_stripe_posts_sortable_columns' ) );
		add_filter( 'manage_edit-iihlms_wh_stripe_sp_sortable_columns', array( $this, 'iihlms_wh_stripe_sp_posts_sortable_columns' ) );
		add_filter( 'manage_edit-iihlms_wh_paypal_sortable_columns', array( $this, 'iihlms_wh_paypal_posts_sortable_columns' ) );
		add_filter( 'manage_edit-iihlms_wh_paypal_sp_sortable_columns', array( $this, 'iihlms_wh_paypal_sp_posts_sortable_columns' ) );
		add_filter( 'wp_insert_post_data', array( $this, 'replace_post_data' ), 99, 2 );
		add_filter( 'wp_insert_post_data', array( $this, 'validate_custom_fields' ), 10, 2 );
		add_filter( 'wp_new_user_notification_email', array( $this, 'iihlms_change_mail_new_user' ), 10, 3 );
		add_filter( 'wp_new_user_notification_email_admin', array( $this, 'iihlms_change_mail_new_user_admin' ), 10, 3 );
		add_filter( 'wp_password_change_notification_email', array( $this, 'iihlms_change_mail_password_reset_done_admin' ), 10, 3 );

		add_shortcode( 'get_logouturl', array( $this, 'get_logouturl' ) );
		add_shortcode( 'iihlms_audio_files', array( $this, 'render_audio_files' ) );
	}

	/**
	 * このプラグインで使用するページおよびメニューを登録
	 * 本プラグイン内で読み込みしたjsやcssは、ここで指定したページに適用される
	 *
	 * @return void
	 */
	public function register_admin_menu() {
		$this->plugin_screen_hook_suffix[] = add_menu_page( esc_html__( '今を生きる人LMS', 'imaoikiruhitolms' ), esc_html__( '今を生きる人LMS', 'imaoikiruhitolms' ), self::CAPABILITY_ADMIN, 'iihlms_dashboard', array( $this, 'dashboard_page' ), 'dashicons-welcome-learn-more' );
		$this->plugin_screen_hook_suffix[] = add_submenu_page( 'iihlms_dashboard', esc_html__( '概要', 'imaoikiruhitolms' ), esc_html__( '概要', 'imaoikiruhitolms' ), self::CAPABILITY_ADMIN, 'iihlms_dashboard', array( $this, 'dashboard_page' ) );
		$this->plugin_screen_hook_suffix[] = add_submenu_page( 'iihlms_dashboard', esc_html__( '講座', 'imaoikiruhitolms' ), esc_html__( '講座', 'imaoikiruhitolms' ), self::CAPABILITY_ADMIN, '/edit.php?post_type=iihlms_items' );
		$this->plugin_screen_hook_suffix[] = add_submenu_page( 'iihlms_dashboard', esc_html__( 'コース', 'imaoikiruhitolms' ), esc_html__( 'コース', 'imaoikiruhitolms' ), self::CAPABILITY_ADMIN, '/edit.php?post_type=iihlms_courses' );
		$this->plugin_screen_hook_suffix[] = add_submenu_page( 'iihlms_dashboard', esc_html__( 'レッスン', 'imaoikiruhitolms' ), esc_html__( 'レッスン', 'imaoikiruhitolms' ), self::CAPABILITY_ADMIN, '/edit.php?post_type=iihlms_lessons' );
		$this->plugin_screen_hook_suffix[] = add_submenu_page( 'iihlms_dashboard', esc_html__( 'システム設定', 'imaoikiruhitolms' ), esc_html__( 'システム設定', 'imaoikiruhitolms' ), self::CAPABILITY_ADMIN, 'iihlms_setting', array( $this, 'setting_page' ) );
		$this->plugin_screen_hook_suffix[] = add_submenu_page( 'iihlms_dashboard', esc_html__( '支払い方法設定', 'imaoikiruhitolms' ), esc_html__( '支払い方法設定', 'imaoikiruhitolms' ), self::CAPABILITY_ADMIN, 'iihlms_payment_method_setting', array( $this, 'payment_method_setting_page' ) );
		$this->plugin_screen_hook_suffix[] = add_submenu_page( 'iihlms_dashboard', esc_html__( '注文一覧', 'imaoikiruhitolms' ), esc_html__( '注文一覧', 'imaoikiruhitolms' ), self::CAPABILITY_ADMIN, 'iihlms_order_list_page', array( $this, 'order_list_page' ) );
		$this->plugin_screen_hook_suffix[] = add_submenu_page( 'iihlms_dashboard', esc_html__( '注文履歴', 'imaoikiruhitolms' ), esc_html__( '注文履歴', 'imaoikiruhitolms' ), self::CAPABILITY_ADMIN, 'iihlms_order_history_list_page', array( $this, 'order_history_list_page' ) );
		$this->plugin_screen_hook_suffix[] = 'profile';
		$this->plugin_screen_hook_suffix[] = 'user-edit';
		$this->plugin_screen_hook_suffix[] = 'user';
		$this->plugin_screen_hook_suffix[] = 'iihlms_items';
		$this->plugin_screen_hook_suffix[] = 'iihlms_courses';
		$this->plugin_screen_hook_suffix[] = 'iihlms_lessons';
		$this->plugin_screen_hook_suffix[] = 'iihlms_tests';
		$this->plugin_screen_hook_suffix[] = 'iihlms_test_results';
		$this->plugin_screen_hook_suffix[] = 'iihlms_test_certs';
		$this->plugin_screen_hook_suffix[] = 'iihlms_wh_paypal';
		$this->plugin_screen_hook_suffix[] = 'iihlms_wh_paypal_sp';
		$this->plugin_screen_hook_suffix[] = 'iihlms_wh_stripe';
		$this->plugin_screen_hook_suffix[] = 'iihlms_wh_stripe_sp';
		do_action( 'iihlms_addition_action_admin_menu' );
	}

	/**
	 * ダッシュボードページ
	 *
	 * @return void
	 */
	public function dashboard_page() {
		include_once IIHLMS_PLUGIN_PATH . '/includes/admin-dashboard.php';
	}

	/**
	 * システム設定ページ
	 *
	 * @return void
	 */
	public function setting_page() {
		include_once IIHLMS_PLUGIN_PATH . '/includes/admin-setting.php';
	}

	/**
	 * PayPal決済設定ページ
	 *
	 * @return void
	 */
	public function paypal_setting_page() {
		include_once IIHLMS_PLUGIN_PATH . '/includes/admin-paypal-setting.php';
	}

	/**
	 * 決済方法設定ページ
	 *
	 * @return void
	 */
	public function payment_method_setting_page() {
		include_once IIHLMS_PLUGIN_PATH . '/includes/admin-payment-method-setting.php';
	}

	/**
	 * 注文一覧ページ
	 *
	 * @return void
	 */
	public function order_list_page() {
		include_once IIHLMS_PLUGIN_PATH . '/includes/admin-order-list.php';
	}

	/**
	 * 注文詳細ページ
	 *
	 * @return void
	 */
	public function order_detail_page() {
		include_once IIHLMS_PLUGIN_PATH . '/includes/admin-order-detail.php';
	}

	/**
	 * 注文履歴詳細ページ
	 *
	 * @return void
	 */
	public function order_history_detail_page() {
		include_once IIHLMS_PLUGIN_PATH . '/includes/admin-order-history-detail.php';
	}

	/**
	 * 注文履歴ページ
	 *
	 * @return void
	 */
	public function order_history_list_page() {
		include_once IIHLMS_PLUGIN_PATH . '/includes/admin-order-history-list.php';
	}

	/**
	 * 注文履歴ページ
	 *
	 * @return void
	 */
	public function subscription_list_page() {
		include_once IIHLMS_PLUGIN_PATH . '/includes/admin-subscription-list.php';
	}

	/**
	 * カスタム投稿タイプ登録
	 *
	 * @return void
	 */
	public function register_post_type() {
		if ( ! current_user_can( self::CAPABILITY_ADMIN ) && is_admin() ) {
			return;
		}

		/**
		 * 講座
		 */
		$labels = array(
			'name'               => esc_html__( '講座', 'imaoikiruhitolms' ),
			'singular_name'      => esc_html__( '講座', 'imaoikiruhitolms' ),
			'menu_name'          => esc_html__( '講座', 'imaoikiruhitolms' ),
			'name_admin_bar'     => esc_html__( '講座', 'imaoikiruhitolms' ),
			'all_itemes'         => esc_html__( '講座', 'imaoikiruhitolms' ),
			'add_new'            => esc_html__( '新規追加', 'imaoikiruhitolms' ),
			'add_new_item'       => esc_html__( '新規講座を追加', 'imaoikiruhitolms' ),
			'edit_item'          => esc_html__( '講座を編集', 'imaoikiruhitolms' ),
			'new_item'           => esc_html__( '新規講座', 'imaoikiruhitolms' ),
			'view_item'          => esc_html__( '講座を表示', 'imaoikiruhitolms' ),
			'search_items'       => esc_html__( '講座を検索', 'imaoikiruhitolms' ),
			'not_found'          => esc_html__( '講座が見つかりませんでした', 'imaoikiruhitolms' ),
			'not_found_in_trash' => esc_html__( 'ゴミ箱内に講座が見つかりませんでした', 'imaoikiruhitolms' ),
		);
		register_post_type(
			'iihlms_items',
			array(
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'has_archive'        => false,
				'menu_position'      => null,
				'capability_type'    => 'page',
				'show_ui'            => true,
				'show_in_menu'       => false,
				'show_in_rest'       => true,
				'supports'           => array( 'title', 'editor', 'thumbnail' ),
			)
		);

		/**
		 * コース
		 */
		$labels = array(
			'name'               => esc_html__( 'コース', 'imaoikiruhitolms' ),
			'singular_name'      => esc_html__( 'コース', 'imaoikiruhitolms' ),
			'menu_name'          => esc_html__( 'コース', 'imaoikiruhitolms' ),
			'name_admin_bar'     => esc_html__( 'コース', 'imaoikiruhitolms' ),
			'all_itemes'         => esc_html__( 'コース', 'imaoikiruhitolms' ),
			'add_new'            => esc_html__( '新規追加', 'imaoikiruhitolms' ),
			'add_new_item'       => esc_html__( '新規コースを追加', 'imaoikiruhitolms' ),
			'edit_item'          => esc_html__( 'コースを編集', 'imaoikiruhitolms' ),
			'new_item'           => esc_html__( '新規コース', 'imaoikiruhitolms' ),
			'view_item'          => esc_html__( 'コースを表示', 'imaoikiruhitolms' ),
			'search_items'       => esc_html__( 'コースを検索', 'imaoikiruhitolms' ),
			'not_found'          => esc_html__( 'コースが見つかりませんでした', 'imaoikiruhitolms' ),
			'not_found_in_trash' => esc_html__( 'ゴミ箱内にコースが見つかりませんでした', 'imaoikiruhitolms' ),
		);
		register_post_type(
			'iihlms_courses',
			array(
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'has_archive'        => false,
				'menu_position'      => 5,
				'capability_type'    => 'page',
				'show_ui'            => true,
				'show_in_menu'       => false,
				'show_in_rest'       => true,
				'supports'           => array( 'title', 'editor', 'thumbnail' ),
			)
		);

		/**
		 * レッスン
		 */
		$labels = array(
			'name'               => esc_html__( 'レッスン', 'imaoikiruhitolms' ),
			'singular_name'      => esc_html__( 'レッスン', 'imaoikiruhitolms' ),
			'menu_name'          => esc_html__( 'レッスン', 'imaoikiruhitolms' ),
			'name_admin_bar'     => esc_html__( 'レッスン', 'imaoikiruhitolms' ),
			'all_itemes'         => esc_html__( 'レッスン', 'imaoikiruhitolms' ),
			'add_new'            => esc_html__( '新規追加', 'imaoikiruhitolms' ),
			'add_new_item'       => esc_html__( '新規レッスンを追加', 'imaoikiruhitolms' ),
			'edit_item'          => esc_html__( 'レッスンを編集', 'imaoikiruhitolms' ),
			'new_item'           => esc_html__( '新規レッスン', 'imaoikiruhitolms' ),
			'view_item'          => esc_html__( 'レッスンを表示', 'imaoikiruhitolms' ),
			'search_items'       => esc_html__( 'レッスンを検索', 'imaoikiruhitolms' ),
			'not_found'          => esc_html__( 'レッスンが見つかりませんでした', 'imaoikiruhitolms' ),
			'not_found_in_trash' => esc_html__( 'ゴミ箱内にレッスンが見つかりませんでした', 'imaoikiruhitolms' ),
		);
		register_post_type(
			'iihlms_lessons',
			array(
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'has_archive'        => false,
				'menu_position'      => 5,
				'capability_type'    => 'page',
				'show_ui'            => true,
				'show_in_menu'       => false,
				'show_in_rest'       => true,
				'supports'           => array( 'title', 'editor' ),
			)
		);

		/**
		 * テスト
		 */
		$labels = array(
			'name'               => esc_html__( 'テスト', 'imaoikiruhitolms' ),
			'singular_name'      => esc_html__( 'テスト', 'imaoikiruhitolms' ),
			'menu_name'          => esc_html__( 'テスト', 'imaoikiruhitolms' ),
			'name_admin_bar'     => esc_html__( 'テスト', 'imaoikiruhitolms' ),
			'all_itemes'         => esc_html__( 'テスト', 'imaoikiruhitolms' ),
			'add_new'            => esc_html__( '新規追加', 'imaoikiruhitolms' ),
			'add_new_item'       => esc_html__( '新規テストを追加', 'imaoikiruhitolms' ),
			'edit_item'          => esc_html__( 'テストを編集', 'imaoikiruhitolms' ),
			'new_item'           => esc_html__( '新規テスト', 'imaoikiruhitolms' ),
			'view_item'          => esc_html__( 'テストを表示', 'imaoikiruhitolms' ),
			'search_items'       => esc_html__( 'テストを検索', 'imaoikiruhitolms' ),
			'not_found'          => esc_html__( 'テストが見つかりませんでした', 'imaoikiruhitolms' ),
			'not_found_in_trash' => esc_html__( 'ゴミ箱内にテストが見つかりませんでした', 'imaoikiruhitolms' ),
		);
		register_post_type(
			'iihlms_tests',
			array(
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'has_archive'        => false,
				'menu_position'      => 5,
				'capability_type'    => 'page',
				'show_ui'            => true,
				'show_in_menu'       => false,
				'show_in_rest'       => true,
				'supports'           => array( 'title', 'editor', 'thumbnail' ),
			)
		);

		/**
		 * テスト結果
		 */
		$labels = array(
			'name'               => esc_html__( 'テスト結果', 'imaoikiruhitolms' ),
			'singular_name'      => esc_html__( 'テスト結果', 'imaoikiruhitolms' ),
			'menu_name'          => esc_html__( 'テスト結果', 'imaoikiruhitolms' ),
			'name_admin_bar'     => esc_html__( 'テスト結果', 'imaoikiruhitolms' ),
			'all_itemes'         => esc_html__( 'テスト結果', 'imaoikiruhitolms' ),
			'add_new'            => esc_html__( '新規追加', 'imaoikiruhitolms' ),
			'add_new_item'       => esc_html__( '新規テスト結果を追加', 'imaoikiruhitolms' ),
			'edit_item'          => esc_html__( 'テスト結果を編集', 'imaoikiruhitolms' ),
			'new_item'           => esc_html__( '新規テスト結果', 'imaoikiruhitolms' ),
			'view_item'          => esc_html__( 'テスト結果を表示', 'imaoikiruhitolms' ),
			'search_items'       => esc_html__( 'テスト結果を検索', 'imaoikiruhitolms' ),
			'not_found'          => esc_html__( 'テスト結果が見つかりませんでした', 'imaoikiruhitolms' ),
			'not_found_in_trash' => esc_html__( 'ゴミ箱内にテスト結果が見つかりませんでした', 'imaoikiruhitolms' ),
		);
		register_post_type(
			'iihlms_test_results',
			array(
				'labels'              => $labels,
				'public'              => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'has_archive'         => false,
				'menu_position'       => 5,
				'capability_type'     => 'page',
				'show_ui'             => true,
				'show_in_menu'        => false,
				'supports'            => array( 'title' ),
			)
		);

		/**
		 * 証明書
		 */
		$labels = array(
			'name'               => esc_html__( '証明書', 'imaoikiruhitolms' ),
			'singular_name'      => esc_html__( '証明書', 'imaoikiruhitolms' ),
			'menu_name'          => esc_html__( '証明書', 'imaoikiruhitolms' ),
			'name_admin_bar'     => esc_html__( '証明書', 'imaoikiruhitolms' ),
			'all_itemes'         => esc_html__( '証明書', 'imaoikiruhitolms' ),
			'add_new'            => esc_html__( '新規追加', 'imaoikiruhitolms' ),
			'add_new_item'       => esc_html__( '新規証明書を追加', 'imaoikiruhitolms' ),
			'edit_item'          => esc_html__( '証明書を編集', 'imaoikiruhitolms' ),
			'new_item'           => esc_html__( '新規証明書', 'imaoikiruhitolms' ),
			'view_item'          => esc_html__( '証明書を表示', 'imaoikiruhitolms' ),
			'search_items'       => esc_html__( '証明書を検索', 'imaoikiruhitolms' ),
			'not_found'          => esc_html__( '証明書が見つかりませんでした', 'imaoikiruhitolms' ),
			'not_found_in_trash' => esc_html__( 'ゴミ箱内に証明書が見つかりませんでした', 'imaoikiruhitolms' ),
		);
		register_post_type(
			'iihlms_test_certs',
			array(
				'labels'              => $labels,
				'public'              => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'has_archive'         => false,
				'menu_position'       => 5,
				'capability_type'     => 'page',
				'show_ui'             => true,
				'show_in_menu'        => false,
				'show_in_rest'        => true,
				'supports'            => array( 'title', 'editor', 'thumbnail' ),
			)
		);

		/**
		 * PayPal Webhooks
		 */
		$labels = array(
			'name'               => esc_html__( 'PayPal Webhooks', 'imaoikiruhitolms' ),
			'singular_name'      => esc_html__( 'PayPal Webhooks', 'imaoikiruhitolms' ),
			'menu_name'          => esc_html__( 'PayPal Webhooks', 'imaoikiruhitolms' ),
			'name_admin_bar'     => esc_html__( 'PayPal Webhooks', 'imaoikiruhitolms' ),
			'all_itemes'         => esc_html__( 'PayPal Webhooks', 'imaoikiruhitolms' ),
			'add_new'            => esc_html__( '新規追加', 'imaoikiruhitolms' ),
			'add_new_item'       => esc_html__( '新規PayPal Webhookを追加', 'imaoikiruhitolms' ),
			'edit_item'          => esc_html__( 'PayPal Webhookを編集', 'imaoikiruhitolms' ),
			'new_item'           => esc_html__( '新規PayPal Webhook', 'imaoikiruhitolms' ),
			'view_item'          => esc_html__( 'PayPal Webhookを表示', 'imaoikiruhitolms' ),
			'search_items'       => esc_html__( 'PayPal Webhookを検索', 'imaoikiruhitolms' ),
			'not_found'          => esc_html__( 'PayPal Webhookが見つかりませんでした', 'imaoikiruhitolms' ),
			'not_found_in_trash' => esc_html__( 'ゴミ箱内にPayPal Webhookが見つかりませんでした', 'imaoikiruhitolms' ),
		);
		register_post_type(
			'iihlms_wh_paypal',
			array(
				'labels'              => $labels,
				'public'              => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'has_archive'         => false,
				'menu_position'       => null,
				'capability_type'     => 'page',
				'show_ui'             => true,
				'show_in_menu'        => false,
				'supports'            => array( 'title' ),
			)
		);

		/**
		 * PayPal 定期購入ログ
		 */
		$labels = array(
			'name'               => esc_html__( 'PayPal 定期購入ログ', 'imaoikiruhitolms' ),
			'singular_name'      => esc_html__( 'PayPal 定期購入ログ', 'imaoikiruhitolms' ),
			'menu_name'          => esc_html__( 'PayPal 定期購入ログ', 'imaoikiruhitolms' ),
			'name_admin_bar'     => esc_html__( 'PayPal 定期購入ログ', 'imaoikiruhitolms' ),
			'all_itemes'         => esc_html__( 'PayPal 定期購入ログ', 'imaoikiruhitolms' ),
			'add_new'            => esc_html__( '新規追加', 'imaoikiruhitolms' ),
			'add_new_item'       => esc_html__( '新規PayPal 定期購入ログを追加', 'imaoikiruhitolms' ),
			'edit_item'          => esc_html__( 'PayPal 定期購入ログを編集', 'imaoikiruhitolms' ),
			'new_item'           => esc_html__( '新規PayPal 定期購入ログ', 'imaoikiruhitolms' ),
			'view_item'          => esc_html__( 'PayPal 定期購入ログを表示', 'imaoikiruhitolms' ),
			'search_items'       => esc_html__( 'PayPal 定期購入ログを検索', 'imaoikiruhitolms' ),
			'not_found'          => esc_html__( 'PayPal 定期購入ログが見つかりませんでした', 'imaoikiruhitolms' ),
			'not_found_in_trash' => esc_html__( 'ゴミ箱内にPayPal 定期購入ログが見つかりませんでした', 'imaoikiruhitolms' ),
		);
		register_post_type(
			'iihlms_wh_paypal_sp',
			array(
				'labels'              => $labels,
				'public'              => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'has_archive'         => false,
				'menu_position'       => null,
				'capability_type'     => 'page',
				'show_ui'             => true,
				'show_in_menu'        => false,
				'supports'            => array( 'title' ),
			)
		);

		/**
		 * Stripe Webhooks
		 */
		$labels = array(
			'name'               => esc_html__( 'Stripe Webhooks', 'imaoikiruhitolms' ),
			'singular_name'      => esc_html__( 'Stripe Webhooks', 'imaoikiruhitolms' ),
			'menu_name'          => esc_html__( 'Stripe Webhooks', 'imaoikiruhitolms' ),
			'name_admin_bar'     => esc_html__( 'Stripe Webhooks', 'imaoikiruhitolms' ),
			'all_itemes'         => esc_html__( 'Stripe Webhooks', 'imaoikiruhitolms' ),
			'add_new'            => esc_html__( '新規追加', 'imaoikiruhitolms' ),
			'add_new_item'       => esc_html__( '新規Stripe Webhookを追加', 'imaoikiruhitolms' ),
			'edit_item'          => esc_html__( 'Stripe Webhookを編集', 'imaoikiruhitolms' ),
			'new_item'           => esc_html__( '新規Stripe Webhook', 'imaoikiruhitolms' ),
			'view_item'          => esc_html__( 'Stripe Webhookを表示', 'imaoikiruhitolms' ),
			'search_items'       => esc_html__( 'Stripe Webhookを検索', 'imaoikiruhitolms' ),
			'not_found'          => esc_html__( 'Stripe Webhookが見つかりませんでした', 'imaoikiruhitolms' ),
			'not_found_in_trash' => esc_html__( 'ゴミ箱内にStripe Webhookが見つかりませんでした', 'imaoikiruhitolms' ),
		);
		register_post_type(
			'iihlms_wh_stripe',
			array(
				'labels'              => $labels,
				'public'              => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'has_archive'         => false,
				'menu_position'       => null,
				'capability_type'     => 'page',
				'show_ui'             => true,
				'show_in_menu'        => false,
				'supports'            => array( 'title' ),
			)
		);

		/**
		 * Stripe 定期購入ログ
		 */
		$labels = array(
			'name'               => esc_html__( 'Stripe 定期購入ログ', 'imaoikiruhitolms' ),
			'singular_name'      => esc_html__( 'Stripe 定期購入ログ', 'imaoikiruhitolms' ),
			'menu_name'          => esc_html__( 'Stripe 定期購入ログ', 'imaoikiruhitolms' ),
			'name_admin_bar'     => esc_html__( 'Stripe 定期購入ログ', 'imaoikiruhitolms' ),
			'all_itemes'         => esc_html__( 'Stripe 定期購入ログ', 'imaoikiruhitolms' ),
			'add_new'            => esc_html__( '新規追加', 'imaoikiruhitolms' ),
			'add_new_item'       => esc_html__( '新規Stripe 定期購入ログを追加', 'imaoikiruhitolms' ),
			'edit_item'          => esc_html__( 'Stripe 定期購入ログを編集', 'imaoikiruhitolms' ),
			'new_item'           => esc_html__( '新規Stripe 定期購入ログ', 'imaoikiruhitolms' ),
			'view_item'          => esc_html__( 'Stripe 定期購入ログを表示', 'imaoikiruhitolms' ),
			'search_items'       => esc_html__( 'Stripe 定期購入ログを検索', 'imaoikiruhitolms' ),
			'not_found'          => esc_html__( 'Stripe 定期購入ログが見つかりませんでした', 'imaoikiruhitolms' ),
			'not_found_in_trash' => esc_html__( 'ゴミ箱内にStripe 定期購入ログが見つかりませんでした', 'imaoikiruhitolms' ),
		);
		register_post_type(
			'iihlms_wh_stripe_sp',
			array(
				'labels'              => $labels,
				'public'              => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'has_archive'         => false,
				'menu_position'       => null,
				'capability_type'     => 'page',
				'show_ui'             => true,
				'show_in_menu'        => false,
				'supports'            => array( 'title' ),
			)
		);
	}

	/**
	 * カスタムフィールド追加
	 *
	 * @return void
	 */
	public function register_custom_fields() {
		add_meta_box( 'lesson_setting_6', esc_html__( '動画URL', 'imaoikiruhitolms' ), array( $this, 'insert_lesson_fields_6' ), 'iihlms_lessons', 'normal' );
		add_meta_box( 'lesson_setting_5', esc_html__( '音声', 'imaoikiruhitolms' ), array( $this, 'insert_lesson_fields_5' ), 'iihlms_lessons', 'normal' );
		add_meta_box( 'lesson_setting_1', esc_html__( 'レッスンの補足', 'imaoikiruhitolms' ), array( $this, 'insert_lesson_fields_1' ), 'iihlms_lessons', 'normal' );
		add_meta_box( 'lesson_setting_4', esc_html__( '添付資料', 'imaoikiruhitolms' ), array( $this, 'insert_lesson_fields_4' ), 'iihlms_lessons', 'normal' );
		add_meta_box( 'lesson_setting_2', esc_html__( '教材', 'imaoikiruhitolms' ), array( $this, 'insert_lesson_fields_2' ), 'iihlms_lessons', 'normal' );
		if ( defined( 'IIHLMS_ADDITION' ) ) {
			if ( is_callable( '\IIHLMS_A\\Imaoikiruhito_LMS_License::is_license_valid' ) && \IIHLMS_A\Imaoikiruhito_LMS_License::is_license_valid() ) {
				add_meta_box( 'lesson_setting_3', esc_html__( 'テスト', 'imaoikiruhitolms' ), array( $this, 'insert_lesson_fields_3' ), 'iihlms_lessons', 'normal' );
			}
		}
		add_meta_box( 'course_setting_1', esc_html__( 'コースとレッスンの関連', 'imaoikiruhitolms' ), array( $this, 'insert_course_fields_1' ), 'iihlms_courses', 'normal' );
		add_meta_box( 'course_setting_2', esc_html__( 'コースの補足', 'imaoikiruhitolms' ), array( $this, 'insert_course_fields_2' ), 'iihlms_courses', 'normal' );
		add_meta_box( 'course_setting_8', esc_html__( '添付資料', 'imaoikiruhitolms' ), array( $this, 'insert_course_fields_8' ), 'iihlms_courses', 'normal' );
		add_meta_box( 'course_setting_3', esc_html__( '教材', 'imaoikiruhitolms' ), array( $this, 'insert_course_fields_3' ), 'iihlms_courses', 'normal' );
		add_meta_box( 'course_setting_4', esc_html__( 'コースへのアクセス許可', 'imaoikiruhitolms' ), array( $this, 'insert_course_fields_4' ), 'iihlms_courses', 'normal' );
		if ( defined( 'IIHLMS_ADDITION' ) && is_plugin_active( 'bbpress/bbpress.php' ) ) {
			if ( is_callable( '\IIHLMS_A\\Imaoikiruhito_LMS_License::is_license_valid' ) && \IIHLMS_A\Imaoikiruhito_LMS_License::is_license_valid() ) {
				add_meta_box( 'course_setting_5', esc_html__( 'フォーラムの追加', 'imaoikiruhitolms' ), array( $this, 'insert_course_fields_5' ), 'iihlms_courses', 'normal' );
			}
		}
		if ( defined( 'IIHLMS_ADDITION' ) ) {
			if ( is_callable( '\IIHLMS_A\\Imaoikiruhito_LMS_License::is_license_valid' ) && \IIHLMS_A\Imaoikiruhito_LMS_License::is_license_valid() ) {
				add_meta_box( 'course_setting_6', esc_html__( 'テスト', 'imaoikiruhitolms' ), array( $this, 'insert_course_fields_6' ), 'iihlms_courses', 'normal' );
			}
		}
		add_meta_box( 'course_setting_7', esc_html__( 'レッスン一覧', 'imaoikiruhitolms' ), array( $this, 'insert_course_fields_7' ), 'iihlms_courses', 'normal' );
		add_meta_box( 'item_setting_1', esc_html__( '支払い方法', 'imaoikiruhitolms' ), array( $this, 'insert_item_fields_1' ), 'iihlms_items', 'normal' );
		add_meta_box( 'item_setting_1a', esc_html__( '支払い設定(一括)', 'imaoikiruhitolms' ), array( $this, 'insert_item_fields_1a' ), 'iihlms_items', 'normal' );
		if ( defined( 'IIHLMS_ADDITION' ) ) {
			if ( is_callable( '\IIHLMS_A\\Imaoikiruhito_LMS_License::is_license_valid' ) && \IIHLMS_A\Imaoikiruhito_LMS_License::is_license_valid() ) {
				add_meta_box( 'item_setting_1b', esc_html__( '支払い設定(サブスクリプション)', 'imaoikiruhitolms' ), array( $this, 'insert_item_fields_1b' ), 'iihlms_items', 'normal' );
				add_meta_box( 'item_setting_1c', esc_html__( '決済設定状況(サブスクリプション)', 'imaoikiruhitolms' ), array( $this, 'insert_item_fields_1c' ), 'iihlms_items', 'normal' );
			}
		}
		add_meta_box( 'item_setting_2', esc_html__( '講座とコースの関連', 'imaoikiruhitolms' ), array( $this, 'insert_item_fields_2' ), 'iihlms_items', 'normal' );
		add_meta_box( 'item_setting_3', esc_html__( '講座を閲覧・購入できるユーザー', 'imaoikiruhitolms' ), array( $this, 'insert_item_fields_3' ), 'iihlms_items', 'normal' );
		add_meta_box( 'item_setting_4', esc_html__( '購入後の処理', 'imaoikiruhitolms' ), array( $this, 'insert_item_fields_4' ), 'iihlms_items', 'normal' );
		add_meta_box( 'item_setting_8', esc_html__( '購入時のメール', 'imaoikiruhitolms' ), array( $this, 'insert_item_fields_8' ), 'iihlms_items', 'normal' );
		add_meta_box( 'item_setting_5', esc_html__( '前提条件（コース）', 'imaoikiruhitolms' ), array( $this, 'insert_item_fields_5' ), 'iihlms_items', 'normal' );
		if ( defined( 'IIHLMS_ADDITION' ) ) {
			if ( is_callable( '\IIHLMS_A\\Imaoikiruhito_LMS_License::is_license_valid' ) && \IIHLMS_A\Imaoikiruhito_LMS_License::is_license_valid() ) {
				add_meta_box( 'item_setting_6', esc_html__( 'テスト', 'imaoikiruhitolms' ), array( $this, 'insert_item_fields_6' ), 'iihlms_items', 'normal' );
				add_meta_box( 'item_setting_7', esc_html__( '前提条件（テスト）', 'imaoikiruhitolms' ), array( $this, 'insert_item_fields_7' ), 'iihlms_items', 'normal' );
				add_meta_box( 'test_setting_1', esc_html__( 'テスト設定', 'imaoikiruhitolms' ), array( $this, 'insert_test_fields_1' ), 'iihlms_tests', 'normal' );
				add_meta_box( 'test_setting_2', esc_html__( '設問・解答', 'imaoikiruhitolms' ), array( $this, 'insert_test_fields_2' ), 'iihlms_tests', 'normal' );
				add_meta_box( 'test_setting_3', esc_html__( '証明書', 'imaoikiruhitolms' ), array( $this, 'insert_test_fields_3' ), 'iihlms_tests', 'normal' );
				add_meta_box( 'test_result_setting_1', esc_html__( 'テスト詳細', 'imaoikiruhitolms' ), array( $this, 'insert_test_result_fields_1' ), 'iihlms_test_results', 'normal' );
				add_meta_box( 'cert_setting_1', esc_html__( '証明書設定', 'imaoikiruhitolms' ), array( $this, 'insert_cert_fields_1' ), 'iihlms_test_certs', 'normal' );
				add_meta_box( 'cert_setting_2', esc_html__( '証明書設定（ビジュアルエディタ）', 'imaoikiruhitolms' ), array( $this, 'insert_cert_fields_2' ), 'iihlms_test_certs', 'normal' );
				add_meta_box( 'webhook_paypal_setting_1', esc_html__( 'Webhook', 'imaoikiruhitolms' ), array( $this, 'insert_webhook_paypal_fields_1' ), 'iihlms_wh_paypal', 'normal' );
				add_meta_box( 'webhook_stripe_setting_1', esc_html__( 'Webhook', 'imaoikiruhitolms' ), array( $this, 'insert_webhook_stripe_fields_1' ), 'iihlms_wh_stripe', 'normal' );
				add_meta_box( 'webhook_paypal_subscription_payment_setting_1', esc_html__( 'PayPal 定期購入ログ', 'imaoikiruhitolms' ), array( $this, 'insert_webhook_paypal_subscription_payment_fields_1' ), 'iihlms_wh_paypal_sp', 'normal' );
				add_meta_box( 'webhook_stripe_subscription_payment_setting_1', esc_html__( 'Stripe 定期購入ログ', 'imaoikiruhitolms' ), array( $this, 'insert_webhook_stripe_subscription_payment_fields_1' ), 'iihlms_wh_stripe_sp', 'normal' );
			}
		}
	}

	/**
	 * レッスンのメタボックス
	 *
	 * @return void
	 */
	public function insert_lesson_fields_1() {
		global $post;

		wp_nonce_field( 'iihlms-lessons-csrf-action', 'iihlms-lessons-csrf' );
		$id                        = get_the_ID();
		$content                   = '';
		$iihlms_lesson_explanation = get_post_meta( $post->ID, 'iihlms_lesson_explanation', true );
		if ( $iihlms_lesson_explanation ) {
			$content = $iihlms_lesson_explanation;
		}
		$settings = array();
		wp_editor( $content, 'iihlms_lesson_explanation_editor', $settings );
	}
	/**
	 * レッスンのメタボックス
	 *
	 * @return void
	 */
	public function insert_lesson_fields_2() {
		global $post;

		$id                      = get_the_ID();
		$content                 = '';
		$iihlms_lesson_materials = get_post_meta( $post->ID, 'iihlms_lesson_materials', true );
		if ( $iihlms_lesson_materials ) {
			$content = $iihlms_lesson_materials;
		}
		$settings = array();
		wp_editor( $content, 'iihlms_lesson_materials_editor', $settings );
	}
	/**
	 * レッスンのメタボックス
	 *
	 * @return void
	 */
	public function insert_lesson_fields_3() {
		echo esc_html( apply_filters( 'iihlms_addition_insert_lesson_fields_3', '' ) );
	}
	/**
	 * レッスンのメタボックス
	 *
	 * @return void
	 */
	public function insert_lesson_fields_4() {
		global $post;

		$pdfs = get_post_meta( $post->ID, 'iihlms_lesson_pdfs', true );
		if ( empty( $pdfs ) ) {
			$pdfs = array();
		}

		echo '<div id="lesson_pdfs_container">';
		foreach ( $pdfs as $pdf ) {
			echo '<div class="lesson_pdf_row">';
			echo '<span class="dashicons dashicons-menu handle"></span>';
			echo '<input type="text" class="lesson_pdf_name" name="lesson_pdf_names[]" value="' . esc_attr( $pdf['name'] ) . '" size="20" placeholder="表示名" />';
			echo '<input type="text" class="lesson_pdf" name="lesson_pdfs[]" value="' . esc_attr( $pdf['url'] ) . '" size="25" placeholder="添付資料 URL" />';
			echo '<input type="button" class="lesson_pdf_button button" value="' . esc_html__( '添付資料を選択', 'imaoikiruhitolms' ) . '" />';
			echo '<input type="button" class="lesson_pdf_remove_button button" value="' . esc_html__( '削除', 'imaoikiruhitolms' ) . '" />';
			echo '</div>';
		}
		echo '</div>';
		echo '<input type="button" id="add_lesson_pdf_button" class="button" value="' . esc_html__( '添付資料を追加', 'imaoikiruhitolms' ) . '" />';

		wp_nonce_field( 'lesson_pdf_nonce_action', 'lesson_pdf_nonce' );

		echo '<script>
		jQuery(document).ready(function($) {
			console.log("JavaScript is loaded");

			function addMediaUploader(button, input) {
				console.log("addMediaUploader is called");
				var frame = wp.media({
					title: "添付資料を選択",
					button: {
						text: "この添付資料を使用"
					},
					multiple: false
				});
				frame.on("select", function() {
					var attachment = frame.state().get("selection").first().toJSON();
					input.val(attachment.url);
				});
				frame.open();
			}

			$(document).on("click", ".lesson_pdf_button", function(e) {
				e.preventDefault();
				addMediaUploader($(this), $(this).prev(".lesson_pdf"));
			});

			$("#add_lesson_pdf_button").click(function(e) {
				e.preventDefault();
				$("#lesson_pdfs_container").append(\'<div class="lesson_pdf_row"><span class="dashicons dashicons-menu handle"></span><input type="text" class="lesson_pdf_name" name="lesson_pdf_names[]" size="20" placeholder="表示名" /><input type="text" class="lesson_pdf" name="lesson_pdfs[]" size="25" placeholder="添付資料 URL" /><input type="button" class="lesson_pdf_button button" value="添付資料を選択" /><input type="button" class="lesson_pdf_remove_button button" value="削除" /></div>\');
			});

			$(document).on("click", ".lesson_pdf_remove_button", function(e) {
				e.preventDefault();
				$(this).parent(".lesson_pdf_row").remove();
			});

			// ソート機能を有効にする
			$("#lesson_pdfs_container").sortable({
				handle: ".handle",
				placeholder: "sortable-placeholder",
				forcePlaceholderSize: true
			});
		});
		</script>';
		echo '<style>
		#lesson_pdfs_container {
			margin-top: 10px;
		}
		.lesson_pdf_row {
			margin-bottom: 10px;
			padding: 10px;
			border: 1px solid #ccc;
			background-color: #f9f9f9;
			cursor: move;
			display: flex;
			flex-wrap: wrap;
			align-items: center;
		}
		.lesson_pdf_row .handle {
			cursor: move;
			margin-right: 10px;
		}
		.lesson_pdf_row input[type="button"] {
			margin-left: 10px; /* ボタン間の余白を設定 */
		}
		.lesson_pdf_row input[type="text"] {
			flex: 1;
			min-width: 150px;
			margin-bottom: 5px;
		}
		.sortable-placeholder {
			border: 1px dashed #ccc;
			background-color: #f0f0f0;
			height: 3em;
		}
		.lesson-pdfs {
			margin-top: 20px;
		}
		.lesson-pdf {
			margin-bottom: 10px;
		}
		.lesson-pdf a {
			color: #0073aa;
			text-decoration: none;
		}
		.lesson-pdf a:hover {
			text-decoration: underline;
		}
		@media (max-width: 600px) {
			.lesson_pdf_row input[type="text"], .lesson_pdf_row input[type="button"] {
				width: 100%;
				margin-left: 0;
			}
			.lesson_pdf_row {
				flex-direction: column;
				align-items: stretch;
			}
			.lesson_pdf_row .handle {
				align-self: flex-start;
			}
		}
		</style>';
	}
	/**
	 * レッスンのメタボックス
	 *
	 * @return void
	 */
	public function insert_lesson_fields_5() {
		global $post;

		$audio_files = get_post_meta( $post->ID, 'iihlms_audio_files', true );
		if ( empty( $audio_files ) ) {
			$audio_files = array();
		}

		echo '<div id="audio_files_container">';
		foreach ( $audio_files as $index => $audio ) {
			echo '<div class="audio_file_row">';
			echo '<input type="text" class="audio_file_name" name="audio_file_names[]" value="' . esc_attr( $audio['name'] ) . '" placeholder="ファイル名" size="20" />';
			echo '<input type="text" class="audio_file_url" name="audio_file_urls[]" value="' . esc_url( $audio['url'] ) . '" placeholder="Dropbox URL" size="40" />';
			echo '<label><input type="checkbox" class="audio_file_downloadable" name="audio_file_downloadables[' . esc_attr( $index ) . ']" ' . checked( isset( $audio['downloadable'] ) ? $audio['downloadable'] : false, true, false ) . ' />' . esc_html__( 'ダウンロードを禁止する', 'imaoikiruhitolms' ) . '</label>';
			echo '<button type="button" class="button audio_file_remove_button">削除</button>';
			echo '</div>';
		}
		echo '</div>';
		echo '<button type="button" id="add_audio_file_button" class="button">' . esc_html__( '音声ファイルを追加', 'imaoikiruhitolms' ) . '</button>';

		echo '<p class="description">' . esc_html__( '任意のディレクトリに置いたファイルのURLか、Dropboxの共有URLを入力できます。Dropboxの場合、「アクセスできるユーザー」は「リンクを知る全ユーザー」、「ダウンロードを無効にする」は「オフ」にしてください。', 'imaoikiruhitolms' ) . '</p>';

		echo '<style>
		.audio_file_row {
			margin-bottom: 10px;
		}
		.audio_file_row input[type="text"] {
			margin-right: 10px;
		}
		.audio_file_row label {
			margin-right: 10px;
		}
		</style>';

		echo '<script>
		jQuery(document).ready(function( $ ) {
			let fileIndex = ' . count( $audio_files ) . ';
			$( "#add_audio_file_button" ).click( function() {
				$( "#audio_files_container" ).append( \'<div class="audio_file_row"><input type="text" class="audio_file_name" name="audio_file_names[]" placeholder="ファイル名" size="20" /><input type="text" class="audio_file_url" name="audio_file_urls[]" placeholder="Dropbox URL" size="40" /><label><input type="checkbox" class="audio_file_downloadable" name="audio_file_downloadables[\' + fileIndex + \']" /> ダウンロードを禁止する</label><button type="button" class="button audio_file_remove_button">削除</button></div>\' );
				fileIndex++;
			});

			$( document ).on( "click", ".audio_file_remove_button", function() {
				$( this ).closest( ".audio_file_row" ).remove();
			});
		});
		</script>';
	}
	/**
	 * レッスンのメタボックス
	 *
	 * @return void
	 */
	public function insert_lesson_fields_6() {
		global $post;

		$iihlms_video_url = get_post_meta( $post->ID, 'iihlms_video_url', true );
		echo '<input type="text" id="iihlms_video_url" name="iihlms_video_url" class="iihlms-custom-text" value="' . esc_attr( $iihlms_video_url ) . '" />';
		echo '<p class="description">' . esc_html__( 'YoutubeのURLか、Dropboxの共有URLを入力できます。Dropboxの場合、「アクセスできるユーザー」は「リンクを知る全ユーザー」、「ダウンロードを無効にする」は「オフ」にしてください。', 'imaoikiruhitolms' ) . '</p>';
		echo '<p class="description">' . esc_html__( 'こちらにURLを入力した場合、ビジュアルエディタに入力した内容は使用されません。', 'imaoikiruhitolms' ) . '</p>';
	}
	/**
	 * PayPal Webhookのメタボックス
	 *
	 * @return void
	 */
	public function insert_webhook_paypal_fields_1() {
		echo esc_html( apply_filters( 'iihlms_addition_insert_webhook_paypal_fields_1', '' ) );
	}
	/**
	 * Stripe Webhookのメタボックス
	 *
	 * @return void
	 */
	public function insert_webhook_stripe_fields_1() {
		echo esc_html( apply_filters( 'iihlms_addition_insert_webhook_stripe_fields_1', '' ) );
	}
	/**
	 * PayPal 定期購入ログのメタボックス
	 *
	 * @return void
	 */
	public function insert_webhook_paypal_subscription_payment_fields_1() {
		echo esc_html( apply_filters( 'iihlms_addition_insert_webhook_paypal_subscription_payment_fields_1', '' ) );
	}
	/**
	 * Stripe 定期購入ログのメタボックス
	 *
	 * @return void
	 */
	public function insert_webhook_stripe_subscription_payment_fields_1() {
		echo esc_html( apply_filters( 'iihlms_addition_insert_webhook_stripe_subscription_payment_fields_1', '' ) );
	}

	/**
	 * コースのメタボックス
	 *
	 * @return void
	 */
	public function insert_course_fields_1() {
		global $post;
		global $wpdb;

		// 関連しているレッスン.
		$iihlms_course_relation      = get_post_meta( $post->ID, 'iihlms_course_relation', true );
		$iihlms_course_relation      = isset( $iihlms_course_relation ) ? (array) $iihlms_course_relation : array();
		$iihlms_course_relation_flip = array_flip( $iihlms_course_relation );

		$option_related         = '';
		$option_related_not     = '';
		$sortable_course_lesson = '';
		wp_nonce_field( 'iihlms-courses-csrf-action', 'iihlms-courses-csrf' );
		echo '<p>' . esc_html__( 'このコースに関連するレッスンを選択してください', 'imaoikiruhitolms' ) . '</p>';
		?>
		<table class="iihlms-select-multi-table">
		<tr>
			<td class="iihlms-select-multi-left">
			<?php
			echo '<input id="target-text-left" placeholder="' . esc_html__( '関連していないレッスンを検索', 'imaoikiruhitolms' ) . '" type="text" class="iihlms-select-search">';
			?>
				<select id="target-options-left" class="iihlms-select-mutiple" name="iihlms-select-mutiple-left" size="10" multiple="multiple">
					<?php
					// 関連していないレッスン一覧.
					$args     = array(
						'post_type'      => 'iihlms_lessons',
						'posts_per_page' => -1,
						'post_status'    => 'publish',
						'orderby'        => 'date',
						'order'          => 'DESC',
					);
					$my_posts = get_posts( $args );
					foreach ( $my_posts as $postdata ) {
						setup_postdata( $postdata );
						if ( ! isset( $iihlms_course_relation_flip[ $postdata->ID ] ) ) {
							echo '<option value="';
							echo esc_attr( $postdata->ID );
							echo '">';
							echo esc_html( get_the_title( $postdata ) );
							echo '</option>';
						}
					}
					wp_reset_postdata();
					?>
				</select>
			</td>
			<td class="iihlms-select-multi-middle">
				<input type="button" name="rightlesson" value="≫" /><br /><br />
				<input type="button" name="leftlesson" value="≪" />
			</td>
			<td class="iihlms-select-multi-right">
			<?php
			echo '<input id="target-text-right" placeholder="' . esc_html__( '関連しているレッスンを検索', 'imaoikiruhitolms' ) . '" type="text" class="iihlms-select-search">';
			?>
				<select id="target-options-right" class="iihlms-select-mutiple" name="iihlms-select-mutiple-right" size="10" multiple="multiple">
					<?php
					// 関連しているレッスン一覧.
					if ( ! empty( $iihlms_course_relation ) ) {
						$args     = array(
							'post_type'           => 'iihlms_lessons',
							'posts_per_page'      => -1,
							'post_status'         => 'publish',
							'post__in'            => $iihlms_course_relation,
							'ignore_sticky_posts' => 1,
							'orderby'             => 'post__in',
						);
						$my_posts = get_posts( $args );
						foreach ( $my_posts as $postdata ) {
							setup_postdata( $postdata );
							echo '<option value="';
							echo esc_attr( $postdata->ID );
							echo '">';
							echo esc_html( get_the_title( $postdata->ID ) );
							echo '</option>';
						}
						wp_reset_postdata();
					}
					?>
			</select>
			</td>
		</tr>
		</table>
		<?php
		echo '<p>' . esc_html__( '関連を変更するレッスン', 'imaoikiruhitolms' ) . '<br><span id="course-lesson-related-change"></span></p>';
		?>
		<span id="course-lesson-related-change-code-add-wrap"></span>
		<span id="course-lesson-related-change-code-del-wrap"></span>
		<?php
		echo '<p>' . esc_html__( 'レッスンの順番を指定してください', 'imaoikiruhitolms' ) . '<br><span id="course-lesson-related-change"></span></p>';
		?>
		<ul id="iihlms-lesson-sortable">
			<?php
			if ( ! empty( $iihlms_course_relation ) ) {
				$args     = array(
					'post_type'           => 'iihlms_lessons',
					'posts_per_page'      => -1,
					'post_status'         => 'publish',
					'post__in'            => $iihlms_course_relation,
					'ignore_sticky_posts' => 1,
					'orderby'             => 'post__in',
				);
				$my_posts = get_posts( $args );
				foreach ( $my_posts as $postdata ) {
					setup_postdata( $postdata );
					echo '<li class="ui-state-default" id="';
					echo esc_attr( $postdata->ID );
					echo '">';
					echo esc_html( get_the_title( $postdata->ID ) );
					echo '<input type="hidden" name="iihlms-lesson-sortable-data[]" value="';
					echo esc_attr( $postdata->ID );
					echo '"></li>';
				}
				wp_reset_postdata();
			}
			?>
		</ul>

		<?php
	}

	/**
	 * コースのメタボックス
	 *
	 * @return void
	 */
	public function insert_course_fields_2() {
		global $post;

		$id                        = get_the_ID();
		$content                   = '';
		$iihlms_course_explanation = get_post_meta( $post->ID, 'iihlms_course_explanation', true );
		if ( $iihlms_course_explanation ) {
			$content = $iihlms_course_explanation;
		}
		$settings = array();
		wp_editor( $content, 'iihlms_course_explanation_editor', $settings );
	}

	/**
	 * コースのメタボックス
	 *
	 * @return void
	 */
	public function insert_course_fields_3() {
		global $post;

		$id                      = get_the_ID();
		$content                 = '';
		$iihlms_course_materials = get_post_meta( $post->ID, 'iihlms_course_materials', true );
		if ( $iihlms_course_materials ) {
			$content = $iihlms_course_materials;
		}
		$settings = array();
		wp_editor( $content, 'iihlms_course_materials_editor', $settings );
	}

	/**
	 * コースのメタボックス
	 *
	 * @return void
	 */
	public function insert_course_fields_4() {
		global $post;
		global $wpdb;

		$iihlms_course_permission = get_post_meta( $post->ID, 'iihlms_course_permission', true );
		echo '<h4>' . esc_html__( 'このコースはログイン不要で誰でもアクセス可能にする', 'imaoikiruhitolms' ) . '</h4>';
		?>
		<label class="iihlms-radio-label"><input type="radio" name="iihlms-course-permission" value="yes"<?php checked( $iihlms_course_permission, 'yes' ); ?> required><?php echo esc_html__( 'する', 'imaoikiruhitolms' ); ?></label>
		<label class="iihlms-radio-label"><input type="radio" name="iihlms-course-permission" value="no"<?php checked( $iihlms_course_permission, 'no' ); ?><?php checked( $iihlms_course_permission, '' ); ?> required><?php echo esc_html__( 'しない', 'imaoikiruhitolms' ); ?></label>
		<?php
	}

	/**
	 * コースのメタボックス
	 *
	 * @return void
	 */
	public function insert_course_fields_5() {
		echo esc_html( apply_filters( 'iihlms_addition_insert_course_fields_bbpress', '' ) );
	}

	/**
	 * コースのメタボックス
	 *
	 * @return void
	 */
	public function insert_course_fields_6() {
		global $post;

		echo '<table class="form-table" role="presentation">';
		echo '<tbody>';

		$iihlms_course_test_relationship = get_post_meta( $post->ID, 'iihlms_course_test_relationship', true );
		echo '<tr>';
		echo '<th scope="row">' . esc_html__( 'このコースに紐付けするテスト', 'imaoikiruhitolms' ) . '</th>';
		echo '<td>';
		echo '<select id="iihlms-course-test-relationship" name="iihlms-course-test-relationship">';
		echo '<option value="0" ';
		echo selected( $iihlms_course_test_relationship, '' );
		echo '>';
		echo esc_html__( 'なし', 'imaoikiruhitolms' );
		echo '</option>';
		$args     = array(
			'post_type'      => 'iihlms_tests',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
		);
		$my_posts = get_posts( $args );
		foreach ( $my_posts as $postdata ) {
			setup_postdata( $postdata );
			echo '<option value="';
			echo esc_attr( $postdata->ID );
			echo '" ';
			echo selected( $iihlms_course_test_relationship, $postdata->ID );
			echo '>';
			echo esc_html( get_the_title( $postdata ) );
			echo '</option>';
		}
		wp_reset_postdata();
		echo '</select>';
		echo '</td>';
		echo '</tr>';

		$iihlms_course_test_cant_proceed_until_pass = get_post_meta( $post->ID, 'iihlms_course_test_cant_proceed_until_pass', true );
		echo '<tr>';
		echo '<th scope="row">' . esc_html__( 'テストに合格するまで次のコースに進めないようにする', 'imaoikiruhitolms' ) . '</th>';
		echo '<td>';
		?>
		<label class="iihlms-radio-label"><input type="radio" name="iihlms-course-test-cant-proceed-until-pass" value="yes"<?php checked( $iihlms_course_test_cant_proceed_until_pass, 'yes' ); ?> required><?php echo esc_html__( 'する', 'imaoikiruhitolms' ); ?></label>
		<label class="iihlms-radio-label"><input type="radio" name="iihlms-course-test-cant-proceed-until-pass" value="no"<?php checked( $iihlms_course_test_cant_proceed_until_pass, 'no' ); ?><?php checked( $iihlms_course_test_cant_proceed_until_pass, '' ); ?> required><?php echo esc_html__( 'しない', 'imaoikiruhitolms' ); ?></label>
		<?php
		echo '</td>';
		echo '</tr>';

		$iihlms_course_test_conditions_for_displaying = get_post_meta( $post->ID, 'iihlms_course_test_conditions_for_displaying', true );
		if ( '' === $iihlms_course_test_conditions_for_displaying ) {
			$iihlms_course_test_conditions_for_displaying = 'nocondition';
		}
		$iihlms_course_test_conditions_number_of_days = get_post_meta( $post->ID, 'iihlms_course_test_conditions_number_of_days', true );
		if ( '' === $iihlms_course_test_conditions_number_of_days ) {
			$iihlms_course_test_conditions_number_of_days = 10;
		}
		echo '<tr>';
		echo '<th scope="row">' . esc_html__( 'テストを表示する条件', 'imaoikiruhitolms' ) . '</th>';
		echo '<td>';
		echo '<fieldset>';
		echo '<p>';
		echo '<label><input name="iihlms-course-test-conditions-for-displaying" type="radio" value="nocondition" ';
		echo checked( $iihlms_course_test_conditions_for_displaying, 'nocondition' );
		echo '>' . esc_html__( '無条件にテストを表示', 'imaoikiruhitolms' ) . '</label><br>';
		echo '<label><input name="iihlms-course-test-conditions-for-displaying" type="radio" value="aftercompleting" ';
		echo checked( $iihlms_course_test_conditions_for_displaying, 'aftercompleting' );
		echo '>' . esc_html__( 'コース完了後テストを表示', 'imaoikiruhitolms' ) . '</label><br>';
		echo '<label><input name="iihlms-course-test-conditions-for-displaying" type="radio" value="dayslater" ';
		echo checked( $iihlms_course_test_conditions_for_displaying, 'dayslater' );
		echo '>' . esc_html__( 'コース開始から', 'imaoikiruhitolms' ) . '<input name="iihlms-course-test-conditions-number-of-days" type="number" step="1" min="1" id="iihlms-course-test-conditions-number-of-days" value="' . esc_attr( $iihlms_course_test_conditions_number_of_days ) . '" class="small-text">' . esc_html__( '日後にテストを表示', 'imaoikiruhitolms' ) . '</label>';
		echo '</p>';
		echo '</fieldset>';
		echo '</td>';
		echo '</tr>';
		echo '</tbody>';
		echo '</table>';
	}
	/**
	 * コースのメタボックス
	 *
	 * @return void
	 */
	public function insert_course_fields_7() {
		global $post;

		echo '<table class="form-table" role="presentation">';
		echo '<tbody>';

		$iihlms_course_display_list_of_lessons_prohibited = get_post_meta( $post->ID, 'iihlms_course_display_list_of_lessons_prohibited', true );
		echo '<tr>';
		echo '<th scope="row">' . esc_html__( '進めないようにしたレッスンをコース画面で一覧表示するか', 'imaoikiruhitolms' ) . '</th>';
		echo '<td>';
		?>
		<label class="iihlms-radio-label"><input type="radio" name="iihlms-course-display-list-of-lessons-prohibited" value="yes"<?php checked( $iihlms_course_display_list_of_lessons_prohibited, 'yes' ); ?><?php checked( $iihlms_course_display_list_of_lessons_prohibited, '' ); ?> required><?php echo esc_html__( 'する', 'imaoikiruhitolms' ); ?></label>
		<label class="iihlms-radio-label"><input type="radio" name="iihlms-course-display-list-of-lessons-prohibited" value="no"<?php checked( $iihlms_course_display_list_of_lessons_prohibited, 'no' ); ?> required><?php echo esc_html__( 'しない', 'imaoikiruhitolms' ); ?></label>
		<?php
		echo '<p class="description">' . esc_html__( 'レッスンの編集画面で「テストに合格するまで次のレッスンに進めないようにする」を設定し、進めないようにしたレッスンをコース画面で一覧表示するか', 'imaoikiruhitolms' ) . '</p>';
		echo '</td>';
		echo '</tr>';

		echo '</tbody>';
		echo '</table>';
	}
	/**
	 * コースのメタボックス
	 *
	 * @return void
	 */
	public function insert_course_fields_8() {
		global $post;

		$pdfs = get_post_meta( $post->ID, 'iihlms_course_pdfs', true );
		if ( empty( $pdfs ) ) {
			$pdfs = array();
		}

		echo '<div id="course_pdfs_container">';
		foreach ( $pdfs as $pdf ) {
			echo '<div class="course_pdf_row">';
			echo '<span class="dashicons dashicons-menu handle"></span>';
			echo '<input type="text" class="course_pdf_name" name="course_pdf_names[]" value="' . esc_attr( $pdf['name'] ) . '" size="20" placeholder="表示名" />';
			echo '<input type="text" class="course_pdf" name="course_pdfs[]" value="' . esc_attr( $pdf['url'] ) . '" size="25" placeholder="添付資料 URL" />';
			echo '<input type="button" class="course_pdf_button button" value="' . esc_html__( '添付資料を選択', 'imaoikiruhitolms' ) . '" />';
			echo '<input type="button" class="course_pdf_remove_button button" value="' . esc_html__( '削除', 'imaoikiruhitolms' ) . '" />';
			echo '</div>';
		}
		echo '</div>';
		echo '<input type="button" id="add_course_pdf_button" class="button" value="' . esc_html__( '添付資料を追加', 'imaoikiruhitolms' ) . '" />';

		wp_nonce_field( 'course_pdf_nonce_action', 'course_pdf_nonce' );

		echo '<script>
		jQuery(document).ready(function($) {
			console.log("JavaScript is loaded");

			function addMediaUploader(button, input) {
				console.log("addMediaUploader is called");
				var frame = wp.media({
					title: "添付資料を選択",
					button: {
						text: "この添付資料を使用"
					},
					multiple: false
				});
				frame.on("select", function() {
					var attachment = frame.state().get("selection").first().toJSON();
					input.val(attachment.url);
				});
				frame.open();
			}

			$(document).on("click", ".course_pdf_button", function(e) {
				e.preventDefault();
				addMediaUploader($(this), $(this).prev(".course_pdf"));
			});

			$("#add_course_pdf_button").click(function(e) {
				e.preventDefault();
				$("#course_pdfs_container").append(\'<div class="course_pdf_row"><span class="dashicons dashicons-menu handle"></span><input type="text" class="course_pdf_name" name="course_pdf_names[]" size="20" placeholder="表示名" /><input type="text" class="course_pdf" name="course_pdfs[]" size="25" placeholder="添付資料 URL" /><input type="button" class="course_pdf_button button" value="添付資料を選択" /><input type="button" class="course_pdf_remove_button button" value="削除" /></div>\');
			});

			$(document).on("click", ".course_pdf_remove_button", function(e) {
				e.preventDefault();
				$(this).parent(".course_pdf_row").remove();
			});

			// ソート機能を有効にする
			$("#course_pdfs_container").sortable({
				handle: ".handle",
				placeholder: "sortable-placeholder",
				forcePlaceholderSize: true
			});
		});
		</script>';
		echo '<style>
		#course_pdfs_container {
			margin-top: 10px;
		}
		.course_pdf_row {
			margin-bottom: 10px;
			padding: 10px;
			border: 1px solid #ccc;
			background-color: #f9f9f9;
			cursor: move;
			display: flex;
			flex-wrap: wrap;
			align-items: center;
		}
		.course_pdf_row .handle {
			cursor: move;
			margin-right: 10px;
		}
		.course_pdf_row input[type="button"] {
			margin-left: 10px; /* ボタン間の余白を設定 */
		}
		.course_pdf_row input[type="text"] {
			flex: 1;
			min-width: 150px;
			margin-bottom: 5px;
		}
		.sortable-placeholder {
			border: 1px dashed #ccc;
			background-color: #f0f0f0;
			height: 3em;
		}
		.course-pdfs {
			margin-top: 20px;
		}
		.course-pdf {
			margin-bottom: 10px;
		}
		.course-pdf a {
			color: #0073aa;
			text-decoration: none;
		}
		.course-pdf a:hover {
			text-decoration: underline;
		}
		@media (max-width: 600px) {
			.course_pdf_row input[type="text"], .course_pdf_row input[type="button"] {
				width: 100%;
				margin-left: 0;
			}
			.course_pdf_row {
				flex-direction: column;
				align-items: stretch;
			}
			.course_pdf_row .handle {
				align-self: flex-start;
			}
		}
		</style>';
	}
	/**
	 * 講座のメタボックス
	 *
	 * @return void
	 */
	public function insert_item_fields_1() {
		global $post;
		echo '<h4>' . esc_html__( '支払い方法', 'imaoikiruhitolms' ) . '</h4>';
		if ( defined( 'IIHLMS_ADDITION' ) && is_callable( '\IIHLMS_A\\Imaoikiruhito_LMS_License::is_license_valid' ) && \IIHLMS_A\Imaoikiruhito_LMS_License::is_license_valid() ) {
			$iihlms_payment_type = get_post_meta( $post->ID, 'iihlms_payment_type', true );
			echo esc_html( apply_filters( 'iihlms_addition_item_fields_payment_type', $iihlms_payment_type ) );
		} else {
			echo '<label class="iihlms-radio-label"><input type="radio" name="iihlms-payment-type" value="onetime" checked required>' . esc_html__( '一括', 'imaoikiruhitolms' ) . '</label>';
		}
	}

	/**
	 * 講座のメタボックス
	 *
	 * @return void
	 */
	public function insert_item_fields_1a() {
		global $post;

		echo '<table class="form-table" role="presentation">';
		echo '<tbody>';
		echo '<tr>';
		echo '<th scope="row">' . esc_html__( '価格', 'imaoikiruhitolms' ) . '</th>';
		echo '<td>';
		echo '<input type="text" name="iihlms-item-price" id="iihlms-item-price" value="' . esc_attr( get_post_meta( $post->ID, 'iihlms_item_price', true ) ) . '" /><br>';
		echo '<p class="description">' . esc_html__( '税抜で入力してください。', 'imaoikiruhitolms' ) . '</p>';
		echo '</td>';
		echo '</tr>';
		echo '</tbody>';
		echo '</table>';
		?>
		<?php
	}

	/**
	 * 講座のメタボックス
	 *
	 * @return void
	 */
	public function insert_item_fields_1b() {
		echo esc_html( apply_filters( 'iihlms_addition_item_fields_subscription_payment_setting', '' ) );
	}

	/**
	 * 講座のメタボックス
	 *
	 * @return void
	 */
	public function insert_item_fields_1c() {
		echo esc_html( apply_filters( 'iihlms_addition_item_fields_subscription_show_setting', '' ) );
	}

	/**
	 * 講座のメタボックス
	 *
	 * @return void
	 */
	public function insert_item_fields_2() {
		global $post;
		global $wpdb;

		// 関連しているコース.
		$iihlms_item_relation      = get_post_meta( $post->ID, 'iihlms_item_relation', true );
		$iihlms_item_relation      = isset( $iihlms_item_relation ) ? (array) $iihlms_item_relation : array();
		$iihlms_item_relation_flip = array_flip( $iihlms_item_relation );

		$option_related       = '';
		$option_related_not   = '';
		$sortable_item_course = '';

		wp_nonce_field( 'iihlms-items-csrf-action', 'iihlms-items-csrf' );
		echo '<h4>' . esc_html__( 'この講座に関連するコースを選択してください', 'imaoikiruhitolms' ) . '</h4>';
		?>
		<table class="iihlms-select-multi-table">
		<tr>
			<td class="iihlms-select-multi-left">
			<?php
			echo '<input id="target-text-left" placeholder="' . esc_html__( '関連していないコースを検索', 'imaoikiruhitolms' ) . '" type="text" class="iihlms-select-search">';
			?>
				<select id="target-options-left" class="iihlms-select-mutiple" name="iihlms-select-mutiple-left" size="10" multiple="multiple">
					<?php
					// 関連していないコースの一覧.
					$args     = array(
						'post_type'      => 'iihlms_courses',
						'posts_per_page' => -1,
						'post_status'    => 'publish',
						'orderby'        => 'date',
						'order'          => 'DESC',
					);
					$my_posts = get_posts( $args );

					foreach ( $my_posts as $postdata ) {
						setup_postdata( $postdata );
						if ( ! isset( $iihlms_item_relation_flip[ $postdata->ID ] ) ) {
							echo '<option value="';
							echo esc_attr( $postdata->ID );
							echo '">';
							echo esc_html( get_the_title( $postdata ) );
							echo '</option>';
						}
					}
					wp_reset_postdata();
					?>
				</select>
			</td>
			<td class="iihlms-select-multi-middle">
				<input type="button" name="rightitem" value="≫" /><br /><br />
				<input type="button" name="leftitem" value="≪" />
			</td>
			<td class="iihlms-select-multi-right">
			<?php
			echo '<input id="target-text-right" placeholder="' . esc_html__( '関連しているコースを検索', 'imaoikiruhitolms' ) . '" type="text" class="iihlms-select-search">';
			?>
				<select id="target-options-right" class="iihlms-select-mutiple" name="iihlms-select-mutiple-right" size="10" multiple="multiple">
					<?php
					// 関連しているコース一覧.
					if ( '' !== $iihlms_item_relation[0] ) {
						$args     = array(
							'post_type'           => 'iihlms_courses',
							'posts_per_page'      => -1,
							'post_status'         => 'publish',
							'post__in'            => $iihlms_item_relation,
							'ignore_sticky_posts' => 1,
							'orderby'             => 'post__in',
						);
						$my_posts = get_posts( $args );
						foreach ( $my_posts as $postdata ) {
							setup_postdata( $postdata );
							echo '<option value="';
							echo esc_attr( $postdata->ID );
							echo '">';
							echo esc_html( get_the_title( $postdata->ID ) );
							echo '</option>';
						}
						wp_reset_postdata();
					}
					?>
				</select>
			</td>
		</tr>
		</table>
		<?php
		echo '<p>' . esc_html__( '関連を変更するコース', 'imaoikiruhitolms' ) . '<br><span id="item-course-related-change"></span></p>';
		?>
		<span id="item-course-related-change-code-add-wrap"></span>
		<span id="item-course-related-change-code-del-wrap"></span>
		<?php
		echo '<h4>' . esc_html__( 'コースの順番を指定してください', 'imaoikiruhitolms' ) . '</h4>';
		?>
		<ul id="iihlms-course-sortable">
			<?php
			if ( '' !== $iihlms_item_relation[0] ) {
				$args     = array(
					'post_type'           => 'iihlms_courses',
					'posts_per_page'      => -1,
					'post_status'         => 'publish',
					'post__in'            => $iihlms_item_relation,
					'ignore_sticky_posts' => 1,
					'orderby'             => 'post__in',
				);
				$my_posts = get_posts( $args );
				foreach ( $my_posts as $postdata ) {
					setup_postdata( $postdata );
					echo '<li class="ui-state-default" id="';
					echo esc_attr( $postdata->ID );
					echo '">';
					echo esc_html( get_the_title( $postdata->ID ) );
					echo '<input type="hidden" name="iihlms-course-sortable-data[]" value="';
					echo esc_attr( $postdata->ID );
					echo '"></li>';
				}
				wp_reset_postdata();
			}
			?>
		</ul>
		<?php
	}
	/**
	 * 講座のメタボックス
	 *
	 * @return void
	 */
	public function insert_item_fields_3() {
		global $post;
		global $wpdb;

		echo '<h4>' . esc_html__( 'この講座を閲覧・購入できるのを以下の会員ステータスに限定する', 'imaoikiruhitolms' ) . '</h4>';

		$iihlms_item_membership_data = get_post_meta( $post->ID, 'iihlms_item_membership', true );
		$iihlms_item_membership_data = isset( $iihlms_item_membership_data ) ? (array) $iihlms_item_membership_data : array();

		$zeronum          = 0;
		$membership_table = $wpdb->prefix . 'iihlms_membership';

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT *
				FROM %1s
				WHERE
					iihlms_membership_id > %2d
				',
				$membership_table,
				$zeronum
			)
		);
		echo '<input type="hidden" name="iihlms-item-membership-hidden" value="1">';

		if ( empty( $results ) ) {
			echo '<p>' . esc_html__( '利用可能な会員ステータスがありません。', 'imaoikiruhitolms' ) . '</p>';
			return;
		}

		foreach ( $results as $result ) {
			$id   = $result->iihlms_membership_id;
			$name = $result->membership_name;
			echo '<label class="iihlms-checkbox-label" for=';
			echo '"iihlms_item_membership_id' . esc_html( $id );
			echo '">';
			echo '<input type="checkbox" name="iihlms_item_membership[]';
			echo '" id="iihlms_item_membership_id' . esc_html( $id );
			echo '" value="' . esc_html( $id );
			echo '" ';
			checked( ( in_array( (string) $id, $iihlms_item_membership_data, true ) ) );
			echo '>';
			echo esc_html( $name );
			echo '</label>';
		}
	}
	/**
	 * 講座のメタボックス
	 *
	 * @return void
	 */
	public function insert_item_fields_4() {
		global $post;
		global $wpdb;

		echo '<h4>' . esc_html__( 'この講座を購入した人に対し以下の会員ステータスを自動付与する', 'imaoikiruhitolms' ) . '</h4>';

		$iihlms_item_membership_automatic_grant_data = get_post_meta( $post->ID, 'iihlms_item_membership_automatic_grant', true );
		$iihlms_item_membership_automatic_grant_data = isset( $iihlms_item_membership_automatic_grant_data ) ? (array) $iihlms_item_membership_automatic_grant_data : array();

		$zeronum          = 0;
		$membership_table = $wpdb->prefix . 'iihlms_membership';

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT *
				FROM %1s
				WHERE
					iihlms_membership_id > %2d
				',
				$membership_table,
				$zeronum
			)
		);
		echo '<input type="hidden" name="iihlms-item-membership-automatic-grant-hidden" value="1">';

		if ( empty( $results ) ) {
			echo '<p>' . esc_html__( '利用可能な会員ステータスがありません。', 'imaoikiruhitolms' ) . '</p>';
			return;
		}
		foreach ( $results as $result ) {
			$id   = $result->iihlms_membership_id;
			$name = $result->membership_name;
			echo '<label class="iihlms-checkbox-label" for=';
			echo '"iihlms-item-membership-automatic-grant-id' . esc_html( $id );
			echo '">';
			echo '<input type="checkbox" name="iihlms-item-membership-automatic-grant[]';
			echo '" id="iihlms-item-membership-automatic-grant-id' . esc_html( $id );
			echo '" value="' . esc_html( $id );
			echo '" ';
			checked( ( in_array( (string) $id, $iihlms_item_membership_automatic_grant_data, true ) ) );
			echo '>';
			echo esc_html( $name );
			echo '</label>';
		}
	}
	/**
	 * 講座のメタボックス
	 *
	 * @return void
	 */
	public function insert_item_fields_5() {
		global $post;
		global $wpdb;

		// 前提条件（コース）に指定済のコース.
		$iihlms_item_course_complete_precondition      = get_post_meta( $post->ID, 'iihlms_item_course_complete_precondition', true );
		$iihlms_item_course_complete_precondition      = isset( $iihlms_item_course_complete_precondition ) ? (array) $iihlms_item_course_complete_precondition : array();
		$iihlms_item_course_complete_precondition_flip = array_flip( $iihlms_item_course_complete_precondition );

		$option_related     = '';
		$option_related_not = '';
		echo '<h4>' . esc_html__( '指定したコースを受講完了後、この講座を閲覧・購入できるよう限定する', 'imaoikiruhitolms' ) . '</h4>';
		?>
		<table class="iihlms-select-multi-table">
		<tr>
			<td class="iihlms-select-multi-left">
			<?php
			echo '<input id="target-text-left-item-course-complete-precondition" placeholder="' . esc_html__( '未指定のコースを検索', 'imaoikiruhitolms' ) . '" type="text" class="iihlms-select-search">';
			?>
				<select id="target-options-item-course-complete-precondition-left" class="iihlms-select-mutiple" name="target-options-item-course-complete-precondition-left" size="10" multiple="multiple">
					<?php
					// 前提条件に指定していないコースの一覧.
					$args = array(
						'post_type'      => 'iihlms_courses',
						'posts_per_page' => -1,
						'post_status'    => 'publish',
						'orderby'        => 'date',
						'order'          => 'DESC',
					);

					$my_posts = get_posts( $args );

					foreach ( $my_posts as $postdata ) {
						setup_postdata( $postdata );
						if ( ! isset( $iihlms_item_course_complete_precondition_flip[ $postdata->ID ] ) ) {
							echo '<option value="';
							echo esc_attr( $postdata->ID );
							echo '">';
							echo esc_html( get_the_title( $postdata ) );
							echo '</option>';
						}
					}
					wp_reset_postdata();
					?>
				</select>
			</td>
			<td class="iihlms-select-multi-middle">
				<input type="button" name="right-item-course-complete-precondition" value="≫" /><br /><br />
				<input type="button" name="left-item-course-complete-precondition" value="≪" />
			</td>
			<td class="iihlms-select-multi-right">
			<?php
			echo '<input id="target-text-right-item-course-complete-precondition" placeholder="' . esc_html__( '指定済みのコースを検索', 'imaoikiruhitolms' ) . '" type="text" class="iihlms-select-search">';
			?>
				<select id="target-options-item-course-complete-precondition-right" class="iihlms-select-mutiple" name="target-options-item-course-complete-precondition-right" size="10" multiple="multiple">
					<?php
					// 前提条件に指定済のコース一覧表示.
					if ( '' !== $iihlms_item_course_complete_precondition[0] ) {
						$args     = array(
							'post_type'           => 'iihlms_courses',
							'posts_per_page'      => -1,
							'post_status'         => 'publish',
							'post__in'            => $iihlms_item_course_complete_precondition,
							'ignore_sticky_posts' => 1,
						);
						$my_posts = get_posts( $args );
						foreach ( $my_posts as $postdata ) {
							setup_postdata( $postdata );
							echo '<option value="';
							echo esc_attr( $postdata->ID );
							echo '">';
							echo esc_html( get_the_title( $postdata->ID ) );
							echo '</option>';
						}
						wp_reset_postdata();
					}

					?>
				</select>
			</td>
		</tr>
		</table>
		<?php
		echo '<p>' . esc_html__( '変更内容', 'imaoikiruhitolms' ) . '<br><span id="iihlms-select-item-course-complete-precondition-change"></span></p>';
		?>
		<span id="iihlms-select-item-course-complete-precondition-change-code-add-wrap"></span>
		<span id="iihlms-select-item-course-complete-precondition-change-code-del-wrap"></span>

		<?php
	}
	/**
	 * 講座のメタボックス
	 *
	 * @return void
	 */
	public function insert_item_fields_6() {
		echo esc_html( apply_filters( 'iihlms_addition_insert_item_fields_6', '' ) );
	}
	/**
	 * 講座のメタボックス
	 *
	 * @return void
	 */
	public function insert_item_fields_7() {
		echo esc_html( apply_filters( 'iihlms_addition_insert_item_fields_7', '' ) );
	}
	/**
	 * 講座のメタボックス
	 *
	 * @return void
	 */
	public function insert_item_fields_8() {
		global $post;

		$use_custom_email = get_post_meta( $post->ID, 'iihlms_use_custom_email', true );
		$email_subject    = get_post_meta( $post->ID, 'iihlms_item_email_subject', true );
		$email_body       = get_post_meta( $post->ID, 'iihlms_item_email_body', true );

		echo '<table class="form-table" role="presentation">';
		echo '<tbody>';
		echo '<tr>';
		echo '<th scope="row">' . esc_html__( 'メール設定変更', 'imaoikiruhitolms' ) . '</th>';
		echo '<td>';
		echo '<label for="iihlms_use_custom_email">';
		echo '<input type="checkbox" id="iihlms_use_custom_email" name="iihlms_use_custom_email" value="1"' . checked( $use_custom_email, '1', false ) . ' />';
		echo esc_html__( 'デフォルトのメールではなくここで入力したメールを使用する', 'imaoikiruhitolms' ) . '</label>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th scope="row">' . esc_html__( 'メール件名', 'imaoikiruhitolms' ) . '</th>';
		echo '<td>';
		echo '<input type="text" id="iihlms_item_email_subject" name="iihlms_item_email_subject" value="' . esc_attr( $email_subject ) . '" size="25" />';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th scope="row">' . esc_html__( 'メール本文', 'imaoikiruhitolms' ) . '</th>';
		echo '<td>';
		echo '<textarea id="iihlms_item_email_body" name="iihlms_item_email_body" rows="5" style="width:100%">' . esc_textarea( $email_body ) . '</textarea>';
		echo '<p class="description">';
		echo esc_html__( '使用可能な予約語:', 'imaoikiruhitolms' ) . '<br>';
		echo esc_html__( '*NAME* - ユーザーの氏名を表示します', 'imaoikiruhitolms' ) . '<br>';
		echo esc_html__( '*APPLICATION_DETAILS* - 申込内容を表示します', 'imaoikiruhitolms' );
		echo '</p>';
		echo '</td>';
		echo '</tr>';
		echo '</tbody>';
		echo '</table>';
	}
	/**
	 * テストのメタボックス
	 *
	 * @return void
	 */
	public function insert_test_fields_1() {
		echo esc_html( apply_filters( 'iihlms_addition_insert_test_fields_1', '' ) );
	}
	/**
	 * テストのメタボックス
	 *
	 * @return void
	 */
	public function insert_test_fields_2() {
		echo esc_html( apply_filters( 'iihlms_addition_insert_test_fields_2', '' ) );
	}
	/**
	 * テストのメタボックス
	 *
	 * @return void
	 */
	public function insert_test_fields_3() {
		echo esc_html( apply_filters( 'iihlms_addition_insert_test_fields_3', '' ) );
	}
	/**
	 * カスタムフィールドの検証
	 *
	 * @param array $data    An array of sanitized post data. This is the data that will be
	 *                       saved to the database if no validation errors are found.
	 * @param array $postarr An array of raw post data. This is the data that was submitted
	 *                       by the user and may contain invalid or unsafe values.
	 *
	 * @return array         The modified $data array if validation errors are found, or the
	 *                       unchanged $data array if no validation errors are found.
	 */
	public function validate_custom_fields( $data, $postarr ) {
		if ( empty( $postarr['ID'] ) || 'auto-draft' === $data['post_status'] ) {
			return $data;
		}

		// Check if this is a delete operation.
		if ( defined( 'DOING_AUTOSAVE' ) || 'trash' === $data['post_status'] || isset( $_GET['action'] ) && 'untrash' === $_GET['action'] ) {
			return $data;
		}

		$post_type = $data['post_type'];
		/**
		 * 講座
		 */
		if ( 'iihlms_items' === $post_type ) {
			if ( ! isset( $_POST['iihlms-items-csrf'] ) ) {
				$message = esc_html__( 'パラメーターに異常があります。', 'imaoikiruhitolms' );
				$this->set_admin_notice_message( 'lmsjp-custom-admin-errors', 'error', $message, 5 );
				return $data;
			}
			if ( ! check_admin_referer( 'iihlms-items-csrf-action', 'iihlms-items-csrf' ) ) {
				$message = esc_html__( 'パラメーターに異常があります。', 'imaoikiruhitolms' );
				$this->set_admin_notice_message( 'lmsjp-custom-admin-errors', 'error', $message, 5 );
				return $data;
			}
			if ( isset( $_POST['iihlms-payment-type'] ) ) {
				if ( 'subscription' === $_POST['iihlms-payment-type'] ) {
					$iihlms_payment_method_setting = get_option( 'iihlms_payment_method_setting', array() );
					if ( empty( $iihlms_payment_method_setting ) ) {
						$message = esc_html__( 'サブスクリプションを登録する前に、支払い方法の設定を完了させてください。', 'imaoikiruhitolms' );
						$this->set_admin_notice_message( 'lmsjp-custom-admin-errors', 'error', $message, 5 );
						$data['post_status'] = 'draft';
						return $data;
					}
					if ( in_array( 'paypal', $iihlms_payment_method_setting, true ) ) {
						$client_id = $this->get_clientid_paypal();
						if ( '' === $client_id ) {
							$message = esc_html__( 'サブスクリプションを登録する前に、支払い方法の設定を完了させてください。', 'imaoikiruhitolms' );
							$this->set_admin_notice_message( 'lmsjp-custom-admin-errors', 'error', $message, 5 );
							$data['post_status'] = 'draft';
							return $data;
						}
						$secret_id = $this->get_secretid_paypal();
						if ( '' === $secret_id ) {
							$message = esc_html__( 'サブスクリプションを登録する前に、支払い方法の設定を完了させてください。', 'imaoikiruhitolms' );
							$this->set_admin_notice_message( 'lmsjp-custom-admin-errors', 'error', $message, 5 );
							$data['post_status'] = 'draft';
							return $data;
						}
					}
					if ( in_array( 'stripe', $iihlms_payment_method_setting, true ) ) {
						$secret_id = $this->get_secret_key_stripe();
						if ( '' === $secret_id ) {
							$message = esc_html__( 'サブスクリプションを登録する前に、支払い方法の設定を完了させてください。', 'imaoikiruhitolms' );
							$this->set_admin_notice_message( 'lmsjp-custom-admin-errors', 'error', $message, 5 );
							$data['post_status'] = 'draft';
							return $data;
						}
					}
				}
			}
		}
		return $data;
	}
	/**
	 * カスタムフィールドの保存
	 *
	 * @param string $post_id 投稿ID.
	 */
	public function save_custom_fields( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		/**
		 * 講座
		 */
		if ( 'iihlms_items' === get_post_type( $post_id ) ) {
			if ( ! isset( $_POST['iihlms-items-csrf'] ) ) {
				return false;
			}
			if ( ! check_admin_referer( 'iihlms-items-csrf-action', 'iihlms-items-csrf' ) ) {
				return false;
			}

			if ( isset( $_POST['iihlms-payment-type'] ) ) {
				if ( 'subscription' === $_POST['iihlms-payment-type'] ) {
					$iihlms_payment_method_setting = get_option( 'iihlms_payment_method_setting', array() );
					if ( empty( $iihlms_payment_method_setting ) ) {
						return false;
					}
					if ( in_array( 'paypal', $iihlms_payment_method_setting, true ) ) {
						$client_id = $this->get_clientid_paypal();
						if ( '' === $client_id ) {
							return false;
						}
						$secret_id = $this->get_secretid_paypal();
						if ( '' === $secret_id ) {
							return false;
						}
					}
					if ( in_array( 'stripe', $iihlms_payment_method_setting, true ) ) {
						$secret_id = $this->get_secret_key_stripe();
						if ( '' === $secret_id ) {
							return false;
						}
					}
				}
				update_post_meta( $post_id, 'iihlms_payment_type', sanitize_text_field( wp_unslash( $_POST['iihlms-payment-type'] ) ) );
			}

			// 支払い設定(一括).
			// 価格.
			if ( isset( $_POST['iihlms-item-price'] ) ) {
				$price = absint( sanitize_text_field( wp_unslash( $_POST['iihlms-item-price'] ) ) );
				$price = $this->round_number_lower_and_upper( $price, 0, 9999999 );
				update_post_meta( $post_id, 'iihlms_item_price', $price );
			}

			// 支払い設定(サブスクリプション).
			echo esc_html( apply_filters( 'iihlms_addition_save_custom_fields_subscription', $post_id ) );

			// 講座とコースの関連.
			if ( isset( $_POST['iihlms-select-items-change-code-add'] ) ) {
				update_post_meta( $post_id, 'iihlms_course_relation', sanitize_text_field( wp_unslash( $_POST['iihlms-select-items-change-code-add'] ) ) );
			}

			// この講座を閲覧・購入できるのを以下の会員ステータスに限定する.
			if ( isset( $_POST['iihlms-item-membership-hidden'] ) ) {
				if ( isset( $_POST['iihlms_item_membership'] ) ) {
					$post_iihlms_item_membership_data = array();
					foreach ( array_map( 'absint', ( wp_unslash( $_POST['iihlms_item_membership'] ) ) ) as $key => $value ) {
						$post_iihlms_item_membership_data[] = sanitize_text_field( $value );
					}
					update_post_meta( $post_id, 'iihlms_item_membership', $post_iihlms_item_membership_data );
				} else {
					delete_post_meta( $post_id, 'iihlms_item_membership' );
				}
			}
			// この講座を購入した人に対し以下の会員ステータスを自動付与する.
			if ( isset( $_POST['iihlms-item-membership-automatic-grant-hidden'] ) ) {
				if ( isset( $_POST['iihlms-item-membership-automatic-grant'] ) ) {
					$post_iihlms_item_membership_automatic_grant_data = array();
					foreach ( array_map( 'absint', ( wp_unslash( $_POST['iihlms-item-membership-automatic-grant'] ) ) ) as $key => $value ) {
						$post_iihlms_item_membership_automatic_grant_data[] = sanitize_text_field( $value );
					}
					update_post_meta( $post_id, 'iihlms_item_membership_automatic_grant', $post_iihlms_item_membership_automatic_grant_data );
				} else {
					delete_post_meta( $post_id, 'iihlms_item_membership_automatic_grant' );
				}
			}
			// 講座とコースの関連.
			if ( isset( $_POST['iihlms-course-sortable-data'] ) ) {
				$post_couse_sortable_data = array();
				foreach ( array_map( 'absint', wp_unslash( $_POST['iihlms-course-sortable-data'] ) ) as $key => $value ) {
					$post_couse_sortable_data[] = sanitize_text_field( $value );
				}
				if ( defined( 'IIHLMS_ADDITION' ) ) {
					if ( is_callable( '\IIHLMS_A\\Imaoikiruhito_LMS_License::is_license_valid' ) && \IIHLMS_A\Imaoikiruhito_LMS_License::is_license_valid() ) {
						update_post_meta( $post_id, 'iihlms_item_relation', $post_couse_sortable_data );
					}
				} else {
					if ( count( $post_couse_sortable_data ) <= 2 ) {
						update_post_meta( $post_id, 'iihlms_item_relation', $post_couse_sortable_data );
					} else {
						$message = esc_html__( '無料版で登録できるコースは2つまでです', 'imaoikiruhitolms' );
						$this->set_admin_notice_message( 'lmsjp-custom-admin-errors', 'error', $message, 5 );
					}
				}
			} else {
				delete_post_meta( $post_id, 'iihlms_item_relation' );
			}

			// 購入時のメール.
			// カスタムメール設定を使用するかどうか.
			if ( isset( $_POST['iihlms_use_custom_email'] ) ) {
				update_post_meta( $post_id, 'iihlms_use_custom_email', '1' );
			} else {
				update_post_meta( $post_id, 'iihlms_use_custom_email', '' );
			}

			// メール件名.
			if ( isset( $_POST['iihlms_item_email_subject'] ) ) {
				$email_subject = sanitize_text_field( wp_unslash( $_POST['iihlms_item_email_subject'] ) );
				update_post_meta( $post_id, 'iihlms_item_email_subject', $email_subject );
			}

			// メール本文.
			if ( isset( $_POST['iihlms_item_email_body'] ) ) {
				$email_body = sanitize_textarea_field( wp_unslash( $_POST['iihlms_item_email_body'] ) );
				update_post_meta( $post_id, 'iihlms_item_email_body', $email_body );
			}

			// 指定したコースを受講完了後、この講座を閲覧・購入できるよう限定する.
			if ( isset( $_POST['iihlms-select-item-course-complete-precondition-change-code-add'] ) || isset( $_POST['iihlms-select-item-course-complete-precondition-change-code-del'] ) ) {
				$post_item_course_complete_precondition_change_code_add_data = array();
				foreach ( array_map( 'absint', wp_unslash( $_POST['iihlms-select-item-course-complete-precondition-change-code-add'] ) ) as $key => $value ) {
					$post_item_course_complete_precondition_change_code_add_data[] = sanitize_text_field( $value );
				}
				$post_item_course_complete_precondition_change_code_del_data = array();
				foreach ( array_map( 'absint', wp_unslash( $_POST['iihlms-select-item-course-complete-precondition-change-code-del'] ) ) as $key => $value ) {
					$post_item_course_complete_precondition_change_code_del_data[] = sanitize_text_field( $value );
				}

				$item_course_complete_precondition      = get_post_meta( $post_id, 'iihlms_item_course_complete_precondition', true );
				$item_course_complete_precondition      = isset( $item_course_complete_precondition ) ? (array) $item_course_complete_precondition : array();
				$item_course_complete_precondition_flip = array_flip( $item_course_complete_precondition );

				if ( ! empty( $post_item_course_complete_precondition_change_code_add_data ) ) {
					foreach ( $post_item_course_complete_precondition_change_code_add_data as $key => $value ) {
						if ( '' === $item_course_complete_precondition[0] ) {
							$item_course_complete_precondition[0] = $value;
						} else {
							$item_course_complete_precondition[] = $value;
						}
					}
				}

				if ( ! empty( $post_item_course_complete_precondition_change_code_del_data ) ) {
					foreach ( $post_item_course_complete_precondition_change_code_del_data as $key => $value ) {
						if ( in_array( $value, $item_course_complete_precondition, true ) ) {
							$item_course_complete_precondition = array_diff( $item_course_complete_precondition, array( $value ) );
							$item_course_complete_precondition = array_values( $item_course_complete_precondition );
						}
					}
				}

				if ( isset( $item_course_complete_precondition ) && ! empty( $item_course_complete_precondition ) ) {
					update_post_meta( $post_id, 'iihlms_item_course_complete_precondition', $item_course_complete_precondition );
				} else {
					delete_post_meta( $post_id, 'iihlms_item_course_complete_precondition' );
				}
			}

			// この講座に紐付けするテスト.
			if ( empty( $_POST['iihlms-item-test-relationship'] ) ) {
				delete_post_meta( $post_id, 'iihlms_item_test_relationship' );
			} else {
				$iihlms_item_test_relationship = sanitize_text_field( wp_unslash( $_POST['iihlms-item-test-relationship'] ) );
				update_post_meta( $post_id, 'iihlms_item_test_relationship', $iihlms_item_test_relationship );
			}

			// テストを表示する条件.
			if ( empty( $_POST['iihlms-item-test-conditions-for-displaying'] ) ) {
				delete_post_meta( $post_id, 'iihlms_item_test_conditions_for_displaying' );
			} else {
				$iihlms_item_test_conditions_for_displaying = sanitize_text_field( wp_unslash( $_POST['iihlms-item-test-conditions-for-displaying'] ) );
				update_post_meta( $post_id, 'iihlms_item_test_conditions_for_displaying', $iihlms_item_test_conditions_for_displaying );
			}

			// 前提条件（テスト）.
			if ( isset( $_POST['iihlms-select-item-test-pass-precondition-change-code-add'] ) || isset( $_POST['iihlms-select-item-test-pass-precondition-change-code-del'] ) ) {
				$post_item_test_pass_precondition_change_code_add_data = array();
				foreach ( array_map( 'absint', wp_unslash( $_POST['iihlms-select-item-test-pass-precondition-change-code-add'] ) ) as $key => $value ) {
					$post_item_test_pass_precondition_change_code_add_data[] = sanitize_text_field( $value );
				}
				$post_item_test_pass_precondition_change_code_del_data = array();
				foreach ( array_map( 'absint', wp_unslash( $_POST['iihlms-select-item-test-pass-precondition-change-code-del'] ) ) as $key => $value ) {
					$post_item_test_pass_precondition_change_code_del_data[] = sanitize_text_field( $value );
				}

				$item_test_pass_precondition      = get_post_meta( $post_id, 'iihlms_item_test_pass_precondition', true );
				$item_test_pass_precondition      = isset( $item_test_pass_precondition ) ? (array) $item_test_pass_precondition : array();
				$item_test_pass_precondition_flip = array_flip( $item_test_pass_precondition );

				if ( ! empty( $post_item_test_pass_precondition_change_code_add_data ) ) {
					foreach ( $post_item_test_pass_precondition_change_code_add_data as $key => $value ) {
						if ( '' === $item_test_pass_precondition[0] ) {
							$item_test_pass_precondition[0] = $value;
						} else {
							$item_test_pass_precondition[] = $value;
						}
					}
				}

				if ( ! empty( $post_item_test_pass_precondition_change_code_del_data ) ) {
					foreach ( $post_item_test_pass_precondition_change_code_del_data as $key => $value ) {
						if ( in_array( $value, $item_test_pass_precondition, true ) ) {
							$item_test_pass_precondition = array_diff( $item_test_pass_precondition, array( $value ) );
							$item_test_pass_precondition = array_values( $item_test_pass_precondition );
						}
					}
				}

				if ( isset( $item_test_pass_precondition ) && ! empty( $item_test_pass_precondition ) ) {
					update_post_meta( $post_id, 'iihlms_item_test_pass_precondition', $item_test_pass_precondition );
				} else {
					delete_post_meta( $post_id, 'iihlms_item_test_pass_precondition' );
				}
			}
		}

		/**
		 * コース
		 */
		if ( 'iihlms_courses' === get_post_type( $post_id ) ) {
			if ( ! isset( $_POST['iihlms-courses-csrf'] ) ) {
				return false;
			}
			if ( ! check_admin_referer( 'iihlms-courses-csrf-action', 'iihlms-courses-csrf' ) ) {
				return false;
			}
			if ( ! empty( $_POST['iihlms-lesson-sortable-data'] ) ) {
				$post_lesson_sortable_data = array();
				foreach ( array_map( 'absint', wp_unslash( $_POST['iihlms-lesson-sortable-data'] ) ) as $key => $value ) {
					$post_lesson_sortable_data[] = sanitize_text_field( $value );
				}
				update_post_meta( $post_id, 'iihlms_course_relation', $post_lesson_sortable_data );
			}
			if ( empty( $_POST['iihlms_course_explanation_editor'] ) ) {
				delete_post_meta( $post_id, 'iihlms_course_explanation' );
			} else {
				update_post_meta( $post_id, 'iihlms_course_explanation', wp_kses_post( wp_unslash( $_POST['iihlms_course_explanation_editor'] ) ) );
			}
			if ( empty( $_POST['iihlms_course_materials_editor'] ) ) {
				delete_post_meta( $post_id, 'iihlms_course_materials' );
			} else {
				update_post_meta( $post_id, 'iihlms_course_materials', wp_kses_post( wp_unslash( $_POST['iihlms_course_materials_editor'] ) ) );
			}
			if ( isset( $_POST['iihlms-course-permission'] ) ) {
				$iihlms_course_permission = sanitize_text_field( wp_unslash( $_POST['iihlms-course-permission'] ) );
				update_post_meta( $post_id, 'iihlms_course_permission', $iihlms_course_permission );
			}
			if ( isset( $_POST['iihlms-course-add-forum-bbpress'] ) ) {
				$iihlms_course_add_forum_bbpress = sanitize_text_field( wp_unslash( $_POST['iihlms-course-add-forum-bbpress'] ) );
				echo esc_html( apply_filters( 'iihlms_addition_save_custom_fields_course_add_forum_bbpress', $post_id, $iihlms_course_add_forum_bbpress ) );
			}
			if ( empty( $_POST['iihlms-course-test-relationship'] ) ) {
				delete_post_meta( $post_id, 'iihlms_course_test_relationship' );
			} else {
				$iihlms_course_test_relationship = sanitize_text_field( wp_unslash( $_POST['iihlms-course-test-relationship'] ) );
				update_post_meta( $post_id, 'iihlms_course_test_relationship', $iihlms_course_test_relationship );
			}
			if ( empty( $_POST['iihlms-course-test-conditions-for-displaying'] ) ) {
				delete_post_meta( $post_id, 'iihlms_course_test_conditions_for_displaying' );
			} else {
				$iihlms_course_test_conditions_for_displaying = sanitize_text_field( wp_unslash( $_POST['iihlms-course-test-conditions-for-displaying'] ) );
				update_post_meta( $post_id, 'iihlms_course_test_conditions_for_displaying', $iihlms_course_test_conditions_for_displaying );
			}
			if ( empty( $_POST['iihlms-course-test-conditions-number-of-days'] ) ) {
				delete_post_meta( $post_id, 'iihlms_course_test_conditions_number_of_days' );
			} else {
				$iihlms_course_test_conditions_number_of_days = absint( sanitize_text_field( wp_unslash( $_POST['iihlms-course-test-conditions-number-of-days'] ) ) );
				update_post_meta( $post_id, 'iihlms_course_test_conditions_number_of_days', $iihlms_course_test_conditions_number_of_days );
			}
			if ( empty( $_POST['iihlms-course-test-cant-proceed-until-pass'] ) ) {
				delete_post_meta( $post_id, 'iihlms_course_test_cant_proceed_until_pass' );
			} else {
				$iihlms_course_test_cant_proceed_until_pass = sanitize_text_field( wp_unslash( $_POST['iihlms-course-test-cant-proceed-until-pass'] ) );
				update_post_meta( $post_id, 'iihlms_course_test_cant_proceed_until_pass', $iihlms_course_test_cant_proceed_until_pass );
			}
			if ( empty( $_POST['iihlms-course-display-list-of-lessons-prohibited'] ) ) {
				delete_post_meta( $post_id, 'iihlms_course_display_list_of_lessons_prohibited' );
			} else {
				$iihlms_course_display_list_of_lessons_prohibited = sanitize_text_field( wp_unslash( $_POST['iihlms-course-display-list-of-lessons-prohibited'] ) );
				update_post_meta( $post_id, 'iihlms_course_display_list_of_lessons_prohibited', $iihlms_course_display_list_of_lessons_prohibited );
			}
			if ( isset( $_POST['course_pdfs'] ) && is_array( $_POST['course_pdfs'] ) && isset( $_POST['course_pdf_names'] ) && is_array( $_POST['course_pdf_names'] ) ) {
				$pdf_urls = array_map( 'sanitize_text_field', wp_unslash( $_POST['course_pdfs'] ) );
				$pdf_names = array_map( 'sanitize_text_field', wp_unslash( $_POST['course_pdf_names'] ) );
				$pdfs = array();

				$pdf_count = count( $pdf_urls );
				for ( $i = 0; $i < $pdf_count; $i++ ) {
					$pdfs[] = array(
						'url'  => $pdf_urls[ $i ],
						'name' => $pdf_names[ $i ],
					);
				}
				update_post_meta( $post_id, 'iihlms_course_pdfs', $pdfs );
			} else {
				delete_post_meta( $post_id, 'iihlms_course_pdfs' );
			}
		}

		/**
		 * レッスン
		 */
		if ( 'iihlms_lessons' === get_post_type( $post_id ) ) {
			if ( ! isset( $_POST['iihlms-lessons-csrf'] ) ) {
				return false;
			}
			if ( ! check_admin_referer( 'iihlms-lessons-csrf-action', 'iihlms-lessons-csrf' ) ) {
				return false;
			}
			if ( empty( $_POST['iihlms_lesson_explanation_editor'] ) ) {
				delete_post_meta( $post_id, 'iihlms_lesson_explanation' );
			} else {
				$allowed_html          = wp_kses_allowed_html( 'post' );
				$allowed_html['audio'] = array_merge(
					$allowed_html['audio'],
					array(
						'controlslist'  => 1,
						'oncontextmenu' => 1,
					)
				);
				update_post_meta( $post_id, 'iihlms_lesson_explanation', wp_kses( wp_unslash( $_POST['iihlms_lesson_explanation_editor'] ), $allowed_html ) );
			}
			if ( empty( $_POST['iihlms_lesson_materials_editor'] ) ) {
				delete_post_meta( $post_id, 'iihlms_lesson_materials' );
			} else {
				update_post_meta( $post_id, 'iihlms_lesson_materials', wp_kses_post( wp_unslash( $_POST['iihlms_lesson_materials_editor'] ) ) );
			}
			if ( empty( $_POST['iihlms-lesson-test-relationship'] ) ) {
				delete_post_meta( $post_id, 'iihlms_lesson_test_relationship' );
			} else {
				$iihlms_lesson_test_relationship = sanitize_text_field( wp_unslash( $_POST['iihlms-lesson-test-relationship'] ) );
				update_post_meta( $post_id, 'iihlms_lesson_test_relationship', $iihlms_lesson_test_relationship );
			}
			if ( empty( $_POST['iihlms-lesson-test-cant-proceed-until-pass'] ) ) {
				delete_post_meta( $post_id, 'iihlms_lesson_test_cant_proceed_until_pass' );
			} else {
				$iihlms_lesson_test_cant_proceed_until_pass = sanitize_text_field( wp_unslash( $_POST['iihlms-lesson-test-cant-proceed-until-pass'] ) );
				update_post_meta( $post_id, 'iihlms_lesson_test_cant_proceed_until_pass', $iihlms_lesson_test_cant_proceed_until_pass );
			}
			if ( empty( $_POST['iihlms-lesson-test-conditions-for-displaying'] ) ) {
				delete_post_meta( $post_id, 'iihlms_lesson_test_conditions_for_displaying' );
			} else {
				$iihlms_lesson_test_conditions_for_displaying = sanitize_text_field( wp_unslash( $_POST['iihlms-lesson-test-conditions-for-displaying'] ) );
				update_post_meta( $post_id, 'iihlms_lesson_test_conditions_for_displaying', $iihlms_lesson_test_conditions_for_displaying );
			}
			if ( isset( $_POST['lesson_pdfs'] ) && is_array( $_POST['lesson_pdfs'] ) && isset( $_POST['lesson_pdf_names'] ) && is_array( $_POST['lesson_pdf_names'] ) ) {
				$pdf_urls  = array_map( 'sanitize_text_field', wp_unslash( $_POST['lesson_pdfs'] ) );
				$pdf_names = array_map( 'sanitize_text_field', wp_unslash( $_POST['lesson_pdf_names'] ) );
				$pdfs      = array();

				$pdf_count = count( $pdf_urls );
				for ( $i = 0; $i < $pdf_count; $i++ ) {
					$pdfs[] = array(
						'url'  => $pdf_urls[ $i ],
						'name' => $pdf_names[ $i ],
					);
				}
				update_post_meta( $post_id, 'iihlms_lesson_pdfs', $pdfs );
			} else {
				delete_post_meta( $post_id, 'iihlms_lesson_pdfs' );
			}
			if ( isset( $_POST['audio_file_names'] ) && is_array( $_POST['audio_file_names'] ) && isset( $_POST['audio_file_urls'] ) && is_array( $_POST['audio_file_urls'] ) ) {
				$audio_files        = array();
				$file_names         = array_map( 'sanitize_text_field', wp_unslash( $_POST['audio_file_names'] ) );
				$file_urls          = array_map( 'esc_url_raw', wp_unslash( $_POST['audio_file_urls'] ) );
				$file_downloadables = isset( $_POST['audio_file_downloadables'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['audio_file_downloadables'] ) ) : array();

				$file_count = count( $file_names );
				for ( $i = 0; $i < $file_count; $i++ ) {
					$audio_files[] = array(
						'name'         => $file_names[ $i ],
						'url'          => $file_urls[ $i ],
						'downloadable' => isset( $file_downloadables[ $i ] ),
					);
				}
				update_post_meta( $post_id, 'iihlms_audio_files', $audio_files );
			} else {
				delete_post_meta( $post_id, 'iihlms_audio_files' );
			}
			if ( empty( $_POST['iihlms_video_url'] ) ) {
				delete_post_meta( $post_id, 'iihlms_video_url' );
			} else {
				$iihlms_video_url = sanitize_text_field( wp_unslash( $_POST['iihlms_video_url'] ) );
				update_post_meta( $post_id, 'iihlms_video_url', $iihlms_video_url );
			}
		}

		/**
		 * テスト
		 */
		if ( 'iihlms_tests' === get_post_type( $post_id ) ) {
			if ( ! isset( $_POST['iihlms-test-csrf'] ) ) {
				return false;
			}
			if ( ! check_admin_referer( 'iihlms-test-csrf-action', 'iihlms-test-csrf' ) ) {
				return false;
			}
			if ( isset( $_POST['iihlms-selected-test-types'] ) ) {
				update_post_meta( $post_id, 'iihlms_selected_test_types', sanitize_text_field( wp_unslash( $_POST['iihlms-selected-test-types'] ) ) );
			}
			if ( isset( $_POST['iihlms-test-number-of-questions'] ) ) {
				update_post_meta( $post_id, 'iihlms_test_number_of_questions', $this->round_number_lower_and_upper( absint( sanitize_text_field( wp_unslash( $_POST['iihlms-test-number-of-questions'] ) ) ), 1, 100 ) );
			}
			if ( isset( $_POST['iihlms-test-scores-required-to-pass'] ) ) {
				update_post_meta( $post_id, 'iihlms_test_scores_required_to_pass', $this->round_number_lower_and_upper( absint( sanitize_text_field( wp_unslash( $_POST['iihlms-test-scores-required-to-pass'] ) ) ), 1, 100000 ) );
			}
			if ( isset( $_POST['iihlms-test-time-limit'] ) ) {
				update_post_meta( $post_id, 'iihlms_test_time_limit', $this->round_number_lower_and_upper( absint( sanitize_text_field( wp_unslash( $_POST['iihlms-test-time-limit'] ) ) ), 0, 100000 ) );
			}
			if ( isset( $_POST['iihlms-test-number-of-times-limit'] ) ) {
				update_post_meta( $post_id, 'iihlms_test_number_of_times_limit', $this->round_number_lower_and_upper( absint( sanitize_text_field( wp_unslash( $_POST['iihlms-test-number-of-times-limit'] ) ) ), 0, 100000 ) );
			}
			if ( isset( $_POST['iihlms-disallow-passed-test'] ) ) {
				update_post_meta( $post_id, 'iihlms_disallow_passed_test', sanitize_text_field( wp_unslash( $_POST['iihlms-disallow-passed-test'] ) ) );
			}
			if ( isset( $_POST['iihlms-test-view-answer-details'] ) ) {
				update_post_meta( $post_id, 'iihlms_test_view_answer_details', sanitize_text_field( wp_unslash( $_POST['iihlms-test-view-answer-details'] ) ) );
			}
			for ( $i = 1; $i <= $this->round_number_lower_and_upper( absint( sanitize_text_field( wp_unslash( $_POST['iihlms-test-number-of-questions'] ) ) ), 1, 100 ); $i++ ) {
				if ( isset( $_POST[ 'iihlms-test-question-sentence-' . esc_html( $i ) ] ) ) {
					update_post_meta( $post_id, 'iihlms_test_question_sentence_' . esc_html( $i ), sanitize_textarea_field( wp_unslash( $_POST[ 'iihlms-test-question-sentence-' . esc_html( $i ) ] ) ) );
				}
				if ( isset( $_POST[ 'iihlms-test-choice-' . esc_html( $i ) ] ) ) {
					update_post_meta( $post_id, 'iihlms_test_choice_' . esc_html( $i ), sanitize_textarea_field( wp_unslash( $_POST[ 'iihlms-test-choice-' . esc_html( $i ) ] ) ) );
				}
				if ( isset( $_POST[ 'iihlms-test-score-' . esc_html( $i ) ] ) ) {
					update_post_meta( $post_id, 'iihlms_test_score_' . esc_html( $i ), sanitize_textarea_field( wp_unslash( $_POST[ 'iihlms-test-score-' . esc_html( $i ) ] ) ) );
				}
				if ( isset( $_POST[ 'iihlms-test-commentary-' . esc_html( $i ) ] ) ) {
					update_post_meta( $post_id, 'iihlms_test_commentary_' . esc_html( $i ), sanitize_textarea_field( wp_unslash( $_POST[ 'iihlms-test-commentary-' . esc_html( $i ) ] ) ) );
				}
			}
			if ( isset( $_POST['iihlms-test-cert-relationship'] ) ) {
				if ( '0' === sanitize_text_field( wp_unslash( $_POST['iihlms-test-cert-relationship'] ) ) ) {
					delete_post_meta( $post_id, 'iihlms_test_cert_relationship' );
				} else {
					update_post_meta( $post_id, 'iihlms_test_cert_relationship', sanitize_text_field( wp_unslash( $_POST['iihlms-test-cert-relationship'] ) ) );
				}
			} else {
				delete_post_meta( $post_id, 'iihlms_test_cert_relationship' );
			}
		}
		/**
		 * 証明書
		 */
		if ( 'iihlms_test_certs' === get_post_type( $post_id ) ) {
			if ( isset( $_POST['iihlms-test-cert-portrait-landscape-orientation'] ) ) {
				$iihlms_test_cert_portrait_landscape_orientation = sanitize_text_field( wp_unslash( $_POST['iihlms-test-cert-portrait-landscape-orientation'] ) );
				if ( 'L' !== $iihlms_test_cert_portrait_landscape_orientation ) {
					$iihlms_test_cert_portrait_landscape_orientation = 'P';
				}
				update_post_meta( $post_id, 'iihlms_test_cert_portrait_landscape_orientation', $iihlms_test_cert_portrait_landscape_orientation );
			} else {
				delete_post_meta( $post_id, 'iihlms_test_cert_portrait_landscape_orientation' );
			}
			if ( isset( $_POST['iihlms-test-cert-how-to-register-certificate-contents'] ) ) {
				$iihlms_test_cert_how_to_register_certificate_contents = sanitize_text_field( wp_unslash( $_POST['iihlms-test-cert-how-to-register-certificate-contents'] ) );
				update_post_meta( $post_id, 'iihlms_test_cert_how_to_register_certificate_contents', $iihlms_test_cert_how_to_register_certificate_contents );
			} else {
				delete_post_meta( $post_id, 'iihlms_test_cert_how_to_register_certificate_contents' );
			}
		}
	}
	/**
	 * テスト結果のメタボックス
	 *
	 * @return void
	 */
	public function insert_test_result_fields_1() {
		echo esc_html( apply_filters( 'iihlms_addition_insert_test_result_fields_1', '' ) );
	}

	/**
	 * 証明書のメタボックス
	 *
	 * @return void
	 */
	public function insert_cert_fields_1() {
		echo esc_html( apply_filters( 'iihlms_addition_insert_cert_fields_1', '' ) );
	}
	/**
	 * 証明書のメタボックス
	 *
	 * @return void
	 */
	public function insert_cert_fields_2() {
		echo esc_html( apply_filters( 'iihlms_addition_insert_cert_fields_2', '' ) );
	}

	/**
	 * ユーザープロファイルページ
	 *
	 * @param object $user ユーザー.
	 */
	public function iihlms_on_load_user_profile( $user ) {
		echo '<h2>' . esc_html__( 'Imaoikiruhito LMS', 'imaoikiruhitolms' ) . '</h2>';
		wp_nonce_field( 'iihlms-user-profile-csrf-action', 'iihlms-user-profile-csrf' );
		?>
	<span class="p-country-name" style="display:none;">Japan</span>
	<script type="text/javascript">
	(function($) {
		var fm = $( '#your-profile' );
		fm.addClass( 'h-adr' );
	})(jQuery);
	function toPostFmt(obj){
		if((obj.value).trim().length == 7 && !isNaN(obj.value)){
			var str = obj.value.trim();
			var h = str.substr(0,3);
			var m = str.substr(3);
			obj.value = h + "-" + m;
		}
	}
	function offPostFmt(obj){
		var reg = new RegExp("-", "g");
		var chgVal = obj.value.replace(reg, "");
		if(!isNaN(chgVal)){
			obj.value = chgVal;
		}
	}
	</script>

	<table class="form-table" role="presentation">
	<tr>
		<?php
		echo '<th><label for="iihlms-name1">' . esc_html__( '姓', 'imaoikiruhitolms' ) . '</label></th>';
		?>
	<td><input type="text" name="iihlms-name1" id="iihlms-name1" value="<?php echo esc_attr( get_user_meta( $user->ID, 'iihlms_user_name1', true ) ); ?>" class="regular-text"> <?php echo esc_html__( '画面上部にある姓ではなくこちらの姓を使用します。', 'imaoikiruhitolms' ); ?></td>
	</tr>
	<tr>
		<?php
		echo '<th><label for="iihlms-name2">' . esc_html__( '名', 'imaoikiruhitolms' ) . '</label></th>';
		?>
	<td><input type="text" name="iihlms-name2" id="iihlms-name2" value="<?php echo esc_attr( get_user_meta( $user->ID, 'iihlms_user_name2', true ) ); ?>" class="regular-text"> <?php echo esc_html__( '画面上部にある名ではなくこちらの名を使用します。', 'imaoikiruhitolms' ); ?></td>
	</tr>
	<tr>
		<?php
		echo '<th><label for="iihlms-zip">' . esc_html__( '郵便番号', 'imaoikiruhitolms' ) . '</label></th>';
		?>
	<td><input type="text" size="8" minlength="7" maxlength="8" name="iihlms-zip" id="iihlms-zip" value="<?php echo esc_attr( get_user_meta( $user->ID, 'iihlms_user_zip', true ) ); ?>" class="p-postal-code" onfocus="offPostFmt(this);" onblur="toPostFmt(this);"></td>
	</tr>
	<tr>
		<?php
		echo '<th><label for="iihlms-prefectures">' . esc_html__( '都道府県', 'imaoikiruhitolms' ) . '</label></th>';
		?>
	<td>
	<select name="iihlms-prefectures" id="iihlms-prefectures" class="p-region">
	<option value="北海道"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '北海道' ); ?>>北海道</option>
	<option value="青森県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '青森県' ); ?>>青森県</option>
	<option value="岩手県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '岩手県' ); ?>>岩手県</option>
	<option value="宮城県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '宮城県' ); ?>>宮城県</option>
	<option value="秋田県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '秋田県' ); ?>>秋田県</option>
	<option value="山形県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '山形県' ); ?>>山形県</option>
	<option value="福島県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '福島県' ); ?>>福島県</option>
	<option value="茨城県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '茨城県' ); ?>>茨城県</option>
	<option value="栃木県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '栃木県' ); ?>>栃木県</option>
	<option value="群馬県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '群馬県' ); ?>>群馬県</option>
	<option value="埼玉県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '埼玉県' ); ?>>埼玉県</option>
	<option value="千葉県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '千葉県' ); ?>>千葉県</option>
	<option value="東京都"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '東京都' ); ?>>東京都</option>
	<option value="神奈川県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '神奈川県' ); ?>>神奈川県</option>
	<option value="新潟県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '新潟県' ); ?>>新潟県</option>
	<option value="富山県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '富山県' ); ?>>富山県</option>
	<option value="石川県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '石川県' ); ?>>石川県</option>
	<option value="福井県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '福井県' ); ?>>福井県</option>
	<option value="山梨県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '山梨県' ); ?>>山梨県</option>
	<option value="長野県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '長野県' ); ?>>長野県</option>
	<option value="岐阜県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '岐阜県' ); ?>>岐阜県</option>
	<option value="静岡県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '静岡県' ); ?>>静岡県</option>
	<option value="愛知県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '愛知県' ); ?>>愛知県</option>
	<option value="三重県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '三重県' ); ?>>三重県</option>
	<option value="滋賀県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '滋賀県' ); ?>>滋賀県</option>
	<option value="京都府"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '京都府' ); ?>>京都府</option>
	<option value="大阪府"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '大阪府' ); ?>>大阪府</option>
	<option value="兵庫県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '兵庫県' ); ?>>兵庫県</option>
	<option value="奈良県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '奈良県' ); ?>>奈良県</option>
	<option value="和歌山県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '和歌山県' ); ?>>和歌山県</option>
	<option value="鳥取県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '鳥取県' ); ?>>鳥取県</option>
	<option value="島根県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '島根県' ); ?>>島根県</option>
	<option value="岡山県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '岡山県' ); ?>>岡山県</option>
	<option value="広島県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '広島県' ); ?>>広島県</option>
	<option value="山口県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '山口県' ); ?>>山口県</option>
	<option value="徳島県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '徳島県' ); ?>>徳島県</option>
	<option value="香川県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '香川県' ); ?>>香川県</option>
	<option value="愛媛県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '愛媛県' ); ?>>愛媛県</option>
	<option value="高知県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '高知県' ); ?>>高知県</option>
	<option value="福岡県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '福岡県' ); ?>>福岡県</option>
	<option value="佐賀県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '佐賀県' ); ?>>佐賀県</option>
	<option value="長崎県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '長崎県' ); ?>>長崎県</option>
	<option value="熊本県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '熊本県' ); ?>>熊本県</option>
	<option value="大分県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '大分県' ); ?>>大分県</option>
	<option value="宮崎県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '宮崎県' ); ?>>宮崎県</option>
	<option value="鹿児島県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '鹿児島県' ); ?>>鹿児島県</option>
	<option value="沖縄県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '沖縄県' ); ?>>沖縄県</option>
	</select>
	</td>
	</tr>
	<tr>
		<?php
		echo '<th><label for="iihlms-address1">' . esc_html__( '市区郡町村', 'imaoikiruhitolms' ) . '</label></th>';
		?>
	<td><input type="text" name="iihlms-address1" id="iihlms-address1" value="<?php echo esc_html( get_user_meta( $user->ID, 'iihlms_user_address1', true ) ); ?>" class="p-locality regular-text"></td>
	</tr>
	<tr>
		<?php
		echo '<th><label for="iihlms-address2">' . esc_html__( '番地・マンション名など', 'imaoikiruhitolms' ) . '</label></th>';
		?>
	<td><input type="text" name="iihlms-address2" id="iihlms-address2" value="<?php echo esc_html( get_user_meta( $user->ID, 'iihlms_user_address2', true ) ); ?>" class="p-street-address regular-text"></td>
	</tr>
	<tr>
		<?php
		echo '<th><label for="iihlms-company-name">' . esc_html__( '会社名', 'imaoikiruhitolms' ) . '</label></th>';
		?>
	<td><input type="text" name="iihlms-company-name" id="iihlms-company-name" value="<?php echo esc_html( get_user_meta( $user->ID, 'iihlms_company_name', true ) ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<?php
		echo '<th><label for="iihlms-tel">' . esc_html__( '電話番号', 'imaoikiruhitolms' ) . '</label></th>';
		?>
	<td><input type="text" name="iihlms-tel" id="iihlms-tel" value="<?php echo esc_html( get_user_meta( $user->ID, 'iihlms_user_tel', true ) ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<?php
		echo '<th><label for="iihlms-tel">' . esc_html__( '会員ステータス', 'imaoikiruhitolms' ) . '</label></th>';
		?>
	<td>
		<?php
		global $wpdb;

		$iihlms_user_membership_status = get_user_meta( $user->ID, 'iihlms_user_membership_status', true );

		$zeronum          = 0;
		$membership_table = $wpdb->prefix . 'iihlms_membership';

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT *
				FROM %1s
				WHERE 
					iihlms_membership_id > %2d
				',
				$membership_table,
				$zeronum
			)
		);

		echo '<input type="hidden" name="iihlms-user-membership-status-hidden" value="1">';
		foreach ( $results as $result ) {
			$id   = $result->iihlms_membership_id;
			$name = $result->membership_name;
			echo '<label class="iihlms-checkbox-label" for=';
			echo '"iihlms-user-membership-status-id' . esc_html( $id );
			echo '">';
			echo '<input type="checkbox" name="iihlms-user-membership-status[]';
			echo '" id="iihlms-user-membership-status-id' . esc_html( $id );
			echo '" value="' . esc_html( $id );
			echo '" ';
			if ( ! empty( $iihlms_user_membership_status ) ) {
				checked( ( in_array( (string) $id, $iihlms_user_membership_status, true ) ) );
			}
			echo '>';
			echo esc_html( $name );
			echo '</label>';
		}
		?>
	</td>
	</tr>
	<tr>
		<?php
		echo '<th>' . esc_html__( '購入済の講座変更', 'imaoikiruhitolms' ) . '</th>';
		?>
	<td>
		<?php
		global $wpdb;

		// 購入済の講座一覧.
		$order_table = $wpdb->prefix . 'iihlms_order';
		$results     = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT item_id
				FROM %1s
				WHERE 
				user_id = %2d
				',
				$order_table,
				$user->ID,
			)
		);

		$items_purchased = array();
		foreach ( $results as $row ) {
			array_push( $items_purchased, $row->item_id );
		}
		$items_purchased_implode = implode( ',', $items_purchased );

		// 未購入の講座一覧.
		$post_type = 'iihlms_items';
		if ( '' === $items_purchased_implode ) {
			$results = $wpdb->get_results(
				$wpdb->prepare(
					"
					SELECT ID
					FROM $wpdb->posts
					WHERE 
						post_type = %s
							AND post_status = 'publish'
					",
					$post_type,
				)
			);
		} else {
			$results = $wpdb->get_results(
				$wpdb->prepare(
					"
					SELECT ID
					FROM $wpdb->posts
					WHERE 
						post_type = %s
							AND post_status = 'publish'
							AND ID NOT IN ( %2s )
					",
					$post_type,
					$items_purchased_implode
				)
			);
		}

		$items_purchased_not = array();
		foreach ( $results as $row ) {
			array_push( $items_purchased_not, $row->ID );
		}
		$items_purchased_not_implode = implode( ',', $items_purchased_not );

		$option_purchased     = '';
		$option_purchased_not = '';
		?>

		<table class="iihlms-select-multi-table">
		<tr>
			<td class="iihlms-select-multi-left">
				<?php
				echo '<input id="target-text-left" placeholder="' . esc_html__( '未購入の講座を検索', 'imaoikiruhitolms' ) . '" type="text" class="iihlms-select-search">';
				?>
				<select id="target-options-left" class="iihlms-select-mutiple" name="iihlms-select-mutiple-left" size="10" multiple="multiple">
					<?php
					if ( ! empty( $items_purchased_not ) ) {
						$args = array(
							'post_type'           => 'iihlms_items',
							'posts_per_page'      => -1,
							'post_status'         => 'publish',
							'post__in'            => $items_purchased_not,
							'ignore_sticky_posts' => 1,
						);

						$my_posts = get_posts( $args );
						foreach ( $my_posts as $postdata ) {
							echo '<option value="';
							echo esc_attr( $postdata->ID );
							echo '">';
							echo esc_html( get_the_title( $postdata ) );
							echo '</option>';
						}
						wp_reset_postdata();
					}
					?>
				</select>
			</td>
			<td class="iihlms-select-multi-middle">
				<input type="button" name="right" value="≫" /><br /><br />
				<input type="button" name="left" value="≪" />
			</td>
			<td class="iihlms-select-multi-right">
				<?php
				echo '<input id="target-text-right" placeholder="' . esc_html__( '購入済みの講座を検索', 'imaoikiruhitolms' ) . '" type="text" class="iihlms-select-search">';
				?>
				<select id="target-options-right" class="iihlms-select-mutiple" name="iihlms-select-mutiple-right" size="10" multiple="multiple">
					<?php
					if ( ! empty( $items_purchased ) ) {
						$args = array(
							'post_type'           => 'iihlms_items',
							'posts_per_page'      => -1,
							'post_status'         => 'publish',
							'post__in'            => $items_purchased,
							'ignore_sticky_posts' => 1,
						);

						$my_posts = get_posts( $args );
						foreach ( $my_posts as $postdata ) {
							echo '<option value="';
							echo esc_attr( $postdata->ID );
							echo '">';
							echo esc_html( get_the_title( $postdata ) );
							echo '</option>';
						}
						wp_reset_postdata();
					}
					?>
				</select>
			</td>
		</tr>
		</table>
		<?php
		echo '<p>' . esc_html__( '変更内容', 'imaoikiruhitolms' ) . '<br><span id="iihlms-select-items-change"></span></p>';
		?>
		<span id="iihlms-select-items-change-code-add-wrap"></span>
		<span id="iihlms-select-items-change-code-del-wrap"></span>

	</td>
	</tr>
	</table>

		<?php
	}

	/**
	 * 新規ユーザーを追加ページ
	 *
	 * @param object $user ユーザー.
	 */
	public function iihlms_new_user_profile( $user ) {
		echo '<h2>' . esc_html__( 'Imaoikiruhito LMS', 'imaoikiruhitolms' ) . '</h2>';
		wp_nonce_field( 'iihlms-user-profile-csrf-action', 'iihlms-user-profile-csrf' );
		?>
	<span class="p-country-name" style="display:none;">Japan</span>
	<script type="text/javascript">
	(function($) {
		var fm = $( '#createuser' );
		fm.addClass( 'h-adr' );
	})(jQuery);
	function toPostFmt(obj){
		if((obj.value).trim().length == 7 && !isNaN(obj.value)){
			var str = obj.value.trim();
			var h = str.substr(0,3);
			var m = str.substr(3);
			obj.value = h + "-" + m;
		}
	}
	function offPostFmt(obj){
		var reg = new RegExp("-", "g");
		var chgVal = obj.value.replace(reg, "");
		if(!isNaN(chgVal)){
			obj.value = chgVal;
		}
	}
	</script>

	<table class="form-table" role="presentation">
	<tr>
		<?php
		echo '<th><label for="iihlms-name1">' . esc_html__( '姓', 'imaoikiruhitolms' ) . '</label></th>';
		echo '<td><input type="text" name="iihlms-name1" id="iihlms-name1" value="" class="regular-text"> ' . esc_html__( '画面上部にある姓ではなくこちらの姓を使用します。', 'imaoikiruhitolms' ) . '</td>';
		?>
	</tr>
	<tr>
		<?php
		echo '<th><label for="iihlms-name2">' . esc_html__( '名', 'imaoikiruhitolms' ) . '</label></th>';
		echo '<td><input type="text" name="iihlms-name2" id="iihlms-name2" value="" class="regular-text"> ' . esc_html__( '画面上部にある名ではなくこちらの名を使用します。', 'imaoikiruhitolms' ) . '</td>';
		?>
	</tr>
	<tr>
		<?php
		echo '<th><label for="iihlms-zip">' . esc_html__( '郵便番号', 'imaoikiruhitolms' ) . '</label></th>';
		?>
	<td><input type="text" size="8" minlength="7" maxlength="8" name="iihlms-zip" id="iihlms-zip" value="" class="p-postal-code" onfocus="offPostFmt(this);" onblur="toPostFmt(this);"></td>
	</tr>
	<tr>
		<?php
		echo '<th><label for="iihlms-prefectures">' . esc_html__( '都道府県', 'imaoikiruhitolms' ) . '</label></th>';
		?>
	<td>
	<select name="iihlms-prefectures" id="iihlms-prefectures" class="p-region">
	<option value=""></option>
	<option value="北海道">北海道</option>
	<option value="青森県">青森県</option>
	<option value="岩手県">岩手県</option>
	<option value="宮城県">宮城県</option>
	<option value="秋田県">秋田県</option>
	<option value="山形県">山形県</option>
	<option value="福島県">福島県</option>
	<option value="茨城県">茨城県</option>
	<option value="栃木県">栃木県</option>
	<option value="群馬県">群馬県</option>
	<option value="埼玉県">埼玉県</option>
	<option value="千葉県">千葉県</option>
	<option value="東京都">東京都</option>
	<option value="神奈川県">神奈川県</option>
	<option value="新潟県">新潟県</option>
	<option value="富山県">富山県</option>
	<option value="石川県">石川県</option>
	<option value="福井県">福井県</option>
	<option value="山梨県">山梨県</option>
	<option value="長野県">長野県</option>
	<option value="岐阜県">岐阜県</option>
	<option value="静岡県">静岡県</option>
	<option value="愛知県">愛知県</option>
	<option value="三重県">三重県</option>
	<option value="滋賀県">滋賀県</option>
	<option value="京都府">京都府</option>
	<option value="大阪府">大阪府</option>
	<option value="兵庫県">兵庫県</option>
	<option value="奈良県">奈良県</option>
	<option value="和歌山県">和歌山県</option>
	<option value="鳥取県">鳥取県</option>
	<option value="島根県">島根県</option>
	<option value="岡山県">岡山県</option>
	<option value="広島県">広島県</option>
	<option value="山口県">山口県</option>
	<option value="徳島県">徳島県</option>
	<option value="香川県">香川県</option>
	<option value="愛媛県">愛媛県</option>
	<option value="高知県">高知県</option>
	<option value="福岡県">福岡県</option>
	<option value="佐賀県">佐賀県</option>
	<option value="長崎県">長崎県</option>
	<option value="熊本県">熊本県</option>
	<option value="大分県">大分県</option>
	<option value="宮崎県">宮崎県</option>
	<option value="鹿児島県">鹿児島県</option>
	<option value="沖縄県">沖縄県</option>
	</select>
	</td>
	</tr>
	<tr>
		<?php
		echo '<th><label for="iihlms-address1">' . esc_html__( '市区郡町村', 'imaoikiruhitolms' ) . '</label></th>';
		?>
	<td><input type="text" name="iihlms-address1" id="iihlms-address1" value="" class="p-locality regular-text"></td>
	</tr>
	<tr>
		<?php
		echo '<th><label for="iihlms-address2">' . esc_html__( '番地・マンション名など', 'imaoikiruhitolms' ) . '</label></th>';
		?>
	<td><input type="text" name="iihlms-address2" id="iihlms-address2" value="" class="p-street-address regular-text"></td>
	</tr>
	<tr>
		<?php
		echo '<th><label for="iihlms-company-name">' . esc_html__( '会社名', 'imaoikiruhitolms' ) . '</label></th>';
		?>
	<td><input type="text" name="iihlms-company-name" id="iihlms-company-name" value="" class="regular-text"></td>
	</tr>
	<tr>
		<?php
		echo '<th><label for="iihlms-tel">' . esc_html__( '電話番号', 'imaoikiruhitolms' ) . '</label></th>';
		?>
	<td><input type="text" name="iihlms-tel" id="iihlms-tel" value="" class="regular-text"></td>
	</tr>
	<tr>
		<?php
		echo '<th><label for="iihlms-tel">' . esc_html__( '会員ステータス', 'imaoikiruhitolms' ) . '</label></th>';
		?>
	<td>
		<?php
		global $wpdb;

		$zeronum          = 0;
		$membership_table = $wpdb->prefix . 'iihlms_membership';

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT *
				FROM %1s
				WHERE 
					iihlms_membership_id > %2d
				',
				$membership_table,
				$zeronum
			)
		);
		echo '<input type="hidden" name="iihlms-user-membership-status-hidden" value="1">';
		foreach ( $results as $result ) {
			$id   = $result->iihlms_membership_id;
			$name = $result->membership_name;
			echo '<label class="iihlms-checkbox-label" for=';
			echo '"iihlms-user-membership-status-id' . esc_html( $id );
			echo '">';
			echo '<input type="checkbox" name="iihlms-user-membership-status[]';
			echo '" id="iihlms-user-membership-status-id' . esc_html( $id );
			echo '" value="' . esc_html( $id );
			echo '" ';
			echo '>';
			echo esc_html( $name );
			echo '</label>';
		}
		?>
	</td>
	</tr>
	</table>

		<?php
	}

	/**
	 * ユーザー情報保存
	 *
	 * @param string $user_id ユーザーID.
	 * @return bool
	 */
	public function iihlms_save_user_profile( $user_id ) {
		if ( ! isset( $_POST['iihlms-user-profile-csrf'] ) ) {
			return false;
		}
		if ( ! check_admin_referer( 'iihlms-user-profile-csrf-action', 'iihlms-user-profile-csrf' ) ) {
			return false;
		}
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}
		if ( empty( $user_id ) ) {
			return false;
		}

		global $wpdb;

		if ( isset( $_POST['iihlms-name1'] ) ) {
			update_user_meta( $user_id, 'iihlms_user_name1', sanitize_text_field( wp_unslash( $_POST['iihlms-name1'] ) ) );
		}
		if ( isset( $_POST['iihlms-name2'] ) ) {
			update_user_meta( $user_id, 'iihlms_user_name2', sanitize_text_field( wp_unslash( $_POST['iihlms-name2'] ) ) );
		}
		if ( isset( $_POST['iihlms-zip'] ) ) {
			update_user_meta( $user_id, 'iihlms_user_zip', sanitize_text_field( wp_unslash( $_POST['iihlms-zip'] ) ) );
		}
		if ( isset( $_POST['iihlms-prefectures'] ) ) {
			update_user_meta( $user_id, 'iihlms_user_prefectures', sanitize_text_field( wp_unslash( $_POST['iihlms-prefectures'] ) ) );
		}
		if ( isset( $_POST['iihlms-address1'] ) ) {
			update_user_meta( $user_id, 'iihlms_user_address1', sanitize_text_field( wp_unslash( $_POST['iihlms-address1'] ) ) );
		}
		if ( isset( $_POST['iihlms-address2'] ) ) {
			update_user_meta( $user_id, 'iihlms_user_address2', sanitize_text_field( wp_unslash( $_POST['iihlms-address2'] ) ) );
		}
		if ( isset( $_POST['iihlms-address3'] ) ) {
			update_user_meta( $user_id, 'iihlms_user_address3', sanitize_text_field( wp_unslash( $_POST['iihlms-address3'] ) ) );
		}
		if ( isset( $_POST['iihlms-company-name'] ) ) {
			update_user_meta( $user_id, 'iihlms_company_name', sanitize_text_field( wp_unslash( $_POST['iihlms-company-name'] ) ) );
		}
		if ( isset( $_POST['iihlms-tel'] ) ) {
			update_user_meta( $user_id, 'iihlms_user_tel', sanitize_text_field( wp_unslash( $_POST['iihlms-tel'] ) ) );
		}
		if ( isset( $_POST['iihlms-user-membership-status-hidden'] ) ) {
			if ( isset( $_POST['iihlms-user-membership-status'] ) ) {
				$post_iihlms_user_membership_status_data = array();
				foreach ( array_map( 'absint', ( wp_unslash( $_POST['iihlms-user-membership-status'] ) ) ) as $key => $value ) {
					$post_iihlms_user_membership_status_data[] = sanitize_text_field( $value );
				}
				update_user_meta( $user_id, 'iihlms_user_membership_status', $post_iihlms_user_membership_status_data );
			} else {
				delete_user_meta( $user_id, 'iihlms_user_membership_status' );
			}
		}

		// 購入済の講座変更.
		$order_table      = $wpdb->prefix . 'iihlms_order';
		$order_cart_table = $wpdb->prefix . 'iihlms_order_cart';

		if ( isset( $_POST['iihlms-select-items-change-code-add'] ) ) {
			// 購入済の講座が含まれているかチェック.
			$post_code_add_data = array();
			foreach ( array_map( 'absint', wp_unslash( $_POST['iihlms-select-items-change-code-add'] ) ) as $key => $value ) {
				$post_code_add_data[] = sanitize_text_field( $value );
			}
			$items = implode( ',', $post_code_add_data );

			$results = $wpdb->get_results(
				$wpdb->prepare(
					'
					SELECT item_id
					FROM %1s
					WHERE 
						user_id = %2d
						AND item_id IN ( %3s )
					',
					$order_table,
					$user_id,
					$items
				)
			);
			$number  = count( $results );

			if ( 0 === $number ) {
				$userdata     = get_userdata( $user_id );
				$email        = $userdata->user_email;
				$name1        = get_user_meta( $user_id, 'iihlms_user_name1', true );
				$name2        = get_user_meta( $user_id, 'iihlms_user_name2', true );
				$tel          = get_user_meta( $user_id, 'iihlms_user_tel', true );
				$payment      = 'admin';
				$order_status = 'manual-assignment-by-administrator';

				foreach ( $post_code_add_data as $key => $value ) {
					$itemid    = $value;
					$item_data = $this->get_item_data( $itemid );

					$wpdb->insert(
						$order_cart_table,
						array(
							'user_id'             => $user_id,
							'item_id'             => $itemid,
							'item_name'           => $item_data['title'],
							'user_email'          => $email,
							'user_name1'          => $name1,
							'user_name2'          => $name2,
							'tel1'                => $tel,
							'payment_name'        => $payment,
							'order_status'        => $order_status,
							'registered_datetime' => current_time( 'mysql' ),
						),
						array(
							'%d',
							'%d',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
						)
					);
					$lastid = $wpdb->insert_id;

					$wpdb->insert(
						$order_table,
						array(
							'user_id'         => $user_id,
							'item_id'         => $itemid,
							'item_name'       => $item_data['title'],
							'user_email'      => $email,
							'user_name1'      => $name1,
							'user_name2'      => $name2,
							'tel1'            => $tel,
							'payment_name'    => $payment,
							'order_status'    => $order_status,
							'order_cart_id'   => $lastid,
							'order_date_time' => current_time( 'mysql' ),
						),
						array(
							'%d',
							'%d',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%d',
							'%s',
						)
					);
					// 会員ステータスを自動付与.
					$iihlms_item_membership_automatic_grant_data = get_post_meta( $itemid, 'iihlms_item_membership_automatic_grant', true );
					$iihlms_item_membership_automatic_grant_data = isset( $iihlms_item_membership_automatic_grant_data ) ? (array) $iihlms_item_membership_automatic_grant_data : array();
					if ( ! empty( $iihlms_item_membership_automatic_grant_data[0] ) ) {
						$iihlms_user_membership_status_now = get_user_meta( $user_id, 'iihlms_user_membership_status', true );
						$iihlms_user_membership_status_now = isset( $iihlms_user_membership_status_now ) ? (array) $iihlms_user_membership_status_now : array();
						$iihlms_user_membership_status     = array_merge( $iihlms_item_membership_automatic_grant_data, $iihlms_user_membership_status_now );
						$iihlms_user_membership_status     = array_unique( $iihlms_user_membership_status );
						$key                               = array_search( '', $iihlms_user_membership_status, true );
						if ( ! is_bool( $key ) ) {
							unset( $iihlms_user_membership_status[ $key ] );
							$iihlms_user_membership_status = array_values( $iihlms_user_membership_status );
						}
						update_user_meta( $user_id, 'iihlms_user_membership_status', $iihlms_user_membership_status );
					}
				}
			}
		}

		if ( isset( $_POST['iihlms-select-items-change-code-del'] ) ) {
			// 未購入の講座が含まれているかチェック.
			$post_code_del_data = array();
			foreach ( array_map( 'absint', wp_unslash( $_POST['iihlms-select-items-change-code-del'] ) ) as $key => $value ) {
				$post_code_del_data[] = sanitize_text_field( $value );
			}
			$items       = implode( ',', $post_code_del_data );
			$items_count = count( $post_code_del_data );

			$results = $wpdb->get_results(
				$wpdb->prepare(
					'
					SELECT item_id
					FROM %1s
					WHERE 
						user_id = %2d
						AND item_id IN ( %3s )
					',
					$order_table,
					$user_id,
					$items
				)
			);
			$number  = count( $results );

			if ( $number === $items_count ) {
				$userdata     = get_userdata( $user_id );
				$email        = $userdata->user_email;
				$name1        = get_user_meta( $user_id, 'iihlms_user_name1', true );
				$name2        = get_user_meta( $user_id, 'iihlms_user_name2', true );
				$tel          = get_user_meta( $user_id, 'iihlms_user_tel', true );
				$payment      = 'admin';
				$order_status = 'manual-deletion-by-administrator';

				$wpdb->query(
					$wpdb->prepare(
						'
						DELETE FROM %1s
						WHERE 
							user_id = %2d
							AND item_id IN ( %3s )
						',
						$order_table,
						$user_id,
						$items
					)
				);

				foreach ( $post_code_del_data as $key => $value ) {
					$item_data = $this->get_item_data( $value );
					$wpdb->insert(
						$order_cart_table,
						array(
							'user_id'             => $user_id,
							'item_id'             => $value,
							'item_name'           => $item_data['title'],
							'user_email'          => $email,
							'user_name1'          => $name1,
							'user_name2'          => $name2,
							'tel1'                => $tel,
							'payment_name'        => $payment,
							'order_status'        => $order_status,
							'registered_datetime' => current_time( 'mysql' ),
						),
						array(
							'%d',
							'%d',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
						)
					);
				}
			}
		}

		if ( isset( $items_purchase_status ) ) {
			update_user_meta( $user_id, 'imoikiruhitolms_items_purchase_status', $items_purchase_status );
		} else {
			delete_user_meta( $user_id, 'imoikiruhitolms_items_purchase_status' );
		}

		// 受講可能なコースの手動割当.
		if ( isset( $_POST['iihlms-select-course-manual-assignment-change-code-add'] ) || ! empty( $_POST['iihlms-select-course-manual-assignment-change-code-del'] ) ) {
			$course_manual_assignment      = get_user_meta( $user_id, 'iihlms_user_course_manual_assignment', true );
			$course_manual_assignment      = isset( $course_manual_assignment ) ? (array) $course_manual_assignment : array();
			$course_manual_assignment_flip = array_flip( $course_manual_assignment );

			if ( ! empty( $_POST['iihlms-select-course-manual-assignment-change-code-add'] ) ) {
				foreach ( sanitize_text_field( wp_unslash( $_POST['iihlms-select-course-manual-assignment-change-code-add'] ) ) as $key => $value ) {
					if ( ! isset( $course_manual_assignment_flip[ $value ] ) ) {
						$course_manual_assignment[] = $value;
					}
				}
			}
			if ( ! empty( $_POST['iihlms-select-course-manual-assignment-change-code-del'] ) ) {
				foreach ( sanitize_text_field( wp_unslash( $_POST['iihlms-select-course-manual-assignment-change-code-del'] ) ) as $key => $value ) {
					if ( isset( $course_manual_assignment_flip[ $value ] ) ) {
						$course_manual_assignment = array_diff( $course_manual_assignment, array( $value ) );
						$course_manual_assignment = array_values( $course_manual_assignment );
					}
				}
			}

			if ( isset( $course_manual_assignment ) ) {
				update_user_meta( $user_id, 'iihlms_user_course_manual_assignment', $course_manual_assignment );
			} else {
				delete_user_meta( $user_id, 'iihlms_user_course_manual_assignment' );
			}
		}

		return true;
	}

	/**
	 * 管理画面で使用するCSS読み込み
	 *
	 * @return void
	 */
	public function register_admin_styles() {
		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( in_array( $screen->id, $this->plugin_screen_hook_suffix, true ) ) {
			wp_enqueue_style( self::DOMAIN . '-admin-style-1', IIHLMS_PLUGIN_URL . '/css/imaoikiruhito-lms.css', '', '1.0.0' );
			wp_enqueue_style( self::DOMAIN . '-admin-style-2', IIHLMS_PLUGIN_URL . '/css/jquery-ui.min.css', '', '1.0.0' );
		}
	}

	/**
	 * 管理画面で使用するスクリプト読み込み
	 *
	 * @return void
	 */
	public function register_admin_scripts() {
		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( in_array( $screen->id, $this->plugin_screen_hook_suffix, true ) ) {
			wp_enqueue_script( self::DOMAIN . '-admin-script-1', IIHLMS_PLUGIN_URL . '/js/imaoikiruhito-lms.js', array( 'jquery' ), '1.0.0', false );
			wp_enqueue_script( self::DOMAIN . '-admin-script-2', IIHLMS_PLUGIN_URL . '/js/jquery-ui.min.js', array( 'jquery' ), '1.0.0', false );
			wp_enqueue_script( 'admin_postcode_script1', 'https://yubinbango.github.io/yubinbango/yubinbango.js', array( 'jquery' ), '1.0.0', true );
		}
	}

	/**
	 * メッセージ通知
	 *
	 * @param int    $transient transient.
	 * @param string $type type.
	 * @param string $message message.
	 * @param int    $expiration expiration.
	 *
	 * @return void
	 */
	public function set_admin_notice_message( $transient, $type, $message, $expiration ) {
		$messages = get_transient( $transient );
		if ( ! is_array( $messages ) ) {
			$messages = array();
		}
		$messages[ $type ][] = $message;

		set_transient( $transient, $messages, $expiration );
	}

	/**
	 * メッセージを表示
	 *
	 * @return void
	 */
	public function my_admin_notices() {
		global $pagenow;
		global $post;

		$messages = get_transient( 'lmsjp-custom-admin-errors' );
		if ( is_array( $messages ) ) {
			foreach ( $messages as $type => $messages_of_type ) {
				foreach ( $messages_of_type as $message ) {
					echo '<div class="notice notice-' . esc_attr( $type ) . ' is-dismissible">';
					echo '<p>';
					echo esc_html( $message );
					echo '</p>';
					echo '</div>';
				}
			}
		}
		delete_transient( 'lmsjp-custom-admin-errors' );
	}

	/**
	 * 自動整形機能を無効にする
	 *
	 * @return void
	 */
	public function disable_page_wpautop() {
		if ( is_page() ) {
			remove_filter( 'the_content', 'wpautop' );
		}
	}

	/**
	 * 管理者以外はダッシュボードに入れない
	 *
	 * @return void
	 */
	public function disable_admin_pages() {
		if ( ! current_user_can( self::CAPABILITY_ADMIN ) ) {
			wp_safe_redirect( get_home_url() );
			exit;
		}
	}

	/**
	 * 管理者以外はアドミンバー非表示
	 *
	 * @param object $content コンテンツ.
	 * @return content or false
	 */
	public function hide_admin_bar( $content ) {
		if ( current_user_can( self::CAPABILITY_ADMIN ) ) {
			return $content;
		} else {
			return false;
		}
	}

	/**
	 * ログアウトリンクを取得
	 *
	 * @return string
	 */
	public function get_logouturl() {
		$string = wp_logout_url();
		return $string;
	}

	/**
	 * プラグインが有効化されたときの処理
	 *
	 * @return void
	 */
	public function activate_plugin() {
		$this->create_table();
		$this->set_default_page();
		$this->set_default_setting();
	}

	/**
	 * プラグインが読み込まれた後の処理
	 *
	 * @return void
	 */
	public function load_plugin() {
		$this->specify_date_format             = esc_html__( 'Y年m月d日', 'imaoikiruhitolms' );
		$this->specify_date_format_hyphen      = esc_html__( 'Y-m-d', 'imaoikiruhitolms' );
		$this->specify_date_time_format        = esc_html__( 'Y年m月d日 G時i分s秒', 'imaoikiruhitolms' );
		$this->specify_date_time_format_hyphen = esc_html__( 'Y-m-d G:i:s', 'imaoikiruhitolms' );
		$this->create_table();
	}

	/**
	 * テーブルを作成更新
	 *
	 * @return void
	 */
	public function create_table() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$order_table              = $wpdb->prefix . 'iihlms_order';
		$order_meta_table         = $wpdb->prefix . 'iihlms_order_meta';
		$order_cart_table         = $wpdb->prefix . 'iihlms_order_cart';
		$order_cart_meta_table    = $wpdb->prefix . 'iihlms_order_cart_meta';
		$user_activity_table      = $wpdb->prefix . 'iihlms_user_activity';
		$user_activity_meta_table = $wpdb->prefix . 'iihlms_user_activity_meta';
		$membership_table         = $wpdb->prefix . 'iihlms_membership';
		$pre_user_table           = $wpdb->prefix . 'iihlms_pre_user';

		$order_table_ver              = get_option( 'iihlms_db_order', '0' );
		$order_meta_table_ver         = get_option( 'iihlms_db_order_meta', '0' );
		$order_cart_table_ver         = get_option( 'iihlms_db_order_cart', '0' );
		$order_cart_meta_table_ver    = get_option( 'iihlms_db_order_cart_meta', '0' );
		$user_activity_table_ver      = get_option( 'iihlms_db_user_activity', '0' );
		$user_activity_meta_table_ver = get_option( 'iihlms_db_user_activity_meta', '0' );
		$membership_table_ver         = get_option( 'iihlms_db_membership', '0' );
		$pre_user_table_ver           = get_option( 'iihlms_db_pre_user', '0' );

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		if ( IIHLMS_DB_ORDER !== $order_table_ver ) {
			$sql = 'CREATE TABLE ' . $order_table . " (
				order_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				user_id bigint(20) unsigned NOT NULL,
				item_id bigint(20) unsigned NOT NULL,
				item_name varchar(255) DEFAULT NULL,
				user_email varchar(100) NOT NULL,
				user_name1 varchar(100) NOT NULL,
				user_name2 varchar(100) DEFAULT NULL,
				user_name3 varchar(100) DEFAULT NULL,
				user_name4 varchar(100) DEFAULT NULL,
				zip varchar(50) DEFAULT NULL,
				prefectures varchar(100) DEFAULT NULL,
				address1 varchar(100) DEFAULT NULL,
				address2 varchar(100) DEFAULT NULL,
				address3 varchar(100) DEFAULT NULL,
				address4 varchar(100) DEFAULT NULL,
				tel1 varchar(100) DEFAULT NULL,
				tel2 varchar(100) DEFAULT NULL,
				fax varchar(100) DEFAULT NULL,
				company_name varchar(255) DEFAULT NULL,
				payment_name varchar(100) NOT NULL,
				order_key varchar(255) DEFAULT NULL,
				order_status varchar(255) DEFAULT NULL,
				price int(11) DEFAULT NULL,
				tax int(11) DEFAULT NULL,
				order_date_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				order_cart_id bigint(20) unsigned NOT NULL,
				expiration_date_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				note text DEFAULT NULL,
				PRIMARY KEY (order_id),
				KEY user_id (user_id),
				KEY user_email (user_email),
				KEY user_name1 (user_name1),
				KEY user_name2 (user_name2),
				KEY prefectures (prefectures),
				KEY address1 (address1),
				KEY tel1 (tel1),
				KEY payment_name (payment_name),
				KEY order_key (order_key),
				KEY order_status (order_status),
				KEY order_date_time (order_date_time),
				KEY order_cart_id (order_cart_id),
				KEY expiration_date_time (expiration_date_time)
			) AUTO_INCREMENT=0 $charset_collate;";

			dbDelta( $sql );
			update_option( 'iihlms_db_order', IIHLMS_DB_ORDER );
		}

		if ( IIHLMS_DB_ORDER_META !== $order_meta_table_ver ) {
			$sql = 'CREATE TABLE ' . $order_meta_table . " (
				order_meta_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				order_id bigint(20) unsigned NOT NULL DEFAULT '0',
				meta_key varchar(255) DEFAULT NULL,
				meta_value longtext DEFAULT NULL,
				PRIMARY KEY (order_meta_id),
				KEY order_id (order_id),
				KEY meta_key (meta_key)
			) AUTO_INCREMENT=0 $charset_collate;";

			dbDelta( $sql );
			update_option( 'iihlms_db_order_meta', IIHLMS_DB_ORDER_META );
		}

		if ( IIHLMS_DB_ORDER_CART !== $order_cart_table_ver ) {
			$sql = 'CREATE TABLE ' . $order_cart_table . " (
				order_cart_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				user_id bigint(20) unsigned NOT NULL DEFAULT '0',
				item_id bigint(20) unsigned NOT NULL DEFAULT '0',
				item_name varchar(255) NOT NULL,
				price int(11) DEFAULT NULL,
				tax int(11) DEFAULT NULL,
				user_email varchar(100) NOT NULL,
				user_name1 varchar(100) NOT NULL,
				user_name2 varchar(100) DEFAULT NULL,
				user_name3 varchar(100) DEFAULT NULL,
				user_name4 varchar(100) DEFAULT NULL,
				zip varchar(50) DEFAULT NULL,
				prefectures varchar(100) DEFAULT NULL,
				address1 varchar(100) DEFAULT NULL,
				address2 varchar(100) DEFAULT NULL,
				address3 varchar(100) DEFAULT NULL,
				address4 varchar(100) DEFAULT NULL,
				tel1 varchar(100) DEFAULT NULL,
				tel2 varchar(100) DEFAULT NULL,
				fax varchar(100) DEFAULT NULL,
				company_name varchar(255) DEFAULT NULL,
				payment_name varchar(100) NOT NULL,
				order_key varchar(255) DEFAULT NULL,
				order_status varchar(255) DEFAULT NULL,
				registered_datetime datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				update_datetime datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				expiration_date_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				note text DEFAULT NULL,
				PRIMARY KEY (order_cart_id),
				KEY user_id (user_id),
				KEY item_id (item_id),
				KEY item_name (item_name),
				KEY user_email (user_email),
				KEY tel1 (tel1),
				KEY payment_name (payment_name),
				KEY order_key (order_key),
				KEY order_status (order_status),
				KEY registered_datetime (registered_datetime),
				KEY update_datetime (update_datetime),
				KEY expiration_date_time (expiration_date_time)
			) AUTO_INCREMENT=0 $charset_collate;";

			dbDelta( $sql );
			update_option( 'iihlms_db_order_cart', IIHLMS_DB_ORDER_CART );
		}

		if ( IIHLMS_DB_ORDER_CART_META !== $order_cart_meta_table_ver ) {
			$sql = 'CREATE TABLE ' . $order_cart_meta_table . " (
				order_cart_meta_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				order_cart_id bigint(20) unsigned NOT NULL,
				meta_key varchar(255) DEFAULT NULL,
				meta_value longtext DEFAULT NULL,
				PRIMARY KEY (order_cart_meta_id),
				KEY order_id (order_cart_id),
				KEY meta_key (meta_key)
			) AUTO_INCREMENT=0 $charset_collate;";

			dbDelta( $sql );
			update_option( 'iihlms_db_order_cart_meta', IIHLMS_DB_ORDER_CART_META );
		}

		if ( IIHLMS_DB_USER_ACTIVITY !== $user_activity_table_ver ) {
			$sql = 'CREATE TABLE ' . $user_activity_table . " (
				user_activity_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				user_id bigint(20) unsigned NOT NULL,
				lesson_id bigint(20) unsigned NOT NULL,
				completed int(11) unsigned DEFAULT NULL,
				registered_datetime datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				update_datetime datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				PRIMARY KEY (user_activity_id),
				KEY user_id (user_id),
				KEY lesson_id (lesson_id),
				KEY completed (completed),
				KEY registered_datetime (registered_datetime),
				KEY update_datetime (update_datetime)
			) AUTO_INCREMENT=0 $charset_collate;";

			dbDelta( $sql );
			update_option( 'iihlms_db_user_activity', IIHLMS_DB_USER_ACTIVITY );
		}

		if ( IIHLMS_DB_USER_ACTIVITY_META !== $user_activity_meta_table_ver ) {
			$sql = 'CREATE TABLE ' . $user_activity_meta_table . " (
				user_activity_meta_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				user_activity_id bigint(20) unsigned NOT NULL,
				meta_key varchar(255) DEFAULT NULL,
				meta_value longtext,
				PRIMARY KEY (user_activity_meta_id),
				KEY user_activity_id (user_activity_id),
				KEY meta_key (meta_key)
			) AUTO_INCREMENT=0 $charset_collate;";

			dbDelta( $sql );
			add_option( 'iihlms_db_user_activity_meta', IIHLMS_DB_USER_ACTIVITY_META );
		}

		if ( IIHLMS_DB_MEMBERSHIP !== $membership_table_ver ) {
			$sql = 'CREATE TABLE ' . $membership_table . " (
				iihlms_membership_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				membership_name varchar(255) DEFAULT NULL,
				PRIMARY KEY (iihlms_membership_id)
			) AUTO_INCREMENT=0 $charset_collate;";

			dbDelta( $sql );
			update_option( 'iihlms_db_membership', IIHLMS_DB_MEMBERSHIP );
		}

		if ( IIHLMS_DB_PRE_USER !== $pre_user_table_ver ) {
			$sql = 'CREATE TABLE ' . $pre_user_table . " (
				iihlms_pre_user_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				urltoken varchar(255) NOT NULL,
				user_email varchar(100) NOT NULL,
				update_datetime datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				available TINYINT(1) NOT NULL DEFAULT 0,
				PRIMARY KEY (iihlms_pre_user_id)
			) AUTO_INCREMENT=0 $charset_collate;";
			dbDelta( $sql );
			update_option( 'iihlms_db_pre_user', IIHLMS_DB_PRE_USER );
		}
	}

	/**
	 * ログインページにcss,jsを適用
	 *
	 * @return void
	 */
	public function custom_login() {
		wp_enqueue_style( self::DOMAIN . '-login-style-1', IIHLMS_PLUGIN_URL . '/css/imaoikiruhito-lms-login.css', '', '1.0.0' );
		wp_enqueue_script( self::DOMAIN . '-login-script-1', IIHLMS_PLUGIN_URL . '/js/imaoikiruhito-lms-login.js', array( 'jquery' ), '1.0.0', true );
		$ex_array = array(
			'url'             => get_home_url(),
			'sitename'        => get_bloginfo(),
			'login_url'       => wp_login_url(),
			'return_to_login' => esc_html__( 'ログインに戻る', 'imaoikiruhitolms' ),
		);
		wp_localize_script( self::DOMAIN . '-login-script-1', 'iihlms_customlogin', $ex_array );

		// パスワードリセットページ.
		global $pagenow;
		if ( ( 'wp-login.php' === $pagenow ) && ( isset( $_GET['action'] ) ) && ( strpos( sanitize_text_field( wp_unslash( $_GET['action'] ) ), 'lostpassword' ) !== false ) ) {
			wp_enqueue_script( self::DOMAIN . '-login-script-2', IIHLMS_PLUGIN_URL . '/js/imaoikiruhito-lms-login-password-reset.js', array( 'jquery' ), '1.0.0', true );
		}
		if ( ( 'wp-login.php' === $pagenow ) && ( isset( $_GET['checkemail'] ) ) && ( strpos( sanitize_text_field( wp_unslash( $_GET['checkemail'] ) ), 'confirm' ) !== false ) ) {
			wp_enqueue_script( self::DOMAIN . '-login-script-2', IIHLMS_PLUGIN_URL . '/js/imaoikiruhito-lms-login-password-reset.js', array( 'jquery' ), '1.0.0', true );
		}
		if ( ( 'wp-login.php' === $pagenow ) && ( isset( $_GET['action'] ) ) && ( strpos( sanitize_text_field( wp_unslash( $_GET['action'] ) ), 'resetpass' ) !== false ) ) {
			wp_enqueue_script( self::DOMAIN . '-login-script-2', IIHLMS_PLUGIN_URL . '/js/imaoikiruhito-lms-login-password-reset.js', array( 'jquery' ), '1.0.0', true );
		}
	}

	/**
	 * Ajaxで使用するWordPressのAjax用URLを変数として出力
	 *
	 * @return void
	 */
	public function get_wp_ajax_root() {
		?>
		<script>
		let wp_ajax_root = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';
		</script>
		<?php
	}

	/**
	 * レッスンの受講状態を更新
	 *
	 * @return bool
	 */
	public function update_lesseon_complete_func_ajax() {
		global $wpdb;

		if ( ( ! isset( $_POST['post_id'] ) ) ||
			( ! isset( $_POST['user_id'] ) ) ||
			( ! isset( $_POST['complete_value'] ) ) ||
			( ! isset( $_POST['nonce'] ) )
		) {
			return false;
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
		if ( ! wp_verify_nonce( $nonce, 'iihlms-ajax-nonce-update-lesseon-complete' ) ) {
			return false;
		}

		$post_id        = sanitize_text_field( wp_unslash( $_POST['post_id'] ) );
		$user_id        = sanitize_text_field( wp_unslash( $_POST['user_id'] ) );
		$complete_value = sanitize_text_field( wp_unslash( $_POST['complete_value'] ) );

		$user_activity_table = $wpdb->prefix . 'iihlms_user_activity';
		if ( '1' === $complete_value ) {
			$results = $wpdb->get_results(
				$wpdb->prepare(
					'
					SELECT user_activity_id
					FROM %1s
					WHERE 
						user_id = %d
						AND lesson_id = %d
					',
					$user_activity_table,
					$user_id,
					$post_id,
				)
			);
			$number  = count( $results );

			if ( 0 === $number ) {
				$wpdb->insert(
					$user_activity_table,
					array(
						'user_id'             => $user_id,
						'lesson_id'           => $post_id,
						'completed'           => $complete_value,
						'registered_datetime' => current_time( 'mysql' ),
						'update_datetime'     => current_time( 'mysql' ),
					),
					array(
						'%d',
						'%d',
						'%d',
						'%s',
						'%s',
					)
				);

			} else {
				$wpdb->update(
					$user_activity_table,
					array(
						'completed'       => $complete_value,
						'update_datetime' => current_time( 'mysql' ),
					),
					array(
						'user_id'   => $user_id,
						'lesson_id' => $post_id,
					),
					array(
						'%d',
						'%s',
					)
				);
			}
		} else {
			$results = $wpdb->get_results(
				$wpdb->prepare(
					'
					SELECT user_activity_id
					FROM %1s
					WHERE 
						user_id = %d
						AND lesson_id = %d
					',
					$user_activity_table,
					$user_id,
					$post_id
				)
			);
			$number  = count( $results );

			if ( $number > 0 ) {
				$wpdb->delete(
					$user_activity_table,
					array(
						'user_id'   => $user_id,
						'lesson_id' => $post_id,
					),
					array(
						'%d',
						'%s',
					)
				);
			}
		}
	}

	/**
	 * Stripe注文作成(1回払い)
	 *
	 * @return void
	 */
	public function create_order_stripe_func_ajax() {
		echo esc_html( apply_filters( 'iihlms_addition_create_order_stripe_func_ajax', '' ) );
	}
	/**
	 * ペイパル注文作成(1回払い)
	 *
	 * @return void
	 */
	public function create_order_paypal_func_ajax() {
		global $wpdb;

		$itemid = sanitize_text_field( wp_unslash( filter_input( INPUT_POST, 'itemid' ) ) );
		$nonce  = sanitize_text_field( wp_unslash( filter_input( INPUT_POST, 'nonce' ) ) );
		$user   = wp_get_current_user();

		if ( ! wp_verify_nonce( $nonce, 'iihlms-ajax-nonce-paypal-onetime' ) ) {
			exit;
		}

		// 購入済かチェック.
		if ( $this->check_item_purchased( $itemid ) ) {
			if ( $this->check_item_within_expiration_date( $itemid ) ) {
				exit;
			}
		}

		$email        = $user->user_email;
		$name1        = get_user_meta( $user->ID, 'iihlms_user_name1', true );
		$name2        = get_user_meta( $user->ID, 'iihlms_user_name2', true );
		$tel          = get_user_meta( $user->ID, 'iihlms_user_tel', true );
		$item_data    = $this->get_item_data( $itemid );
		$price        = $item_data['price'];
		$tax          = $this->get_consumption_tax_value( $price );
		$item_name    = $item_data['title'];
		$payment      = 'paypal';
		$order_key    = $this->create_order_key();
		$order_status = 'unsettled';

		$access_token_paypal = $this->get_accesstoken_paypal();
		if ( '' === $access_token_paypal ) {
			exit;
		}

		$client_id = $this->get_clientid_paypal();
		if ( '' === $client_id ) {
			exit;
		}
		$secret_id = $this->get_secretid_paypal();
		if ( '' === $secret_id ) {
			exit;
		}
		$base_url = $this->get_baseurl_paypal();

		$post_purchase = '{"intent": "CAPTURE",
			"purchase_units": [{
				"amount": {
					"currency_code": "JPY",
					"value": "' . ( $price + $tax ) . '",
					"breakdown": {
						"item_total": {
							"currency_code": "JPY",
							"value": "' . ( $price + $tax ) . '"
						}
					}
				},
				"items": [
					{
						"name": "' . $item_name . '",
						"description": "",
						"unit_amount": {
							"currency_code": "JPY",
							"value": "' . ( $price + $tax ) . '"
						},
						"quantity": "1"
					}
				]
			}]
		}';

		$url  = $base_url . '/v2/checkout/orders';
		$args = array(
			'method'  => 'POST',
			'headers' => array(
				'Content-Type'      => 'application/json',
				'Authorization'     => 'Bearer ' . $access_token_paypal,
				'PayPal-Request-Id' => $order_key,
			),
			'body'    => $post_purchase,
		);

		$response = wp_remote_post( $url, $args );
		if ( is_wp_error( $response ) ) {
			exit;
		}

		$response_json                   = json_decode( wp_remote_retrieve_body( $response ), true );
		$paypal_create_order_response_id = $response_json['id'];
		$order_status                    = 'unsettled';

		// DBに保存.
		$order_cart_table      = $wpdb->prefix . 'iihlms_order_cart';
		$order_cart_meta_table = $wpdb->prefix . 'iihlms_order_cart_meta';

		$wpdb->insert(
			$order_cart_table,
			array(
				'user_id'             => $user->ID,
				'item_id'             => $itemid,
				'item_name'           => $item_name,
				'price'               => $price,
				'tax'                 => $tax,
				'user_email'          => $email,
				'user_name1'          => $name1,
				'user_name2'          => $name2,
				'tel1'                => $tel,
				'payment_name'        => $payment,
				'order_key'           => $order_key,
				'order_status'        => $order_status,
				'registered_datetime' => current_time( 'mysql' ),
			),
			array(
				'%d',
				'%d',
				'%s',
				'%d',
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			)
		);
		$lastid = $wpdb->insert_id;

		$wpdb->insert(
			$order_cart_meta_table,
			array(
				'order_cart_id' => $lastid,
				'meta_key'      => 'paypal_create_order_response_id',
				'meta_value'    => $paypal_create_order_response_id,
			),
			array(
				'%d',
				'%s',
				'%s',
			)
		);
		$wpdb->insert(
			$order_cart_meta_table,
			array(
				'order_cart_id' => $lastid,
				'meta_key'      => 'paypal_create_order_response',
				'meta_value'    => $response,
			),
			array(
				'%d',
				'%s',
				'%s',
			)
		);
		$wpdb->insert(
			$order_cart_meta_table,
			array(
				'order_cart_id' => $lastid,
				'meta_key'      => 'paypal_create_order_request',
				'meta_value'    => $post_purchase,
			),
			array(
				'%d',
				'%s',
				'%s',
			)
		);

		wp_send_json( $response_json );
	}

	/**
	 * ペイパル注文実行(1回払い)
	 *
	 * @return void
	 */
	public function checkout_paypal_func_ajax() {
		global $wpdb;

		$orderid = sanitize_text_field( wp_unslash( filter_input( INPUT_POST, 'orderid' ) ) );
		$nonce   = sanitize_text_field( wp_unslash( filter_input( INPUT_POST, 'nonce' ) ) );

		if ( ! wp_verify_nonce( $nonce, 'iihlms-ajax-nonce-paypal-onetime-onapprove' ) ) {
			exit;
		}
		$access_token_paypal = $this->get_accesstoken_paypal();
		if ( '' === $access_token_paypal ) {
			exit;
		}
		$user                  = wp_get_current_user();
		$order_cart_table      = $wpdb->prefix . 'iihlms_order_cart';
		$order_cart_meta_table = $wpdb->prefix . 'iihlms_order_cart_meta';

		$order_cart_id = $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT order_cart_id
				FROM %1s
				WHERE 
					meta_key = 'paypal_create_order_response_id'
					AND meta_value = %s
				",
				$order_cart_meta_table,
				$orderid
			)
		);
		$results       = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT item_id, order_key
				FROM %1s
				WHERE 
					order_cart_id = %d
				',
				$order_cart_table,
				$order_cart_id
			)
		);
		foreach ( $results as $result ) {
			$item_id   = $result->item_id;
			$order_key = $result->order_key;
		}

		// 注文実行.
		$client_id = $this->get_clientid_paypal();
		$secret_id = $this->get_secretid_paypal();
		$base_url  = $this->get_baseurl_paypal();

		$url  = $base_url . '/v2/checkout/orders/' . $orderid . '/capture';
		$args = array(
			'method'  => 'POST',
			'headers' => array(
				'Content-Type'      => 'application/json',
				'Authorization'     => 'Bearer ' . $access_token_paypal,
				'PayPal-Request-Id' => $order_key,
			),
		);

		$response = wp_remote_post( $url, $args );

		if ( is_wp_error( $response ) ) {
			exit;
		}

		$response_json               = json_decode( wp_remote_retrieve_body( $response ), true );
		$paypal_checkout_response_id = $response_json['id'];

		$wpdb->insert(
			$order_cart_meta_table,
			array(
				'order_cart_id' => $order_cart_id,
				'meta_key'      => 'paypal_checkout_response_id',
				'meta_value'    => $paypal_checkout_response_id,
			),
			array(
				'%d',
				'%s',
			)
		);
		$wpdb->insert(
			$order_cart_meta_table,
			array(
				'order_cart_id' => $order_cart_id,
				'meta_key'      => 'paypal_checkout_response',
				'meta_value'    => $response,
			),
			array(
				'%d',
				'%s',
			)
		);
		// 会員ステータスを自動付与.
		$iihlms_item_membership_automatic_grant_data = get_post_meta( $item_id, 'iihlms_item_membership_automatic_grant', true );
		$iihlms_item_membership_automatic_grant_data = isset( $iihlms_item_membership_automatic_grant_data ) ? (array) $iihlms_item_membership_automatic_grant_data : array();
		if ( ! empty( $iihlms_item_membership_automatic_grant_data[0] ) ) {
			$iihlms_user_membership_status_now = get_user_meta( $user->ID, 'iihlms_user_membership_status', true );
			$iihlms_user_membership_status_now = isset( $iihlms_user_membership_status_now ) ? (array) $iihlms_user_membership_status_now : array();
			$iihlms_user_membership_status     = array_merge( $iihlms_item_membership_automatic_grant_data, $iihlms_user_membership_status_now );
			$iihlms_user_membership_status     = array_unique( $iihlms_user_membership_status );
			$key                               = array_search( '', $iihlms_user_membership_status, true );
			if ( ! is_bool( $key ) ) {
				unset( $iihlms_user_membership_status[ $key ] );
				$iihlms_user_membership_status = array_values( $iihlms_user_membership_status );
			}
			update_user_meta( $user->ID, 'iihlms_user_membership_status', $iihlms_user_membership_status );
		}

		wp_send_json( $response_json );
	}

	/**
	 * Stripeサブスクリプション注文時処理
	 *
	 * @return void
	 */
	public function create_subscription_stripe_func_ajax() {
		echo esc_html( apply_filters( 'iihlms_addition_create_subscription_stripe_func_ajax', '' ) );
	}

	/**
	 * ペイパルサブスクリプション注文時処理
	 *
	 * @return void
	 */
	public function create_subscription_paypal_func_ajax() {
		echo esc_html( apply_filters( 'iihlms_addition_create_subscription_paypal_func_ajax', '' ) );
	}

	/**
	 * ペイパルサブスクリプション決済時処理
	 *
	 * @return void
	 */
	public function subscription_approve_paypal_func_ajax() {
		echo esc_html( apply_filters( 'iihlms_addition_subscription_approve_paypal_func_ajax', '' ) );
	}

	/**
	 * レッスンIDをキーとし、受講完了かチェック
	 *
	 * @param array $lessonid レッスンID.
	 * @param array $userid ユーザーID.
	 * @return bool true:受講完了 false:未受講
	 */
	public function check_user_activity( $lessonid, $userid ) {
		global $wpdb;

		$user_activity_table = $wpdb->prefix . 'iihlms_user_activity';

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT user_activity_id
				FROM %1s
				WHERE 
					user_id = %d
					AND lesson_id = %d
				',
				$user_activity_table,
				$userid,
				$lessonid
			)
		);
		$number  = count( $results );

		if ( $number > 0 ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 講座IDをキーとし、関連しているコースの数を返す
	 *
	 * @param array $itemid 講座ID.
	 * @return int
	 */
	public function get_item_course_relation_number( $itemid ) {
		global $wpdb;

		$iihlms_item_relation = get_post_meta( $itemid, 'iihlms_item_relation', true );
		$iihlms_item_relation = isset( $iihlms_item_relation ) ? (array) $iihlms_item_relation : array();
		if ( empty( $iihlms_item_relation[0] ) ) {
			return 0;
		}

		$iihlms_item_relation = implode( ',', $iihlms_item_relation );

		$post_type = 'iihlms_courses';
		$results   = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT post_title
				FROM $wpdb->posts
				WHERE 
					post_status = 'publish'
						AND ID IN ( %1s )
						AND post_type = %s
				",
				$iihlms_item_relation,
				$post_type
			)
		);
		$number    = count( $results );

		return $number;
	}

	/**
	 * コースIDをキーとし、関連しているレッスンの数を返す
	 *
	 * @param array $courseid コースID.
	 * @return int
	 */
	public function get_course_lesson_relation_number( $courseid ) {

		global $wpdb;

		$course_lesson_relation = get_post_meta( $courseid, 'iihlms_course_relation', true );
		$course_lesson_relation = isset( $course_lesson_relation ) ? (array) $course_lesson_relation : array();
		if ( empty( $course_lesson_relation[0] ) ) {
			return 0;
		}

		$course_lesson_relation = implode( ',', $course_lesson_relation );

		$post_type = 'iihlms_lessons';

		$results = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT post_title
				FROM $wpdb->posts
				WHERE 
					post_status = 'publish'
					AND ID IN ( %1s )
					AND post_type = %s
				",
				$course_lesson_relation,
				$post_type
			)
		);
		$number  = count( $results );

		return $number;
	}

	/**
	 * コースIDをキーとし、関連しているレッスンのうち受講完了となっている数を返す
	 *
	 * @param array $courseid コースID.
	 * @return int
	 */
	public function get_course_lesson_complete_number( $courseid ) {
		global $wpdb;

		$user = wp_get_current_user();

		// 指定したコースに関連するレッスン.
		$course_lesson_related = get_post_meta( $courseid, 'iihlms_course_relation', true );

		if ( empty( $course_lesson_related ) ) {
			return 0;
		}

		$course_lesson_related_search = implode( ',', $course_lesson_related );

		$user_activity_table = $wpdb->prefix . 'iihlms_user_activity';

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT user_activity_id
				FROM %1s
				WHERE 
					lesson_id IN ( %2s )
					AND user_id = %d
					
				',
				$user_activity_table,
				$course_lesson_related_search,
				$user->ID
			)
		);
		$number  = count( $results );

		return $number;
	}

	/**
	 * 講座IDをキーとし、関連しているコースの進捗一覧を配列で得る
	 *
	 * @param array $itemid 講座ID.
	 * @return array
	 */
	public function get_item_course_progress_number( $itemid ) {
		// 関連しているコース.
		$iihlms_item_relation = get_post_meta( $itemid, 'iihlms_item_relation', true );
		if ( empty( $iihlms_item_relation ) ) {
			return array();
		}

		$iihlms_item_relation = isset( $iihlms_item_relation ) ? (array) $iihlms_item_relation : array();

		$course_progress        = array();
		$course_progress_number = 0;

		if ( ! empty( $iihlms_item_relation[0] ) ) {
			foreach ( $iihlms_item_relation as $value ) {
				$course_progress_number = $this->get_course_lesson_progress_number( $value );

				if ( empty( $course_progress ) ) {
					$course_progress = array( $value => $course_progress_number );
				} else {
					$course_progress = $course_progress + array( $value => $course_progress_number );
				}
			}
		}
		return $course_progress;
	}

	/**
	 * コースIDを渡すと、進捗を返す
	 *
	 * @param array $courseid コースID.
	 * @return int
	 */
	public function get_course_lesson_progress_number( $courseid ) {
		$course_lesson_relation_number = $this->get_course_lesson_relation_number( $courseid );
		$course_lesson_complete_number = $this->get_course_lesson_complete_number( $courseid );

		if ( 0 === $course_lesson_relation_number ) {
			return 0;
		}
		try {
			$percent = ( $course_lesson_complete_number / $course_lesson_relation_number ) * 100;
			return (int) round( $percent );
		} catch ( Exception $e ) {
			return 0;
		}
	}

	/**
	 * 講座IDの配列を渡すと、講座ID,進捗の配列を返す
	 *
	 * @param array $itemid_array 講座ID配列.
	 * @return array
	 */
	public function get_item_progress_number( $itemid_array ) {

		$item_progress = array();

		if ( empty( $itemid_array ) ) {
			return $item_progress;
		}

		foreach ( $itemid_array as $value ) {

			// 講座IDをキーに、関連しているコースIDとコースの進捗をゲット.
			$item_course_progress_number_data = $this->get_item_course_progress_number( $value );

			// コースの進捗平均.
			if ( ! empty( $item_course_progress_number_data ) ) {

				$count = count( $item_course_progress_number_data );
				$sum   = 0;
				$avg   = 0;
				foreach ( $item_course_progress_number_data as $val ) {
					$sum += $val;
				}
				if ( $count > 0 ) {
					$avg = (int) round( $sum / $count );
				}

				// 平均値を、講座IDをキーとした配列に入れる.
				if ( empty( $item_progress ) ) {
					$item_progress = array( $value => $avg );
				} else {
					$item_progress = $item_progress + array( $value => $avg );
				}
			} else {
				// 進捗0の時.
				if ( empty( $item_progress ) ) {
					$item_progress = array( $value => 0 );
				} else {
					$item_progress = $item_progress + array( $value => 0 );
				}
			}
		}

		return $item_progress;
	}

	/**
	 * 講座IDを渡すと、進捗を返す
	 *
	 * @param string $itemid 講座ID.
	 * @return int
	 */
	public function get_item_progress_number_one( $itemid ) {

		$item_progress = 0;

		// 講座IDをキーに、含まれるコースIDとコースの進捗をゲット.
		$item_course_progress_number_data = $this->get_item_course_progress_number( $itemid );

		// コースの進捗平均.
		if ( ! empty( $item_course_progress_number_data ) ) {

			$count = count( $item_course_progress_number_data );
			$sum   = 0;
			$avg   = 0;
			foreach ( $item_course_progress_number_data as $val ) {
				$sum += $val;
			}
			if ( $count > 0 ) {
				$avg = (int) round( $sum / $count );
			}

			$item_progress = $avg;
		}

		return $item_progress;

	}

	/**
	 * コースIDの配列を渡すと、コースID,進捗の配列を返す
	 *
	 * @param array $courseid_array コースID配列.
	 * @return array
	 */
	public function get_course_progress_number( $courseid_array ) {
		$course_progress = array();

		if ( empty( $courseid_array ) ) {
			return $course_progress;
		}
		foreach ( $courseid_array as $value ) {
			// コースIDをキーに、コースの進捗をゲット.
			$course_lesson_progress_number_data = $this->get_course_lesson_progress_number( $value );

			// 進捗を、コースIDをキーとした配列に入れる.
			if ( empty( $course_progress ) ) {
				$course_progress = array( $value => $course_lesson_progress_number_data );
			} else {
				$course_progress = $course_progress + array( $value => $course_lesson_progress_number_data );
			}
		}
		return $course_progress;
	}

	/**
	 * 指定した講座は、カレントユーザーが購入済の講座か
	 *
	 * @param string $itemid 講座ID.
	 * @return bool
	 */
	public function check_item_purchased( $itemid ) {

		global $wpdb;

		$user = wp_get_current_user();

		// カレントユーザーは指定した講座を購入しているか?
		$order_table = $wpdb->prefix . 'iihlms_order';
		$results     = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT item_id
				FROM %1s
				WHERE 
					user_id = %d
					AND item_id = %d
				',
				$order_table,
				$user->ID,
				$itemid
			)
		);
		$number      = count( $results );

		if ( $number > 0 ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 指定した講座は、有効期限内の講座か
	 *
	 * @param string $itemid 講座ID.
	 * @return bool
	 */
	public function check_item_within_expiration_date( $itemid ) {

		global $wpdb;

		$user  = wp_get_current_user();
		$today = current_datetime();

		$order_table = $wpdb->prefix . 'iihlms_order';
		$results     = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT item_id, expiration_date_time
				FROM %1s
				WHERE 
					user_id = %d
					AND item_id = %d
				',
				$order_table,
				$user->ID,
				$itemid
			)
		);

		$number = count( $results );

		if ( 0 === $number ) {
			return true;
		}

		foreach ( $results as $row ) {
			$expiration_day = new DateTimeImmutable( $row->expiration_date_time );
			if ( '0000-00-00 00:00:00' === $row->expiration_date_time ) {
				return true;
			} elseif ( $expiration_day->format( $this->specify_date_format ) >= $today->format( $this->specify_date_format ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * 指定した講座の有効期限を取得
	 *
	 * @param string $itemid 講座ID.
	 * @return string
	 */
	public function get_item_expiration_date( $itemid ) {

		global $wpdb;

		$expiration_day = '';

		$user = wp_get_current_user();

		$order_table = $wpdb->prefix . 'iihlms_order';
		$results     = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT expiration_date_time
				FROM %1s
				WHERE 
					user_id = %d
					AND item_id = %d
				',
				$order_table,
				$user->ID,
				$itemid
			)
		);

		$number = count( $results );

		if ( 0 === $number ) {
			return $expiration_day;
		}

		foreach ( $results as $row ) {
			$expiration_day = $row->expiration_date_time;
		}
		return $expiration_day;
	}

	/**
	 * 指定したコースは、カレントユーザーが購入済のコースか
	 *
	 * @param string $courseid コースID.
	 * @return bool
	 */
	public function check_course_purchased( $courseid ) {

		global $wpdb;

		// 購入済の講座.
		$order_table = $wpdb->prefix . 'iihlms_order';
		$user        = wp_get_current_user();

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT item_id
				FROM %1s
				WHERE 
					user_id = %d
				',
				$order_table,
				$user->ID,
			)
		);

		$items_purchased = array();
		foreach ( $results as $row ) {
			array_push( $items_purchased, $row->item_id );
		}

		$purchase_flg = true;
		if ( empty( $items_purchased ) ) {
			$purchase_flg = false;
		}

		if ( true === $purchase_flg ) {
			$courses_purchased = array();
			foreach ( $items_purchased as $value ) {
				$iihlms_item_relation = get_post_meta( $value, 'iihlms_item_relation', false );
				if ( ! empty( $iihlms_item_relation ) ) {
					$courses_purchased = array_merge( $courses_purchased, $iihlms_item_relation[0] );
				}
			}
			$courses_purchased = array_values( $courses_purchased );
			$courses_purchased = array_unique( $courses_purchased );

			// 指定したコースIDは、購入済のコースIDに含まれるか.
			if ( ! in_array( (string) $courseid, $courses_purchased, true ) ) {
				$purchase_flg = false;
			}
		}

		if ( true === $purchase_flg ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 指定したレッスンは、カレントユーザーが購入済のレッスンか
	 *
	 * @param string $lessonid レッスンID.
	 * @return bool
	 */
	public function lesson_purchased_check( $lessonid ) {
		global $wpdb;

		// カレントユーザーの購入済の講座.
		$order_table = $wpdb->prefix . 'iihlms_order';
		$user        = wp_get_current_user();

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT item_id
				FROM %1s
				WHERE 
					user_id = %d
				',
				$order_table,
				$user->ID,
			)
		);
		$number  = count( $results );
		if ( 0 === $number ) {
			return false;
		}

		$items_purchased = array();
		foreach ( $results as $row ) {
			array_push( $items_purchased, $row->item_id );
		}
		if ( empty( $items_purchased ) ) {
			return false;
		}

		$courses_purchased = array();
		foreach ( $items_purchased as $value ) {
			$iihlms_item_relation = get_post_meta( $value, 'iihlms_item_relation', false );
			if ( ! empty( $iihlms_item_relation ) ) {
				$courses_purchased = array_merge( $courses_purchased, $iihlms_item_relation[0] );
			}
		}
		$courses_purchased = array_values( $courses_purchased );
		$courses_purchased = array_unique( $courses_purchased );
		if ( empty( $courses_purchased[0] ) ) {
			return false;
		}

		$lessons_purchased = array();
		foreach ( $courses_purchased as $value ) {
			$iihlms_course_relation = get_post_meta( $value, 'iihlms_course_relation', false );
			if ( ! empty( $iihlms_course_relation ) ) {
				$lessons_purchased = array_merge( $lessons_purchased, $iihlms_course_relation[0] );
			}
		}
		// 購入済のレッスン一覧.
		$lessons_purchased = array_unique( $lessons_purchased );

		// 購入済のレッスン一覧の中に、指定したレッスンIDが存在するか.
		$lessonid_key = array_search( (string) $lessonid, $lessons_purchased, true );
		if ( false === $lessonid_key ) {
			return false;
		} else {
			return true;
		}
	}
	/**
	 * 指定したレッスンは、指定したコースに含まれるか
	 *
	 * @param string $lessonid レッスンID.
	 * @param string $courseid コースID.
	 * @return bool
	 */
	public function lesson_included_specified_course_check( $lessonid, $courseid ) {
		global $wpdb;

		$lessons_included       = array();
		$iihlms_course_relation = get_post_meta( $courseid, 'iihlms_course_relation', false );
		if ( ! empty( $iihlms_course_relation ) ) {
			$lessons_included = array_merge( $lessons_included, $iihlms_course_relation[0] );
		}

		$lessonid_key = array_search( (string) $lessonid, $lessons_included, true );
		if ( false === $lessonid_key ) {
			return false;
		} else {
			return true;
		}
	}
	/**
	 * 指定したコースは、指定した講座に含まれるか
	 *
	 * @param string $courseid コースID.
	 * @param string $itemid 講座ID.
	 * @return bool
	 */
	public function course_included_specified_item_check( $courseid, $itemid ) {
		$courses_included     = array();
		$iihlms_item_relation = get_post_meta( $itemid, 'iihlms_item_relation', false );
		if ( ! empty( $iihlms_item_relation ) ) {
			$courses_included = array_merge( $courses_included, $iihlms_item_relation[0] );
		}
		$courseid_key = array_search( (string) $courseid, $courses_included, true );
		if ( false === $courseid_key ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * 指定した講座IDが存在するか
	 *
	 * @param string $itemid 講座ID.
	 * @return bool
	 */
	public function item_exists_check( $itemid ) {

		global $wpdb;

		$post_type = 'iihlms_items';
		$results   = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT ID
				FROM $wpdb->posts
				WHERE 
					post_type = %s
						AND post_status = 'publish'
						AND ID = %d
				",
				$post_type,
				$itemid
			)
		);

		$number = $wpdb->num_rows;

		if ( $number > 0 ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 指定したコースIDが存在するか
	 *
	 * @param string $courseid コースID.
	 * @return bool
	 */
	public function course_exists_check( $courseid ) {
		global $wpdb;

		$post_type = 'iihlms_courses';
		$results   = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT ID
				FROM $wpdb->posts
				WHERE 
					post_type = %s
						AND post_status = 'publish'
						AND ID = %d
				",
				$post_type,
				$courseid
			)
		);

		$number = $wpdb->num_rows;

		if ( $number > 0 ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 指定したレッスンIDが存在するか
	 *
	 * @param string $lessonid レッスンID.
	 * @return bool
	 */
	public function lesson_exists_check( $lessonid ) {
		global $wpdb;

		$post_type = 'iihlms_lessons';
		$results   = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT ID
				FROM $wpdb->posts
				WHERE 
					post_type = %s
						AND post_status = 'publish'
						AND ID = %d
				",
				$post_type,
				$lessonid
			)
		);

		$number = $wpdb->num_rows;

		if ( $number > 0 ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 指定したテストIDが存在するか
	 *
	 * @param string $testid テストID.
	 * @return bool
	 */
	public function test_exists_check( $testid ) {
		global $wpdb;

		$post_type = 'iihlms_tests';
		$results   = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT ID
				FROM $wpdb->posts
				WHERE 
					post_type = %s
						AND post_status = 'publish'
						AND ID = %d
				",
				$post_type,
				$testid
			)
		);

		$number = $wpdb->num_rows;

		if ( $number > 0 ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 講座IDを渡すと、表示するための価格を返す
	 *
	 * @param string $itemid 講座ID.
	 * @return array
	 */
	public function get_price_for_disp_by_id( $itemid ) {
		$iihlms_payment_type = get_post_meta( $itemid, 'iihlms_payment_type', true );
		if ( 'subscription' === $iihlms_payment_type ) {
			$subscription_price          = get_post_meta( $itemid, 'iihlms_item_subscription_price', true );
			$subscription_interval_count = get_post_meta( $itemid, 'iihlms_item_subscription_interval_count', true );
			$subscription_interval_unit  = get_post_meta( $itemid, 'iihlms_item_subscription_interval_unit', true );

			$subscription_price = $this->get_price_for_disp( $subscription_price );
			if ( '1' === $subscription_interval_count ) {
				$subscription_interval_count = '';
			}
			$subscription_interval_unit = $this->get_interval_unit_for_disp( $subscription_interval_unit );

			return $subscription_price . '/' . $subscription_interval_count . $subscription_interval_unit;
		}
		if ( 'onetime' === $iihlms_payment_type ) {
			$price = get_post_meta( $itemid, 'iihlms_item_price', true );
			return $this->get_price_for_disp( $price );
		}
		return 0;
	}
	/**
	 * 講座IDを渡すと、税抜価格を返す
	 *
	 * @param string $itemid 講座ID.
	 * @return array
	 */
	public function get_tax_excluded_price_by_id( $itemid ) {
		$iihlms_payment_type = get_post_meta( $itemid, 'iihlms_payment_type', true );
		if ( 'subscription' === $iihlms_payment_type ) {
			$subscription_price          = get_post_meta( $itemid, 'iihlms_item_subscription_price', true );
			$subscription_interval_count = get_post_meta( $itemid, 'iihlms_item_subscription_interval_count', true );
			$subscription_interval_unit  = get_post_meta( $itemid, 'iihlms_item_subscription_interval_unit', true );

			if ( '1' === $subscription_interval_count ) {
				$subscription_interval_count = '';
			}
			$subscription_interval_unit = $this->get_interval_unit_for_disp( $subscription_interval_unit );

			return $subscription_price . '/' . $subscription_interval_count . $subscription_interval_unit;
		}
		if ( 'onetime' === $iihlms_payment_type ) {
			$price = get_post_meta( $itemid, 'iihlms_item_price', true );
			return $price;
		}
		return 0;
	}
	/**
	 * 税抜価格を渡すと、表示するための価格を返す
	 *
	 * @param int $price 税抜価格.
	 * @return string
	 */
	public function get_price_for_disp( $price ) {
		if ( 0 === (int) $price ) {
			return esc_html__( '無料', 'imaoikiruhitolms' );
		}
		$tax = $this->get_consumption_tax_value( $price );
		return '&yen;' . number_format( $price + $tax );
	}

	/**
	 * 講座IDをキーに、講座の情報を配列で得る
	 *
	 * @param string $itemid 講座ID.
	 * @return array
	 */
	public function get_item_data( $itemid ) {
		global $wpdb;

		$item_data = array();

		$results = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT post_title
				FROM $wpdb->posts
				WHERE
					ID = %s
				",
				$itemid,
			)
		);

		$title = '';
		$price = '';
		foreach ( $results as $result ) {
			$title = $result->post_title;
			$price = get_post_meta( $itemid, 'iihlms_item_price', true );

			$subscription_price          = get_post_meta( $itemid, 'iihlms_item_subscription_price', true );
			$subscription_interval_count = get_post_meta( $itemid, 'iihlms_item_subscription_interval_count', true );
			$subscription_interval_unit  = get_post_meta( $itemid, 'iihlms_item_subscription_interval_unit', true );

			$item_data = array(
				'title'                       => $title,
				'price'                       => $price,
				'subscription_price'          => $subscription_price,
				'subscription_interval_count' => $subscription_interval_count,
				'subscription_interval_unit'  => $subscription_interval_unit,
			);
		}

		return $item_data;
	}

	/**
	 * コースIDをキーに、コースの情報を配列で得る
	 *
	 * @param string $courseid コースID.
	 * @return array
	 */
	public function get_course_data( $courseid ) {
		global $wpdb;

		$course_data = array();

		$results = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT post_title
				FROM $wpdb->posts
				WHERE
					post_status = 'publish'
					AND ID = %s
				",
				$courseid,
			)
		);

		$title = '';
		foreach ( $results as $result ) {
			$title       = $result->post_title;
			$course_data = array( 'title' => $title );
		}

		return $course_data;
	}

	/**
	 * 次のレッスンへのリンクを取得
	 *
	 * @param string $lessonid レッスンID.
	 * @param string $courseid コースID.
	 * @return string
	 */
	public function get_lesson_next_link( $lessonid, $courseid ) {
		$lesson_next_link = '';

		// コースIDをキーに、登録されたレッスン一覧を取得.
		$course_lesson_related = get_post_meta( $courseid, 'iihlms_course_relation', true );
		if ( false === $course_lesson_related ) {
			return $lesson_next_link;
		}
		$course_lesson_related = isset( $course_lesson_related ) ? (array) $course_lesson_related : array();

		if ( ! empty( $course_lesson_related ) ) {
			$count = count( $course_lesson_related );
			$key   = array_search( (string) $lessonid, $course_lesson_related, true );

			$nextkey = 0;
			if ( $count - 1 !== $key ) {
				$nextkey = $course_lesson_related[ $key + 1 ];
			}
			if ( $nextkey > 0 ) {
				$lesson_next_link = get_permalink( $nextkey );
			}
		}

		return $lesson_next_link;
	}

	/**
	 * 前のレッスンへのリンクを取得
	 *
	 * @param string $lessonid レッスンID.
	 * @param string $courseid コースID.
	 * @return string
	 */
	public function get_previous_lesson_link( $lessonid, $courseid ) {
		$lesson_previous_link = '';

		// コースIDをキーに、登録されたレッスン一覧を取得.
		$course_lesson_related = get_post_meta( $courseid, 'iihlms_course_relation', true );
		if ( false === $course_lesson_related ) {
			return $lesson_previous_link;
		}
		$course_lesson_related = isset( $course_lesson_related ) ? (array) $course_lesson_related : array();

		if ( ! empty( $course_lesson_related ) ) {
			$count = count( $course_lesson_related );
			$key   = array_search( (string) $lessonid, $course_lesson_related, true );

			$prevkey = 0;
			if ( 0 !== $key ) {
				$prevkey = $course_lesson_related[ $key - 1 ];
			}
			if ( $prevkey > 0 ) {
				$lesson_previous_link = get_permalink( $prevkey );
			}
		}

		return $lesson_previous_link;
	}

	/**
	 * 次のコースへのリンクを取得
	 *
	 * @param string $courseid コースID.
	 * @param string $itemid 講座ID.
	 * @return string
	 */
	public function get_next_course_link( $courseid, $itemid ) {
		$course_next_link = '';

		// 講座IDをキーに、登録されたコース一覧を取得.
		$item_course_related = get_post_meta( $itemid, 'iihlms_item_relation', true );

		if ( false === $item_course_related ) {
			return $course_next_link;
		}
		$item_course_related = isset( $item_course_related ) ? (array) $item_course_related : array();

		if ( ! empty( $item_course_related ) ) {
			$count = count( $item_course_related );
			$key   = array_search( (string) $courseid, $item_course_related, true );

			$nextkey = 0;
			if ( $count - 1 !== $key ) {
				$nextkey = $item_course_related[ $key + 1 ];
			}
			if ( $nextkey > 0 ) {
				$course_next_link = get_permalink( $nextkey );
			}
		}

		return $course_next_link;
	}
	/**
	 * 次のコースのコースIDを取得
	 *
	 * @param string $courseid コースID.
	 * @param string $itemid 講座ID.
	 * @return string
	 */
	public function get_next_course_id( $courseid, $itemid ) {
		$course_next_id = '';

		// 講座IDをキーに、登録されたコース一覧を取得.
		$item_course_related = get_post_meta( $itemid, 'iihlms_item_relation', true );

		if ( false === $item_course_related ) {
			return $course_next_id;
		}
		$item_course_related = isset( $item_course_related ) ? (array) $item_course_related : array();

		if ( ! empty( $item_course_related ) ) {
			$count = count( $item_course_related );
			$key   = array_search( (string) $courseid, $item_course_related, true );

			$nextkey = 0;
			if ( $count - 1 !== $key ) {
				$nextkey = $item_course_related[ $key + 1 ];
			}
			if ( $nextkey > 0 ) {
				$course_next_id = $nextkey;
			}
		}

		return $course_next_id;
	}
	/**
	 * 前のコースへのリンクを取得
	 *
	 * @param string $courseid コースID.
	 * @param string $itemid 講座ID.
	 * @return string
	 */
	public function get_previous_course_link( $courseid, $itemid ) {
		$course_previous_link = '';

		// 講座IDをキーに、登録されたコース一覧を取得.
		$item_course_related = get_post_meta( $itemid, 'iihlms_item_relation', true );

		if ( false === $item_course_related ) {
			return $course_previous_link;
		}
		$item_course_related = isset( $item_course_related ) ? (array) $item_course_related : array();

		if ( ! empty( $item_course_related ) ) {
			$count = count( $item_course_related );
			$key   = array_search( (string) $courseid, $item_course_related, true );

			$prevkey = 0;
			if ( 0 !== $key ) {
				$prevkey = $item_course_related[ $key - 1 ];
			}
			if ( $prevkey > 0 ) {
				$course_previous_link = get_permalink( $prevkey );
			}
		}
	}
	/**
	 * 前のコースのコースIDを取得
	 *
	 * @param string $courseid コースID.
	 * @param string $itemid 講座ID.
	 * @return string
	 */
	public function get_previous_course_id( $courseid, $itemid ) {
		$course_previous_id = '';

		// 講座IDをキーに、登録されたコース一覧を取得.
		$item_course_related = get_post_meta( $itemid, 'iihlms_item_relation', true );

		if ( false === $item_course_related ) {
			return $course_previous_id;
		}
		$item_course_related = isset( $item_course_related ) ? (array) $item_course_related : array();

		if ( ! empty( $item_course_related ) ) {
			$count = count( $item_course_related );
			$key   = array_search( (string) $courseid, $item_course_related, true );

			$prevkey = 0;
			if ( 0 !== $key ) {
				$prevkey = $item_course_related[ $key - 1 ];
			}
			if ( $prevkey > 0 ) {
				$course_previous_id = $prevkey;
			}
		}

		return $course_previous_id;
	}

	/**
	 * カスタムクエリの追加
	 *
	 * @param array $vars 配列.
	 * @return $vars
	 */
	public function ext_query_vars( $vars ) {
		$vars[] = 'iihlmsmypageitem';
		$vars[] = 'iihlmsmypagecourse';
		$vars[] = 'iihlmsmypagelesson';
		$vars[] = 'iihlmsapplyorderkey';
		$vars[] = 'iihlmsacceptmail';
		$vars[] = 'iihlmsregisttoken';
		$vars[] = 'iihlmspageoffset';
		$vars[] = 'iihlmsuserpage';
		$vars[] = 'iihlms-api';
		$vars[] = 'iihlms-orderid';
		$vars[] = 'iihlmstestc';
		$vars[] = 'iihlmstestr';
		$vars[] = 'setup_intent';
		$vars[] = 'setup_intent_client_secret';
		$vars[] = 'payment_intent';
		$vars[] = 'payment_intent_client_secret';
		$vars[] = 'redirect_status';
		return $vars;
	}

	/**
	 * 初期ページの登録
	 *
	 * @return void
	 */
	public function set_default_page() {
		global $wpdb;
		$post_table = $wpdb->posts;

		// 申し込みページ.
		$count = $wpdb->get_var(
			$wpdb->prepare(
				'
				SELECT ID
				FROM %1s
				WHERE
					post_name = %s
				',
				$post_table,
				IIHLMS_APPLYPAGE_NAME,
			)
		);
		if ( null === $count ) {
			$user = wp_get_current_user();

			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO $wpdb->posts
					(post_author,
					post_date,
					post_date_gmt,
					post_content,
					post_title,
					post_excerpt,
					post_status,
					comment_status,
					ping_status,
					post_password,
					post_name,
					to_ping,
					pinged,
					post_modified,
					post_modified_gmt,
					post_content_filtered,
					post_parent,
					guid,
					menu_order,
					post_type,
					post_mime_type,
					comment_count)
					VALUES (
					%d,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%d,
					%s,
					%d,
					%s,
					%s,
					%d)
					",
					$user->ID,
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					esc_html__( '申込み', 'imaoikiruhitolms' ),
					'',
					'publish',
					'closed',
					'closed',
					'',
					IIHLMS_APPLYPAGE_NAME,
					'',
					'',
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					0,
					'',
					0,
					'page',
					'',
					0
				)
			);
			$count = $wpdb->insert_id;
		}
		update_option( 'iihlms_applypage_id', $count );

		// 申し込み完了ページ.
		$count = $wpdb->get_var(
			$wpdb->prepare(
				'
				SELECT ID
				FROM %1s
				WHERE
					post_name = %s
				',
				$post_table,
				IIHLMS_APPLYRESULTPAGE_NAME,
			)
		);
		if ( null === $count ) {
			$user = wp_get_current_user();

			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO $wpdb->posts
					(post_author,
					post_date,
					post_date_gmt,
					post_content,
					post_title,
					post_excerpt,
					post_status,
					comment_status,
					ping_status,
					post_password,
					post_name,
					to_ping,
					pinged,
					post_modified,
					post_modified_gmt,
					post_content_filtered,
					post_parent,
					guid,
					menu_order,
					post_type,
					post_mime_type,
					comment_count)
					VALUES (
					%d,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%d,
					%s,
					%d,
					%s,
					%s,
					%d)
					",
					$user->ID,
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					esc_html__( '申し込み完了', 'imaoikiruhitolms' ),
					'',
					'publish',
					'closed',
					'closed',
					'',
					IIHLMS_APPLYRESULTPAGE_NAME,
					'',
					'',
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					0,
					'',
					0,
					'page',
					'',
					0
				)
			);
			$count = $wpdb->insert_id;
		}
		update_option( 'iihlms_applyresultpage_id', $count );

		// ユーザー情報ページ.
		$count = $wpdb->get_var(
			$wpdb->prepare(
				'
				SELECT ID
				FROM %1s
				WHERE
					post_name = %s
				',
				$post_table,
				IIHLMS_USERPAGE_NAME,
			)
		);
		if ( null === $count ) {
			$user = wp_get_current_user();

			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO $wpdb->posts
					(post_author,
					post_date,
					post_date_gmt,
					post_content,
					post_title,
					post_excerpt,
					post_status,
					comment_status,
					ping_status,
					post_password,
					post_name,
					to_ping,
					pinged,
					post_modified,
					post_modified_gmt,
					post_content_filtered,
					post_parent,
					guid,
					menu_order,
					post_type,
					post_mime_type,
					comment_count)
					VALUES (
					%d,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%d,
					%s,
					%d,
					%s,
					%s,
					%d)
					",
					$user->ID,
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					esc_html__( 'ユーザー情報', 'imaoikiruhitolms' ),
					'',
					'publish',
					'closed',
					'closed',
					'',
					IIHLMS_USERPAGE_NAME,
					'',
					'',
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					0,
					'',
					0,
					'page',
					'',
					0
				)
			);
			$count = $wpdb->insert_id;
		}
		update_option( 'iihlms_userpage_id', $count );

		// 新規ユーザー登録ページ.
		$count = $wpdb->get_var(
			$wpdb->prepare(
				'
				SELECT ID
				FROM %1s
				WHERE
					post_name = %s
				',
				$post_table,
				IIHLMS_USERREGISTRATIONPAGE_NAME,
			)
		);
		if ( null === $count ) {
			$user = wp_get_current_user();

			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO $wpdb->posts
					(post_author,
					post_date,
					post_date_gmt,
					post_content,
					post_title,
					post_excerpt,
					post_status,
					comment_status,
					ping_status,
					post_password,
					post_name,
					to_ping,
					pinged,
					post_modified,
					post_modified_gmt,
					post_content_filtered,
					post_parent,
					guid,
					menu_order,
					post_type,
					post_mime_type,
					comment_count)
					VALUES (
					%d,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%d,
					%s,
					%d,
					%s,
					%s,
					%d)
					",
					$user->ID,
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					esc_html__( '新規ユーザー登録', 'imaoikiruhitolms' ),
					'',
					'publish',
					'closed',
					'closed',
					'',
					IIHLMS_USERREGISTRATIONPAGE_NAME,
					'',
					'',
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					0,
					'',
					0,
					'page',
					'',
					0
				)
			);
			$count = $wpdb->insert_id;
		}
		update_option( 'iihlms_userregistpage_id', $count );

		// 新規ユーザー登録ページ.
		$count = $wpdb->get_var(
			$wpdb->prepare(
				'
				SELECT ID
				FROM %1s
				WHERE
					post_name = %s
				',
				$post_table,
				IIHLMS_ACCEPTINGUSERREGISTRATION_NAME,
			)
		);
		if ( null === $count ) {
			$user = wp_get_current_user();

			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO $wpdb->posts
					(post_author,
					post_date,
					post_date_gmt,
					post_content,
					post_title,
					post_excerpt,
					post_status,
					comment_status,
					ping_status,
					post_password,
					post_name,
					to_ping,
					pinged,
					post_modified,
					post_modified_gmt,
					post_content_filtered,
					post_parent,
					guid,
					menu_order,
					post_type,
					post_mime_type,
					comment_count) 
					VALUES (
					%d,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%d,
					%s,
					%d,
					%s,
					%s,
					%d)
					",
					$user->ID,
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					esc_html__( '新規ユーザー登録', 'imaoikiruhitolms' ),
					'',
					'publish',
					'closed',
					'closed',
					'',
					IIHLMS_ACCEPTINGUSERREGISTRATION_NAME,
					'',
					'',
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					0,
					'',
					0,
					'page',
					'',
					0
				)
			);
			$count = $wpdb->insert_id;
		}
		update_option( 'iihlms_acceptinguserregistpage_id', $count );

		// 注文履歴ページ.
		$count = $wpdb->get_var(
			$wpdb->prepare(
				'
				SELECT ID
				FROM %1s
				WHERE
					post_name = %s
				',
				$post_table,
				IIHLMS_ORDERHISTORY_NAME,
			)
		);
		if ( null === $count ) {
			$user = wp_get_current_user();

			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO $wpdb->posts
					(post_author,
					post_date,
					post_date_gmt,
					post_content,
					post_title,
					post_excerpt,
					post_status,
					comment_status,
					ping_status,
					post_password,
					post_name,
					to_ping,
					pinged,
					post_modified,
					post_modified_gmt,
					post_content_filtered,
					post_parent,
					guid,
					menu_order,
					post_type,
					post_mime_type,
					comment_count) 
					VALUES (
					%d,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%d,
					%s,
					%d,
					%s,
					%s,
					%d)
					",
					$user->ID,
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					esc_html__( '注文履歴', 'imaoikiruhitolms' ),
					'',
					'publish',
					'closed',
					'closed',
					'',
					IIHLMS_ORDERHISTORY_NAME,
					'',
					'',
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					0,
					'',
					0,
					'page',
					'',
					0
				)
			);
			$count = $wpdb->insert_id;
		}
		update_option( 'iihlms_orderhistorypage_id', $count );

		// サブスクリプション解約ページ.
		$count = $wpdb->get_var(
			$wpdb->prepare(
				'
				SELECT ID
				FROM %1s
				WHERE
					post_name = %s
				',
				$post_table,
				IIHLMS_SUBSCRIPTIONCANCELLATION_NAME,
			)
		);
		if ( null === $count ) {
			$user = wp_get_current_user();

			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO $wpdb->posts
					(post_author,
					post_date,
					post_date_gmt,
					post_content,
					post_title,
					post_excerpt,
					post_status,
					comment_status,
					ping_status,
					post_password,
					post_name,
					to_ping,
					pinged,
					post_modified,
					post_modified_gmt,
					post_content_filtered,
					post_parent,
					guid,
					menu_order,
					post_type,
					post_mime_type,
					comment_count) 
					VALUES (
					%d,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%d,
					%s,
					%d,
					%s,
					%s,
					%d)
					",
					$user->ID,
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					esc_html__( 'サブスクリプション解約', 'imaoikiruhitolms' ),
					'',
					'publish',
					'closed',
					'closed',
					'',
					IIHLMS_SUBSCRIPTIONCANCELLATION_NAME,
					'',
					'',
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					0,
					'',
					0,
					'page',
					'',
					0
				)
			);
			$count = $wpdb->insert_id;
		}
		update_option( 'iihlms_subscriptioncancellationpage_id', $count );

		// サブスクリプション解約完了ページ.
		$count = $wpdb->get_var(
			$wpdb->prepare(
				'
				SELECT ID
				FROM %1s
				WHERE
					post_name = %s
				',
				$post_table,
				IIHLMS_SUBSCRIPTIONCANCELLATIONRESULT_NAME,
			)
		);
		if ( null === $count ) {
			$user = wp_get_current_user();

			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO $wpdb->posts
					(post_author,
					post_date,
					post_date_gmt,
					post_content,
					post_title,
					post_excerpt,
					post_status,
					comment_status,
					ping_status,
					post_password,
					post_name,
					to_ping,
					pinged,
					post_modified,
					post_modified_gmt,
					post_content_filtered,
					post_parent,
					guid,
					menu_order,
					post_type,
					post_mime_type,
					comment_count) 
					VALUES (
					%d,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%d,
					%s,
					%d,
					%s,
					%s,
					%d)
					",
					$user->ID,
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					esc_html__( 'サブスクリプション解約完了', 'imaoikiruhitolms' ),
					'',
					'publish',
					'closed',
					'closed',
					'',
					IIHLMS_SUBSCRIPTIONCANCELLATIONRESULT_NAME,
					'',
					'',
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					0,
					'',
					0,
					'page',
					'',
					0
				)
			);
			$count = $wpdb->insert_id;
		}
		update_option( 'iihlms_subscriptioncancellationresultpage_id', $count );

		// テスト結果一覧ページ.
		$count = $wpdb->get_var(
			$wpdb->prepare(
				'
				SELECT ID
				FROM %1s
				WHERE
					post_name = %s
				',
				$post_table,
				IIHLMS_TESTRESULTLIST_NAME,
			)
		);
		if ( null === $count ) {
			$user = wp_get_current_user();

			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO $wpdb->posts
					(post_author,
					post_date,
					post_date_gmt,
					post_content,
					post_title,
					post_excerpt,
					post_status,
					comment_status,
					ping_status,
					post_password,
					post_name,
					to_ping,
					pinged,
					post_modified,
					post_modified_gmt,
					post_content_filtered,
					post_parent,
					guid,
					menu_order,
					post_type,
					post_mime_type,
					comment_count) 
					VALUES (
					%d,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%d,
					%s,
					%d,
					%s,
					%s,
					%d)
					",
					$user->ID,
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					esc_html__( 'テスト結果一覧', 'imaoikiruhitolms' ),
					'',
					'publish',
					'closed',
					'closed',
					'',
					IIHLMS_TESTRESULTLIST_NAME,
					'',
					'',
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					0,
					'',
					0,
					'page',
					'',
					0
				)
			);
			$count = $wpdb->insert_id;
		}
		update_option( 'iihlms_testresultlistpage_id', $count );
		// テスト結果一覧ページ.
		$count = $wpdb->get_var(
			$wpdb->prepare(
				'
				SELECT ID
				FROM %1s
				WHERE
					post_name = %s
				',
				$post_table,
				IIHLMS_TESTRESULT_NAME,
			)
		);
		if ( null === $count ) {
			$user = wp_get_current_user();

			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO $wpdb->posts
					(post_author,
					post_date,
					post_date_gmt,
					post_content,
					post_title,
					post_excerpt,
					post_status,
					comment_status,
					ping_status,
					post_password,
					post_name,
					to_ping,
					pinged,
					post_modified,
					post_modified_gmt,
					post_content_filtered,
					post_parent,
					guid,
					menu_order,
					post_type,
					post_mime_type,
					comment_count) 
					VALUES (
					%d,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%d,
					%s,
					%d,
					%s,
					%s,
					%d)
					",
					$user->ID,
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					esc_html__( 'テスト結果', 'imaoikiruhitolms' ),
					'',
					'publish',
					'closed',
					'closed',
					'',
					IIHLMS_TESTRESULT_NAME,
					'',
					'',
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					0,
					'',
					0,
					'page',
					'',
					0
				)
			);
			$count = $wpdb->insert_id;
		}
		update_option( 'iihlms_testresultpage_id', $count );
		// 回答の詳細を表示ページ.
		$count = $wpdb->get_var(
			$wpdb->prepare(
				'
				SELECT ID
				FROM %1s
				WHERE
					post_name = %s
				',
				$post_table,
				IIHLMS_TESTRESULT_VIEWANSWERDETAILS_NAME,
			)
		);
		if ( null === $count ) {
			$user = wp_get_current_user();

			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO $wpdb->posts
					(post_author,
					post_date,
					post_date_gmt,
					post_content,
					post_title,
					post_excerpt,
					post_status,
					comment_status,
					ping_status,
					post_password,
					post_name,
					to_ping,
					pinged,
					post_modified,
					post_modified_gmt,
					post_content_filtered,
					post_parent,
					guid,
					menu_order,
					post_type,
					post_mime_type,
					comment_count) 
					VALUES (
					%d,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%d,
					%s,
					%d,
					%s,
					%s,
					%d)
					",
					$user->ID,
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					esc_html__( '回答の詳細', 'imaoikiruhitolms' ),
					'',
					'publish',
					'closed',
					'closed',
					'',
					IIHLMS_TESTRESULT_VIEWANSWERDETAILS_NAME,
					'',
					'',
					current_time( 'mysql' ),
					current_time( 'mysql', 1 ),
					'',
					0,
					'',
					0,
					'page',
					'',
					0
				)
			);
			$count = $wpdb->insert_id;
		}
		update_option( 'iihlms_testresult_viewanswerdetails_page_id', $count );
	}

	/**
	 * 初期値の登録
	 *
	 * @return bool
	 */
	public function set_default_setting() {

		if ( '' === get_option( 'iihlms_admin_mailaddress', '' ) ) {
			update_option( 'iihlms_admin_mailaddress', get_option( 'admin_email' ) );
		}
		if ( '' === get_option( 'iihlms_admin_mailname', '' ) ) {
			update_option( 'iihlms_admin_mailname', get_option( 'blogname' ) );
		}

		$default_subject = 'お申込みありがとうございます';
		$default_body    = '*NAME*様

お申込みを受け付けました。

*APPLICATION_DETAILS*
';
		$default_body   .= '
---
' . get_option( 'blogname' ) . '
' . home_url() . '
';

		if ( '' === get_option( 'iihlms_mailsubject_application_completed', '' ) ) {
			update_option( 'iihlms_mailsubject_application_completed', $default_subject );
		}

		if ( '' === get_option( 'iihlms_mailbody_application_completed', '' ) ) {
			update_option( 'iihlms_mailbody_application_completed', $default_body );
		}

		$default_subject = 'ユーザー登録仮受付';
		$default_body    = '下記のURLからユーザー登録をお願いいたします。URLは24時間有効です。

*USER_REGISTRATION_URL*
';
		$default_body   .= '
---
' . get_option( 'blogname' ) . '
' . home_url() . '
';

		$default_subject = 'サブスクリプションにお申込みありがとうございます';
		$default_body    = '*NAME*様

サブスクリプションのお申込みを受け付けました。

*APPLICATION_DETAILS*
';
		$default_body   .= '
---
' . get_option( 'blogname' ) . '
' . home_url() . '
';

		if ( '' === get_option( 'iihlms_mailsubject_subscription_application_completed', '' ) ) {
			update_option( 'iihlms_mailsubject_subscription_application_completed', $default_subject );
		}

		if ( '' === get_option( 'iihlms_mailbody_subscription_application_completed', '' ) ) {
			update_option( 'iihlms_mailbody_subscription_application_completed', $default_body );
		}

		$default_subject = 'サブスクリプションを解約いたしました';
		$default_body    = '*NAME*様

サブスクリプションを解約いたしました。

*CANCELLATION_DETAILS*
';
		$default_body   .= '
---
' . get_option( 'blogname' ) . '
' . home_url() . '
';

		if ( '' === get_option( 'iihlms_mailsubject_subscription_cancellation_completed', '' ) ) {
			update_option( 'iihlms_mailsubject_subscription_cancellation_completed', $default_subject );
		}

		if ( '' === get_option( 'iihlms_mailbody_subscription_cancellation_completed', '' ) ) {
			update_option( 'iihlms_mailbody_subscription_cancellation_completed', $default_body );
		}

		$default_subject = 'サブスクリプションの支払に失敗しました';
		$default_body    = '*NAME*様

サブスクリプションの支払に失敗しました。

*PAYMENT_FAILED_DETAILS*

*PAYMENT_URL*
';
		$default_body   .= '
---
' . get_option( 'blogname' ) . '
' . home_url() . '
';

		if ( '' === get_option( 'iihlms_mailsubject_subscription_payment_failed', '' ) ) {
			update_option( 'iihlms_mailsubject_subscription_payment_failed', $default_subject );
		}

		if ( '' === get_option( 'iihlms_mailbody_subscription_payment_failed', '' ) ) {
			update_option( 'iihlms_mailbody_subscription_payment_failed', $default_body );
		}

		$default_subject = '支払い失敗上限によりサブスクリプションを解約いたしました';
		$default_body    = '*NAME*様

支払い失敗が上限回数に達したため、サブスクリプションを解約いたしました。

*CANCELLATION_DETAILS*
';
		$default_body   .= '
---
' . get_option( 'blogname' ) . '
' . home_url() . '
';

		if ( '' === get_option( 'iihlms_mailsubject_subscription_suspended_cancellation_completed', '' ) ) {
			update_option( 'iihlms_mailsubject_subscription_suspended_cancellation_completed', $default_subject );
		}

		if ( '' === get_option( 'iihlms_mailbody_subscription_suspended_cancellation_completed', '' ) ) {
			update_option( 'iihlms_mailbody_subscription_suspended_cancellation_completed', $default_body );
		}

		$default_subject = '期限を迎えたため、サブスクリプションを解約いたしました';
		$default_body    = '*NAME*様

期限を迎えたため、サブスクリプションを解約いたしました。

*CANCELLATION_DETAILS*
';
		$default_body   .= '
---
' . get_option( 'blogname' ) . '
' . home_url() . '
';

		if ( '' === get_option( 'iihlms_mailsubject_subscription_expired_cancellation_completed', '' ) ) {
			update_option( 'iihlms_mailsubject_subscription_expired_cancellation_completed', $default_subject );
		}

		if ( '' === get_option( 'iihlms_mailbody_subscription_expired_cancellation_completed', '' ) ) {
			update_option( 'iihlms_mailbody_subscription_expired_cancellation_completed', $default_body );
		}

		$default_subject = 'ユーザー登録仮受付';
		$default_body    = '下記のURLからユーザー登録をお願いいたします。URLは24時間有効です。

*USER_REGISTRATION_URL*
';
		$default_body   .= '
---
' . get_option( 'blogname' ) . '
' . home_url() . '
';

		if ( '' === get_option( 'iihlms_mailsubject_user_registration_reception', '' ) ) {
			update_option( 'iihlms_mailsubject_user_registration_reception', $default_subject );
		}
		if ( '' === get_option( 'iihlms_mailbody_user_registration_reception', '' ) ) {
			update_option( 'iihlms_mailbody_user_registration_reception', $default_body );
		}

		$default_subject = 'ユーザー登録完了いたしました';
		$default_body    = '*NAME*様

ユーザー登録完了いたしました。

ユーザー名：*SIGNUP_USER_NAME*
メールアドレス：*USER_MAIL*
';
		$default_body   .= '
---
' . get_option( 'blogname' ) . '
' . home_url() . '
';

		if ( '' === get_option( 'iihlms_mailsubject_user_registration_completed', '' ) ) {
			update_option( 'iihlms_mailsubject_user_registration_completed', $default_subject );
		}

		if ( '' === get_option( 'iihlms_mailbody_user_registration_completed', '' ) ) {
			update_option( 'iihlms_mailbody_user_registration_completed', $default_body );
		}

		$default_subject = 'ユーザー情報を修正いたしました';
		$default_body    = '*NAME*様

ユーザー情報を修正いたしました。

';
		$default_body   .= '
---
' . get_option( 'blogname' ) . '
' . home_url() . '
';

		if ( '' === get_option( 'iihlms_mailsubject_userpage_change', '' ) ) {
			update_option( 'iihlms_mailsubject_userpage_change', $default_subject );
		}

		if ( '' === get_option( 'iihlms_mailbody_userpage_change', '' ) ) {
			update_option( 'iihlms_mailbody_userpage_change', $default_body );
		}

		$default_subject = 'パスワードリセット';
		$default_body    = 'パスワードリセットのリクエストを受け付けました。

もしこれが間違いだった場合は、このメールを無視すれば何も起こりません。

パスワードをリセットするには、以下へアクセスしてください。

*PASSWORD_RESET_URL*
';
		$default_body   .= '
---
' . get_option( 'blogname' ) . '
' . home_url() . '
';

		if ( '' === get_option( 'iihlms_mailsubject_change_mail_password_reset', '' ) ) {
			update_option( 'iihlms_mailsubject_change_mail_password_reset', $default_subject );
		}

		if ( '' === get_option( 'iihlms_mailbody_change_mail_password_reset', '' ) ) {
			update_option( 'iihlms_mailbody_change_mail_password_reset', $default_body );
		}

		$default_subject = 'パスワードが変更されました';
		$default_body    = '*NAME*様のパスワードが変更されました。
';
		$default_body   .= '
---
' . get_option( 'blogname' ) . '
' . home_url() . '
';

		if ( '' === get_option( 'iihlms_mailsubject_change_mail_password_reset_completed', '' ) ) {
			update_option( 'iihlms_mailsubject_change_mail_password_reset_completed', $default_subject );
		}

		if ( '' === get_option( 'iihlms_mailbody_change_mail_password_reset_completed', '' ) ) {
			update_option( 'iihlms_mailbody_change_mail_password_reset_completed', $default_body );
		}

		$default_subject = '新規ユーザー登録のお知らせ';
		$default_body    = 'ユーザー登録完了いたしました。

ユーザー名：*USER_NAME*

以下のURLにアクセスし、パスワードを設定してください。
*PASSWORD_RESET_URL*
';
		$default_body   .= '
---
' . get_option( 'blogname' ) . '
' . home_url() . '
';

		if ( '' === get_option( 'iihlms_mailsubject_add_new_user', '' ) ) {
			update_option( 'iihlms_mailsubject_add_new_user', $default_subject );
		}

		if ( '' === get_option( 'iihlms_mailbody_add_new_user', '' ) ) {
			update_option( 'iihlms_mailbody_add_new_user', $default_body );
		}

		$default_subject = '新規ユーザー登録のお知らせ';
		$default_body    = 'ユーザー登録完了いたしました。

ユーザー名：*USER_NAME*
メールアドレス：*USER_MAIL*
';
		$default_body   .= '
---
' . get_option( 'blogname' ) . '
' . home_url() . '
';

		if ( '' === get_option( 'iihlms_mailsubject_add_new_user_admin', '' ) ) {
			update_option( 'iihlms_mailsubject_add_new_user_admin', $default_subject );
		}

		if ( '' === get_option( 'iihlms_mailbody_add_new_user_admin', '' ) ) {
			update_option( 'iihlms_mailbody_add_new_user_admin', $default_body );
		}

		$default_subject = 'メールアドレス変更のお知らせ';
		$default_body    = '*NAME*様

メールアドレスを変更いたしました。
変更前のメールアドレス：*NEW_MAIL*
変更後のメールアドレス：*OLD_MAIL*
';
		$default_body   .= '
---
' . get_option( 'blogname' ) . '
' . home_url() . '
';

		if ( '' === get_option( 'iihlms_mailsubject_change_email', '' ) ) {
			update_option( 'iihlms_mailsubject_change_email', $default_subject );
		}

		if ( '' === get_option( 'iihlms_mailbody_change_email', '' ) ) {
			update_option( 'iihlms_mailbody_change_email', $default_body );
		}

		return true;
	}

	/**
	 * レッスンページにて、コースページへのリンクを取得
	 *
	 * @param string $course_id コースID.
	 * @param string $item_id 講座ID.
	 * @return string
	 */
	public function get_course_link_lessonpage( $course_id, $item_id ) {
		if ( '' !== $item_id ) {
			$course_url = esc_url( add_query_arg( 'iihlmsmypageitem', $item_id, get_permalink( $course_id ) ) );
		} else {
			$course_url = esc_url( get_permalink( $course_id ) );
		}
		return $course_url;
	}

	/**
	 * 注文受付ページかチェック
	 *
	 * @param string $url 対象URL.
	 * @return bool
	 */
	public function is_applypage( $url ) {
		$iihlms_applypage_id = get_option( 'iihlms_applypage_id' );

		$postid = (string) url_to_postid( $url );

		if ( $iihlms_applypage_id === $postid ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 注文受付完了ページかチェック
	 *
	 * @param string $url 対象URL.
	 * @return bool
	 */
	public function is_applyresultpage( $url ) {
		$iihlms_applyresultpage_id = get_option( 'iihlms_applyresultpage_id' );

		$postid = (string) url_to_postid( $url );

		if ( $iihlms_applyresultpage_id === $postid ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * ユーザーページかチェック
	 *
	 * @param string $url 対象URL.
	 * @return bool
	 */
	public function is_userpage( $url ) {
		$iihlms_userpage_id = get_option( 'iihlms_userpage_id' );

		$postid = (string) url_to_postid( $url );

		if ( $iihlms_userpage_id === $postid ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 新規ユーザー登録ページかチェック
	 *
	 * @param string $url 対象URL.
	 * @return bool
	 */
	public function is_userregistpage( $url ) {
		$iihlms_userregistpage_id = get_option( 'iihlms_userregistpage_id' );

		$postid = (string) url_to_postid( $url );

		if ( $iihlms_userregistpage_id === $postid ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 新規ユーザー登録ページかチェック
	 *
	 * @param string $url 対象URL.
	 * @return bool
	 */
	public function is_acceptinguserregistpage( $url ) {
		$iihlms_acceptinguserregistpage_id = get_option( 'iihlms_acceptinguserregistpage_id' );

		$postid = (string) url_to_postid( $url );

		if ( $iihlms_acceptinguserregistpage_id === $postid ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 注文履歴ページかチェック
	 *
	 * @param string $url 対象URL.
	 * @return bool
	 */
	public function is_orderhistorypage( $url ) {
		$iihlms_orderhistorypage_id = get_option( 'iihlms_orderhistorypage_id' );

		$postid = (string) url_to_postid( $url );

		if ( $iihlms_orderhistorypage_id === $postid ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * サブスクリプション解約ページかチェック
	 *
	 * @param string $url 対象URL.
	 * @return bool
	 */
	public function is_subscriptioncancellationpage( $url ) {
		$iihlms_subscriptioncancellationpage_id = get_option( 'iihlms_subscriptioncancellationpage_id' );

		$postid = (string) url_to_postid( $url );

		if ( $iihlms_subscriptioncancellationpage_id === $postid ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * サブスクリプション解約完了ページかチェック
	 *
	 * @param string $url 対象URL.
	 * @return bool
	 */
	public function is_subscriptioncancellationresultpage( $url ) {
		$iihlms_subscriptioncancellationresultpage_id = get_option( 'iihlms_subscriptioncancellationresultpage_id' );

		$postid = (string) url_to_postid( $url );

		if ( $iihlms_subscriptioncancellationresultpage_id === $postid ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * テスト結果一覧ページかチェック
	 *
	 * @param string $url 対象URL.
	 * @return bool
	 */
	public function is_testresultlistpage( $url ) {
		$iihlms_testresultlistpage_id = get_option( 'iihlms_testresultlistpage_id' );

		$postid = (string) url_to_postid( $url );

		if ( $iihlms_testresultlistpage_id === $postid ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * テスト結果ページかチェック
	 *
	 * @param string $url 対象URL.
	 * @return bool
	 */
	public function is_testresultpage( $url ) {
		$iihlms_testresultpage_id = get_option( 'iihlms_testresultpage_id' );

		$postid = (string) url_to_postid( $url );

		if ( $iihlms_testresultpage_id === $postid ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 回答の詳細を表示ページかチェック
	 *
	 * @param string $url 対象URL.
	 * @return bool
	 */
	public function is_testresultviewanswerdetailspage( $url ) {
		$iihlms_testresult_viewanswerdetails_page_id = get_option( 'iihlms_testresult_viewanswerdetails_page_id' );

		$postid = (string) url_to_postid( $url );

		if ( $iihlms_testresult_viewanswerdetails_page_id === $postid ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * ログインページかチェック
	 *
	 * @return bool
	 */
	public function is_loginpage() {
		$pagenow = sanitize_text_field( wp_unslash( $GLOBALS['pagenow'] ) );
		if ( 'wp-login.php' === $pagenow ) {
			return true;
		}
		return false;
	}

	/**
	 * HOMEページ
	 *
	 * @return void
	 */
	public function iihlms_homepage_content() {
		global $wpdb;

		echo '<div class="container container-width justify-content-center">';
		echo '<div class="row iihlms-row-m0">';
		echo '<h2 class="title-text-top">' . esc_html( get_bloginfo( 'name' ) ) . esc_html__( 'へようこそ', 'imaoikiruhitolms' ) . '</h2>';
		echo '</div>';
		echo '</div>';

		// 購入済の講座一覧.
		$order_table = $wpdb->prefix . 'iihlms_order';
		$user        = wp_get_current_user();
		$results     = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT item_id, expiration_date_time
				FROM %1s
				WHERE 
					user_id = %d
				',
				$order_table,
				$user->ID,
			)
		);

		$items_purchased = array();
		foreach ( $results as $row ) {
			$today          = current_datetime();
			$expiration_day = new DateTimeImmutable( $row->expiration_date_time );
			if ( '0000-00-00 00:00:00' === $row->expiration_date_time ) {
				array_push( $items_purchased, $row->item_id );
			} elseif ( $expiration_day->format( $this->specify_date_format ) >= $today->format( $this->specify_date_format ) ) {
				array_push( $items_purchased, $row->item_id );
			}
		}

		echo '<a id="mylearning" class="anchor-link"></a>';
		echo '<div class="current-course-area">';
		echo '<div class="container container-width justify-content-center">';
		echo '<div class="row">';

		$h2_title_text = esc_html__( '受講中の講座', 'imaoikiruhitolms' );
		if ( '' !== apply_filters( 'iihlms_lc_ctf_1', '' ) ) {
			$h2_title_text = apply_filters( 'iihlms_lc_ctf_1', '' );
		}
		echo '<h2 class="title-text-mylearning">';
		echo esc_html( $h2_title_text );
		echo '</h2>';

		if ( empty( $items_purchased ) ) {
			echo '<p>' . esc_html__( 'まだありません', 'imaoikiruhitolms' ) . '</p>';
		} else {
			$item_progress_number_data = $this->get_item_progress_number( $items_purchased );

			$args     = array(
				'post_type'           => 'iihlms_items',
				'posts_per_page'      => -1,
				'post_status'         => 'publish',
				'post__in'            => $items_purchased,
				'ignore_sticky_posts' => 1,
			);
			$my_posts = get_posts( $args );

			$counter = 0;
			foreach ( $my_posts as $post ) {
				setup_postdata( $post );

				// この講座を表示する権限チェック.
				$permission_item_membership = $this->check_permission_item_membership( $post->ID );
				if ( false === $permission_item_membership ) {
					continue;
				}

				// 指定したコースを受講完了しているかチェック.
				$permission_item_course_complete_precondition = $this->check_item_course_complete_precondition( $post->ID );
				if ( false === $permission_item_course_complete_precondition ) {
					continue;
				}

				$counter++;
				if ( 1 === $counter ) {
					echo '<div class="card-group">';
				}
				if ( 5 === $counter ) {
					echo '<p class="text-center">';
					echo '<button class="btn btn-more mb-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">';
					echo '<span id="btn-more-text">' . esc_html__( 'もっと見る', 'imaoikiruhitolms' ) . '</span>';
					echo '<i class="bi bi-chevron-down btn-more-icon-down"></i>';
					echo '<i class="bi bi-chevron-up btn-more-icon-up icon-display-none"></i>';
					echo '</button>';
					echo '</p>';
					echo '<div class="collapse" id="collapseExample">';
					echo '<div class="card-group">';
				}

				echo '<div class="mb-3 mb-sm-4 me-0 me-sm-4">';
				echo '<div class="card iihlmscardsize">';
				if ( has_post_thumbnail( $post->ID ) ) {
					echo '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">';
					echo '<img src="' . esc_url( get_the_post_thumbnail_url( $post->ID ) ) . '" class="card-img-top card-img-top-size" alt="...">';
					echo '</a>';
				} else {
					echo '<div class="iihlmscardimg">';
					echo '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">';
					echo '<img src="' . esc_url( get_template_directory_uri() . '/images/default_thumbnail.png' ) . '" class="card-img-top card-img-top-size" alt="...">';
					echo '</a>';
					echo '</div>';
				}
				echo '<div class="card-body">';
				echo '<h5 class="card-title card-title-overflow">' . esc_html( get_the_title( $post ) ) . '</h5>';
				if ( '' === apply_filters( 'iihlms_lc_ctf_1', '' ) ) {
					if ( array_key_exists( $post->ID, $item_progress_number_data ) ) {
						echo '<div class="card-course-text">';
						echo '<i class="bi bi-book"></i> ';
						echo esc_html( $this->get_item_course_relation_number( $post->ID ) );
						echo esc_html__( 'コース', 'imaoikiruhitolms' );
						echo '</div>';
						echo '<div class="progress progress-card"><div class="progress-bar progress-bar-card" role="progressbar" style="width: ' . esc_attr( $item_progress_number_data[ $post->ID ] ) . '%" aria-valuenow="' . esc_attr( $item_progress_number_data[ $post->ID ] ) . '" aria-valuemin="0" aria-valuemax="100"></div></div>';
						if ( 100 === $item_progress_number_data[ $post->ID ] ) {
							echo '<p class="card-text progress-card-text"><i class="bi bi-check-square-fill bi-progress-card-text"></i>' . esc_html__( '完了', 'imaoikiruhitolms' ) . '</p>';
						} else {
							echo '<p class="card-text progress-card-text">' . esc_html( $item_progress_number_data[ $post->ID ] ) . '%</p>';
						}
					} else {
						echo '<div class="card-course-text">';
						echo '<i class="bi bi-book"></i> ';
						echo esc_html( $this->get_item_course_relation_number( $post->ID ) );
						echo esc_html__( 'コース', 'imaoikiruhitolms' );
						echo '</div>';
						echo '<div class="progress progress-card"><div class="progress-bar progress-bar-card" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>';
						echo '<p class="card-text progress-card-text">0%</p>';
					}
				}
				echo '</div>';
				echo '</div>';
				echo '</div>';

				if ( 4 === $counter ) {
					echo '</div>';
				}
			}
			if ( $counter > 4 ) {
				echo '</div>';
			}

			echo '</div>';
			wp_reset_postdata();
		}
		?>
		<script>
			(function($) {
				$('#collapseExample').on('shown.bs.collapse', function () {
					$('.bi-chevron-down').hide();
					$('.bi-chevron-up').show();
				});
				$('#collapseExample').on('hidden.bs.collapse', function () {
					$('.bi-chevron-down').show();
					$('.bi-chevron-up').hide();
				});

			})(jQuery);
		</script>
		<?php
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '<a id="learnmore" class="anchor-link"></a>';
		echo '<div class="container container-width justify-content-center">';
		echo '<div class="row">';

		$h2_title_text = esc_html__( '講座一覧', 'imaoikiruhitolms' );
		if ( '' !== apply_filters( 'iihlms_lc_ctf_2', '' ) ) {
			$h2_title_text = apply_filters( 'iihlms_lc_ctf_2', '' );
		}
		echo '<h2 class="title-text-more">';
		echo esc_html( $h2_title_text );
		echo '</h2>';

		$args     = array(
			'post_type'      => 'iihlms_items',
			'posts_per_page' => -1,
		);
		$my_posts = get_posts( $args );

		echo '<div class="card-group">';
		foreach ( $my_posts as $post ) {
			setup_postdata( $post );

			// この講座を表示する権限チェック.
			$permission_item_membership = $this->check_permission_item_membership( $post->ID );
			if ( false === $permission_item_membership ) {
				continue;
			}

			// 指定したコースを受講完了しているかチェック.
			$permission_item_course_complete_precondition = $this->check_item_course_complete_precondition( $post->ID );
			if ( false === $permission_item_course_complete_precondition ) {
				continue;
			}

			if ( ! in_array( (string) $post->ID, $items_purchased, true ) ) {
				echo '<div class="mb-3 mb-sm-4 me-0 me-sm-4">';
				echo '<div class="card iihlmscardsize">';
				if ( has_post_thumbnail( $post->ID ) ) {
					echo '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">';
					echo '<img src="' . esc_url( get_the_post_thumbnail_url( $post->ID ) ) . '" class="card-img-top card-img-top-size" alt="...">';
					echo '</a>';
				} else {
					echo '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">';
					echo '<img src="' . esc_url( get_template_directory_uri() . '/images/default_thumbnail.png' ) . '" class="card-img-top card-img-top-size" alt="...">';
					echo '</a>';
				}
				echo '<div class="card-body">';
				echo '<h5 class="card-title card-title-overflow">' . esc_html( get_the_title( $post ) ) . '</h5>';

				if ( '' === apply_filters( 'iihlms_lc_ctf_2', '' ) ) {
					echo '<div class="card-course-text">';
						echo '<i class="bi bi-book"></i> ';
						echo esc_html( $this->get_item_course_relation_number( $post->ID ) );
						echo esc_html__( 'コース', 'imaoikiruhitolms' );
					echo '</div>';
				}
				echo '<div class="card-price-text">' . esc_html( $this->get_price_for_disp_by_id( $post->ID ) ) . '</div>';

				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
		}
		echo '</div>';
		echo '</div>';
		echo '</div>';
		wp_reset_postdata();
	}

	/**
	 * 講座ページ
	 *
	 * @return void
	 */
	public function iihlms_items_page_content() {
		global $wpdb;
		global $post;

		$this_item_id = $post->ID;

		if ( ! current_user_can( self::CAPABILITY_ADMIN ) ) {
			// この講座を表示する権限チェック.
			$permission_item_membership = $this->check_permission_item_membership( $this_item_id );
			if ( false === $permission_item_membership ) {
				$this->show_err_iihlms_items_page_content( esc_html__( '表示する権限がありません。', 'imaoikiruhitolms' ) );
				exit;
			}

			// 指定したコースを受講完了しているかチェック.
			$permission_item_course_complete_precondition = $this->check_item_course_complete_precondition( $this_item_id );
			if ( false === $permission_item_course_complete_precondition ) {
				$this->show_err_iihlms_items_page_content( esc_html__( '表示する権限がありません。', 'imaoikiruhitolms' ) );
				exit;
			}

			// 指定したテストに合格した後、この講座を閲覧・購入できるよう限定する.
			$check_item_test_pass_precondition = $this->check_item_test_pass_precondition( $this_item_id );
			if ( false === $check_item_test_pass_precondition ) {
				$this->show_err_iihlms_items_page_content( esc_html__( 'テストに合格するまで表示できません。', 'imaoikiruhitolms' ) );
				exit;
			}
		}

		// 購入済の講座.
		$order_table = $wpdb->prefix . 'iihlms_order';
		$user        = wp_get_current_user();

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT item_id, expiration_date_time
				FROM %1s
				WHERE 
					user_id = %d
					AND item_id = %d
				ORDER BY order_date_time DESC
				',
				$order_table,
				$user->ID,
				$post->ID,
			)
		);
		$number  = count( $results );

		$item_available = false;

		if ( $number > 0 ) {
			// 指定した講座IDは有効期限内か.
			$today = current_datetime();
			foreach ( $results as $row ) {
				$expiration_day = new DateTimeImmutable( $row->expiration_date_time );
				if ( '0000-00-00 00:00:00' === $row->expiration_date_time ) {
					$item_available = true;
				} elseif ( $expiration_day->format( $this->specify_date_format ) >= $today->format( $this->specify_date_format ) ) {
					$item_available = true;
				}
			}
		}

		if ( true === $item_available ) { // 購入済、有効期限内.
			echo '<div class="navigation-black">';
			echo '<div class="container container-width justify-content-center p-0">';
			echo '<div class="row iihlms-row-m0">';

			echo '<table class="table navigation-black-table">';
			echo '<tbody>';
			echo '<tr>';
			echo '<td class="navigation-black-table-left">';

			echo '<div class="navigation-black-left">';
			echo '<div class="navigation-black-title">';
			echo esc_html( get_the_title() );
			echo '</div>';

			echo '<div class="navigation-black-course-text">';
			if ( '' === apply_filters( 'iihlms_lc_ctf_1', '' ) ) {
				echo '<i class="bi bi-book"></i> ';
				echo esc_html( $this->get_item_course_relation_number( $post->ID ) );
				echo esc_html__( 'コース', 'imaoikiruhitolms' );
			}
			echo '</div>';
			if ( '' === apply_filters( 'iihlms_lc_ctf_1', '' ) ) {
				echo '<div class="progress navigation-progress-black"><div class="progress-bar progress-bar-card" role="progressbar" style="width: ' . esc_attr( $this->get_item_progress_number_one( $post->ID ) ) . '%" aria-valuenow="' . esc_attr( $this->get_item_progress_number_one( $post->ID ) ) . '" aria-valuemin="0" aria-valuemax="100"></div></div>';
				echo '<p class="navigation-black-progress-text">' . esc_html( $this->get_item_progress_number_one( $post->ID ) ) . esc_html__( '%完了', 'imaoikiruhitolms' ) . '</p>';
			}

			if ( ! empty( get_the_content() ) ) {
				echo '<div class="navigation-black-description-title">';
				$nav_title_text = esc_html__( 'この講座について', 'imaoikiruhitolms' );
				if ( '' !== apply_filters( 'iihlms_lc_ctf_3', '' ) ) {
					$nav_title_text = apply_filters( 'iihlms_lc_ctf_3', '' );
				}
				echo esc_html( $nav_title_text );
				echo '</div>';
				echo '<div class="navigation-black-description-text">';
				the_content();
				echo '</div>';
			}
			echo '</div>';
			echo '</td>';

			echo '<td class="navigation-black-table-right">';
			echo '<div class="navigation-black-right">';
			if ( has_post_thumbnail() ) {
				echo '<img src="' . esc_url( get_the_post_thumbnail_url( $post->ID, 'large' ) ) . '" class="navigation-black-img" alt="...">';
			} else {
				echo '<img src="' . esc_url( get_template_directory_uri() . '/images/default_thumbnail.png' ) . '" class="navigation-black-img" alt="...">';
			}
			echo '</div>';
			echo '</td>';
			echo '</tr>';
			echo '</tbody>';
			echo '</table>';
			echo '</div>';
			echo '</div>';
			echo '</div>';

			echo '<div class="container container-width justify-content-center">';

			if ( ! empty( get_the_content() ) ) {
				echo '<div class="navigation-content-description-title">';
				echo esc_html__( 'この講座について', 'imaoikiruhitolms' );
				echo '</div>';
				echo '<div class="navigation-content-description-text">';
				the_content();
				echo '</div>';
			}
			echo '</div>';

			echo '<div class="container container-width iihlms-container-p0">';
			echo '<div class="row iihlms-row-m0">';
			if ( '' === apply_filters( 'iihlms_lc_ctf_1', '' ) ) {
				echo '<h2 class="title-text-sub">' . esc_html__( 'この講座のコース', 'imaoikiruhitolms' ) . '</h2>';
			}
			echo '</div>';
			echo '</div>';

			echo '<div class="container container-width">';
			echo '<div class="row iihlms-row-m0">';
			// 関連しているコース.
			$iihlms_item_relation = get_post_meta( $post->ID, 'iihlms_item_relation', true );
			$iihlms_item_relation = isset( $iihlms_item_relation ) ? (array) $iihlms_item_relation : array();

			// 関連しているコース一覧.
			if ( '' !== $iihlms_item_relation[0] ) {

				// コースの進捗を取得.
				$item_course_progress_number_data = $this->get_course_progress_number( $iihlms_item_relation );

				$args     = array(
					'post_type'           => 'iihlms_courses',
					'posts_per_page'      => -1,
					'post_status'         => 'publish',
					'post__in'            => $iihlms_item_relation,
					'ignore_sticky_posts' => 1,
					'orderby'             => 'post__in',
				);
				$my_posts = get_posts( $args );

				echo '<div class="card-group iihlms-card-group">';
				foreach ( $my_posts as $postdata ) {
					setup_postdata( $postdata );

					echo '<div class="mb-3 mb-sm-4 me-0 me-sm-4">';
					echo '<div class="card iihlmscardsize">';
					if ( has_post_thumbnail( $postdata->ID ) ) {
						echo '<a href="' . esc_url( add_query_arg( 'iihlmsmypageitem', $this_item_id, get_permalink( $postdata->ID ) ) ) . '">';
						echo '<img src="' . esc_url( get_the_post_thumbnail_url( $postdata->ID ) ) . '" class="card-img-top card-img-top-size" alt="...">';
						echo '</a>';
					} else {
						echo '<a href="' . esc_url( add_query_arg( 'iihlmsmypageitem', $this_item_id, get_permalink( $postdata->ID ) ) ) . '">';
						echo '<img src="' . esc_url( get_template_directory_uri() . '/images/default_thumbnail.png' ) . '" class="card-img-top card-img-top-size" alt="...">';
						echo '</a>';
					}
					echo '<div class="card-body">';
					echo '<h5 class="card-title card-title-overflow">' . esc_html( get_the_title( $postdata->ID ) ) . '</h5>';

					echo '<div class="card-lesson-text">';
					echo '<i class="bi bi-play-circle"></i> ';
					echo esc_html( $this->get_course_lesson_relation_number( $postdata->ID ) );
					echo esc_html__( 'レッスン', 'imaoikiruhitolms' );
					echo '</div>';

					if ( array_key_exists( $postdata->ID, $item_course_progress_number_data ) ) {
						echo '<div class="progress progress-card"><div class="progress-bar progress-bar-card" role="progressbar" style="width: ' . esc_attr( $item_course_progress_number_data[ $postdata->ID ] ) . '%" aria-valuenow="' . esc_attr( $item_course_progress_number_data[ $postdata->ID ] ) . '" aria-valuemin="0" aria-valuemax="100"></div></div>';
						if ( 100 === $item_course_progress_number_data[ $postdata->ID ] ) {
							echo '<p class="card-text progress-card-text"><i class="bi bi-check-square-fill bi-progress-card-text"></i>' . esc_html__( '完了', 'imaoikiruhitolms' ) . '</p>';
						} else {
							echo '<p class="card-text progress-card-text">' . esc_html( $item_course_progress_number_data[ $postdata->ID ] ) . '%</p>';
						}
					} else {
						echo '<div class="progress progress-card"><div class="progress-bar progress-bar-card" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>';
						echo '<p class="card-text progress-card-text">0%</p>';
					}

					echo '</div>';
					echo '</div>';
					echo '</div>';

				}
				wp_reset_postdata();
				echo '</div>';
				echo '</div>';
				echo '</div>';

				$disp_test_flg = false;
				if ( $this->tests_associated_with_item_exists_check( $this_item_id ) ) {
					$iihlms_item_test_conditions_for_displaying = get_post_meta( $this_item_id, 'iihlms_item_test_conditions_for_displaying', true );
					if ( 'nocondition' === $iihlms_item_test_conditions_for_displaying ) {
						$disp_test_flg = true;
					} elseif ( 'aftercompleting' === $iihlms_item_test_conditions_for_displaying ) {
						// 指定した講座内の進捗が100か.
						$this_item_id_array       = (array) $this_item_id;
						$get_item_progress_number = $this->get_item_progress_number( $this_item_id_array );
						if ( 100 === $get_item_progress_number[ $this_item_id ] ) {
							$disp_test_flg = true;
						}
					} else {
						$disp_test_flg = false;
					}

					if ( true === $disp_test_flg ) {
						echo '<div class="container item-container-width iihlms-container-p0">';
						echo '<div class="row iihlms-row-m0">';
						$iihlms_item_test_relationship = get_post_meta( $this_item_id, 'iihlms_item_test_relationship', true );
						$iihlms_item_test_relationship = isset( $iihlms_item_test_relationship ) ? (array) $iihlms_item_test_relationship : array();

						// 関連しているテスト.
						if ( '' !== $iihlms_item_test_relationship[0] ) {
							echo '<div class="mt-5 p-0">';
							echo '<div class="list-group list-group-test">';
							$args     = array(
								'post_type'           => 'iihlms_tests',
								'posts_per_page'      => -1,
								'post_status'         => 'publish',
								'post__in'            => $iihlms_item_test_relationship,
								'ignore_sticky_posts' => 1,
								'orderby'             => 'post__in',
							);
							$my_posts = get_posts( $args );

							$query_prm_arg = array(
								'iihlmsmypageitem'   => $this_item_id,
							);

							$counter = 0;
							foreach ( $my_posts as $postdata ) {
								setup_postdata( $postdata );
								if ( 0 === $counter ) {
									echo '<button type="button" class="list-group-item list-group-item-action disabled list-group-item-test-title">';
									echo esc_html__( '講座の学習成果をチェック', 'imaoikiruhitolms' );
									echo '</button>';
								}
								echo '<form name="test_form" action="' . esc_url( get_permalink( $postdata->ID ) ) . '" method="post" class="iihlms-form">';
								echo '<table class="table align-middle test-table">';
								echo '<tr>';
								echo '<td class="test-table-title-td">';
								echo '<div class="test-table-title">';
								echo esc_html( get_the_title( $postdata->ID ) );
								echo '</div>';
								echo '</td>';
								echo '<td class="test-table-btn-td" rowspan="2">';
								wp_nonce_field( 'iihlms-start-the-test-csrf-action', 'iihlms-start-the-test-csrf' );
								echo '<input type="hidden" name="iihlms-test-start" value="start">';
								echo '<input type="hidden" name="iihlms-mypageitem-id" value="' . esc_attr( $this_item_id ) . '">';
								echo '<input type="hidden" name="iihlms-mypagecourse-id" value="">';
								echo '<input type="hidden" name="iihlms-mypagelesson-id" value="">';
								echo '<input type="hidden" name="iihlms-this-test-id" value="' . esc_attr( $postdata->ID ) . '">';
								echo '<div class="text-center">';
								echo '<button class="btn btn-test-table" type="submit">' . esc_html__( 'テストを受ける', 'imaoikiruhitolmsaddition' ) . '</button>';
								echo '</div>';
								echo '</td>';
								echo '</tr>';
								echo '<tr>';
								echo '<td class="test-table-settings-td">';
								echo '<div class="test-table-settings">';
								echo '<span class="test-table-settings-number-of-questions">';
								echo esc_html__( '問題数：', 'imaoikiruhitolms' );
								echo esc_html( $this->get_test_number_of_questions( $postdata->ID ) );
								echo esc_html__( '問', 'imaoikiruhitolms' );
								echo '</span>';
								echo '<span class="test-table-settings-time-limit">';
								echo esc_html__( '制限時間：', 'imaoikiruhitolms' );
								echo esc_html( $this->get_test_time_limit_for_disp( $postdata->ID ) );
								echo '</span>';
								echo '</div>';
								echo '</td>';
								echo '</tr>';
								echo '<tr>';
								echo '<td class="test-table-btn-td-m">';
								echo '<button class="btn btn-test-table" type="submit">' . esc_html__( 'テストを受ける', 'imaoikiruhitolmsaddition' ) . '</button>';
								echo '</td>';
								echo '</tr>';
								echo '</table>';
								echo '</form>';
								$counter++;
							}
							wp_reset_postdata();
							echo '</div>';
							echo '</div>';
						}
						echo '</div>';
						echo '</div>';
					}
				}
			}
			echo '</div>';
			echo '</div>';

		} else { // 未購入の講座、有効期限切れの講座.
			echo '<div class="navigation-black">';
			echo '<div class="container container-width justify-content-center p-0">';
			echo '<div class="row iihlms-row-m0">';

			echo '<table class="table navigation-black-table">';
			echo '<tbody>';
			echo '<tr>';
			echo '<td class="navigation-black-table-left">';

			echo '<div class="navigation-black-left">';
			echo '<div class="navigation-black-title">';
			echo esc_html( get_the_title() );
			echo '</div>';

			echo '<div class="navigation-black-course-text">';
			if ( '' === apply_filters( 'iihlms_lc_ctf_1', '' ) ) {
				echo '<i class="bi bi-book"></i> ';
				echo esc_html( $this->get_item_course_relation_number( $post->ID ) );
				echo esc_html__( 'コース', 'imaoikiruhitolms' );
			}
			echo '</div>';
			echo '<div class="navigation-black-price-text">' . esc_html( $this->get_price_for_disp_by_id( $post->ID ) ) . '</div>';

			if ( ! empty( get_the_content() ) ) {
				echo '<div class="navigation-black-description-title">';
				$nav_title_text = esc_html__( 'この講座について', 'imaoikiruhitolms' );
				if ( '' !== apply_filters( 'iihlms_lc_ctf_3', '' ) ) {
					$nav_title_text = apply_filters( 'iihlms_lc_ctf_3', '' );
				}
				echo esc_html( $nav_title_text );
				echo '</div>';
				echo '<div class="navigation-black-description-text">';
				the_content();
				echo '</div>';
			}
			?>

			<div class="navigation-black-cart">
			<form method="post" action="<?php echo esc_url( home_url( '/' . IIHLMS_APPLYPAGE_NAME . '/' ) ); ?>">
			<section>
			<?php wp_nonce_field( 'cart-csrf', 'cartcsrftoken' ); ?>
			<input type="hidden" name="iihlms-apply-item-id" value="<?php echo get_the_ID(); ?>">
			<button id="addProductButton" class="btn btn-cart">
			<?php
			$btn_text = esc_html__( '受講申込みへ進む', 'imaoikiruhitolms' );
			if ( '' !== apply_filters( 'iihlms_lc_ctf_4', '' ) ) {
				$btn_text = apply_filters( 'iihlms_lc_ctf_4', '' );
			}
			echo esc_html( $btn_text );
			?>
			</button>
			</section>
			</form>
			</div>

			<div class="fixed-bottom bottom-cart">
			<div class="d-flex justify-content-center p-3">
			<?php
			echo '<div class="bottom-cart-price">' . esc_html( $this->get_price_for_disp_by_id( $post->ID ) ) . '</div>';
			?>
			<div class="bottom-cart-form">
			<form method="post" action="<?php echo esc_url( home_url( '/' . IIHLMS_APPLYPAGE_NAME . '/' ) ); ?>">
			<section>
			<?php wp_nonce_field( 'cart-csrf', 'cartcsrftoken' ); ?>
			<input type="hidden" name="iihlms-apply-item-id" value="<?php echo get_the_ID(); ?>">

			<button id="addProductButtonBottom" class="btn btn-cart-bottom">
			<?php
			$btn_text = esc_html__( '受講申込みへ進む', 'imaoikiruhitolms' );
			if ( '' !== apply_filters( 'iihlms_lc_ctf_4', '' ) ) {
				$btn_text = apply_filters( 'iihlms_lc_ctf_4', '' );
			}
			echo esc_html( $btn_text );
			?>
			</button>
			</section>
			</form>
			</div>
			</div>
			</div>
			<style>
			@media (max-width: 576px) {
				body { padding-bottom: 70px; }
			}
			</style>

			<?php
			echo '</div>';
			echo '</td>';

			echo '<td class="navigation-black-table-right">';
			echo '<div class="navigation-black-right">';
			if ( has_post_thumbnail() ) {
				echo '<img src="' . esc_url( get_the_post_thumbnail_url( $post->ID, 'large' ) ) . '" class="navigation-black-img" alt="...">';
			} else {
				echo '<img src="' . esc_url( get_template_directory_uri() . '/images/default_thumbnail.png' ) . '" class="navigation-black-img" alt="...">';
			}
			echo '</div>';
			echo '</td>';
			echo '</tr>';
			echo '</tbody>';
			echo '</table>';
			echo '</div>';
			echo '</div>';
			echo '</div>';

			echo '<div class="container container-width justify-content-center">';

			if ( ! empty( get_the_content() ) ) {
				echo '<div class="navigation-content-description-title">';
				$nav_title_text = esc_html__( 'この講座について', 'imaoikiruhitolms' );
				if ( '' !== apply_filters( 'iihlms_lc_ctf_3', '' ) ) {
					$nav_title_text = apply_filters( 'iihlms_lc_ctf_3', '' );
				}
				echo esc_html( $nav_title_text );
				echo '</div>';
				echo '<div class="navigation-content-description-text">';
				the_content();
				echo '</div>';
			}

			?>

			<div class="navigation-content-cart">
			<form method="post" action="<?php echo esc_url( home_url( '/' . IIHLMS_APPLYPAGE_NAME . '/' ) ); ?>">
			<section>
			<?php wp_nonce_field( 'cart-csrf', 'cartcsrftoken' ); ?>
			<input type="hidden" name="iihlms-apply-item-id" value="<?php echo get_the_ID(); ?>">
			<button id="addProductButtonContent" class="btn btn-cart">
			<?php
			$btn_text = esc_html__( '受講申込みへ進む', 'imaoikiruhitolms' );
			if ( '' !== apply_filters( 'iihlms_lc_ctf_4', '' ) ) {
				$btn_text = apply_filters( 'iihlms_lc_ctf_4', '' );
			}
			echo esc_html( $btn_text );
			?>
			</button>
			</section>
			</form>
			</div>
			</div>

			<?php
			echo '<div class="container container-width iihlms-container-p0">';
			echo '<div class="row iihlms-row-m0">';
			if ( '' === apply_filters( 'iihlms_lc_ctf_1', '' ) ) {
				echo '<h2 class="title-text-sub">' . esc_html__( 'この講座のコース', 'imaoikiruhitolms' ) . '</h2>';
			}
			echo '</div>';
			echo '</div>';

			// 関連しているコース.
			$iihlms_item_relation = get_post_meta( $post->ID, 'iihlms_item_relation', true );
			$iihlms_item_relation = isset( $iihlms_item_relation ) ? (array) $iihlms_item_relation : array();

			// 関連しているコース一覧.
			if ( '' !== $iihlms_item_relation[0] ) {
				echo '<div class="container container-width">';
				echo '<div class="row iihlms-row-m0">';
				$args     = array(
					'post_type'           => 'iihlms_courses',
					'posts_per_page'      => -1,
					'post_status'         => 'publish',
					'post__in'            => $iihlms_item_relation,
					'ignore_sticky_posts' => 1,
					'orderby'             => 'post__in',
				);
				$my_posts = get_posts( $args );

				echo '<div class="card-group iihlms-card-group">';
				foreach ( $my_posts as $postdata ) {
					setup_postdata( $postdata );

					echo '<div class="mb-3 mb-sm-4 me-0 me-sm-4">';
					echo '<div class="card iihlmscardsize">';
					if ( has_post_thumbnail( $postdata->ID ) ) {
						echo '<img src="' . esc_url( get_the_post_thumbnail_url( $postdata->ID ) ) . '" class="card-img-top-nolink card-img-top-size" alt="...">';
					} else {
						echo '<img src="' . esc_url( get_template_directory_uri( $postdata->ID ) . '/images/default_thumbnail.png' ) . '" class="card-img-top-nolink card-img-top-size" alt="...">';
					}
					echo '<div class="card-body">';
					echo '<h5 class="card-title card-title-overflow">' . esc_html( get_the_title( $postdata->ID ) ) . '</h5>';

					echo '<div class="card-lesson-text">';
					echo '<i class="bi bi-play-circle"></i> ';
					echo esc_html( $this->get_course_lesson_relation_number( $postdata->ID ) );
					echo esc_html__( 'レッスン', 'imaoikiruhitolms' );
					echo '</div>';

					echo '</div>';
					echo '</div>';
					echo '</div>';

				}
				wp_reset_postdata();
				echo '</div>';

				echo '</div>';
				echo '</div>';
			}
		}               //$purchase_flg

		echo '<div class="iihlms-spacer-white"></div>';
		echo '<div class="footer-home-btn-wrap">';
		echo '<div class="text-center"><button type="button" class="btn btn-mypage-content" onclick="location.href=\'' . esc_url( get_home_url() ) . '/\'"><div class="btn-mypage-text"><i class="bi bi-house btn-mypage-icon"></i> HOME</div></button></div>';
		echo '</div>';
	}
	/**
	 * 講座ページエラー表示
	 *
	 * @param string $err_msg エラーメッセージ.
	 * @return void
	 */
	public function show_err_iihlms_items_page_content( $err_msg ) {
		echo '<div class="container container-width justify-content-center">';
		echo '<div class="row">';
		echo '<p class="mt-3">';
		echo esc_html( $err_msg );
		echo '</p>';
		echo '<p><button class="btn btn-primary" onclick="history.back(-1)">' . esc_html__( '戻る', 'imaoikiruhitolms' ) . '</button></p>';
		echo '</div>';
		echo '</div>';
		get_template_part( 'footer' );
	}
	/**
	 * 会員ステータスによる講座表示権限チェック
	 *
	 * @param string $item_id 講座ID.
	 * @return bool
	 */
	public function check_permission_item_membership( $item_id ) {

		$iihlms_item_membership = get_post_meta( $item_id, 'iihlms_item_membership', true );
		// 未設定.
		if ( ! is_array( $iihlms_item_membership ) ) {
			return true;
		}
		if ( ! is_user_logged_in() ) {
			return false;
		}
		if ( current_user_can( self::CAPABILITY_ADMIN ) ) {
			return true;
		}

		$user                          = wp_get_current_user();
		$iihlms_user_membership_status = get_user_meta( $user->ID, 'iihlms_user_membership_status', true );
		if ( ! is_array( $iihlms_user_membership_status ) ) {
			return false;
		}
		$matching_array = array_intersect( $iihlms_item_membership, $iihlms_user_membership_status );

		if ( ! empty( $matching_array ) ) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * 「指定したコースを受講完了後、この講座を閲覧・購入できるよう限定する」が設定されている場合、指定したコースをすべて受講完了しているかチェック
	 *
	 * @param string $item_id 講座ID.
	 * @return bool
	 */
	public function check_item_course_complete_precondition( $item_id ) {

		$iihlms_item_course_complete_precondition = get_post_meta( $item_id, 'iihlms_item_course_complete_precondition', true );

		// 未設定.
		if ( '' === $iihlms_item_course_complete_precondition ) {
			return true;
		}

		// 指定したコースを全て受講完了しているか.
		$iihlms_item_course_complete_precondition_data = $this->get_course_progress_number( $iihlms_item_course_complete_precondition );

		foreach ( $iihlms_item_course_complete_precondition_data as $key => $value ) {
			if ( '100' !== $value ) {
				return false;
			}
		}

		return true;
	}
	/**
	 * 「指定したテストに合格した後、この講座を閲覧・購入できるよう限定する」が設定されている場合、指定したテストにすべて合格しているかチェック
	 *
	 * @param string $item_id 講座ID.
	 * @return bool
	 */
	public function check_item_test_pass_precondition( $item_id ) {
		$user = wp_get_current_user();

		$iihlms_item_test_pass_precondition = get_post_meta( $item_id, 'iihlms_item_test_pass_precondition', true );

		// 未設定.
		if ( '' === $iihlms_item_test_pass_precondition ) {
			return true;
		}

		// 指定したテストに全て合格しているか.
		foreach ( $iihlms_item_test_pass_precondition as $test_id ) {
			$check_whether_the_specified_test_is_passed = $this->check_whether_the_specified_test_is_passed( $test_id, $user->ID );
			if ( false === $check_whether_the_specified_test_is_passed ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * コースページ
	 *
	 * @return void
	 */
	public function iihlms_courses_page_content() {
		global $wpdb;
		global $post;
		$user = wp_get_current_user();

		// 経由した講座ページのID.
		$iihlmsmypageitem = get_query_var( 'iihlmsmypageitem' );
		$this_course_id   = $post->ID;

		if ( ! current_user_can( self::CAPABILITY_ADMIN ) ) {
			// このコースはログイン不要で誰でもアクセス可能にする.
			$iihlms_course_permission = get_post_meta( $post->ID, 'iihlms_course_permission', true );
			if ( ( 'no' === $iihlms_course_permission ) || ( '' === $iihlms_course_permission ) ) {
				// 指定した講座IDは購入済か.
				if ( ! $this->check_item_purchased( $iihlmsmypageitem ) ) {
					$iihlmsmypageitem = '';
				}

				// 指定した講座IDは有効期限内か.
				if ( ! $this->check_item_within_expiration_date( $iihlmsmypageitem ) ) {
					$this->show_err_iihlms_courses_page_content( esc_html__( '有効期限を過ぎています。', 'imaoikiruhitolms' ) );
					exit;
				}

				$purchase_flg = true;

				// このコースを表示する権限チェック.
				$purchase_flg = $this->check_course_purchased( $this_course_id );

				if ( false === $purchase_flg ) {
					$this->show_err_iihlms_courses_page_content( esc_html__( '表示する権限がありません。', 'imaoikiruhitolms' ) );
					exit;
				}
			}

			// テストに合格するまで次のコースに進めないようにする.
			$check_course_test_cant_proceed_until_pass = $this->check_course_test_cant_proceed_until_pass( $iihlmsmypageitem, $this_course_id, $user->ID );
			if ( 'ng' === $check_course_test_cant_proceed_until_pass ) {
				$this->show_err_iihlms_courses_page_content( esc_html__( 'テストに合格するまで表示できません。', 'imaoikiruhitolms' ) );
				exit;
			}
		}

		$show_home_link = false;
		if ( ! $this->check_item_purchased( $iihlmsmypageitem ) ) {
			$show_home_link = true;
		}

		if ( false === $show_home_link ) {
			$item_data = $this->get_item_data( $iihlmsmypageitem );

			echo '<div class="navigation-black-narrow">';
			echo '<div class="container container-width justify-content-center p-0">';
			echo '<span class="navigation-black-narrow-title">';
			echo '<a href="' . esc_url( get_permalink( $iihlmsmypageitem ) ) . '">';
			echo '<i class="bi bi-chevron-left navigation-black-narrow-icon"></i>';
			echo esc_html( $item_data['title'] );
			echo '</a>';
			echo '</span>';
			echo '</div>';
			echo '</div>';
		} else {
			echo '<div class="navigation-black-narrow">';
			echo '<div class="container container-width justify-content-center p-0">';
			echo '<span class="navigation-black-narrow-title">';
			echo '<a href="' . esc_url( get_home_url() ) . '">';
			echo '<i class="bi bi-chevron-left navigation-black-narrow-icon"></i>';
			echo esc_html__( 'HOME', 'imaoikiruhitolms' );
			echo '</a>';
			echo '</span>';
			echo '</div>';
			echo '</div>';
		}
		echo '<div class="navigation-white">';
		echo '<div class="container container-width iihlms-container-p0">';
		echo '<div class="row iihlms-row-m0">';

		echo '<table class="table navigation-white-table">';
		echo '<tbody>';
		echo '<tr>';
		echo '<td class="navigation-white-table-left">';

		echo '<div class="navigation-white-left">';
		echo '<div class="navigation-white-title">';
		echo esc_html( get_the_title() );
		echo '</div>';

		echo '<div class="navigation-white-lesson-text">';
		echo '<i class="bi bi-play-circle navigation-white-lesson-icon"></i> ';
		echo esc_html( $this->get_course_lesson_relation_number( $post->ID ) );
		echo 'レッスン';
		echo '</div>';
		echo '<div class="progress navigation-progress-white"><div class="progress-bar progress-bar-card" role="progressbar" style="width: ' . esc_attr( $this->get_course_lesson_progress_number( $post->ID ) ) . '%" aria-valuenow="' . esc_attr( $this->get_course_lesson_progress_number( $post->ID ) ) . '" aria-valuemin="0" aria-valuemax="100"></div></div>';
		echo '<p class="navigation-white-progress-text">' . esc_html( $this->get_course_lesson_progress_number( $post->ID ) ) . esc_html__( '%完了', 'imaoikiruhitolms' ) . '</p>';
		if ( ! empty( get_the_content() ) ) {
			echo '<div class="navigation-white-description-title">';
			echo esc_html__( 'このコースについて', 'imaoikiruhitolms' );
			echo '</div>';
			echo '<div class="navigation-white-description-text">';
			the_content();
			echo '</div>';
		}
		echo '</div>';
		echo '</td>';

		echo '<td class="navigation-white-table-right">';
		echo '<div class="navigation-white-right">';
		if ( has_post_thumbnail() ) {
			echo '<img src="' . esc_url( get_the_post_thumbnail_url( $post->ID, 'large' ) ) . '" class="navigation-white-img" alt="...">';
		} else {
			echo '<img src="' . esc_url( get_template_directory_uri() . '/images/default_thumbnail.png' ) . '" class="navigation-white-img" alt="...">';
		}
		echo '</div>';
		echo '</td>';
		echo '</tr>';
		echo '</tbody>';
		echo '</table>';
		echo '</div>';
		echo '</div>';
		echo '</div>';

		echo '<div class="container course-container-width">';
		echo '<div class="row">';

		if ( ! empty( get_the_content() ) ) {
			echo '<div class="navigation-content-description-title">';
			echo esc_html__( 'このコースについて', 'imaoikiruhitolms' );
			echo '</div>';
			echo '<div class="navigation-content-description-text">';
			the_content();
			echo '</div>';
		}
		echo '</div>';
		echo '</div>';

		$course_lesson_related = get_post_meta( $post->ID, 'iihlms_course_relation', true );
		$course_lesson_related = isset( $course_lesson_related ) ? (array) $course_lesson_related : array();

		$iihlms_course_display_list_of_lessons_prohibited = get_post_meta( $post->ID, 'iihlms_course_display_list_of_lessons_prohibited', true );

		if ( false === post_password_required( $post ) ) {
			if ( '' !== $course_lesson_related[0] ) {
				echo '<div class="container course-container-width iihlms-container-p0">';
				echo '<div class="row iihlms-row-m0">';
				echo '<div class="list-group list-group-course">';

				// 教材.
				if ( '' !== get_post_meta( $post->ID, 'iihlms_course_materials', true ) ) {
					echo '<button type="button" class="list-group-item list-group-item-action disabled list-group-item-course-title">';
					echo esc_html__( 'コースの教材', 'imaoikiruhitolms' );
					echo '</button>';
					echo '<div class="course-materials">';
					$iihlms_course_materials = wp_kses_post( nl2br( get_post_meta( $post->ID, 'iihlms_course_materials', true ) ) );
					echo do_shortcode( $iihlms_course_materials );
					echo '</div>';
				}

				// 添付資料.
				$pdfs = get_post_meta( $post->ID, 'iihlms_course_pdfs', true );
				if ( ! empty( $pdfs ) ) {
					echo '<button type="button" class="list-group-item list-group-item-action disabled list-group-item-course-title">';
					echo esc_html__( '資料', 'imaoikiruhitolms' );
					echo '</button>';
					echo '<div class="course-pdfs">';
					foreach ( $pdfs as $pdf ) {
						echo '<div class="course-pdf">';
						echo '<a href="' . esc_url( $pdf['url'] ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $pdf['name'] ) . '</a>';
						echo '</div>';
					}
					echo '</div>';
				}

				// 関連済レッスン一覧.
				$args     = array(
					'post_type'           => 'iihlms_lessons',
					'posts_per_page'      => -1,
					'post_status'         => 'publish',
					'post__in'            => $course_lesson_related,
					'ignore_sticky_posts' => 1,
					'orderby'             => 'post__in',
				);
				$my_posts = get_posts( $args );
				$counter  = 0;
				foreach ( $my_posts as $postdata ) {
					setup_postdata( $postdata );
					if ( 0 === $counter ) {
						echo '<button type="button" class="list-group-item list-group-item-action disabled list-group-item-course-title">';
						echo esc_html__( 'コース内容', 'imaoikiruhitolms' );
						echo '</button>';
					}
					if ( false === $show_home_link ) {
						echo '<a href="';
						echo esc_url(
							add_query_arg(
								array(
									'iihlmsmypagecourse' => $this_course_id,
									'iihlmsmypageitem'   => $iihlmsmypageitem,
								),
								get_permalink( $postdata->ID )
							)
						);
					} else {
						echo '<a href="';
						echo esc_url(
							add_query_arg(
								array(
									'iihlmsmypagecourse' => $this_course_id,
								),
								get_permalink( $postdata->ID )
							)
						);
					}
					echo '" class="list-group-item list-group-item-action list-group-item-course';
					if ( 'yes' === $iihlms_course_display_list_of_lessons_prohibited ) {
						$check_lesson_test_cant_proceed_until_pass = $this->check_lesson_test_cant_proceed_until_pass( $this_course_id, $postdata->ID, $user->ID );
						if ( 'ng' === $check_lesson_test_cant_proceed_until_pass ) {
							echo ' d-none';
						}
					}
					echo '">';

					if ( $this->check_user_activity( $postdata->ID, $user->ID ) ) {
						echo '<i class="bi bi-check-square-fill list-group-icon"></i> ';
					}
					echo '<div class="list-group-item-title">';
					echo esc_html( get_the_title( $postdata->ID ) );
					echo '</div>';
					echo '<div class="list-group-icon-right"><i class="bi bi-chevron-right"></i></div>';

					echo '</a>';
					$counter++;
				}
				wp_reset_postdata();
				echo '</div>';
				echo '</div>';

				// テスト.
				$disp_test_flg = false;
				if ( $this->tests_associated_with_course_exists_check( $this_course_id ) ) {
					$iihlms_course_test_conditions_for_displaying = get_post_meta( $this_course_id, 'iihlms_course_test_conditions_for_displaying', true );
					if ( 'nocondition' === $iihlms_course_test_conditions_for_displaying ) {
						$disp_test_flg = true;
					} elseif ( 'aftercompleting' === $iihlms_course_test_conditions_for_displaying ) {
						// 指定したコース内の進捗が100か.
						$this_course_id_array       = (array) $this_course_id;
						$get_course_progress_number = $this->get_course_progress_number( $this_course_id_array );
						if ( 100 === $get_course_progress_number[ $this_course_id ] ) {
							$disp_test_flg = true;
						}
					} elseif ( 'dayslater' === $iihlms_course_test_conditions_for_displaying ) {
						// コース開始日から指定した日数が過ぎているか.
						$iihlms_course_test_conditions_number_of_days = get_post_meta( $this_course_id, 'iihlms_course_test_conditions_number_of_days', true );
						$course_start_date                            = $this->get_course_start_date( $this_course_id, $user->ID );

						$today                    = current_datetime();
						$format_course_start_date = new DateTimeImmutable( $course_start_date );

						$format_course_start_date = $format_course_start_date->add( new DateInterval( 'P' . $iihlms_course_test_conditions_number_of_days . 'D' ) );
						if ( $format_course_start_date->format( $this->specify_date_format ) <= $today->format( $this->specify_date_format ) ) {
							$disp_test_flg = true;
						}
					} else {
						$disp_test_flg = false;
					}
				}

				if ( true === $disp_test_flg ) {
					$iihlms_course_test_relationship = get_post_meta( $this_course_id, 'iihlms_course_test_relationship', true );
					$iihlms_course_test_relationship = isset( $iihlms_course_test_relationship ) ? (array) $iihlms_course_test_relationship : array();

					// 関連しているテスト.
					if ( '' !== $iihlms_course_test_relationship[0] ) {
						echo '<div class="row iihlms-row-m0 mt-5">';
						echo '<div class="list-group list-group-test">';
						$args     = array(
							'post_type'           => 'iihlms_tests',
							'posts_per_page'      => -1,
							'post_status'         => 'publish',
							'post__in'            => $iihlms_course_test_relationship,
							'ignore_sticky_posts' => 1,
							'orderby'             => 'post__in',
						);
						$my_posts = get_posts( $args );

						$query_prm_arg = array(
							'iihlmsmypagecourse' => $this_course_id,
							'iihlmsmypageitem'   => $iihlmsmypageitem,
						);

						$counter = 0;
						foreach ( $my_posts as $postdata ) {
							setup_postdata( $postdata );
							if ( 0 === $counter ) {
								echo '<button type="button" class="list-group-item list-group-item-action disabled list-group-item-test-title">';
								echo esc_html__( 'コースの学習成果をチェック', 'imaoikiruhitolms' );
								echo '</button>';
							}
							echo '<form name="test_form" action="' . esc_url( get_permalink( $postdata->ID ) ) . '" method="post" class="iihlms-form">';
							echo '<table class="table align-middle">';
							echo '<tr>';
							echo '<td class="test-table-title-td">';
							echo '<div class="test-table-title">';
							echo esc_html( get_the_title( $postdata->ID ) );
							echo '</div>';
							echo '</td>';
							echo '<td class="test-table-btn-td" rowspan="2">';
							wp_nonce_field( 'iihlms-start-the-test-csrf-action', 'iihlms-start-the-test-csrf' );
							echo '<input type="hidden" name="iihlms-test-start" value="start">';
							echo '<input type="hidden" name="iihlms-mypageitem-id" value="' . esc_attr( $iihlmsmypageitem ) . '">';
							echo '<input type="hidden" name="iihlms-mypagecourse-id" value="' . esc_attr( $this_course_id ) . '">';
							echo '<input type="hidden" name="iihlms-mypagelesson-id" value="">';
							echo '<input type="hidden" name="iihlms-this-test-id" value="' . esc_attr( $postdata->ID ) . '">';
							echo '<div class="text-center">';
							echo '<button class="btn btn-test-table" type="submit">' . esc_html__( 'テストを受ける', 'imaoikiruhitolmsaddition' ) . '</button>';
							echo '</div>';
							echo '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td class="test-table-settings-td">';
							echo '<div class="test-table-settings">';
							echo '<span class="test-table-settings-number-of-questions">';
							echo esc_html__( '問題数：', 'imaoikiruhitolms' );
							echo esc_html( $this->get_test_number_of_questions( $postdata->ID ) );
							echo esc_html__( '問', 'imaoikiruhitolms' );
							echo '</span>';
							echo '<span class="test-table-settings-time-limit">';
							echo esc_html__( '制限時間：', 'imaoikiruhitolms' );
							echo esc_html( $this->get_test_time_limit_for_disp( $postdata->ID ) );
							echo '</span>';
							echo '</div>';
							echo '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td class="test-table-btn-td-m">';
							echo '<button class="btn btn-test-table" type="submit">' . esc_html__( 'テストを受ける', 'imaoikiruhitolmsaddition' ) . '</button>';
							echo '</td>';
							echo '</tr>';
							echo '</table>';
							echo '</form>';
							$counter++;
						}
						wp_reset_postdata();
						echo '</div>';
						echo '</div>';
					}
				}

				// 補足資料等.
				if ( '' !== get_post_meta( $post->ID, 'iihlms_course_explanation', true ) ) {
					echo '<div class="iihlms-course-explanation mt-4">';
					echo wp_kses_post( nl2br( get_post_meta( $post->ID, 'iihlms_course_explanation', true ) ) );
					echo '</div>';
				}

				echo '</div>';
			}
		}

		echo '<div class="iihlms-spacer-white"></div>';

		$get_previous_course_id = $this->get_previous_course_id( $this_course_id, $iihlmsmypageitem );
		$get_next_course_id     = $this->get_next_course_id( $this_course_id, $iihlmsmypageitem );

		$query_prm_arg = array(
			'iihlmsmypagecourse' => $this_course_id,
			'iihlmsmypageitem'   => $iihlmsmypageitem,
		);

		echo '<div class="footer-course-previous-next-table-wrap">';
		echo '<table class="footer-course-previous-next-table">';
		echo '<tr>';
		if ( '' !== $get_previous_course_id ) {
			echo '<td class="footer-course-previous-next-table-td">';
		} else {
			echo '<td class="footer-course-previous-next-table-td footer-course-previous-next-table-td-nodata">';
		}

		if ( '' !== $get_previous_course_id ) {
			echo '<a class="footer-course-next-table-wrap" href="' . esc_url( add_query_arg( $query_prm_arg, get_permalink( $get_previous_course_id ) ) ) . '">';
			echo '<table class="footer-course-previous-table">';
			echo '<tr>';
			echo '<td class="footer-course-previous-icon-td">';
			echo '<i class="bi bi-chevron-left"></i>';
			echo '</td>';
			echo '<td class="footer-course-previous-text-td">';
			echo '<div class="footer-course-previous-text-sub">';
			echo esc_html__( '前のコース', 'imaoikiruhitolms' );
			echo '</div>';
			echo '<div class="footer-course-previous-text-title">';
			echo esc_html( get_the_title( $get_previous_course_id ) );
			echo '</div>';
			echo '</td>';
			echo '<td class="footer-course-previous-img-td">';
			if ( has_post_thumbnail( $get_previous_course_id ) ) {
				echo '<img src="' . esc_url( get_the_post_thumbnail_url( $get_previous_course_id, 'large' ) ) . '" class="footer-course-previous-img" alt="...">';
			} else {
				echo '<img src="' . esc_url( get_template_directory_uri() . '/images/default_thumbnail.png' ) . '" class="footer-course-previous-img" alt="...">';
			}
			echo '</td>';
			echo '</tr>';
			echo '</table>';
			echo '</a>';
		} else {
			echo '<table class="footer-course-previous-table">';
			echo '<tr>';
			echo '<td class="footer-course-previous-none">';
			echo '</td>';
			echo '<td class="footer-course-previous-text-td footer-course-previous-none">';
			echo '</td>';
			echo '<td class="footer-course-previous-none">';
			echo '</td>';
			echo '</tr>';
			echo '</table>';
		}

		echo '</td>';
		if ( '' !== $get_next_course_id ) {
			echo '<td class="footer-course-previous-next-table-td">';
		} else {
			echo '<td class="footer-course-previous-next-table-td footer-course-previous-next-table-td-nodata">';
		}
		if ( '' !== $get_next_course_id ) {
			echo '<a class="footer-course-next-table-wrap" href="' . esc_url( add_query_arg( $query_prm_arg, get_permalink( $get_next_course_id ) ) ) . '">';
			echo '<table class="footer-course-next-table">';
			echo '<tr>';
			echo '<td class="footer-course-next-img-td">';
			if ( has_post_thumbnail( $get_next_course_id ) ) {
				echo '<img src="' . esc_url( get_the_post_thumbnail_url( $get_next_course_id, 'large' ) ) . '" class="footer-course-next-img" alt="...">';
			} else {
				echo '<img src="' . esc_url( get_template_directory_uri() . '/images/default_thumbnail.png' ) . '" class="footer-course-next-img" alt="...">';
			}
			echo '</td>';
			echo '<td class="footer-course-next-text-td">';
			echo '<div class="footer-course-next-text-sub">';
			echo esc_html__( '次のコース', 'imaoikiruhitolms' );
			echo '</div>';
			echo '<div class="footer-course-next-text-title">';
			echo esc_html( get_the_title( $get_next_course_id ) );
			echo '</div>';
			echo '</td>';
			echo '<td class="footer-course-next-icon-td">';
			echo '<i class="bi bi-chevron-right"></i>';
			echo '</td>';
			echo '</tr>';
			echo '</table>';
			echo '</a>';
		} else {
			echo '<table class="footer-course-next-table">';
			echo '<tr>';
			echo '<td class="footer-course-next-none">';
			echo '</td>';
			echo '<td class="footer-course-next-text-td footer-course-next-none">';
			echo '</td>';
			echo '<td class="footer-course-next-none">';
			echo '</td>';
			echo '</tr>';
			echo '</table>';
		}

		echo '</td>';
		echo '</tr>';
		echo '</table>';
		echo '</div>';

		if ( ! empty( $iihlmsmypageitem ) ) {
			// 講座へのリンク.
			$args     = array(
				'post_type'      => 'iihlms_items',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'include'        => $iihlmsmypageitem,
			);
			$my_posts = get_posts( $args );

			foreach ( $my_posts as $postdata ) {
				setup_postdata( $postdata );
				echo '<div class="footer-to-top-item-wrap">';
				echo '<table class="footer-to-top-item-table">';
				echo '<tr>';
				echo '<td class="footer-to-top-item-img-td p-0">';
				echo '<a href="' . esc_url( get_permalink( $postdata->ID ) ) . '">';
				if ( has_post_thumbnail( $postdata->ID ) ) {
					echo '<img src="' . esc_url( get_the_post_thumbnail_url( $postdata->ID, 'large' ) ) . '" class="footer-to-top-item-img" alt="...">';
				} else {
					echo '<img src="' . esc_url( get_template_directory_uri() . '/images/default_thumbnail.png' ) . '" class="footer-to-top-item-img" alt="...">';
				}
				echo '</a>';
				echo '</td>';
				echo '<td class="footer-to-top-item-text-td p-0">';
				echo '<a href="' . esc_url( get_permalink( $postdata->ID ) ) . '">';
				echo esc_html__( 'この講座のTOPへ', 'imaoikiruhitolms' );
				echo '</a>';
				echo '</td>';
				echo '</tr>';
				echo '</table>';
				echo '</div>';
			}
			wp_reset_postdata();
		}

		echo '<div class="footer-home-btn-wrap">';
		echo '<div class="text-center"><button type="button" class="btn btn-mypage-content" onclick="location.href=\'' . esc_url( get_home_url() ) . '/\'"><div class="btn-mypage-text"><i class="bi bi-house btn-mypage-icon"></i> HOME</div></button></div>';
		echo '</div>';

	}
	/**
	 * コースページエラー表示
	 *
	 * @param string $err_msg エラーメッセージ.
	 * @return void
	 */
	public function show_err_iihlms_courses_page_content( $err_msg ) {
		echo '<div class="container container-width justify-content-center">';
		echo '<div class="row">';
		echo '<p class="mt-5">';
		echo esc_html( $err_msg );
		echo '</p>';
		echo '<p class="mb-5"><button class="btn btn-primary" onclick="history.back(-1)">' . esc_html__( '戻る', 'imaoikiruhitolms' ) . '</button></p>';
		echo '</div>';
		echo '</div>';
		get_template_part( 'footer' );
	}

	/**
	 * レッスンページ
	 *
	 * @return void
	 */
	public function iihlms_lessons_page_content() {
		global $wpdb;
		global $post;

		$this_lesson_id = $post->ID;

		// 経由したコースページのID.
		$iihlmsmypagecourse = get_query_var( 'iihlmsmypagecourse' );

		// 経由した講座ページのID.
		$iihlmsmypageitem = get_query_var( 'iihlmsmypageitem' );

		// このコースはログイン不要で誰でもアクセス可能にする、の時のレッスンか.
		$iihlms_course_permission = get_post_meta( $iihlmsmypagecourse, 'iihlms_course_permission', true );

		$user = wp_get_current_user();

		if ( ! current_user_can( self::CAPABILITY_ADMIN ) ) {
			if ( ( 'no' === $iihlms_course_permission ) || ( '' === $iihlms_course_permission ) ) {

				// 指定したコースIDは購入済か.
				if ( ! empty( $iihlmsmypagecourse ) ) {
					if ( ! $this->check_course_purchased( $iihlmsmypagecourse ) ) {
						$iihlmsmypagecourse = '';
					}
				}

				// 指定した講座IDは購入済か.
				if ( ! empty( $iihlmsmypageitem ) ) {
					if ( ! $this->check_item_purchased( $iihlmsmypageitem ) ) {
						$iihlmsmypageitem = '';
					}
				}

				// 指定した講座IDは有効期限内か.
				if ( ! $this->check_item_within_expiration_date( $iihlmsmypageitem ) ) {
					$this->show_err_iihlms_lessons_page_content( esc_html__( '有効期限を過ぎています。', 'imaoikiruhitolms' ) );
					exit;
				}
			}

			if ( ( 'no	' === $iihlms_course_permission ) || ( '' === $iihlms_course_permission ) ) {
				$purchase_flg = $this->lesson_purchased_check( $this_lesson_id );

				if ( false === $purchase_flg ) {
					$this->show_err_iihlms_lessons_page_content( esc_html__( '表示する権限がありません。', 'imaoikiruhitolms' ) );
					exit;
				}
			} else {
				// このレッスンは、指定した無料コースに含まれるか.
				$included_flg = $this->lesson_included_specified_course_check( $this_lesson_id, $iihlmsmypagecourse );
				if ( false === $included_flg ) {
					$this->show_err_iihlms_lessons_page_content( esc_html__( '表示する権限がありません。', 'imaoikiruhitolms' ) );
					exit;
				}
			}

			// テストに合格するまで次のレッスンに進めないようにする.
			$check_lesson_test_cant_proceed_until_pass = $this->check_lesson_test_cant_proceed_until_pass( $iihlmsmypagecourse, $this_lesson_id, $user->ID );
			if ( 'ng' === $check_lesson_test_cant_proceed_until_pass ) {
				$this->show_err_iihlms_lessons_page_content( esc_html__( 'テストに合格するまで表示できません。', 'imaoikiruhitolms' ) );
				exit;
			}
		}

		$course_data = $this->get_course_data( $iihlmsmypagecourse );
		if ( ! empty( $course_data ) ) {
			echo '<div class="navigation-black-narrow">';
			echo '<div class="container container-width justify-content-center p-0">';
			echo '<span class="navigation-black-narrow-title">';
			echo '<a href="' . esc_url( $this->get_course_link_lessonpage( $iihlmsmypagecourse, $iihlmsmypageitem ) ) . '">';
			echo '<i class="bi bi-chevron-left navigation-black-narrow-icon"></i>';
			echo esc_html( $course_data['title'] );
			echo '</a>';
			echo '</span>';
			echo '</div>';
			echo '</div>';
		} else {
			echo '<div class="navigation-black-narrow">';
			echo '<div class="container container-width justify-content-center p-0">';
			echo '<span class="navigation-black-narrow-title">';
			echo '<a href="' . esc_url( get_home_url() ) . '">';
			echo '<i class="bi bi-chevron-left navigation-black-narrow-icon"></i>';
			echo esc_html__( 'HOME', 'imaoikiruhitolms' );
			echo '</a>';
			echo '</span>';
			echo '</div>';
			echo '</div>';
		}

		echo '<div class="container container-width p-0">';
		echo '<div class="lesson-title">' . esc_html( get_the_title() ) . '</div>';
		echo '</div>';
		$video_url = get_post_meta( $this_lesson_id, 'iihlms_video_url', true );

		if ( ! empty( $video_url ) ) {
			$video_url = htmlspecialchars_decode( $video_url );

			if ( strpos( $video_url, 'dropbox.com' ) !== false ) {
				$video_url = preg_replace( '/(\?|&)dl=0/', '$1raw=1', $video_url );

				echo '<div class="lesson-movie-container">';
				echo '<div class="container container-width justify-content-center p-0">';
				echo '<div class="iihlms-movie-container-wrap">';
				echo '<div class="iihlms-movie-container">';
				echo '<video width="640" height="360" controls controlsList="nodownload" oncontextmenu="return false;">';
				echo '<source src="' . esc_url( $video_url ) . '" type="video/mp4">';
				echo 'Your browser does not support the video tag.';
				echo '</video>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
				echo '</div>';

			} elseif ( strpos( $video_url, 'youtube.com' ) !== false || strpos( $video_url, 'youtu.be' ) !== false ) {
				// YouTubeの埋め込みURLに変換.
				if ( preg_match( '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $matches ) ) {
					$youtube_id = $matches[1];
					$embed_url = 'https://www.youtube.com/embed/' . $youtube_id;

					echo '<div class="lesson-movie-container">';
					echo '<div class="container container-width justify-content-center p-0">';
					echo '<div class="iihlms-movie-container-wrap">';
					echo '<div class="iihlms-movie-container">';
					echo '<iframe width="640" height="360" src="' . esc_url( $embed_url ) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
				}
			}
		} elseif ( ! empty( $post->post_content ) ) {
			echo '<div class="lesson-movie-container">';
			echo '<div class="container container-width justify-content-center p-0">';
			echo '<div class="iihlms-movie-container-wrap">';
			echo '<div class="iihlms-movie-container">';
			the_content();
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}

		echo '<div class="container lesson-container-width p-0">';
		echo '<div class="row m-0">';

		$btn_lesson_complete_display        = '';
		$btn_lesson_complete_remove_display = '';
		if ( true === $this->check_user_activity( $post->ID, $user->ID ) ) {
			$btn_lesson_complete_display = 'btn-display-none';
		} else {
			$btn_lesson_complete_remove_display = 'btn-display-none';
		}
		echo '<div class="btn-lesson-complete-wrap">';
		if ( ! empty( $post->post_content ) ) {
			if ( is_user_logged_in() ) {
				echo '<button id="btn-lesson-complete" class="btn btn-lesson-complete-action ' . esc_attr( $btn_lesson_complete_display ) . '"><i class="bi bi-check-square-fill btn-lesson-complete-action-icon"></i> ' . esc_html__( '受講完了にする', 'imaoikiruhitolms' ) . '</button>';
				echo '<button id="btn-lesson-complete-remove" class="btn btn-lesson-complete-remove ' . esc_attr( $btn_lesson_complete_remove_display ) . '"><i class="bi bi-check-square-fill btn-lesson-complete-remove-icon"></i> ' . esc_html__( '受講済', 'imaoikiruhitolms' ) . '</button>';
			}
		}
		echo '</div>';

		echo '<div class="container lesson-container-width p-0">';
		echo '<div class="row m-0">';
		echo do_shortcode( '[iihlms_audio_files]' );
		echo '</div>';
		echo '</div>';

		if ( '' !== get_post_meta( $post->ID, 'iihlms_lesson_explanation', true ) ) {
			echo '<div class="mb-4 lesson-explanation">';
			$allowed_html              = wp_kses_allowed_html( 'post' );
			$allowed_html['audio']     = array_merge(
				$allowed_html['audio'],
				array(
					'controlslist'  => 1,
					'oncontextmenu' => 1,
				)
			);
			$iihlms_lesson_explanation = wp_kses( nl2br( get_post_meta( $post->ID, 'iihlms_lesson_explanation', true ) ), $allowed_html );
			echo do_shortcode( $iihlms_lesson_explanation );
			echo '</div>';
		}

		if ( '' !== get_post_meta( $post->ID, 'iihlms_lesson_materials', true ) ) {
			echo '<div class="mb-4 lesson-materials">';
			$iihlms_lesson_materials = wp_kses_post( nl2br( get_post_meta( $post->ID, 'iihlms_lesson_materials', true ) ) );
			echo do_shortcode( $iihlms_lesson_materials );
			echo '</div>';
		}

		$pdfs = get_post_meta( $post->ID, 'iihlms_lesson_pdfs', true );
		if ( ! empty( $pdfs ) ) {
			echo '<div class="lesson-pdfs">';
			foreach ( $pdfs as $pdf ) {
				echo '<div class="lesson-pdf">';
				echo '<a href="' . esc_url( $pdf['url'] ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $pdf['name'] ) . '</a>';
				echo '</div>';
			}
			echo '</div>';
		}

		// テスト.
		$disp_test_flg = false;
		if ( $this->tests_associated_with_lesson_exists_check( $this_lesson_id ) ) {
			$iihlms_lesson_test_conditions_for_displaying = get_post_meta( $this_lesson_id, 'iihlms_lesson_test_conditions_for_displaying', true );
			if ( 'nocondition' === $iihlms_lesson_test_conditions_for_displaying ) {
				$disp_test_flg = true;
			} elseif ( 'aftercompleting' === $iihlms_lesson_test_conditions_for_displaying ) {
				// 指定したレッスンは受講済か.
				if ( true === $this->check_user_activity( $post->ID, $user->ID ) ) {
					$disp_test_flg = true;
					echo '<div id="lesson-test-wrap" class="' . esc_attr( $btn_lesson_complete_remove_display ) . ' p-0">';
				}
			} else {
				$disp_test_flg = false;
			}

			echo '<div class="p-0">';
			$iihlms_lesson_test_relationship = get_post_meta( $this_lesson_id, 'iihlms_lesson_test_relationship', true );
			$iihlms_lesson_test_relationship = isset( $iihlms_lesson_test_relationship ) ? (array) $iihlms_lesson_test_relationship : array();

			// 関連しているテスト.
			if ( '' !== $iihlms_lesson_test_relationship[0] ) {
				$args     = array(
					'post_type'           => 'iihlms_tests',
					'posts_per_page'      => -1,
					'post_status'         => 'publish',
					'post__in'            => $iihlms_lesson_test_relationship,
					'ignore_sticky_posts' => 1,
					'orderby'             => 'post__in',
				);
				$my_posts = get_posts( $args );

				$query_prm_arg = array(
					'iihlmsmypagelesson' => $this_lesson_id,
					'iihlmsmypagecourse' => $iihlmsmypagecourse,
					'iihlmsmypageitem'   => $iihlmsmypageitem,
				);

				$counter = 0;
				foreach ( $my_posts as $postdata ) {
					setup_postdata( $postdata );
					if ( 0 === $counter ) {
						echo '<button type="button" class="list-group-item list-group-item-action disabled list-group-item-test-title">';
						echo esc_html__( 'レッスンの学習成果をチェック', 'imaoikiruhitolms' );
						echo '</button>';
					}
					echo '<form name="test_form" action="' . esc_url( get_permalink( $postdata->ID ) ) . '" method="post" class="iihlms-form">';
					echo '<table class="table align-middle test-table">';
					echo '<tr>';
					echo '<td class="test-table-title-td">';
					echo '<div class="test-table-title">';
					echo esc_html( get_the_title( $postdata->ID ) );
					echo '</div>';
					echo '</td>';
					echo '<td class="test-table-btn-td" rowspan="2">';
					wp_nonce_field( 'iihlms-start-the-test-csrf-action', 'iihlms-start-the-test-csrf' );
					echo '<input type="hidden" name="iihlms-test-start" value="start">';
					echo '<input type="hidden" name="iihlms-mypageitem-id" value="' . esc_attr( $iihlmsmypageitem ) . '">';
					echo '<input type="hidden" name="iihlms-mypagecourse-id" value="' . esc_attr( $iihlmsmypagecourse ) . '">';
					echo '<input type="hidden" name="iihlms-mypagelesson-id" value="' . esc_attr( $this_lesson_id ) . '">';
					echo '<input type="hidden" name="iihlms-this-test-id" value="' . esc_attr( $postdata->ID ) . '">';
					echo '<div class="text-center">';
					echo '<button class="btn btn-test-table" type="submit">' . esc_html__( 'テストを受ける', 'imaoikiruhitolmsaddition' ) . '</button>';
					echo '</div>';
					echo '</td>';
					echo '</tr>';
					echo '<tr>';
					echo '<td class="test-table-settings-td">';
					echo '<div class="test-table-settings">';
					echo '<span class="test-table-settings-number-of-questions">';
					echo esc_html__( '問題数：', 'imaoikiruhitolms' );
					echo esc_html( $this->get_test_number_of_questions( $postdata->ID ) );
					echo esc_html__( '問', 'imaoikiruhitolms' );
					echo '</span>';
					echo '<span class="test-table-settings-time-limit">';
					echo esc_html__( '制限時間：', 'imaoikiruhitolms' );
					echo esc_html( $this->get_test_time_limit_for_disp( $postdata->ID ) );
					echo '</span>';
					echo '</div>';
					echo '</td>';
					echo '</tr>';
					echo '<tr>';
					echo '<td class="test-table-btn-td-m">';
					echo '<button class="btn btn-test-table" type="submit">' . esc_html__( 'テストを受ける', 'imaoikiruhitolmsaddition' ) . '</button>';
					echo '</td>';
					echo '</tr>';
					echo '</table>';
					echo '</form>';
					$counter++;
				}
				wp_reset_postdata();
			}
			echo '</div>';
			if ( 'aftercompleting' === $iihlms_lesson_test_conditions_for_displaying ) {
				echo '</div>';
			}
		}

		echo '</div>';
		echo '</div>';      // container.
		echo '<div class="iihlms-spacer-white"></div>';

		$lesson_next_link     = $this->get_lesson_next_link( $this_lesson_id, $iihlmsmypagecourse );
		$lesson_previous_link = $this->get_previous_lesson_link( $this_lesson_id, $iihlmsmypagecourse );

		if ( ( '' !== $lesson_previous_link ) || ( '' !== $lesson_next_link ) ) {
			echo '<div class="container container-prevnext-btn-group">';
			echo '<div class="prevnext-btn-group">';
			if ( '' !== $lesson_previous_link ) {
				if ( ! empty( $iihlmsmypageitem ) && ! empty( $iihlmsmypagecourse ) ) {
					$lesson_previous_link_url = esc_url(
						add_query_arg(
							array(
								'iihlmsmypagecourse' => $iihlmsmypagecourse,
								'iihlmsmypageitem'   => $iihlmsmypageitem,
							),
							$lesson_previous_link
						)
					);
				} elseif ( ! empty( $iihlmsmypageitem ) && empty( $iihlmsmypagecourse ) ) {
					$lesson_previous_link_url = esc_url( add_query_arg( 'iihlmsmypageitem', $iihlmsmypageitem, $lesson_previous_link ) );
				} elseif ( empty( $iihlmsmypageitem ) && ! empty( $iihlmsmypagecourse ) ) {
					$lesson_previous_link_url = esc_url( add_query_arg( 'iihlmsmypagecourse', $iihlmsmypagecourse, $lesson_previous_link ) );
				} else {
					$lesson_previous_link_url = esc_url( $lesson_previous_link );
				}
				echo '<button type="button" class="btn btn-previous-link" onclick="location.href=\'' . esc_url( $lesson_previous_link_url ) . '\'"><i class="bi bi-chevron-left btn-previous-link-icon"></i> ' . esc_html__( '前のレッスン', 'imaoikiruhitolms' ) . '</button>';
			} else {
				echo '<button type="button" class="btn btn-previous-link btn-previous-link-disabled disabled">&nbsp;</button>';
			}
			if ( '' !== $lesson_next_link ) {
				if ( ! empty( $iihlmsmypageitem ) && ! empty( $iihlmsmypagecourse ) ) {
					$lesson_next_link_url = esc_url(
						add_query_arg(
							array(
								'iihlmsmypagecourse' => $iihlmsmypagecourse,
								'iihlmsmypageitem'   => $iihlmsmypageitem,
							),
							$lesson_next_link
						)
					);
				} elseif ( ! empty( $iihlmsmypageitem ) && empty( $iihlmsmypagecourse ) ) {
					$lesson_next_link_url = esc_url( add_query_arg( 'iihlmsmypageitem', $iihlmsmypageitem, $lesson_next_link ) );
				} elseif ( empty( $iihlmsmypageitem ) && ! empty( $iihlmsmypagecourse ) ) {
					$lesson_next_link_url = esc_url( add_query_arg( 'iihlmsmypagecourse', $iihlmsmypagecourse, $lesson_next_link ) );
				} else {
					$lesson_next_link_url = esc_url( $lesson_next_link );
				}

				echo '<button type="button" class="btn btn-next-link" onclick="location.href=\'' . esc_url( $lesson_next_link_url ) . '\'">' . esc_html__( '次のレッスン', 'imaoikiruhitolms' ) . ' <i class="bi bi-chevron-right btn-next-link-icon"></i></button>';
			} else {
				echo '<button type="button" class="btn btn-next-link btn-next-link-disabled disabled">&nbsp;</button>';
			}
			echo '</div>';
			echo '</div>';
		}

		if ( ! empty( $iihlmsmypagecourse ) ) {
			// コースへのリンク.
			$args     = array(
				'post_type'      => 'iihlms_courses',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'include'        => $iihlmsmypagecourse,
			);
			$my_posts = get_posts( $args );

			foreach ( $my_posts as $postdata ) {
				setup_postdata( $postdata );
				if ( ! empty( $iihlmsmypageitem ) ) {
					$course_url = add_query_arg( 'iihlmsmypageitem', $iihlmsmypageitem, get_permalink( $postdata->ID ) );
				} else {
					$course_url = get_permalink( $postdata->ID );
				}
				echo '<div class="footer-to-top-course-wrap">';
				echo '<table class="footer-to-top-course-table">';
				echo '<tr>';
				echo '<td class="footer-to-top-course-img-td p-0">';
				echo '<a href="' . esc_url( get_permalink( $postdata->ID ) ) . '">';
				if ( has_post_thumbnail( $postdata->ID ) ) {
					echo '<img src="' . esc_url( get_the_post_thumbnail_url( $postdata->ID, 'large' ) ) . '" class="footer-to-top-course-img" alt="...">';
				} else {
					echo '<img src="' . esc_url( get_template_directory_uri() . '/images/default_thumbnail.png' ) . '" class="footer-to-top-course-img" alt="...">';
				}
				echo '</a>';
				echo '</td>';
				echo '<td class="footer-to-top-course-text-td p-0">';
				echo '<a href="' . esc_url( $course_url ) . '">';
				echo esc_html__( 'このコースのTOPへ', 'imaoikiruhitolms' );
				echo '</a>';
				echo '</td>';
				echo '</tr>';
				echo '</table>';
				echo '</div>';
			}
			wp_reset_postdata();
		}

		if ( ! empty( $iihlmsmypageitem ) ) {
			// 講座へのリンク.
			$args     = array(
				'post_type'      => 'iihlms_items',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'include'        => $iihlmsmypageitem,
			);
			$my_posts = get_posts( $args );

			foreach ( $my_posts as $postdata ) {
				setup_postdata( $postdata );

				echo '<div class="footer-to-top-item-wrap">';
				echo '<table class="footer-to-top-item-table">';
				echo '<tr>';
				echo '<td class="footer-to-top-item-img-td p-0">';
				echo '<a href="' . esc_url( get_permalink( $postdata->ID ) ) . '">';
				if ( has_post_thumbnail( $postdata->ID ) ) {
					echo '<img src="' . esc_url( get_the_post_thumbnail_url( $postdata->ID, 'large' ) ) . '" class="footer-to-top-item-img" alt="...">';
				} else {
					echo '<img src="' . esc_url( get_template_directory_uri() . '/images/default_thumbnail.png' ) . '" class="footer-to-top-item-img" alt="...">';
				}
				echo '</a>';
				echo '</td>';
				echo '<td class="footer-to-top-item-text-td p-0">';
				echo '<a href="' . esc_url( get_permalink( $postdata->ID ) ) . '">';
				echo esc_html__( 'この講座のTOPへ', 'imaoikiruhitolms' );
				echo '</a>';
				echo '</td>';
				echo '</tr>';
				echo '</table>';

				echo '</div>';
			}
			wp_reset_postdata();
		}

		echo '<div class="footer-home-btn-wrap">';
		echo '<div class="text-center"><button type="button" class="btn btn-mypage-content" onclick="location.href=\'' . esc_url( get_home_url() ) . '/\'"><div class="btn-mypage-text"><i class="bi bi-house btn-mypage-icon"></i> HOME</div></button></div>';
		echo '</div>';

		echo "
		<script>
		(function($){
			'use strict';

			/**
			 * WordPressのAjax処理用クラス
			 *
			 * @param wp_ajax_root WordPressのAjax用URL
			 */
			let WPAjaxUtil = function (wp_ajax_root) {
			    this.wp_ajax_root = wp_ajax_root;
			};

			/**
			 * Ajax処理でデータ更新
			 */
			WPAjaxUtil.prototype.updatePostMeta = function (postId, userId, completeValue) {
				$.ajax({
					type: 'POST',
					url: this.wp_ajax_root,
					timeout : 30000,
					cache: false,
					data: {
						'action' : 'update_lesseon_complete',
						'post_id': postId,
						'user_id': userId,
						'complete_value': completeValue,
						'nonce': '" . esc_html( wp_create_nonce( 'iihlms-ajax-nonce-update-lesseon-complete' ) ) . "'
					},
					beforeSend: function() {
						$('#btn-lesson-complete').prop('disabled', true);
						$('#btn-lesson-complete-remove').prop('disabled', true);
					},
				})
				.done(function (data, textStatus, jqXHR) {
					console.log('Ajax communication succeeded!');
				})
				.always( function() {
					$('#btn-lesson-complete').prop('disabled', false);
					$('#btn-lesson-complete-remove').prop('disabled', false);
				});
			};

			/**
			 * ボタンをクリックしたら、受講完了にする
			 */
			$('#btn-lesson-complete').on('click', function() {
				let postId      = " . esc_html( $post->ID ) . ';
				let userId      = ' . esc_html( $user->ID ) . ";
				let completeValue = 1; //1:complete 0:incomplete

				// Ajax処理用クラスに登録してあるメソッドで更新
				new WPAjaxUtil(wp_ajax_root).updatePostMeta(postId, userId, completeValue);

				$('#btn-lesson-complete').addClass('btn-display-none');
				$('#btn-lesson-complete-remove').removeClass('btn-display-none');
				$('#lesson-test-wrap').removeClass('btn-display-none');
			});

			/**
			 * ボタンをクリックしたら、受講完了から解除する
			 */
			$('#btn-lesson-complete-remove').on('click', function() {

				let postId      = " . esc_html( $post->ID ) . ';
				let userId      = ' . esc_html( $user->ID ) . ";
				let completeValue = 0; //1:complete 0:incomplete 

				// Ajax処理用クラスに登録してあるメソッドで更新
				new WPAjaxUtil(wp_ajax_root).updatePostMeta(postId, userId, completeValue);

				$('#btn-lesson-complete-remove').addClass('btn-display-none');
				$('#btn-lesson-complete').removeClass('btn-display-none');
				$('#lesson-test-wrap').addClass('btn-display-none');
			});

		})(jQuery);
		</script>
		";
	}
	/**
	 * レッスンページエラー表示
	 *
	 * @param string $err_msg エラーメッセージ.
	 * @return void
	 */
	public function show_err_iihlms_lessons_page_content( $err_msg ) {
		echo '<div class="container container-width justify-content-center">';
		echo '<div class="row">';
		echo '<p class="mt-3">';
		echo esc_html( $err_msg );
		echo '</p>';
		echo '<p><button class="btn btn-primary" onclick="history.back(-1)">' . esc_html__( '戻る', 'imaoikiruhitolms' ) . '</button></p>';
		echo '</div>';
		echo '</div>';
		get_template_part( 'footer' );
	}
	/**
	 * テストページ
	 *
	 * @return void
	 */
	public function iihlms_tests_page_content() {
		echo esc_html( apply_filters( 'iihlms_addition_iihlms_tests_page_content', '' ) );
	}
	/**
	 * テストページエラー表示
	 *
	 * @param string $err_msg エラーメッセージ.
	 * @return void
	 */
	public function show_err_iihlms_tests_page_content( $err_msg ) {
		echo esc_html( apply_filters( 'iihlms_addition_show_err_iihlms_tests_page_content', $err_msg ) );
	}
	/**
	 * テストページエラー表示(page_controller)
	 *
	 * @param string $err_msg エラーメッセージ.
	 * @return void
	 */
	public function show_err_iihlms_tests_page_content_controller( $err_msg ) {
		echo esc_html( apply_filters( 'iihlms_addition_show_err_iihlms_tests_page_content_controller', $err_msg ) );
	}

	/**
	 * 注文ページ
	 *
	 * @return void
	 */
	public function iihlms_apply_page_content() {
		global $wpdb;
		global $post;

		$user                 = wp_get_current_user();
		$iihlms_apply_item_id = '';
		$username             = get_user_meta( $user->ID, 'iihlms_user_name1', true ) . get_user_meta( $user->ID, 'iihlms_user_name2', true );

		echo '<div class="container container-width">';
		echo '<div class="row iihlms-row-m0">';
		echo '<h2 class="title-text">' . esc_html( get_the_title() ) . '</h2>';

		if ( isset( $_POST['iihlms-apply-item-id'] ) ) {
			if ( ! isset( $_POST['cartcsrftoken'] ) ) {
				$this->show_err_iihlms_apply_page_content( esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' ) );
				exit;
			}
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cartcsrftoken'] ) ), 'cart-csrf' ) ) {
				$this->show_err_iihlms_apply_page_content( esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' ) );
				exit;
			}
			// 講座ID.
			$iihlms_apply_item_id = sanitize_text_field( wp_unslash( $_POST['iihlms-apply-item-id'] ) );
			// 講座の存在チェック.
			if ( ! $this->item_exists_check( $iihlms_apply_item_id ) ) {
				$this->show_err_iihlms_apply_page_content( esc_html__( '講座が存在しません。', 'imaoikiruhitolms' ) );
				exit;
			}
			// 指定した講座IDは購入済か.
			if ( $this->check_item_purchased( $iihlms_apply_item_id ) ) {
				if ( $this->check_item_within_expiration_date( $iihlms_apply_item_id ) ) {
					$this->show_err_iihlms_apply_page_content( esc_html__( '購入済の講座です。', 'imaoikiruhitolms' ) );
					exit;
				}
			}
			$_SESSION['iihlms']['iihlms_apply_item_id'] = $iihlms_apply_item_id;
		} else {
			// ログインリダイレクト.
			if ( isset( $_SESSION['iihlms']['iihlms_apply_item_id'] ) ) {
				$iihlms_apply_item_id = sanitize_text_field( $_SESSION['iihlms']['iihlms_apply_item_id'] );
			} else {
				$iihlms_apply_item_id = '';
			}
			// 指定した講座IDは購入済か.
			if ( $this->check_item_purchased( $iihlms_apply_item_id ) ) {
				if ( $this->check_item_within_expiration_date( $iihlms_apply_item_id ) ) {
					$this->show_err_iihlms_apply_page_content( esc_html__( '購入済の講座です。', 'imaoikiruhitolms' ) );
					exit;
				}
			}
		}
		if ( '' === $iihlms_apply_item_id ) {
			$this->show_err_iihlms_apply_page_content( esc_html__( '講座が指定されていません。', 'imaoikiruhitolms' ) );
			exit;
		}
		// この講座を表示する権限チェック.
		$permission_item_membership = $this->check_permission_item_membership( $iihlms_apply_item_id );
		if ( false === $permission_item_membership ) {
			$this->show_err_iihlms_apply_page_content( esc_html__( '権限がありません。', 'imaoikiruhitolms' ) );
			exit;
		}

		$item_data = $this->get_item_data( $iihlms_apply_item_id );

		echo '<div class="card p-0">';
		echo '<h5 class="card-header">';
		$h5_title_text = esc_html__( 'お申込みの講座', 'imaoikiruhitolms' );
		if ( '' !== apply_filters( 'iihlms_lc_ctf_5', '' ) ) {
			$h5_title_text = apply_filters( 'iihlms_lc_ctf_5', '' );
		}
		echo esc_html( $h5_title_text );
		echo '</h5>';
		echo '<div class="card-body">';
		echo '<p class="card-text">';
		echo esc_html( $item_data['title'] );
		echo '</p>';

		$iihlms_payment_type = get_post_meta( $iihlms_apply_item_id, 'iihlms_payment_type', true );
		if ( 'subscription' === $iihlms_payment_type ) {
			$iihlms_item_subscription_trial_interval_count = get_post_meta( $iihlms_apply_item_id, 'iihlms_item_subscription_trial_interval_count', true );
			$iihlms_item_subscription_trial_interval_unit  = get_post_meta( $iihlms_apply_item_id, 'iihlms_item_subscription_trial_interval_unit', true );
			$iihlms_item_subscription_trial_price          = get_post_meta( $iihlms_apply_item_id, 'iihlms_item_subscription_trial_price', true );
			if ( ( '0' !== $iihlms_item_subscription_trial_interval_count ) || ( '0' !== $iihlms_item_subscription_trial_price ) ) {
				$iihlms_item_subscription_trial_price = $this->get_price_for_disp( $iihlms_item_subscription_trial_price );
				echo '<p class="card-text">';
				echo esc_html__( 'トライアル価格', 'imaoikiruhitolms' );
				echo '　';
				echo esc_html( $iihlms_item_subscription_trial_price );
				echo '</p>';
				echo '<p class="card-text">';
				echo esc_html__( 'トライアル期間', 'imaoikiruhitolms' );
				echo '　';
				echo esc_html( $iihlms_item_subscription_trial_interval_count );
				echo esc_html( $this->get_interval_unit_for_disp_long( $iihlms_item_subscription_trial_interval_unit ) );
				echo '</p>';
				echo '<p class="card-text">';
				echo esc_html__( '請求開始日', 'imaoikiruhitolms' );
				echo '　';
				$now_date_time      = current_datetime();
				$billing_start_date = $this->get_billing_start_date( $now_date_time, $iihlms_item_subscription_trial_interval_unit, $iihlms_item_subscription_trial_interval_count );
				echo esc_html( $billing_start_date->format( $this->specify_date_format ) );
				echo '</p>';
			} else {
				echo '<p class="card-text">';
				echo esc_html__( '請求開始日', 'imaoikiruhitolms' );
				echo '　';
				$now = current_datetime();
				echo esc_html( $now->format( $this->specify_date_format ) );
				echo '</p>';
			}
			echo '<p class="card-text">';
			echo esc_html__( '請求内容', 'imaoikiruhitolms' );
			echo '　';
			echo esc_html( $this->get_price_for_disp_by_id( $iihlms_apply_item_id ) );
			echo '</p>';
		} else {
			echo '<p class="card-text">';
			echo esc_html__( '価格', 'imaoikiruhitolms' );
			echo '　';
			echo esc_html( $this->get_price_for_disp_by_id( $iihlms_apply_item_id ) );
			echo '</p>';
		}

		echo '</div>';
		echo '</div>';

		if ( ! is_user_logged_in() ) {
			echo '<div class="card p-0 mt-3">';
			echo '<h5 class="card-header">';
			echo esc_html__( 'お申込みにはログインが必要です', 'imaoikiruhitolms' );
			echo '</h5>';
			echo '<div class="card-body">';
			echo '<p class="card-text">';
			echo '<a href="' . esc_url( wp_login_url( get_permalink() ) ) . '" title="' . esc_html__( 'ログイン', 'imaoikiruhitolms' ) . '">' . esc_html__( 'ログイン', 'imaoikiruhitolms' ) . '</a>';
			echo '</p>';
			echo '</div>';
			echo '</div>';
		} else {
			echo '<div class="card p-0 mt-3">';
			echo '<h5 class="card-header">';
			echo esc_html__( 'お客様の情報', 'imaoikiruhitolms' );
			echo '</h5>';
			echo '<div class="card-body">';
			echo '<p class="card-text">';
			echo esc_html( $username ) . esc_html__( '様', 'imaoikiruhitolms' );
			echo '</p>';
			echo '<p class="card-text">';
			echo esc_html( $user->user_email );
			echo '</p>';
			echo '</div>';
			echo '</div>';

			if ( '0' === $this->get_tax_excluded_price_by_id( $iihlms_apply_item_id ) ) {
				echo '<form name="apply_form" id="apply_form" action="" method="post" class="iihlms-form">';
				echo '<input type="hidden" name="apply_item_id" value="' . esc_attr( $iihlms_apply_item_id ) . '">';
				wp_nonce_field( 'iihlms-apply-free-csrf-action', 'iihlms-apply-free-csrf' );
				?>
				<input type="hidden" name="iihlms-apply-itemid" value="<?php echo esc_attr( $iihlms_apply_item_id ); ?>">
				<input type="hidden" name="iihlms-apply-type" value="free">
				<div class="iihlms-confirm-application-button-wrap">
				<button type="submit" id="iihlms-confirm-application-button" class="btn btn-cart"><?php echo esc_html__( '申込みを確定する', 'imaoikiruhitolms' ); ?></button>
				</div>
				</form>
				<?php
			} else {
				echo '<div class="card p-0 mt-3">';
				echo '<h5 class="card-header">';
				echo esc_html__( 'お支払い', 'imaoikiruhitolms' );
				echo '</h5>';
				echo '<div class="card-body">';
				echo '<p class="card-text">';
				?>
				<div class="accordion" id="accordionPay">
					<?php
					$iihlms_payment_method_setting = get_option( 'iihlms_payment_method_setting', array() );
					if ( in_array( 'paypal', $iihlms_payment_method_setting, true ) ) {
						?>
						<div class="accordion-item">
							<h2 class="accordion-header" id="headingOne">
							<button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
							PayPal
							</button>
							</h2>
							<?php
							if ( 1 === count( $iihlms_payment_method_setting ) ) {
								echo '<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionPay">';
							} else {
								echo '<div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionPay">';
							}
							?>
								<div class="accordion-body">
								<div id="payment-accordion-wrap-paypal">
								<?php
								$iihlms_payment_type = get_post_meta( $iihlms_apply_item_id, 'iihlms_payment_type', true );

								if ( 'onetime' === $iihlms_payment_type ) {
									$this->show_onetime_paypal( $iihlms_apply_item_id );
								} elseif ( 'subscription' === $iihlms_payment_type ) {
									$this->show_subscription_paypal( $iihlms_apply_item_id );
								}
								?>
								</div>
								</div>
							</div>
						</div>
						<?php
					}

					if ( in_array( 'stripe', $iihlms_payment_method_setting, true ) ) {
						?>
						<div class="accordion-item">
							<h2 class="accordion-header" id="headingTwo">
							<button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
							Stripe
							</button>
							</h2>
							<?php
							if ( 1 === count( $iihlms_payment_method_setting ) ) {
								echo '<div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#accordionPay">';
							} else {
								echo '<div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionPay">';
							}
							?>
								<div class="accordion-body">
								<div id="payment-accordion-wrap-stripe">
								<?php
								$iihlms_payment_type = get_post_meta( $iihlms_apply_item_id, 'iihlms_payment_type', true );

								if ( 'onetime' === $iihlms_payment_type ) {
									$this->show_onetime_stripe( $iihlms_apply_item_id );
								} elseif ( 'subscription' === $iihlms_payment_type ) {
									$this->show_subscription_stripe( $iihlms_apply_item_id );
								}
								?>
								</div>
								</div>
							</div>
						</div>
						<?php
					}
					?>
				</div>
				<?php
				echo '</p>';
				echo '</div>';
				echo '</div>';
			}
		}
		echo '</div>';
		echo '</div>';
		echo '<div class="iihlms-spacer-white"></div>';
	}
	/**
	 * 注文ページエラー表示
	 *
	 * @param string $err_msg エラーメッセージ.
	 * @return void
	 */
	public function show_err_iihlms_apply_page_content( $err_msg ) {
		echo '<p>';
		echo esc_html( $err_msg );
		echo '</p>';
		echo '<p><button class="btn btn-primary" onclick="history.back(-1)">' . esc_html__( '戻る', 'imaoikiruhitolms' ) . '</button></p>';
		get_template_part( 'footer' );
	}

	/**
	 * 注文受付完了
	 *
	 * @return void
	 */
	public function iihlms_applyresult_page_content() {
		echo '<div class="container container-width">';
		echo '<div class="row iihlms-row-m0">';
		echo '<h2 class="title-text">' . esc_attr( get_the_title() ) . '</h2>';

		$iihlmsapplyorderkey = get_query_var( 'iihlmsapplyorderkey' );
		echo '<div class="lmsjp-body-text-wrap mb-5">';
		echo '<p>' . esc_html__( 'ありがとうございます。申し込みを受け付けました。', 'imaoikiruhitolms' ) . '</p>';
		echo '<p>' . esc_html__( '注文番号', 'imaoikiruhitolms' ) . '：' . esc_html( $iihlmsapplyorderkey ) . '</p>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '<div class="footer-home-btn-wrap">';
		echo '<div class="text-center"><button type="button" class="btn btn-mypage-content" onclick="location.href=\'' . esc_url( get_home_url() ) . '/\'"><div class="btn-mypage-text"><i class="bi bi-house btn-mypage-icon"></i> HOME</div></button></div>';
		echo '</div>';
	}

	/**
	 * ユーザー情報ページ（ユーザー画面）
	 *
	 * @return void
	 */
	public function iihlms_userpage_content() {
		$user = wp_get_current_user();
		echo '<div class="container-fluied">';
		echo '</div>';
		echo '<div class="container container-width justify-content-center">';
		echo '<div class="row m-0">';
		echo '<h2 class="title-text">' . esc_html__( 'ユーザー情報', 'imaoikiruhitolms' ) . '</h2>';
		$iihlmsuserpage = get_query_var( 'iihlmsuserpage' );
		if ( 'ok' === $iihlmsuserpage ) {
			echo '<p>' . esc_html__( '更新しました。', 'imaoikiruhitolms' ) . '</p>';
		}
		if ( 'name1' === $iihlmsuserpage ) {
			echo '<p>' . esc_html__( '姓が未入力です。処理を中断しました。', 'imaoikiruhitolms' ) . '</p>';
		}
		if ( 'name2' === $iihlmsuserpage ) {
			echo '<p>' . esc_html__( '名が未入力です。処理を中断しました。', 'imaoikiruhitolms' ) . '</p>';
		}
		if ( 'zip' === $iihlmsuserpage ) {
			echo '<p>' . esc_html__( '郵便番号が未入力です。処理を中断しました。', 'imaoikiruhitolms' ) . '</p>';
		}
		if ( 'prefectures' === $iihlmsuserpage ) {
			echo '<p>' . esc_html__( '都道府県が未入力です。処理を中断しました。', 'imaoikiruhitolms' ) . '</p>';
		}
		if ( 'address1' === $iihlmsuserpage ) {
			echo '<p>' . esc_html__( '市区郡町村が未入力です。処理を中断しました。', 'imaoikiruhitolms' ) . '</p>';
		}
		if ( 'address2' === $iihlmsuserpage ) {
			echo '<p>' . esc_html__( '番地・マンション名などが未入力です。処理を中断しました。', 'imaoikiruhitolms' ) . '</p>';
		}
		if ( 'tel' === $iihlmsuserpage ) {
			echo '<p>' . esc_html__( '電話番号が未入力です。処理を中断しました。', 'imaoikiruhitolms' ) . '</p>';
		}
		?>
		<script type="text/javascript">
		(function($) {
			$(function(){
				function toHalfWidth(input) {
					return input.replace(/[！-～]/g,
						function(input){
							return String.fromCharCode(input.charCodeAt(0)-0xFEE0);
						}
					);
				};
				function toFullWidth(input) {
					return input.replace(/[!-~]/g,
						function(input){
							return String.fromCharCode(input.charCodeAt(0)+0xFEE0);
						}
					);
				};

				$('#iihlms-zip').on('input',function(e){
					$(this).val(toHalfWidth($(this).val()));
				});
				$('#iihlms-tel').on('input',function(e){
					$(this).val(toHalfWidth($(this).val()));
				});

				$.validator.addMethod(
					"regex",
					function(value, element, regexp) {
						var check = false;
						return this.optional(element) || regexp.test(value);
					},
					"<?php echo esc_html__( '無効な文字があります。', 'imaoikiruhitolms' ); ?>"
				);
				let wp_ajax_root = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';
				$('#form-iihlms-userpage').validate({
					errorClass:'iihlms-validation-error',
					errorElement:'span',
					rules: {
						'iihlms-name1': {
							required: true,
						},
						'iihlms-name2': {
							required: true,
						},
						'iihlms-zip': {
							required: true,
							regex: /^[0-9-]+$/,
							minlength: 7,
							maxlength: 8,
						},
						'iihlms-prefectures': {
							required: true,
						},
						'iihlms-address1': {
							required: true,
						},
						'iihlms-address2': {
							required: true,
						},
						'iihlms-tel': {
							required: true,
							regex: /^[0-9-]+$/,
						},
					},
					messages: {
						'iihlms-name1': {
							required: "<?php echo esc_html__( '姓を入力してください。', 'imaoikiruhitolms' ); ?>",
						},
						'iihlms-name2': {
							required: "<?php echo esc_html__( '名を入力してください。', 'imaoikiruhitolms' ); ?>",
						},
						'iihlms-zip': {
							required: "<?php echo esc_html__( '郵便番号を入力してください。', 'imaoikiruhitolms' ); ?>",
							regex: "<?php echo esc_html__( '郵便番号の内容が正しくありません。', 'imaoikiruhitolms' ); ?>",
							minlength: "<?php echo esc_html__( '郵便番号は7文字以上で入力してください。', 'imaoikiruhitolms' ); ?>",
							maxlength: "<?php echo esc_html__( '郵便番号は8文字以下で入力してください。', 'imaoikiruhitolms' ); ?>",
						},
						'iihlms-prefectures': {
							required: "<?php echo esc_html__( '都道府県を選択してください。', 'imaoikiruhitolms' ); ?>",
						},
						'iihlms-address1': {
							required: "<?php echo esc_html__( '市区郡町村を入力してください。', 'imaoikiruhitolms' ); ?>",
						},
						'iihlms-address2': {
							required: "<?php echo esc_html__( '番地・マンション名などを入力してください。', 'imaoikiruhitolms' ); ?>",
						},
						'iihlms-tel': {
							required: "<?php echo esc_html__( '電話番号を入力してください。', 'imaoikiruhitolms' ); ?>",
							regex: "<?php echo esc_html__( '半角数字および-が使用できます。', 'imaoikiruhitolms' ); ?>",
						}
					}
				});
			});
		})(jQuery);
		</script>
		<script type="text/javascript">
		function toPostFmt(obj){
			if((obj.value).trim().length == 7 && !isNaN(obj.value)){
				var str = obj.value.trim();
				var h = str.substr(0,3);
				var m = str.substr(3);
				obj.value = h + "-" + m;
			}
		}
		function offPostFmt(obj){
			var reg = new RegExp("-", "g");
			var chgVal = obj.value.replace(reg, "");
			if(!isNaN(chgVal)){
				obj.value = chgVal;
			}
		}
		</script>

		<form method="post" class="h-adr iihlms-form" name="form-iihlms-userpage" id="form-iihlms-userpage" action="<?php echo esc_url( '/' . IIHLMS_USERPAGE_NAME ); ?>/">
		<?php echo '<span class="p-country-name" style="display:none;">' . esc_html__( 'Japan', 'imaoikiruhitolms' ) . '</span>'; ?>
		<div class="mb-3">
		<label for="iihlms-name1" class="form-label"><?php echo esc_html__( '姓', 'imaoikiruhitolms' ); ?></label>
		<input type="text" name="iihlms-name1" id="iihlms-name1" value="<?php echo esc_html( get_user_meta( $user->ID, 'iihlms_user_name1', true ) ); ?>" class="form-control">
		</div>
		<div class="mb-3">
		<label for="iihlms-name2" class="form-label"><?php echo esc_html__( '名', 'imaoikiruhitolms' ); ?></label>
		<input type="text" name="iihlms-name2" id="iihlms-name2" value="<?php echo esc_html( get_user_meta( $user->ID, 'iihlms_user_name2', true ) ); ?>" class="form-control">
		</div>
		<div class="mb-3">
		<label for="iihlms-zip" class="form-label"><?php echo esc_html__( '郵便番号', 'imaoikiruhitolms' ); ?></label>
		<input type="text" size="8" minlength="7" maxlength="8" name="iihlms-zip" id="iihlms-zip" value="<?php echo esc_html( get_user_meta( $user->ID, 'iihlms_user_zip', true ) ); ?>" class="p-postal-code form-control" onfocus="offPostFmt(this);" onblur="toPostFmt(this);">
		</div>
		<div class="mb-3">
		<label for="iihlms-prefectures" class="form-label"><?php echo esc_html__( '都道府県', 'imaoikiruhitolms' ); ?></label>
		<select name="iihlms-prefectures" id="iihlms-prefectures" class="p-region form-select">
		<option value="" disabled selected style='display:none;'><?php echo esc_html__( '選択', 'imaoikiruhitolms' ); ?></option>
		<option value="北海道"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '北海道' ); ?>>北海道</option>
		<option value="青森県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '青森県' ); ?>>青森県</option>
		<option value="岩手県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '岩手県' ); ?>>岩手県</option>
		<option value="宮城県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '宮城県' ); ?>>宮城県</option>
		<option value="秋田県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '秋田県' ); ?>>秋田県</option>
		<option value="山形県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '山形県' ); ?>>山形県</option>
		<option value="福島県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '福島県' ); ?>>福島県</option>
		<option value="茨城県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '茨城県' ); ?>>茨城県</option>
		<option value="栃木県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '栃木県' ); ?>>栃木県</option>
		<option value="群馬県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '群馬県' ); ?>>群馬県</option>
		<option value="埼玉県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '埼玉県' ); ?>>埼玉県</option>
		<option value="千葉県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '千葉県' ); ?>>千葉県</option>
		<option value="東京都"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '東京都' ); ?>>東京都</option>
		<option value="神奈川県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '神奈川県' ); ?>>神奈川県</option>
		<option value="新潟県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '新潟県' ); ?>>新潟県</option>
		<option value="富山県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '富山県' ); ?>>富山県</option>
		<option value="石川県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '石川県' ); ?>>石川県</option>
		<option value="福井県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '福井県' ); ?>>福井県</option>
		<option value="山梨県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '山梨県' ); ?>>山梨県</option>
		<option value="長野県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '長野県' ); ?>>長野県</option>
		<option value="岐阜県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '岐阜県' ); ?>>岐阜県</option>
		<option value="静岡県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '静岡県' ); ?>>静岡県</option>
		<option value="愛知県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '愛知県' ); ?>>愛知県</option>
		<option value="三重県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '三重県' ); ?>>三重県</option>
		<option value="滋賀県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '滋賀県' ); ?>>滋賀県</option>
		<option value="京都府"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '京都府' ); ?>>京都府</option>
		<option value="大阪府"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '大阪府' ); ?>>大阪府</option>
		<option value="兵庫県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '兵庫県' ); ?>>兵庫県</option>
		<option value="奈良県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '奈良県' ); ?>>奈良県</option>
		<option value="和歌山県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '和歌山県' ); ?>>和歌山県</option>
		<option value="鳥取県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '鳥取県' ); ?>>鳥取県</option>
		<option value="島根県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '島根県' ); ?>>島根県</option>
		<option value="岡山県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '岡山県' ); ?>>岡山県</option>
		<option value="広島県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '広島県' ); ?>>広島県</option>
		<option value="山口県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '山口県' ); ?>>山口県</option>
		<option value="徳島県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '徳島県' ); ?>>徳島県</option>
		<option value="香川県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '香川県' ); ?>>香川県</option>
		<option value="愛媛県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '愛媛県' ); ?>>愛媛県</option>
		<option value="高知県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '高知県' ); ?>>高知県</option>
		<option value="福岡県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '福岡県' ); ?>>福岡県</option>
		<option value="佐賀県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '佐賀県' ); ?>>佐賀県</option>
		<option value="長崎県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '長崎県' ); ?>>長崎県</option>
		<option value="熊本県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '熊本県' ); ?>>熊本県</option>
		<option value="大分県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '大分県' ); ?>>大分県</option>
		<option value="宮崎県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '宮崎県' ); ?>>宮崎県</option>
		<option value="鹿児島県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '鹿児島県' ); ?>>鹿児島県</option>
		<option value="沖縄県"<?php selected( get_user_meta( $user->ID, 'iihlms_user_prefectures', true ), '沖縄県' ); ?>>沖縄県</option>
		</select>
		</div>
		<div class="mb-3">
		<label for="iihlms-address1" class="form-label"><?php echo esc_html__( '市区郡町村', 'imaoikiruhitolms' ); ?></label>
		<input type="text" name="iihlms-address1" id="iihlms-address1" value="<?php echo esc_html( get_user_meta( $user->ID, 'iihlms_user_address1', true ) ); ?>" class="p-locality form-control">
		</div>
		<div class="mb-3">
		<label for="iihlms-address2" class="form-label"><?php echo esc_html__( '番地・マンション名など', 'imaoikiruhitolms' ); ?></label>
		<input type="text" name="iihlms-address2" id="iihlms-address2" value="<?php echo esc_html( get_user_meta( $user->ID, 'iihlms_user_address2', true ) ); ?>" class="p-street-address form-control">
		</div>
		<div class="mb-3">
		<label for="iihlms-company-name" class="form-label"><?php echo esc_html__( '会社名', 'imaoikiruhitolms' ); ?></label>
		<input type="text" name="iihlms-company-name" id="iihlms-company-name" value="<?php echo esc_html( get_user_meta( $user->ID, 'iihlms_company_name', true ) ); ?>" class="form-control">
		</div>
		<div class="mb-3">
		<label for="iihlms-tel" class="form-label"><?php echo esc_html__( '電話番号', 'imaoikiruhitolms' ); ?></label>
		<input type="text" name="iihlms-tel" id="iihlms-tel" value="<?php echo esc_html( get_user_meta( $user->ID, 'iihlms_user_tel', true ) ); ?>" class="form-control">
		</div>
		<input type="hidden" name="action-type" id="action-type" value="iihlms-userpage">
		<?php wp_nonce_field( 'iihlms-userpage-csrf-action', 'iihlms-userpage-csrf' ); ?>
		<p class="text-center"><input type="submit" name="iihlms-userpage-submit" id="iihlms-userpage-submit" class="iihlms-regist-button" value="<?php echo esc_html__( '更新', 'imaoikiruhitolms' ); ?>" class="btn btn-primary"></p>
		<p class="text-center text-pwd-change"><a href="<?php echo esc_html( wp_lostpassword_url() ); ?>" target="_blank"><?php echo esc_html__( 'パスワード変更', 'imaoikiruhitolms' ); ?></a></p>
		</form>
		<?php
		echo '</div>';
		echo '</div>';

		echo '<div class="footer-home-btn-wrap">';
		echo '<div class="text-center"><button type="button" class="btn btn-mypage-content" onclick="location.href=\'' . esc_url( get_home_url() ) . '/\'"><div class="btn-mypage-text"><i class="bi bi-house btn-mypage-icon"></i> HOME</div></button></div>';
		echo '</div>';
	}

	/**
	 * 新規ユーザー登録ページ
	 *
	 * @return void
	 */
	public function iihlms_userregistpage_content() {
		global $wpdb;

		echo '<style>body{background-color: #F2F9FA;}</style>';
		echo '<div class="iihlms-container-regist-wrap">';
		echo '<div class="iihlms-container-regist">';
		echo '<h2 class="iihlms-title-regist">' . esc_html( get_the_title() ) . '</h2>';

		$pre_user_table    = $wpdb->prefix . 'iihlms_pre_user';
		$iihlmsregisttoken = get_query_var( 'iihlmsregisttoken' );

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT user_email
				FROM %1s
				WHERE 
					urltoken = %s
					AND available = 0
					AND update_datetime > now() - interval 24 HOUR
				',
				$pre_user_table,
				$iihlmsregisttoken,
			)
		);
		$number  = count( $results );

		if ( 1 !== $number ) {
			$this->show_err_iihlms_userregistpage_content( esc_html__( 'このURLは使用できません。', 'imaoikiruhitolms' ) );
			exit;
		}
		foreach ( $results as $result ) {
			$user_email = $result->user_email;
		}

		?>
		<script type="text/javascript">
		(function($) {
			$(function(){
				function toHalfWidth(input) {
					return input.replace(/[！-～]/g,
						function(input){
							return String.fromCharCode(input.charCodeAt(0)-0xFEE0);
						}
					);
				};
				function toFullWidth(input) {
					return input.replace(/[!-~]/g,
						function(input){
							return String.fromCharCode(input.charCodeAt(0)+0xFEE0);
						}
					);
				};

				$('#signup-user-name').on('input',function(e){
					$(this).val(toHalfWidth($(this).val()));
				});
				$('#signup-user-password').on('input',function(e){
					$(this).val(toHalfWidth($(this).val()));
				});
				$('#iihlms-zip').on('input',function(e){
					$(this).val(toHalfWidth($(this).val()));
				});
				$('#iihlms-tel').on('input',function(e){
					$(this).val(toHalfWidth($(this).val()));
				});

				$.validator.addMethod(
					"regex",
					function(value, element, regexp) {
						var check = false;
						return this.optional(element) || regexp.test(value);
					},
					"<?php echo esc_html__( '無効な文字があります。', 'imaoikiruhitolms' ); ?>"
				);
				let wp_ajax_root = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';
				$('#form-user-registration').validate({
					errorClass:'iihlms-validation-error',
					errorElement:'span',
					rules: {
						'signup-user-name': {
							required: true,
							minlength: 4,
							maxlength: 60,
							regex: /^[a-zA-Z0-9-.@_]+$/,
							remote: {
								url: wp_ajax_root,
								type: "post",
								timeout : 30000,
								data: {
									'user_email': function() {
											return $( "#signup-user-name" ).val();
										},
									'nonce': '<?php echo esc_html( wp_create_nonce( 'iihlms-ajax-nonce-check-signup-user-name' ) ); ?>',
									'action': 'check_signup_user_name'
								}
							}
						},
						'signup-user-password': {
							required: true,
							minlength: 12,
							maxlength: 100,
							regex: /^[a-zA-Z0-9!#\$%&'\(\)\*\+-\.\/:;=\?\@\[\]\^_`\{\|\}~]+$/,
						},
						'iihlms-name1': {
							required: true,
						},
						'iihlms-name2': {
							required: true,
						},
						'iihlms-zip': {
							required: true,
							regex: /^[0-9-]+$/,
							minlength: 7,
							maxlength: 8,
						},
						'iihlms-prefectures': {
							required: true,
						},
						'iihlms-address1': {
							required: true,
						},
						'iihlms-address2': {
							required: true,
						},
						'iihlms-tel': {
							required: true,
							regex: /^[0-9-]+$/,
						},
					},
					messages: {
						'signup-user-name': {
							required: "<?php echo esc_html__( 'ユーザ名を入力してください。', 'imaoikiruhitolms' ); ?>",
							regex: "<?php echo esc_html__( '半角英数および-.@_が使用できます。', 'imaoikiruhitolms' ); ?>",
							minlength: "<?php echo esc_html__( 'ユーザ名は4文字以上で入力してください。', 'imaoikiruhitolms' ); ?>",
							maxlength: "<?php echo esc_html__( 'ユーザ名は60文字以内で入力してください。', 'imaoikiruhitolms' ); ?>",
						},
						'signup-user-password': {
							required: "<?php echo esc_html__( 'パスワードを入力してください。', 'imaoikiruhitolms' ); ?>",
							regex: "<?php echo esc_html__( '半角英数および', 'imaoikiruhitolms' ) . '!@#$%^&*()-_ []{}~`+=,.;:/?|' . esc_html__( 'が使用できます。', 'imaoikiruhitolms' ); ?>",
							minlength: "<?php echo esc_html__( 'パスワードは12文字以上で入力してください。', 'imaoikiruhitolms' ); ?>",
							maxlength: "<?php echo esc_html__( 'パスワードは100文字以内で入力してください。', 'imaoikiruhitolms' ); ?>",
						},
						'iihlms-name1': {
							required: "<?php echo esc_html__( '姓を入力してください。', 'imaoikiruhitolms' ); ?>",
						},
						'iihlms-name2': {
							required: "<?php echo esc_html__( '名を入力してください。', 'imaoikiruhitolms' ); ?>",
						},
						'iihlms-zip': {
							required: "<?php echo esc_html__( '郵便番号を入力してください。', 'imaoikiruhitolms' ); ?>",
							regex: "<?php echo esc_html__( '郵便番号の内容が正しくありません。', 'imaoikiruhitolms' ); ?>",
							minlength: "<?php echo esc_html__( '郵便番号は7文字以上で入力してください。', 'imaoikiruhitolms' ); ?>",
							maxlength: "<?php echo esc_html__( '郵便番号は8文字以下で入力してください。', 'imaoikiruhitolms' ); ?>",
						},
						'iihlms-prefectures': {
							required: "<?php echo esc_html__( '都道府県を選択してください。', 'imaoikiruhitolms' ); ?>",
						},
						'iihlms-address1': {
							required: "<?php echo esc_html__( '市区郡町村を入力してください。', 'imaoikiruhitolms' ); ?>",
						},
						'iihlms-address2': {
							required: "<?php echo esc_html__( '番地・マンション名などを入力してください。', 'imaoikiruhitolms' ); ?>",
						},
						'iihlms-tel': {
							required: "<?php echo esc_html__( '電話番号を入力してください。', 'imaoikiruhitolms' ); ?>",
							regex: "<?php echo esc_html__( '半角数字および-が使用できます。', 'imaoikiruhitolms' ); ?>",
						}
					}
				});
			});
		})(jQuery);
		</script>

		<script type="text/javascript">
		function toPostFmt(obj){
			if((obj.value).trim().length == 7 && !isNaN(obj.value)){
				var str = obj.value.trim();
				var h = str.substr(0,3);
				var m = str.substr(3);
				obj.value = h + "-" + m;
			}
		}
		function offPostFmt(obj){
			var reg = new RegExp("-", "g");
			var chgVal = obj.value.replace(reg, "");
			if(!isNaN(chgVal)){
				obj.value = chgVal;
			}
		}
		</script>

		<form method="post" class="h-adr iihlms-regist-form" name="form-user-registration" id="form-user-registration" action="">
		<?php echo '<span class="p-country-name" style="display:none;">' . esc_html__( 'Japan', 'imaoikiruhitolms' ) . '</span>'; ?>
		<div class="mb-3">
		<label for="signup_email_disponly" class="form-label"><?php echo esc_html__( 'メールアドレス', 'imaoikiruhitolms' ); ?></label>
		<input type="email" name="user_email" id="signup_email_disponly" value="<?php echo esc_html( $user_email ); ?>" class="form-control" disabled>
		</div>
		<div class="mb-3">
		<label for="signup-user-name" class="form-label"><?php echo esc_html__( 'ユーザ名', 'imaoikiruhitolms' ); ?> <span class="badge iihlms-badge"><?php echo esc_html__( '必須', 'imaoikiruhitolms' ); ?></span></label>
		<input type="text" name="signup-user-name" id="signup-user-name" value="" minlength="4" maxlength="60" class="form-control" required>
		</div>
		<div class="mb-3">
		<label for="signup-user-password" class="form-label"><?php echo esc_html__( 'パスワード', 'imaoikiruhitolms' ); ?> <span class="badge iihlms-badge"><?php echo esc_html__( '必須', 'imaoikiruhitolms' ); ?></span></label>
		<input type="text" name="signup-user-password" id="signup-user-password" value="" minlength="12" maxlength="100" class="form-control" autocomplete="off" required>
		</div>
		<div class="mb-3">
		<label for="iihlms-name1" class="form-label"><?php echo esc_html__( '姓', 'imaoikiruhitolms' ); ?> <span class="badge iihlms-badge"><?php echo esc_html__( '必須', 'imaoikiruhitolms' ); ?></span></label>
		<input type="text" name="iihlms-name1" id="iihlms-name1" value="" class="form-control" required>
		</div>
		<div class="mb-3">
		<label for="iihlms-name2" class="form-label"><?php echo esc_html__( '名', 'imaoikiruhitolms' ); ?> <span class="badge iihlms-badge"><?php echo esc_html__( '必須', 'imaoikiruhitolms' ); ?></span></label>
		<input type="text" name="iihlms-name2" id="iihlms-name2" value="" class="form-control" required>
		</div>
		<div class="mb-3">
		<label for="iihlms-zip" class="form-label"><?php echo esc_html__( '郵便番号', 'imaoikiruhitolms' ); ?> <span class="badge iihlms-badge"><?php echo esc_html__( '必須', 'imaoikiruhitolms' ); ?></span></label>
		<input type="text" size="8" minlength="7" maxlength="8" name="iihlms-zip" id="iihlms-zip" value="" class="p-postal-code form-control" onfocus="offPostFmt(this);" onblur="toPostFmt(this);" required>
		</div>
		<div class="mb-3">
		<label for="iihlms-prefectures" class="form-label"><?php echo esc_html__( '都道府県', 'imaoikiruhitolms' ); ?> <span class="badge iihlms-badge"><?php echo esc_html__( '必須', 'imaoikiruhitolms' ); ?></span></label>
		<select name="iihlms-prefectures" id="iihlms-prefectures" class="p-region form-select">
		<option value="" disabled selected style='display:none;'><?php echo esc_html__( '選択', 'imaoikiruhitolms' ); ?></option>
		<option value="北海道">北海道</option>
		<option value="青森県">青森県</option>
		<option value="岩手県">岩手県</option>
		<option value="宮城県">宮城県</option>
		<option value="秋田県">秋田県</option>
		<option value="山形県">山形県</option>
		<option value="福島県">福島県</option>
		<option value="茨城県">茨城県</option>
		<option value="栃木県">栃木県</option>
		<option value="群馬県">群馬県</option>
		<option value="埼玉県">埼玉県</option>
		<option value="千葉県">千葉県</option>
		<option value="東京都">東京都</option>
		<option value="神奈川県">神奈川県</option>
		<option value="新潟県">新潟県</option>
		<option value="富山県">富山県</option>
		<option value="石川県">石川県</option>
		<option value="福井県">福井県</option>
		<option value="山梨県">山梨県</option>
		<option value="長野県">長野県</option>
		<option value="岐阜県">岐阜県</option>
		<option value="静岡県">静岡県</option>
		<option value="愛知県">愛知県</option>
		<option value="三重県">三重県</option>
		<option value="滋賀県">滋賀県</option>
		<option value="京都府">京都府</option>
		<option value="大阪府">大阪府</option>
		<option value="兵庫県">兵庫県</option>
		<option value="奈良県">奈良県</option>
		<option value="和歌山県">和歌山県</option>
		<option value="鳥取県">鳥取県</option>
		<option value="島根県">島根県</option>
		<option value="岡山県">岡山県</option>
		<option value="広島県">広島県</option>
		<option value="山口県">山口県</option>
		<option value="徳島県">徳島県</option>
		<option value="香川県">香川県</option>
		<option value="愛媛県">愛媛県</option>
		<option value="高知県">高知県</option>
		<option value="福岡県">福岡県</option>
		<option value="佐賀県">佐賀県</option>
		<option value="長崎県">長崎県</option>
		<option value="熊本県">熊本県</option>
		<option value="大分県">大分県</option>
		<option value="宮崎県">宮崎県</option>
		<option value="鹿児島県">鹿児島県</option>
		<option value="沖縄県">沖縄県</option>
		</select>
		</div>
		<div class="mb-3">
		<label for="iihlms-address1" class="form-label"><?php echo esc_html__( '市区郡町村', 'imaoikiruhitolms' ); ?> <span class="badge iihlms-badge"><?php echo esc_html__( '必須', 'imaoikiruhitolms' ); ?></span></label>
		<input type="text" name="iihlms-address1" id="iihlms-address1" value="" class="p-locality form-control" required>
		</div>
		<div class="mb-3">
		<label for="iihlms-address2" class="form-label"><?php echo esc_html__( '番地・マンション名など', 'imaoikiruhitolms' ); ?> <span class="badge iihlms-badge"><?php echo esc_html__( '必須', 'imaoikiruhitolms' ); ?></span></label>
		<input type="text" name="iihlms-address2" id="iihlms-address2" value="" class="p-street-address form-control" required>
		</div>
		<div class="mb-3">
		<label for="iihlms-company-name" class="form-label"><?php echo esc_html__( '会社名', 'imaoikiruhitolms' ); ?></label>
		<input type="text" name="iihlms-company-name" id="iihlms-company-name" value="" class="form-control">
		</div>
		<div class="mb-3">
		<label for="iihlms-tel" class="form-label"><?php echo esc_html__( '電話番号', 'imaoikiruhitolms' ); ?> <span class="badge iihlms-badge"><?php echo esc_html__( '必須', 'imaoikiruhitolms' ); ?></span></label>
		<input type="tel" name="iihlms-tel" id="iihlms-tel" value="" class="form-control">
		</div>
		<input type="hidden" name="action-type" id="action-type" value="iihlms-user-signup">
		<?php wp_nonce_field( 'iihlms_user_regist_action', 'iihlms_user_regist' ); ?>
		<p class="text-center"><input type="submit" name="iihlms_user_regist_submit" id="iihlms_user_regist_submit" value="<?php echo esc_html__( '会員登録', 'imaoikiruhitolms' ); ?>" class="iihlms-regist-button"></p>
		</form>

		<?php
		echo '</div>';
		echo '</div>';
	}

	/**
	 * 新規ユーザー登録ページエラー表示
	 *
	 * @param string $err_msg エラーメッセージ.
	 * @return void
	 */
	public function show_err_iihlms_userregistpage_content( $err_msg ) {
		echo '<p>';
		echo esc_html( $err_msg );
		echo '</p>';
		get_template_part( 'footer' );
	}

	/**
	 * 新規ユーザー登録ページ
	 *
	 * @return void
	 */
	public function iihlms_accepting_userregistpage_content() {
		global $post;

		echo '<style>body{background-color: #F2F9FA;}</style>';
		echo '<div class="iihlms-container-regist-wrap">';
		echo '<div class="iihlms-container-regist">';
		echo '<h2 class="iihlms-title-regist">' . esc_html( get_the_title() ) . '</h2>';

		$iihlmsacceptmail = get_query_var( 'iihlmsacceptmail' );
		if ( 'send' === $iihlmsacceptmail ) {
			echo '<p class="text-center">' . esc_html__( 'メールを送信しました。メールに記載されたURLから登録をお願いします。', 'imaoikiruhitolms' ) . '</p>';
		} else {
			?>
			<form method="post" class="iihlms-regist-form" name="iihlms-regist-user-form" id="iihlms-regist-user-form" action="">
			<div class="mb-1">
			<label for="signup_email" class="form-label"><?php echo esc_html__( 'メールアドレス', 'imaoikiruhitolms' ); ?></label>
			<input type="email" name="accepting_userregist_email" id="accepting_userregist_email" value="" class="form-control" required>
			</div>
			<p class="text-center"><input type="submit" name="iihlms_accepting_user_regist_submit" id="iihlms_accepting_user_regist_submit" class="iihlms-regist-button" value="<?php echo esc_html__( 'メール送信', 'imaoikiruhitolms' ); ?>"></p>
			<?php wp_nonce_field( 'iihlms_accepting_user_regist_action', 'iihlms_accepting_user_regist' ); ?>
			</form>
			<?php
			if ( true === $this->is_recaptcha_on() ) {
				$iihlms_recaptcha_sitekey   = get_option( 'iihlms_recaptcha_sitekey', '' );
				$iihlms_recaptcha_secretkey = get_option( 'iihlms_recaptcha_secretkey', '' );
				wp_enqueue_script( 'recaptcha_script1', esc_url( 'https://www.google.com/recaptcha/api.js?render=' . $iihlms_recaptcha_sitekey ), array(), '1.0.0', true );
				?>
				<script>
				(function($) {
					$('#iihlms-regist-user-form').submit(function(event) {
						event.preventDefault();
						grecaptcha.ready(function() {
							grecaptcha.execute('<?php echo esc_html( $iihlms_recaptcha_sitekey ); ?>', {action: 'submit'}).then(function(token) {
								$('#iihlms-regist-user-form').prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
								$('#iihlms-regist-user-form').unbind('submit').submit();
							});;
						});
					});
				})(jQuery);
				</script>
				<?php
			}
			?>
			<div class="mt-4">
			<hr class="iihlms-login-hr">
			</div>
			<p id="iihlms-accepting-registration-nav"><?php echo esc_html__( 'アカウントをお持ちの場合', 'imaoikiruhitolms' ); ?></p>
			<p id="iihlms-accepting-registration"><a href="<?php echo esc_html( wp_login_url() ); ?>"><?php echo esc_html__( 'ログイン', 'imaoikiruhitolms' ); ?></a>
			<?php
		}
		echo '</div>';
		echo '</div>';
	}

	/**
	 * 注文履歴ページ
	 *
	 * @return void
	 */
	public function iihlms_orderhistory_content() {
		global $wpdb;

		echo '<div class="container container-width">';
		echo '<div class="row m-0">';
		echo '<h2 class="title-text">' . esc_attr( get_the_title() ) . '</h2>';

		$order_table           = $wpdb->prefix . 'iihlms_order';
		$order_cart_table      = $wpdb->prefix . 'iihlms_order_cart';
		$order_cart_meta_table = $wpdb->prefix . 'iihlms_order_cart_meta';

		$status_search_array = array(
			'paypal-payment-completed',
			'paypal-subscription-registration-completed',
			'manual-deletion-by-administrator',
			'manual-assignment-by-administrator',
			'paypal-subscription-cancelled',
			'free-completed',
			'stripe-payment-completed',
			'stripe-subscription-registration-completed',
			'stripe-subscription-cancelled',
		);

		$user    = wp_get_current_user();
		$results = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT *
				FROM %1s
				WHERE 
					user_id = %d
					AND order_status IN ( %s, %s, %s, %s, %s, %s, %s, %s, %s )
				ORDER BY order_date_time DESC
				',
				$order_table,
				$user->ID,
				$status_search_array[0],
				$status_search_array[1],
				$status_search_array[2],
				$status_search_array[3],
				$status_search_array[4],
				$status_search_array[5],
				$status_search_array[6],
				$status_search_array[7],
				$status_search_array[8],
			)
		);

		$result_num      = $wpdb->num_rows;
		$items_purchased = array();
		foreach ( $results as $row ) {
			echo '<ul class="list-group order-panel mb-4">';
			echo '<li class="list-group-item bg-light text-dark">';
			echo esc_html__( '注文日', 'imaoikiruhitolms' ) . '：';
			$formatday = new DateTimeImmutable( $row->order_date_time );
			echo esc_html( $formatday->format( $this->specify_date_format ) );
			echo '　';
			echo esc_html__( '注文番号', 'imaoikiruhitolms' ) . '：';
			echo esc_html( $row->order_key );
			echo '</li>';
			echo '<li class="list-group-item">';
			echo '<p>';
			echo '<b>';
			echo esc_html( $row->item_name );
			echo '</b>';
			echo '</p>';
			if ( 'paypal-subscription-cancelled' === $row->order_status ) {
				$paypal_subscription_cancel_datetime = $this->get_post_meta_order_meta_table( $row->order_id, 'paypal_subscription_cancel_datetime' );
				if ( '' !== $paypal_subscription_cancel_datetime ) {
					echo '<p>';
					echo esc_html__( '解約日時', 'imaoikiruhitolms' ) . '：';
					$formatday = new DateTimeImmutable( $paypal_subscription_cancel_datetime );
					echo esc_html( $formatday->format( $this->specify_date_time_format ) );
					echo '</p>';
				}
			}
			if ( 'stripe-subscription-cancelled' === $row->order_status ) {
				$stripe_subscription_cancel_datetime = $this->get_post_meta_order_meta_table( $row->order_id, 'stripe_subscription_cancel_datetime' );
				if ( '' !== $stripe_subscription_cancel_datetime ) {
					echo '<p>';
					echo esc_html__( '解約日時', 'imaoikiruhitolms' ) . '：';
					$formatday = new DateTimeImmutable( $stripe_subscription_cancel_datetime );
					echo esc_html( $formatday->format( $this->specify_date_time_format ) );
					echo '</p>';
				}
			}
			// 有効期限.
			$item_expiration_date = $this->get_item_expiration_date( $row->item_id );
			if ( ( '' !== $item_expiration_date ) && ( '0000-00-00 00:00:00' !== $item_expiration_date ) ) {
				echo '<p>';
				echo esc_html__( '有効期限', 'imaoikiruhitolms' ) . '：';
				$formatday = new DateTimeImmutable( $item_expiration_date );
				echo esc_html( $formatday->format( $this->specify_date_format ) );
				echo '</p>';
			}
			echo esc_html__( '状態', 'imaoikiruhitolms' ) . '：';
			echo esc_html( $this->get_order_status_name( $row->order_status ) );
			if ( 'paypal-subscription-cancelled' === $row->order_status ) {
				$formatday = new DateTimeImmutable( $row->expiration_date_time );
				echo '（';
				echo esc_html( $formatday->format( $this->specify_date_format ) );
				echo esc_html__( 'まで有効', 'imaoikiruhitolms' );
				echo '）';
			}
			echo '<br>';
			echo esc_html__( '支払い方法', 'imaoikiruhitolms' ) . '：';
			echo esc_html( $this->get_payment_name( $row->payment_name ) );
			echo '<br>';
			if ( $this->is_subscription( $row->item_id ) && ( 'manual-assignment-by-administrator' !== $row->order_status ) ) {
				if ( 'stripe-subscription' === $row->payment_name ) {
					// stripe.
					$iihlms_item_subscription_trial_price          = $this->get_post_meta_order_cart_meta_table( $row->order_cart_id, 'iihlms_item_subscription_trial_price_stripe' );
					$iihlms_item_subscription_trial_tax            = $this->get_post_meta_order_cart_meta_table( $row->order_cart_id, 'iihlms_item_subscription_trial_tax_stripe' );
					$iihlms_item_subscription_trial_interval_count = $this->get_post_meta_order_cart_meta_table( $row->order_cart_id, 'iihlms_item_subscription_trial_interval_count_stripe' );
					$iihlms_item_subscription_trial_interval_unit  = $this->get_post_meta_order_cart_meta_table( $row->order_cart_id, 'iihlms_item_subscription_trial_interval_unit_stripe' );
					$iihlms_item_subscription_price                = $this->get_post_meta_order_cart_meta_table( $row->order_cart_id, 'iihlms_item_subscription_price_stripe' );
					$iihlms_item_subscription_tax                  = $this->get_post_meta_order_cart_meta_table( $row->order_cart_id, 'iihlms_item_subscription_tax_stripe' );
					$iihlms_item_subscription_interval_count       = $this->get_post_meta_order_cart_meta_table( $row->order_cart_id, 'iihlms_item_subscription_interval_count_stripe' );
					$iihlms_item_subscription_interval_unit        = $this->get_post_meta_order_cart_meta_table( $row->order_cart_id, 'iihlms_item_subscription_interval_unit_stripe' );
					$iihlms_item_subscription_total_cycles         = $this->get_post_meta_order_cart_meta_table( $row->order_cart_id, 'iihlms_item_subscription_total_cycles_stripe' );
				} else {
					// paypal.
					$iihlms_item_subscription_trial_price          = $this->get_post_meta_order_cart_meta_table( $row->order_cart_id, 'iihlms_item_subscription_trial_price_paypal' );
					$iihlms_item_subscription_trial_tax            = $this->get_post_meta_order_cart_meta_table( $row->order_cart_id, 'iihlms_item_subscription_trial_tax_paypal' );
					$iihlms_item_subscription_trial_interval_count = $this->get_post_meta_order_cart_meta_table( $row->order_cart_id, 'iihlms_item_subscription_trial_interval_count_paypal' );
					$iihlms_item_subscription_trial_interval_unit  = $this->get_post_meta_order_cart_meta_table( $row->order_cart_id, 'iihlms_item_subscription_trial_interval_unit_paypal' );
					$iihlms_item_subscription_price                = $this->get_post_meta_order_cart_meta_table( $row->order_cart_id, 'iihlms_item_subscription_price_paypal' );
					$iihlms_item_subscription_tax                  = $this->get_post_meta_order_cart_meta_table( $row->order_cart_id, 'iihlms_item_subscription_tax_paypal' );
					$iihlms_item_subscription_interval_count       = $this->get_post_meta_order_cart_meta_table( $row->order_cart_id, 'iihlms_item_subscription_interval_count_paypal' );
					$iihlms_item_subscription_interval_unit        = $this->get_post_meta_order_cart_meta_table( $row->order_cart_id, 'iihlms_item_subscription_interval_unit_paypal' );
					$iihlms_item_subscription_total_cycles         = $this->get_post_meta_order_cart_meta_table( $row->order_cart_id, 'iihlms_item_subscription_total_cycles_paypal' );
				}
				$iihlms_item_subscription_trial_price_tax_included = $iihlms_item_subscription_trial_price + $iihlms_item_subscription_trial_tax;
				echo esc_html__( 'トライアル価格', 'imaoikiruhitolms' ) . '：' . esc_html( number_format( $iihlms_item_subscription_trial_price ) ) . esc_html__( '円', 'imaoikiruhitolms' ) . '（' . esc_html__( '税込', 'imaoikiruhitolms' ) . '：' . esc_html( number_format( $iihlms_item_subscription_trial_price_tax_included ) ) . esc_html__( '円', 'imaoikiruhitolms' ) . '）';
				echo esc_html__( 'トライアル期間', 'imaoikiruhitolms' ) . '：' . esc_html( $iihlms_item_subscription_trial_interval_count ) . esc_html( $this->get_interval_unit_for_disp_long( esc_html( $iihlms_item_subscription_trial_interval_unit ) ) );
				echo '<br>';
				echo esc_html__( '請求開始日', 'imaoikiruhitolms' ) . '：';
				$date               = new DateTimeImmutable( $row->order_date_time );
				$billing_start_date = $this->get_billing_start_date( $date, $iihlms_item_subscription_trial_interval_unit, $iihlms_item_subscription_trial_interval_count );
				echo esc_html( $billing_start_date->format( $this->specify_date_format ) );
				echo '<br>';
				$iihlms_item_subscription_price_tax_included = $iihlms_item_subscription_price + $iihlms_item_subscription_tax;
				echo esc_html__( '価格', 'imaoikiruhitolms' ) . '：' . esc_html( number_format( $iihlms_item_subscription_price ) ) . esc_html__( '円', 'imaoikiruhitolms' ) . '（' . esc_html__( '税込', 'imaoikiruhitolms' ) . '：' . esc_html( number_format( $iihlms_item_subscription_price_tax_included ) ) . esc_html__( '円', 'imaoikiruhitolms' ) . '）';
				echo esc_html__( '請求間隔', 'imaoikiruhitolms' ) . '：' . esc_html( $iihlms_item_subscription_interval_count ) . esc_html( $this->get_interval_unit_for_disp_long( $iihlms_item_subscription_interval_unit ) );
				if ( $iihlms_item_subscription_total_cycles > 0 ) {
					echo '<br>';
					echo esc_html__( '合計請求数', 'imaoikiruhitolms' ) . '：' . esc_html( $iihlms_item_subscription_total_cycles );
				}

				if ( ( 'paypal-subscription-registration-completed' === $row->order_status ) || ( 'stripe-subscription-registration-completed' === $row->order_status ) ) {
					echo '<br>';
					echo '<form method="post" action="' . esc_url( home_url( '/' . IIHLMS_SUBSCRIPTIONCANCELLATION_NAME . '/' ) ) . '">';
					echo '<input type="hidden" name="iihlms-subscription-cancel-id" value="' . esc_attr( $row->item_id ) . '">';
					echo '<input type="hidden" name="action-type" value="iihlms-orderhistory-cancel">';
					wp_nonce_field( 'iihlms-subscription-cancel-csrf-action', 'iihlms-subscription-cancel-csrf' );
					echo '<input type="submit" id="iihlms-orderhistory-submit-' . esc_attr( $row->item_id ) . '" value="' . esc_html__( 'サブスクリプションを解約する', 'imaoikiruhitolms' ) . '" class="btn btn-outline-secondary">';
					echo '</form>';
				}
			} else {
				echo esc_html__( '価格', 'imaoikiruhitolms' ) . '：' . esc_html( number_format( $row->price + $row->tax ) ) . esc_html__( '円', 'imaoikiruhitolms' );
			}

			echo '</li>';
			echo '</ul>';
		}
		if ( 0 === $result_num ) {
			echo '<p>';
			echo esc_html__( 'データがありません', 'imaoikiruhitolms' );
			echo '</p>';
		}
		echo '</div>';
		echo '</div>';

		echo '<div class="footer-home-btn-wrap">';
		echo '<div class="text-center"><button type="button" class="btn btn-mypage-content" onclick="location.href=\'' . esc_url( get_home_url() ) . '/\'"><div class="btn-mypage-text"><i class="bi bi-house btn-mypage-icon"></i> HOME</div></button></div>';
		echo '</div>';
	}

	/**
	 * サブスクリプション解約ページ
	 *
	 * @return void
	 */
	public function iihlms_subscriptioncancellation_content() {
		global $wpdb;

		if ( ! isset( $_POST['action-type'] ) ) {
			$this->show_err_iihlms_subscriptioncancellation_content( esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' ) );
			exit;
		}

		if ( 'iihlms-orderhistory-cancel' === $_POST['action-type'] ) {
			if ( ! isset( $_POST['iihlms-subscription-cancel-csrf'] ) ) {
				$this->show_err_iihlms_subscriptioncancellation_content( esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' ) );
				exit;
			}
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['iihlms-subscription-cancel-csrf'] ) ), 'iihlms-subscription-cancel-csrf-action' ) ) {
				$this->show_err_iihlms_subscriptioncancellation_content( esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' ) );
				exit;
			}
			if ( ! isset( $_POST['iihlms-subscription-cancel-csrf'] ) ) {
				$this->show_err_iihlms_subscriptioncancellation_content( esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' ) );
				exit;
			}
			if ( ! isset( $_POST['iihlms-subscription-cancel-id'] ) ) {
				$this->show_err_iihlms_subscriptioncancellation_content( esc_html__( 'IDが指定されていません。', 'imaoikiruhitolms' ) );
				exit;
			}
			$item_id = sanitize_text_field( wp_unslash( $_POST['iihlms-subscription-cancel-id'] ) );
		}

		echo '<div class="container container-width">';
		echo '<div class="row">';
		echo '<h2 class="title-text">' . esc_attr( get_the_title() ) . '</h2>';

		$order_table           = $wpdb->prefix . 'iihlms_order';
		$order_cart_table      = $wpdb->prefix . 'iihlms_order_cart';
		$order_cart_meta_table = $wpdb->prefix . 'iihlms_order_cart_meta';

		$status_search_array = array(
			// 'paypal-payment-completed',
			'paypal-subscription-registration-completed',
			// 'manual-deletion-by-administrator',
			// 'manual-assignment-by-administrator',
			// 'paypal-subscription-cancelled',
			// 'free-completed',
			// 'stripe-payment-completed',
			'stripe-subscription-registration-completed',
			// 'stripe-subscription-cancelled',
		);
		$search_date_time = '0000-00-00 00:00:00';

		$user    = wp_get_current_user();
		$results = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT *
				FROM %1s
				WHERE 
					user_id = %d
					AND item_id = %s
					AND expiration_date_time = %s
					AND order_status IN ( %s, %s )
				',
				$order_table,
				$user->ID,
				$item_id,
				$search_date_time,
				$status_search_array[0],
				$status_search_array[1],
			)
		);
		$num     = $wpdb->num_rows;
		if ( 1 !== $num ) {
			$this->show_err_iihlms_subscriptioncancellation_content( esc_html__( 'データに異常があります。処理を中断します。', 'imaoikiruhitolms' ) );
			exit;
		}
		foreach ( $results as $result ) {
			$order_id        = $result->order_id;
			$item_name       = $result->item_name;
			$order_key       = $result->order_key;
			$order_date_time = $result->order_date_time;
			$order_cart_id   = $result->order_cart_id;

			$iihlms_item_subscription_trial_price          = '';
			$iihlms_item_subscription_trial_tax            = '';
			$iihlms_item_subscription_trial_interval_count = '';
			$iihlms_item_subscription_trial_interval_unit  = '';
			$iihlms_item_subscription_price                = '';
			$iihlms_item_subscription_tax                  = '';
			$iihlms_item_subscription_interval_count       = '';
			$iihlms_item_subscription_interval_unit        = '';
			$iihlms_item_subscription_total_cycles         = '';

			if ( 'paypal-subscription' === $result->payment_name ) {
				$iihlms_item_subscription_trial_price          = $this->get_post_meta_order_cart_meta_table( $result->order_cart_id, 'iihlms_item_subscription_trial_price_paypal' );
				$iihlms_item_subscription_trial_tax            = $this->get_post_meta_order_cart_meta_table( $result->order_cart_id, 'iihlms_item_subscription_trial_tax_paypal' );
				$iihlms_item_subscription_trial_interval_count = $this->get_post_meta_order_cart_meta_table( $result->order_cart_id, 'iihlms_item_subscription_trial_interval_count_paypal' );
				$iihlms_item_subscription_trial_interval_unit  = $this->get_post_meta_order_cart_meta_table( $result->order_cart_id, 'iihlms_item_subscription_trial_interval_unit_paypal' );
				$iihlms_item_subscription_price                = $this->get_post_meta_order_cart_meta_table( $result->order_cart_id, 'iihlms_item_subscription_price_paypal' );
				$iihlms_item_subscription_tax                  = $this->get_post_meta_order_cart_meta_table( $result->order_cart_id, 'iihlms_item_subscription_tax_paypal' );
				$iihlms_item_subscription_interval_count       = $this->get_post_meta_order_cart_meta_table( $result->order_cart_id, 'iihlms_item_subscription_interval_count_paypal' );
				$iihlms_item_subscription_interval_unit        = $this->get_post_meta_order_cart_meta_table( $result->order_cart_id, 'iihlms_item_subscription_interval_unit_paypal' );
				$iihlms_item_subscription_total_cycles         = $this->get_post_meta_order_cart_meta_table( $result->order_cart_id, 'iihlms_item_subscription_total_cycles_paypal' );
			}
			if ( 'stripe-subscription' === $result->payment_name ) {
				$iihlms_item_subscription_trial_price          = $this->get_post_meta_order_cart_meta_table( $result->order_cart_id, 'iihlms_item_subscription_trial_price_stripe' );
				$iihlms_item_subscription_trial_tax            = $this->get_post_meta_order_cart_meta_table( $result->order_cart_id, 'iihlms_item_subscription_trial_tax_stripe' );
				$iihlms_item_subscription_trial_interval_count = $this->get_post_meta_order_cart_meta_table( $result->order_cart_id, 'iihlms_item_subscription_trial_interval_count_stripe' );
				$iihlms_item_subscription_trial_interval_unit  = $this->get_post_meta_order_cart_meta_table( $result->order_cart_id, 'iihlms_item_subscription_trial_interval_unit_stripe' );
				$iihlms_item_subscription_price                = $this->get_post_meta_order_cart_meta_table( $result->order_cart_id, 'iihlms_item_subscription_price_stripe' );
				$iihlms_item_subscription_tax                  = $this->get_post_meta_order_cart_meta_table( $result->order_cart_id, 'iihlms_item_subscription_tax_stripe' );
				$iihlms_item_subscription_interval_count       = $this->get_post_meta_order_cart_meta_table( $result->order_cart_id, 'iihlms_item_subscription_interval_count_stripe' );
				$iihlms_item_subscription_interval_unit        = $this->get_post_meta_order_cart_meta_table( $result->order_cart_id, 'iihlms_item_subscription_interval_unit_stripe' );
				$iihlms_item_subscription_total_cycles         = $this->get_post_meta_order_cart_meta_table( $result->order_cart_id, 'iihlms_item_subscription_total_cycles_stripe' );
			}
		}
		$iihlms_item_subscription_trial_price_tax_included = $iihlms_item_subscription_trial_price + $iihlms_item_subscription_trial_tax;
		echo '<p>';
		echo esc_html( $item_name );
		echo '</p>';
		echo '<p>';
		echo esc_html__( '申込日', 'imaoikiruhitolms' ) . '：';
		$date = new DateTimeImmutable( $order_date_time );
		echo esc_html( $date->format( $this->specify_date_format ) );
		echo '</p>';
		echo '<p>';
		echo esc_html__( 'トライアル価格', 'imaoikiruhitolms' ) . '：' . esc_html( $iihlms_item_subscription_trial_price ) . esc_html__( '円', 'imaoikiruhitolms' ) . '（' . esc_html__( '税込', 'imaoikiruhitolms' ) . '：' . esc_html( $iihlms_item_subscription_trial_price_tax_included ) . esc_html__( '円', 'imaoikiruhitolms' ) . '）';
		echo esc_html__( 'トライアル期間', 'imaoikiruhitolms' ) . '：' . esc_html( $iihlms_item_subscription_trial_interval_count ) . esc_html( $this->get_interval_unit_for_disp_long( esc_html( $iihlms_item_subscription_trial_interval_unit ) ) );
		echo '</p>';
		echo '<p>';
		echo esc_html__( '請求開始日', 'imaoikiruhitolms' ) . '：';
		$date               = new DateTimeImmutable( $order_date_time );
		$billing_start_date = $this->get_billing_start_date( $date, $iihlms_item_subscription_trial_interval_unit, $iihlms_item_subscription_trial_interval_count );
		echo esc_html( $billing_start_date->format( $this->specify_date_format ) );
		echo '<br>';
		$iihlms_item_subscription_price_tax_included = $iihlms_item_subscription_price + $iihlms_item_subscription_tax;
		echo esc_html__( '価格', 'imaoikiruhitolms' ) . '：' . esc_html( $iihlms_item_subscription_price ) . esc_html__( '円', 'imaoikiruhitolms' ) . '（' . esc_html__( '税込', 'imaoikiruhitolms' ) . '：' . esc_html( $iihlms_item_subscription_price_tax_included ) . esc_html__( '円', 'imaoikiruhitolms' ) . '）';
		echo esc_html__( '請求間隔', 'imaoikiruhitolms' ) . '：' . esc_html( $iihlms_item_subscription_interval_count ) . esc_html( $this->get_interval_unit_for_disp_long( $iihlms_item_subscription_interval_unit ) );
		if ( $iihlms_item_subscription_total_cycles > 0 ) {
			echo '<br>';
			echo esc_html__( '合計請求数', 'imaoikiruhitolms' ) . '：' . esc_html( $iihlms_item_subscription_total_cycles );
		}
		echo '</p>';
		echo '<p>';
		echo esc_html__( '次回請求日', 'imaoikiruhitolms' ) . '：';
		$billing_date = $this->get_next_billing_date( $billing_start_date, $iihlms_item_subscription_interval_unit, $iihlms_item_subscription_interval_count );
		echo esc_html( $billing_date->format( $this->specify_date_format ) );
		echo '</p>';
		echo '<form method="post" name="form-iihlms-subscription-cancellation" id="form-iihlms-subscription-cancellation" action="' . esc_url( '/' . IIHLMS_SUBSCRIPTIONCANCELLATION_NAME ) . '/">';
		echo '<input type="hidden" name="action-type" id="action-type" value="iihlms-subscription-cancellation">';
		echo '<input type="hidden" name="subscription-item-id" id="subscription-item-id" value="' . esc_attr( $item_id ) . '">';
		wp_nonce_field( 'iihlms-subscription-cancellation-csrf-action', 'iihlms-subscription-cancellation-csrf' );
		if ( 'paypal-subscription' === $result->payment_name ) {
			echo '<input type="hidden" name="action-type-subscription-cancellation" id="action-type-subscription-cancellation" value="iihlms-paypal-subscription-cancellation">';
		}
		if ( 'stripe-subscription' === $result->payment_name ) {
			echo '<input type="hidden" name="action-type-subscription-cancellation" id="action-type-subscription-cancellation" value="iihlms-stripe-subscription-cancellation">';
		}
		echo '<input type="submit" name="iihlms-subscription-cancellation-submit" id="iihlms-subscription-cancellation-submit" class="iihlms-regist-button" value="' . esc_html__( '解約実行', 'imaoikiruhitolms' ) . '" class="btn btn-primary">';
		echo '</form>';

		echo '</div>';
		echo '</div>';
		echo '<div class="iihlms-spacer-white"></div>';

		echo '<div class="footer-home-btn-wrap">';
		echo '<div class="text-center"><button type="button" class="btn btn-mypage-content" onclick="location.href=\'' . esc_url( get_home_url() ) . '/\'"><div class="btn-mypage-text"><i class="bi bi-house btn-mypage-icon"></i> HOME</div></button></div>';
		echo '</div>';
	}
	/**
	 * サブスクリプション解約ページエラー表示
	 *
	 * @param string $err_msg エラーメッセージ.
	 * @return void
	 */
	public function show_err_iihlms_subscriptioncancellation_content( $err_msg ) {
		echo '<div class="container container-width justify-content-center">';
		echo '<div class="row">';
		echo '<p class="mt-3">';
		echo esc_html( $err_msg );
		echo '</p>';
		echo '<p><button class="btn btn-primary" onclick="history.back(-1)">' . esc_html__( '戻る', 'imaoikiruhitolms' ) . '</button></p>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		get_template_part( 'footer' );
	}
	/**
	 * サブスクリプション解約完了ページ
	 *
	 * @return void
	 */
	public function iihlms_subscriptioncancellationresult_content() {
		global $wpdb;

		echo '<div class="container container-width">';
		echo '<div class="row">';
		echo '<h2 class="title-text">' . esc_attr( get_the_title() ) . '</h2>';

		echo '<p>' . esc_html__( 'サブスクリプションを解約いたしました。', 'imaoikiruhitolms' ) . '</p>';

		echo '</div>';
		echo '</div>';

		echo '<div class="iihlms-spacer-white"></div>';
		echo '<div class="footer-home-btn-wrap">';
		echo '<div class="text-center"><button type="button" class="btn btn-mypage-content" onclick="location.href=\'' . esc_url( get_home_url() ) . '/\'"><div class="btn-mypage-text"><i class="bi bi-house btn-mypage-icon"></i> HOME</div></button></div>';
		echo '</div>';
	}
	/**
	 * テスト結果一覧ページ
	 *
	 * @return void
	 */
	public function iihlms_test_result_list_content() {
		echo esc_html( apply_filters( 'iihlms_addition_iihlms_test_result_list_content', '' ) );
	}
	/**
	 * テスト結果ページ
	 *
	 * @return void
	 */
	public function iihlms_test_result_content() {
		echo esc_html( apply_filters( 'iihlms_addition_iihlms_tests_result_page_content', '' ) );
	}
	/**
	 * 回答の詳細を表示ページ
	 *
	 * @return void
	 */
	public function iihlms_test_result_view_answer_details_content() {
		echo esc_html( apply_filters( 'iihlms_addition_iihlms_tests_result_view_answer_details_page_content', '' ) );
	}
	/**
	 * Stripe Webhook
	 *
	 * @return void
	 */
	public function iihlms_receive_stripe_webhook() {
		echo esc_html( apply_filters( 'iihlms_addition_iihlms_receive_stripe_webhook', '' ) );
	}
	/**
	 * PayPal Webhook
	 *
	 * @return void
	 */
	public function iihlms_receive_paypal_webhook() {
		echo esc_html( apply_filters( 'iihlms_addition_iihlms_receive_paypal_webhook', '' ) );
	}

	/**
	 * PayPal Webhook list
	 *
	 * @return void
	 */
	public function iihlms_paypal_list_webhook() {
		echo esc_html( apply_filters( 'iihlms_addition_iihlms_paypal_list_webhook', '' ) );
	}
	/**
	 * PayPal Webhook delete
	 *
	 * @param string $webhook_id Webhook id.
	 * @return void
	 */
	public function iihlms_paypal_delete_webhook( $webhook_id ) {
		echo esc_html( apply_filters( 'iihlms_addition_iihlms_paypal_delete_webhook', $webhook_id ) );
	}
	/**
	 * PayPal Webhook Show details
	 *
	 * @param string $webhook_id Webhook id.
	 * @return void
	 */
	public function iihlms_paypal_show_details_webhook( $webhook_id ) {
		echo esc_html( apply_filters( 'iihlms_addition_iihlms_paypal_show_details_webhook', $webhook_id ) );
	}
	/**
	 * PayPalのWebhook IDとURLのリストを取得します。
	 *
	 * この関数は、PayPalのAPIを使用して登録されているすべてのWebhookのIDとURLを取得し、
	 * その配列を返します。エラーハンドリングも含まれています。
	 *
	 * @return array|false WebhookのIDとURLの配列、または失敗した場合はfalseを返します。
	 */
	public function iihlms_get_paypal_webhooks() {
		$access_token_paypal = $this->get_accesstoken_paypal();
		if ( '' === $access_token_paypal ) {
			return false;
		}

		$base_url = $this->get_baseurl_paypal();

		$url = $base_url . '/v1/notifications/webhooks';

		// List webhooks.
		$args = array(
			'method'  => 'GET',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $access_token_paypal,
			),
		);

		$response = wp_remote_get( $url, $args );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		return $body['webhooks'] ?? false;
	}
	/**
	 * 指定されたWebhook IDがPayPalで有効かどうかを確認します.
	 *
	 * この関数は、指定されたWebhook IDがPayPalのAPIで有効かどうかをチェックします.
	 * PayPalのアクセストークンを取得し、Webhookの詳細を取得して、IDが一致するかどうかを確認します.
	 *
	 * @param string $webhook_id 確認するWebhook ID.
	 * @return bool 指定されたWebhook IDが有効な場合はtrue、無効な場合はfalseを返します.
	 */
	public function iihlms_verify_paypal_webhook_id( $webhook_id ) {
		$access_token_paypal = $this->get_accesstoken_paypal();
		if ( '' === $access_token_paypal ) {
			return false;
		}
		$base_url = $this->get_baseurl_paypal();

		$url = $base_url . '/v1/notifications/webhooks/' . $webhook_id;
		$args = array(
			'method'  => 'GET',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $access_token_paypal,
			),
		);

		$response = wp_remote_get( $url, $args );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		$webhook_id_exists   = isset( $body['id'] );
		$webhook_id_matches  = ( $body['id'] === $webhook_id );
		$webhook_url_matches = ( get_home_url() . '/?iihlms-api=iihlms-api-paypal' === $body['url'] );

		if ( $webhook_id_exists && $webhook_id_matches && $webhook_url_matches ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * ページ制御
	 *
	 * @return void
	 */
	public function page_controller() {
		global $post;
		global $wpdb;

		$user = wp_get_current_user();

		if ( ! empty( $_SERVER['REQUEST_URI'] ) ) {
			$url = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );
		}

		// Stripe決済OK.
		if ( 'iihlms-api-stripe-onetime' === get_query_var( 'iihlms-api' ) ) {
			echo esc_html( apply_filters( 'iihlms_addition_page_controller_stripeonetime_api', '' ) );
		}

		// Stripe Subscription.
		if ( 'iihlms-api-stripe-subscription' === get_query_var( 'iihlms-api' ) ) {
			echo esc_html( apply_filters( 'iihlms_addition_page_controller_stripesubscription_api', '' ) );
		}

		// Stripe Webhook.
		if ( 'iihlms-api-stripe' === get_query_var( 'iihlms-api' ) ) {
			$this->iihlms_receive_stripe_webhook();
			exit;
		}

		// 証明書出力.
		if ( isset( $_POST['iihlms-certificate-pdf-output'] ) ) {
			echo esc_html( apply_filters( 'iihlms_addition_page_controller_certificate_pdf_output', '' ) );
		}

		// テスト開始.
		if ( isset( $_POST['iihlms-test-start'] ) ) {
			echo esc_html( apply_filters( 'iihlms_addition_page_controller_test_start', '' ) );
		}

		// テスト中断.
		if ( isset( $_POST['iihlms-test-abort'] ) ) {
			echo esc_html( apply_filters( 'iihlms_addition_page_controller_test_abort', '' ) );
		}

		// テスト結果受け取り.
		if ( isset( $_POST['iihlms-test-submit'] ) ) {
			echo esc_html( apply_filters( 'iihlms_addition_page_controller_test_receive', '' ) );
		}

		// 新規ユーザー登録.
		if ( isset( $_POST['accepting_userregist_email'] ) ) {
			if ( ! isset( $_POST['iihlms_accepting_user_regist'] ) ) {
				echo esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' );
				exit;
			} else {
				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['iihlms_accepting_user_regist'] ) ), 'iihlms_accepting_user_regist_action' ) ) {
					echo esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' );
					exit;
				}
			}
			$ret = $this->iihlms_accept_user_regist();
			if ( true === $ret ) {
				wp_safe_redirect( add_query_arg( array( 'iihlmsacceptmail' => send ), get_home_url() . '/' . IIHLMS_ACCEPTINGUSERREGISTRATION_NAME . '/' ) );
				exit;
			} else {
				exit;
			}
		}

		// 新規ユーザー登録.
		if ( isset( $_POST['iihlms_user_regist_submit'] ) ) {
			if ( isset( $_POST['iihlms_user_regist'] ) ) {
				if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['iihlms_user_regist'] ) ), 'iihlms_user_regist_action' ) ) {
					$this->iihlms_user_signup();
					exit;
				}
			}
		}
		// ユーザー情報更新.
		if ( isset( $_POST['iihlms-userpage-submit'] ) ) {
			$this->iihlms_userpage_update();
			exit;
		}

		// ペイパルカード決済OK.
		if ( isset( $_POST['iihlmsapplyorderid'] ) &&
			isset( $_POST['iihlmsapplytransactionid'] ) &&
			( '' !== $_POST['iihlmsapplyorderid'] ) &&
			( '' !== $_POST['iihlmsapplytransactionid'] )
		) {
			$this->iihlms_paypal_payment_success( sanitize_text_field( wp_unslash( $_POST['iihlmsapplyorderid'] ) ), sanitize_text_field( wp_unslash( $_POST['iihlmsapplytransactionid'] ) ) );
			exit;
		}

		// ペイパルサブスクリプション決済OK.
		if ( isset( $_POST['iihlmspaypalsubscriptionid'] ) &&
		isset( $_POST['iihlmspaypalorderid'] ) &&
		isset( $_POST['iihlmspaypalplanid'] )
		) {
			echo esc_html( apply_filters( 'iihlms_addition_page_controller_paypalsubscription_settlement_ok', '' ) );
			exit;
		}
		// サブスクリプション解約.
		if ( isset( $_POST['iihlms-subscription-cancellation-submit'] ) &&
		isset( $_POST['action-type'] ) &&
		isset( $_POST['action-type-subscription-cancellation'] )
		) {
			if ( 'iihlms-paypal-subscription-cancellation' === sanitize_text_field( wp_unslash( $_POST['action-type-subscription-cancellation'] ) ) ) {
				echo esc_html( apply_filters( 'iihlms_addition_page_controller_paypalsubscription_cancellation_ok', '' ) );
			} elseif ( 'iihlms-stripe-subscription-cancellation' === sanitize_text_field( wp_unslash( $_POST['action-type-subscription-cancellation'] ) ) ) {
				echo esc_html( apply_filters( 'iihlms_addition_page_controller_stripesubscription_cancellation_ok', '' ) );
			}
			exit;
		}

		// PayPal Webhook.
		if ( 'iihlms-api-paypal' === get_query_var( 'iihlms-api' ) ) {
			$this->iihlms_receive_paypal_webhook();
			exit;
		}

		// 無料講座.
		if ( isset( $_POST['iihlms-apply-itemid'] ) &&
		isset( $_POST['iihlms-apply-type'] )
		) {
			$this->iihlms_apply_free_course( sanitize_text_field( wp_unslash( $_POST['iihlms-apply-itemid'] ) ), sanitize_text_field( wp_unslash( $_POST['iihlms-apply-type'] ) ) );
			exit;
		}

		if ( is_front_page() ) {
			if ( file_exists( get_stylesheet_directory() . '/iihlms_templates/iihlms-homepage.php' ) ) {
				include get_stylesheet_directory() . '/iihlms_templates/iihlms-homepage.php';
				exit;
			}
		}

		if ( true === $this->is_applypage( $url ) ) {
			if ( file_exists( get_stylesheet_directory() . '/iihlms_templates/iihlms-apply-page.php' ) ) {
				include get_stylesheet_directory() . '/iihlms_templates/iihlms-apply-page.php';
				exit;
			}
		}

		if ( true === $this->is_applyresultpage( $url ) ) {
			if ( file_exists( get_stylesheet_directory() . '/iihlms_templates/iihlms-applyresult-page.php' ) ) {
				include get_stylesheet_directory() . '/iihlms_templates/iihlms-applyresult-page.php';
				exit;
			}
		}

		if ( true === $this->is_userpage( $url ) ) {
			if ( file_exists( get_stylesheet_directory() . '/iihlms_templates/iihlms-userpage.php' ) ) {
				include get_stylesheet_directory() . '/iihlms_templates/iihlms-userpage.php';
				exit;
			}
		}

		if ( true === $this->is_userregistpage( $url ) ) {
			if ( file_exists( get_stylesheet_directory() . '/iihlms_templates/iihlms-userregistpage.php' ) ) {
				include get_stylesheet_directory() . '/iihlms_templates/iihlms-userregistpage.php';
				exit;
			}
		}

		if ( true === $this->is_acceptinguserregistpage( $url ) ) {
			if ( file_exists( get_stylesheet_directory() . '/iihlms_templates/iihlms-acceptinguserregistpage.php' ) ) {
				include get_stylesheet_directory() . '/iihlms_templates/iihlms-acceptinguserregistpage.php';
				exit;
			}
		}

		if ( true === $this->is_orderhistorypage( $url ) ) {
			if ( file_exists( get_stylesheet_directory() . '/iihlms_templates/iihlms-orderhistorypage.php' ) ) {
				include get_stylesheet_directory() . '/iihlms_templates/iihlms-orderhistorypage.php';
				exit;
			}
		}

		if ( true === $this->is_subscriptioncancellationpage( $url ) ) {
			if ( file_exists( get_stylesheet_directory() . '/iihlms_templates/iihlms-subscriptioncancellationpage.php' ) ) {
				include get_stylesheet_directory() . '/iihlms_templates/iihlms-subscriptioncancellationpage.php';
				exit;
			}
		}

		if ( true === $this->is_subscriptioncancellationresultpage( $url ) ) {
			if ( file_exists( get_stylesheet_directory() . '/iihlms_templates/iihlms-subscriptioncancellationresultpage.php' ) ) {
				include get_stylesheet_directory() . '/iihlms_templates/iihlms-subscriptioncancellationresultpage.php';
				exit;
			}
		}

		if ( true === $this->is_testresultlistpage( $url ) ) {
			if ( file_exists( get_stylesheet_directory() . '/iihlms_templates/iihlms-test-result-list-page.php' ) ) {
				include get_stylesheet_directory() . '/iihlms_templates/iihlms-test-result-list-page.php';
				exit;
			}
		}

		if ( true === $this->is_testresultpage( $url ) ) {
			if ( file_exists( get_stylesheet_directory() . '/iihlms_templates/iihlms-test-result-page.php' ) ) {
				include get_stylesheet_directory() . '/iihlms_templates/iihlms-test-result-page.php';
				exit;
			}
		}

		if ( true === $this->is_testresultviewanswerdetailspage( $url ) ) {
			if ( file_exists( get_stylesheet_directory() . '/iihlms_templates/iihlms-test-result-view-answer-details-page.php' ) ) {
				include get_stylesheet_directory() . '/iihlms_templates/iihlms-test-result-view-answer-details-page.php';
				exit;
			}
		}

		if ( is_singular( 'iihlms_items' ) ) {
			if ( file_exists( get_stylesheet_directory() . '/iihlms_templates/iihlms-items-page.php' ) ) {
				include get_stylesheet_directory() . '/iihlms_templates/iihlms-items-page.php';
				exit;
			}
		}

		if ( is_singular( 'iihlms_courses' ) ) {
			if ( file_exists( get_stylesheet_directory() . '/iihlms_templates/iihlms-courses-page.php' ) ) {
				include get_stylesheet_directory() . '/iihlms_templates/iihlms-courses-page.php';
				exit;
			}
		}
		if ( is_singular( 'iihlms_lessons' ) ) {
			if ( file_exists( get_stylesheet_directory() . '/iihlms_templates/iihlms-lessons-page.php' ) ) {
				include get_stylesheet_directory() . '/iihlms_templates/iihlms-lessons-page.php';
				exit;
			}
		}

		if ( is_singular( 'iihlms_tests' ) ) {
			if ( file_exists( get_stylesheet_directory() . '/iihlms_templates/iihlms-tests-page.php' ) ) {
				include get_stylesheet_directory() . '/iihlms_templates/iihlms-tests-page.php';
				exit;
			}
		}
	}

	/**
	 * Stripeの公開キー取得
	 *
	 * @return string
	 */
	public function get_public_key_stripe() {
		$iihlms_public_key_stripe = get_option( 'iihlms_public_key_stripe', '' );
		return $iihlms_public_key_stripe;
	}
	/**
	 * Stripeのシークレットキー取得
	 *
	 * @return string
	 */
	public function get_secret_key_stripe() {
		$iihlms_secret_key_stripe = get_option( 'iihlms_secret_key_stripe', '' );
		return $iihlms_secret_key_stripe;
	}
	/**
	 * ペイパルのアクセストークン取得
	 *
	 * @return string
	 */
	public function get_accesstoken_paypal() {

		$client_id = $this->get_clientid_paypal();
		if ( '' === $client_id ) {
			return '';
		}
		$secret_id = $this->get_secretid_paypal();
		if ( '' === $secret_id ) {
			return '';
		}
		$base_url = $this->get_baseurl_paypal();

		$url  = $base_url . '/v1/oauth2/token';
		$args = array(
			'headers' => array(
				'Content-Type'  => 'application/x-www-form-urlencoded;application/json',
				'Accept'        => 'application/json',
				'Authorization' => 'Basic ' . base64_encode( $client_id . ':' . $secret_id ),
			),
			'body'    => array( 'grant_type' => 'client_credentials' ),
		);

		$response = wp_remote_post( $url, $args );

		if ( is_wp_error( $response ) ) {
			return '';
		}

		$response_json = json_decode( wp_remote_retrieve_body( $response ), true );
		$access_token  = '';
		if ( isset( $response_json['access_token'] ) ) {
			$access_token = $response_json['access_token'];
		}

		return $access_token;
	}

	/**
	 * ペイパルのクライアントトークン取得
	 *
	 * @param string $access_token アクセストークン.
	 * @return string
	 */
	public function get_clienttoken_paypal( $access_token ) {
		$base_url = $this->get_baseurl_paypal();

		// Client Tokenを取得.
		$param = array(
			'headers' => array(
				'Content-Type'    => 'application/json;charset=utf-8',
				'Authorization'   => 'Bearer ' . $access_token,
				'Accept-Language' => 'en_US',
			),
		);

		$url      = $base_url . '/v1/identity/generate-token';
		$response = wp_remote_post( $url, $param );

		if ( is_wp_error( $response ) ) {
			return '';
		}

		$response_data = json_decode( wp_remote_retrieve_body( $response ) );

		$client_token = '';
		if ( isset( $response_data->client_token ) ) {
			$client_token = $response_data->client_token;
		}
		return $client_token;
	}

	/**
	 * Stripeのボタン表示（1回払い）
	 *
	 * @param string $item_id 講座ID.
	 * @return void
	 */
	public function show_onetime_stripe( $item_id ) {
		echo esc_html( apply_filters( 'iihlms_addition_show_onetime_stripe', $item_id ) );
	}
	/**
	 * Stripeのボタン表示（サブスクリプション）
	 *
	 * @param string $item_id 講座ID.
	 * @return void
	 */
	public function show_subscription_stripe( $item_id ) {
		echo esc_html( apply_filters( 'iihlms_addition_show_subscription_stripe', $item_id ) );
	}
	/**
	 * ペイパルのボタン表示（1回払い）
	 *
	 * @param string $item_id 講座ID.
	 * @return void
	 */
	public function show_onetime_paypal( $item_id ) {

		$access_token_paypal = $this->get_accesstoken_paypal();
		if ( '' === $access_token_paypal ) {
			exit;
		}
		$client_token = $this->get_clienttoken_paypal( $access_token_paypal );

		$client_id = $this->get_clientid_paypal();
		if ( '' === $client_id ) {
			exit;
		}

		echo '<form name="apply_form" id="apply_form" action="" method="post" class="iihlms-form">';
		echo '<input type="hidden" name="apply_item_id" value="' . esc_attr( $item_id ) . '">';
		wp_nonce_field( 'iihlms-apply-page-csrf-action', 'iihlms-apply-page-csrf' );
		echo '<input type="hidden" name="iihlmsapplyorderid" value="">';
		echo '<input type="hidden" name="iihlmsapplytransactionid" value="">';

		echo '<script src="https://www.paypal.com/sdk/js?components=buttons,hosted-fields&currency=JPY&client-id=' . esc_attr( $client_id ) . '" data-client-token="' . esc_attr( $client_token ) . '"></script>'	// phpcs:ignore
		?>

		<div id="paypal-button-container"></div>

		<!-- Implementation -->
		<script>
		let orderId;

		paypal.Buttons({
			style: {
				layout: 'vertical',
				label: 'paypal',
			},

			createOrder: function(data, actions) {
				let params = new URLSearchParams();
				params.append( 'action', 'create_order_paypal_func' );
				params.append( 'itemid', '<?php echo esc_html( $item_id ); ?>' );
				params.append( 'nonce', '<?php echo esc_html( wp_create_nonce( 'iihlms-ajax-nonce-paypal-onetime' ) ); ?>' );
				return fetch( wp_ajax_root, {
					method: 'post',
					body: params
				}).then(function(res) {
					return res.json();
				}).then(function(orderData) {
					return orderData.id;
				});
			},

			// finalize the transaction
			onApprove: function(data, actions) {
				let params = new URLSearchParams();
				params.append( 'action', 'checkout_paypal_func' );
				params.append( 'orderid', data.orderID );
				params.append( 'nonce', '<?php echo esc_html( wp_create_nonce( 'iihlms-ajax-nonce-paypal-onetime-onapprove' ) ); ?>' );
				return fetch( wp_ajax_root, {
					method: 'post',
					body: params
				}).then(function(res) {
					return res.json();
				}).then(function(orderData) {
					var errorDetail = Array.isArray(orderData.details) && orderData.details[0];

					if (errorDetail && errorDetail.issue === 'INSTRUMENT_DECLINED') {
						return actions.restart();
					}

					if (errorDetail) {
						var msg = 'Sorry, your transaction could not be processed.';
						if (errorDetail.description) msg += '\n\n' + errorDetail.description;
						if (orderData.debug_id) msg += ' (' + orderData.debug_id + ')';
						return alert(msg);
					}

					var transaction = orderData.purchase_units[0].payments.captures[0];

					var apply_form = document.forms.apply_form;
					apply_form.iihlmsapplyorderid.value = data.orderID;
					apply_form.iihlmsapplytransactionid.value = transaction.id;
					apply_form.submit();
				});
			}

		}).render('#paypal-button-container');
		</script>
		<?php
		echo '</form>';
	}

	/**
	 * ペイパルのボタン表示（サブスクリプション）
	 *
	 * @param string $item_id 講座ID.
	 * @return void
	 */
	public function show_subscription_paypal( $item_id ) {
		echo esc_html( apply_filters( 'iihlms_addition_show_subscription_paypal', $item_id ) );
	}

	/**
	 * ペイパルのクライアントIDを取得
	 *
	 * @return string
	 */
	public function get_clientid_paypal() {
		$clientid = get_option( 'iihlms_paypal_clientid', '' );
		return $clientid;
	}

	/**
	 * ペイパルのシークレットIDを取得
	 *
	 * @return string
	 */
	public function get_secretid_paypal() {
		$secretid = get_option( 'iihlms_paypal_secretid', '' );
		return $secretid;
	}

	/**
	 * ペイパルのURLを取得（本番またはサンドボックス）
	 *
	 * @return string
	 */
	public function get_baseurl_paypal() {
		$iihlms_paypal_liveorsandbox = get_option( 'iihlms_paypal_liveorsandbox', 'PayPalSandbox' );

		if ( 'PayPalSandbox' === $iihlms_paypal_liveorsandbox ) {
			$base_url = 'https://api-m.sandbox.paypal.com';
		} else {
			$base_url = 'https://api-m.paypal.com';
		}
		return $base_url;
	}

	/**
	 * 支払い方法の名称取得
	 *
	 * @param string $payment_name 支払い方法.
	 * @return string
	 */
	public function get_payment_name( $payment_name ) {

		$ret = '';
		if ( 'paypal' === $payment_name ) {
			$ret = esc_html__( 'PayPal', 'imaoikiruhitolms' );
			return $ret;
		}
		if ( 'paypal-subscription' === $payment_name ) {
			$ret = esc_html__( 'PayPal サブスクリプション', 'imaoikiruhitolms' );
			return $ret;
		}
		if ( 'stripe' === $payment_name ) {
			$ret = esc_html__( 'Stripe', 'imaoikiruhitolms' );
			return $ret;
		}
		if ( 'stripe-subscription' === $payment_name ) {
			$ret = esc_html__( 'Stripe サブスクリプション', 'imaoikiruhitolms' );
			return $ret;
		}
		if ( 'admin' === $payment_name ) {
			$ret = esc_html__( '管理者', 'imaoikiruhitolms' );
			return $ret;
		}
		if ( 'free' === $payment_name ) {
			$ret = esc_html__( '無料', 'imaoikiruhitolms' );
			return $ret;
		}
		return $ret;
	}

	/**
	 * ステータスの名称取得
	 *
	 * @param string $order_status 注文の状態.
	 * @return string
	 */
	public function get_order_status_name( $order_status ) {

		$ret = '';
		if ( 'unsettled' === $order_status ) {
			$ret = esc_html__( '未決済', 'imaoikiruhitolms' );
			return $ret;
		}
		if ( 'paypal-payment-completed' === $order_status ) {
			$ret = esc_html__( 'PayPal決済完了', 'imaoikiruhitolms' );
			return $ret;
		}
		if ( 'paypal-subscription-registration-completed' === $order_status ) {
			$ret = esc_html__( 'PayPalサブスクリプション登録完了', 'imaoikiruhitolms' );
			return $ret;
		}
		if ( 'stripe-payment-completed' === $order_status ) {
			$ret = esc_html__( 'Stripe決済完了', 'imaoikiruhitolms' );
			return $ret;
		}
		if ( 'stripe-subscription-registration-completed' === $order_status ) {
			$ret = esc_html__( 'Stripe サブスクリプション登録完了', 'imaoikiruhitolms' );
			return $ret;
		}
		if ( 'stripe-subscription-cancelled' === $order_status ) {
			$ret = esc_html__( 'Stripe サブスクリプション解約', 'imaoikiruhitolms' );
			return $ret;
		}
		if ( 'manual-deletion-by-administrator' === $order_status ) {
			$ret = esc_html__( '管理者による手動削除', 'imaoikiruhitolms' );
			return $ret;
		}
		if ( 'manual-assignment-by-administrator' === $order_status ) {
			$ret = esc_html__( '管理者による手動追加', 'imaoikiruhitolms' );
			return $ret;
		}
		if ( 'paypal-subscription-cancelled' === $order_status ) {
			$ret = esc_html__( 'ペイパルサブスクリプション解約', 'imaoikiruhitolms' );
			return $ret;
		}
		if ( 'free-completed' === $order_status ) {
			$ret = esc_html__( '無料申込み完了', 'imaoikiruhitolms' );
			return $ret;
		}
		return $ret;
	}

	/**
	 * 注文番号作成
	 *
	 * @return string
	 */
	public function create_order_key() {
		$day   = current_datetime();
		$day_f = $day->format( 'YmdHis' );
		$rand  = random_int( 10000000, 99999999 );
		$ret   = $day_f . (string) $rand;
		return $ret;
	}

	/**
	 * 新規ユーザー登録
	 *
	 * @return void
	 */
	public function iihlms_user_signup() {
		global $wpdb;

		if ( ! isset( $_POST['iihlms_user_regist'] ) ) {
			$this->show_err_iihlms_user_signup( esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' ) );
			exit;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['iihlms_user_regist'] ) ), 'iihlms_user_regist_action' ) ) {
			$this->show_err_iihlms_user_signup( esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' ) );
			exit;
		}

		$iihlmsregisttoken = get_query_var( 'iihlmsregisttoken' );

		$pre_user_table = $wpdb->prefix . 'iihlms_pre_user';

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT user_email
				FROM %1s
				WHERE 
					urltoken = %s
					AND available = 0
					AND update_datetime > now() - interval 24 HOUR
				',
				$pre_user_table,
				$iihlmsregisttoken,
			)
		);
		$number  = count( $results );
		if ( 1 !== $number ) {
			$this->show_err_iihlms_user_signup( esc_html__( 'このURLは使用できません。', 'imaoikiruhitolms' ) );
			exit;
		}
		foreach ( $results as $result ) {
			$user_email = $result->user_email;
		}
		if ( '' === $user_email ) {
			$this->show_err_iihlms_user_signup( esc_html__( 'メールアドレスに異常があります。', 'imaoikiruhitolms' ) );
			exit;
		}
		// メールアドレス重複チェック.
		$user_id = email_exists( $user_email );
		if ( false !== $user_id ) {
			$this->show_err_iihlms_user_signup( esc_html__( 'すでにメールアドレス「', 'imaoikiruhitolms' ) . $user_email . esc_html__( '」は登録されています。', 'imaoikiruhitolms' ) );
			exit;
		}

		if ( isset( $_POST['signup-user-name'] ) ) {
			$signup_user_name = sanitize_text_field( wp_unslash( $_POST['signup-user-name'] ) );
		} else {
			$signup_user_name = '';
		}
		if ( isset( $_POST['signup-user-password'] ) ) {
			$signup_user_password = sanitize_text_field( wp_unslash( $_POST['signup-user-password'] ) );
		} else {
			$signup_user_password = '';
		}
		if ( isset( $_POST['iihlms-name1'] ) ) {
			$iihlms_name1 = sanitize_text_field( wp_unslash( $_POST['iihlms-name1'] ) );
		} else {
			$iihlms_name1 = '';
		}
		if ( isset( $_POST['iihlms-name2'] ) ) {
			$iihlms_name2 = sanitize_text_field( wp_unslash( $_POST['iihlms-name2'] ) );
		} else {
			$iihlms_name2 = '';
		}
		if ( isset( $_POST['iihlms-zip'] ) ) {
			$iihlms_zip = sanitize_text_field( wp_unslash( $_POST['iihlms-zip'] ) );
		} else {
			$iihlms_zip = '';
		}
		if ( isset( $_POST['iihlms-prefectures'] ) ) {
			$iihlms_prefectures = sanitize_text_field( wp_unslash( $_POST['iihlms-prefectures'] ) );
		} else {
			$iihlms_prefectures = '';
		}
		if ( isset( $_POST['iihlms-address1'] ) ) {
			$iihlms_address1 = sanitize_text_field( wp_unslash( $_POST['iihlms-address1'] ) );
		} else {
			$iihlms_address1 = '';
		}
		if ( isset( $_POST['iihlms-address2'] ) ) {
			$iihlms_address2 = sanitize_text_field( wp_unslash( $_POST['iihlms-address2'] ) );
		} else {
			$iihlms_address2 = '';
		}
		if ( isset( $_POST['iihlms-address3'] ) ) {
			$iihlms_address3 = sanitize_text_field( wp_unslash( $_POST['iihlms-address3'] ) );
		} else {
			$iihlms_address3 = '';
		}
		if ( isset( $_POST['iihlms-company-name'] ) ) {
			$iihlms_company_name = sanitize_text_field( wp_unslash( $_POST['iihlms-company-name'] ) );
		} else {
			$iihlms_company_name = '';
		}
		if ( isset( $_POST['iihlms-tel'] ) ) {
			$iihlms_tel = sanitize_text_field( wp_unslash( $_POST['iihlms-tel'] ) );
		} else {
			$iihlms_tel = '';
		}

		if ( '' === $signup_user_name ) {
			$this->show_err_iihlms_user_signup( esc_html__( 'ユーザー名が未入力です。', 'imaoikiruhitolms' ) );
			exit;
		}
		$user_id = username_exists( $signup_user_name );
		if ( false !== $user_id ) {
			$this->show_err_iihlms_user_signup( esc_html__( 'すでにユーザー名「', 'imaoikiruhitolms' ) . esc_html( $signup_user_name ) . esc_html__( '」は登録されています。', 'imaoikiruhitolms' ) );
			exit;
		}
		if ( '' === $signup_user_password ) {
			$this->show_err_iihlms_user_signup( esc_html__( 'パスワードが未入力です。', 'imaoikiruhitolms' ) );
			exit;
		}
		if ( '' === $iihlms_name1 ) {
			$this->show_err_iihlms_user_signup( esc_html__( '姓が未入力です。', 'imaoikiruhitolms' ) );
			exit;
		}
		if ( '' === $iihlms_name2 ) {
			$this->show_err_iihlms_user_signup( esc_html__( '名が未入力です。', 'imaoikiruhitolms' ) );
			exit;
		}
		if ( '' === $iihlms_zip ) {
			$this->show_err_iihlms_user_signup( esc_html__( '郵便番号が未入力です。', 'imaoikiruhitolms' ) );
			exit;
		}
		if ( '' === $iihlms_prefectures ) {
			$this->show_err_iihlms_user_signup( esc_html__( '都道府県が未入力です。', 'imaoikiruhitolms' ) );
			exit;
		}
		if ( '' === $iihlms_address1 ) {
			$this->show_err_iihlms_user_signup( esc_html__( '市区郡町村が未入力です。', 'imaoikiruhitolms' ) );
			exit;
		}
		if ( '' === $iihlms_address2 ) {
			$this->show_err_iihlms_user_signup( esc_html__( '番地・マンション名などが未入力です。', 'imaoikiruhitolms' ) );
			exit;
		}
		if ( '' === $iihlms_tel ) {
			$this->show_err_iihlms_user_signup( esc_html__( '電話番号が未入力です。', 'imaoikiruhitolms' ) );
			exit;
		}

		// 新規ユーザー登録.
		$userdata = array(
			'user_login' => $signup_user_name,
			'user_pass'  => $signup_user_password,
			'user_email' => $user_email,
			'role'       => 'subscriber',
		);
		$user_id  = wp_insert_user( $userdata );

		// ユーザーの作成に失敗した場合.
		if ( is_wp_error( $user_id ) ) {
			$this->show_err_iihlms_user_signup( $user_id->get_error_code() . $user_id->get_error_message() );
			exit;
		}

		update_user_meta( $user_id, 'iihlms_user_name1', $iihlms_name1 );
		update_user_meta( $user_id, 'iihlms_user_name2', $iihlms_name2 );
		update_user_meta( $user_id, 'iihlms_user_zip', $iihlms_zip );
		update_user_meta( $user_id, 'iihlms_user_prefectures', $iihlms_prefectures );
		update_user_meta( $user_id, 'iihlms_user_address1', $iihlms_address1 );
		update_user_meta( $user_id, 'iihlms_user_address2', $iihlms_address2 );
		update_user_meta( $user_id, 'iihlms_user_address3', $iihlms_address3 );
		update_user_meta( $user_id, 'iihlms_company_name', $iihlms_company_name );
		update_user_meta( $user_id, 'iihlms_user_tel', $iihlms_tel );

		$wpdb->update(
			$pre_user_table,
			array(
				'available' => 1,
			),
			array(
				'urltoken' => $iihlmsregisttoken,
			),
			array(
				'%d',
			),
			array(
				'%s',
			)
		);

		$admin_mail_name                                = $this->get_admin_mailname();
		$admin_mail_address                             = $this->get_admin_mailaddress();
		$iihlms_mailsubject_user_registration_completed = get_option( 'iihlms_mailsubject_user_registration_completed' );
		$iihlms_mailbody_user_registration_completed    = get_option( 'iihlms_mailbody_user_registration_completed' );

		// 予約語の置換.
		$iihlms_mailbody_user_registration_completed = str_replace( '*NAME*', $iihlms_name1 . $iihlms_name2, $iihlms_mailbody_user_registration_completed );
		$iihlms_mailbody_user_registration_completed = str_replace( '*SIGNUP_USER_NAME*', $signup_user_name, $iihlms_mailbody_user_registration_completed );
		$iihlms_mailbody_user_registration_completed = str_replace( '*USER_MAIL*', $user_email, $iihlms_mailbody_user_registration_completed );

		// メール送信.
		$mail_subject = $iihlms_mailsubject_user_registration_completed;
		$mail_body    = $iihlms_mailbody_user_registration_completed;
		$headers[]    = 'From: ' . $admin_mail_name . ' <' . $admin_mail_address . '>';
		$ret          = wp_mail( $user_email, $mail_subject, $mail_body, $headers );
		$ret          = wp_mail( $admin_mail_address, $mail_subject, $mail_body, $headers );

		if ( ! is_user_logged_in() ) {
			// 登録完了後、そのままログイン.
			wp_set_auth_cookie( $user_id, false, is_ssl() );

			// 登録完了ページへ.
			wp_safe_redirect( get_home_url() );
			exit;
		}
		if ( current_user_can( self::CAPABILITY_ADMIN ) ) {
			// ユーザー一覧ページへ.
			wp_safe_redirect( get_home_url() . '/wp-admin/users.php' );
		}
	}

	/**
	 * 新規ユーザー登録エラー表示
	 *
	 * @param string $err_msg エラーメッセージ.
	 * @return void
	 */
	public function show_err_iihlms_user_signup( $err_msg ) {
		get_template_part( 'header' );
		echo '<style>body{background-color: #F2F9FA;}</style>';
		echo '<div class="iihlms-container-regist-wrap">';
		echo '<div class="iihlms-container-regist">';
		echo '<h2 class="iihlms-title-regist">' . esc_html( get_the_title() ) . '</h2>';
		echo '<p class="iihlms-regist-form">';
		echo esc_html( $err_msg );
		echo '</p>';
		echo '<p>';
		echo '<p class="text-center"><button class="iihlms-regist-button" onclick="history.back(-1)">' . esc_html__( '戻る', 'imaoikiruhitolms' ) . '</button></p>';
		echo '</p>';
		echo '</div>';
		echo '</div>';
		get_template_part( 'footer' );
	}

	/**
	 * ユーザー更新
	 *
	 * @return void
	 */
	public function iihlms_userpage_update() {
		global $wpdb;

		if ( ! isset( $_POST['iihlms-userpage-csrf'] ) ) {
			echo esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' );
			exit;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['iihlms-userpage-csrf'] ) ), 'iihlms-userpage-csrf-action' ) ) {
			echo esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' );
			exit;
		}

		if ( isset( $_POST['iihlms-name1'] ) ) {
			$iihlms_name1 = sanitize_text_field( wp_unslash( $_POST['iihlms-name1'] ) );
		} else {
			$iihlms_name1 = '';
		}
		if ( isset( $_POST['iihlms-name2'] ) ) {
			$iihlms_name2 = sanitize_text_field( wp_unslash( $_POST['iihlms-name2'] ) );
		} else {
			$iihlms_name2 = '';
		}
		if ( isset( $_POST['iihlms-zip'] ) ) {
			$iihlms_zip = sanitize_text_field( wp_unslash( $_POST['iihlms-zip'] ) );
		} else {
			$iihlms_zip = '';
		}
		if ( isset( $_POST['iihlms-prefectures'] ) ) {
			$iihlms_prefectures = sanitize_text_field( wp_unslash( $_POST['iihlms-prefectures'] ) );
		} else {
			$iihlms_prefectures = '';
		}
		if ( isset( $_POST['iihlms-address1'] ) ) {
			$iihlms_address1 = sanitize_text_field( wp_unslash( $_POST['iihlms-address1'] ) );
		} else {
			$iihlms_address1 = '';
		}
		if ( isset( $_POST['iihlms-address2'] ) ) {
			$iihlms_address2 = sanitize_text_field( wp_unslash( $_POST['iihlms-address2'] ) );
		} else {
			$iihlms_address2 = '';
		}
		if ( isset( $_POST['iihlms-address3'] ) ) {
			$iihlms_address3 = sanitize_text_field( wp_unslash( $_POST['iihlms-address3'] ) );
		} else {
			$iihlms_address3 = '';
		}
		if ( isset( $_POST['iihlms-company-name'] ) ) {
			$iihlms_company_name = sanitize_text_field( wp_unslash( $_POST['iihlms-company-name'] ) );
		} else {
			$iihlms_company_name = '';
		}
		if ( isset( $_POST['iihlms-tel'] ) ) {
			$iihlms_tel = sanitize_text_field( wp_unslash( $_POST['iihlms-tel'] ) );
		} else {
			$iihlms_tel = '';
		}

		$errcode = '';
		if ( '' === $iihlms_name1 ) {
			$errcode = 'name1';
		}
		if ( '' === $iihlms_name2 ) {
			$errcode = 'name2';
		}
		if ( '' === $iihlms_zip ) {
			$errcode = 'zip';
		}
		if ( '' === $iihlms_prefectures ) {
			$errcode = 'prefectures';
		}
		if ( '' === $iihlms_address1 ) {
			$errcode = 'address1';
		}
		if ( '' === $iihlms_address2 ) {
			$errcode = 'address2';
		}
		if ( '' === $iihlms_tel ) {
			$errcode = 'tel';
		}
		// 画面遷移.
		if ( '' !== $errcode ) {
			wp_safe_redirect( add_query_arg( array( 'iihlmsuserpage' => $errcode ), get_home_url() . '/' . IIHLMS_USERPAGE_NAME . '/' ) );
			exit;
		}

		$user = wp_get_current_user();

		update_user_meta( $user->ID, 'iihlms_user_name1', $iihlms_name1 );
		update_user_meta( $user->ID, 'iihlms_user_name2', $iihlms_name2 );
		update_user_meta( $user->ID, 'iihlms_user_zip', $iihlms_zip );
		update_user_meta( $user->ID, 'iihlms_user_prefectures', $iihlms_prefectures );
		update_user_meta( $user->ID, 'iihlms_user_address1', $iihlms_address1 );
		update_user_meta( $user->ID, 'iihlms_user_address2', $iihlms_address2 );
		update_user_meta( $user->ID, 'iihlms_company_name', $iihlms_company_name );
		update_user_meta( $user->ID, 'iihlms_user_tel', $iihlms_tel );

		$admin_mail_name                    = $this->get_admin_mailname();
		$admin_mail_address                 = $this->get_admin_mailaddress();
		$iihlms_mailsubject_userpage_change = get_option( 'iihlms_mailsubject_userpage_change' );
		$iihlms_mailbody_userpage_change    = get_option( 'iihlms_mailbody_userpage_change' );

		// 予約語の置換.
		$iihlms_mailbody_userpage_change = str_replace( '*NAME*', $iihlms_name1 . $iihlms_name2, $iihlms_mailbody_userpage_change );

		// メール送信.
		$mail_subject = $iihlms_mailsubject_userpage_change;
		$mail_body    = $iihlms_mailbody_userpage_change;
		$headers[]    = 'From: ' . $admin_mail_name . ' <' . $admin_mail_address . '>';
		$ret          = wp_mail( $user->user_email, $mail_subject, $mail_body, $headers );
		$ret          = wp_mail( $admin_mail_address, $mail_subject, $mail_body, $headers );

		$errcode = 'ok';
		wp_safe_redirect( add_query_arg( array( 'iihlmsuserpage' => $errcode ), get_home_url() . '/' . IIHLMS_USERPAGE_NAME . '/' ) );
		exit;
	}
	/**
	 * 新規ユーザー登録
	 *
	 * @return bool
	 */
	public function iihlms_accept_user_regist() {
		global $wpdb;

		if ( ! isset( $_POST['iihlms_accepting_user_regist'] ) ) {
			$this->show_err_iihlms_accept_user_regist( esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' ) );
			return false;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['iihlms_accepting_user_regist'] ) ), 'iihlms_accepting_user_regist_action' ) ) {
			$this->show_err_iihlms_accept_user_regist( esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' ) );
			return false;
		}
		// reCAPTCHA v3.
		if ( true === $this->is_recaptcha_on() ) {
			$secret_key = get_option( 'iihlms_recaptcha_secretkey', '' );
			if ( isset( $_POST['g-recaptcha-response'] ) ) {
				$recaptchatoken = sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ) );
			}
			$response = wp_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $recaptchatoken );

			if ( is_wp_error( $response ) ) {
				$this->show_err_iihlms_accept_user_regist( esc_html__( 'reCAPTCHAの検証中にエラーが発生しました。', 'imaoikiruhitolms' ) );
				return false;
			}

			$body = wp_remote_retrieve_body( $response );
			$chk = json_decode( $body );

			if ( ! isset( $chk->success ) || false === $chk->success ) {
				$this->show_err_iihlms_accept_user_regist( esc_html__( 'reCAPTCHAによる異常を検出しました。', 'imaoikiruhitolms' ) );
				return false;
			}
		}
		if ( isset( $_POST['accepting_userregist_email'] ) ) {
			$accepting_userregist_email = sanitize_email( wp_unslash( $_POST['accepting_userregist_email'] ) );
		} else {
			$accepting_userregist_email = '';
		}

		if ( '' === $accepting_userregist_email ) {
			$this->show_err_iihlms_accept_user_regist( esc_html__( '異常が発生しました。', 'imaoikiruhitolms' ) );
			return false;
		}

		$match = '/^[0-9,A-Z,a-z][0-9,a-z,A-Z,_,\.,-]+'
			. '@[0-9,A-Z,a-z][0-9,a-z,A-Z,_,\.,-]+\.'
			. '(af|al|dz|as|ad|ao|ai|aq|ag|ar|am|aw|ac|au|at|az|bh|bd|bb|by|bj|bm|bt|bo|ba|bw|br|io|bn|bg|bf|bi|kh|cm|ca|cv|cf|td|gg|je|cl|cn|cx|cc|co|km|cg|cd|ck|cr|ci|hr|cu|cy|cz|dk|dj|dm|do|tp|ec|eg|sv|gq|er|ee|et|fk|fo|fj|fi|fr|gf|pf|tf|fx|ga|gm|ge|de|gh|gi|gd|gp|gu|gt|gn|gw|gy|ht|hm|hn|hk|hu|is|in|id|ir|iq|ie|im|il|it|jm|jo|kz|ke|ki|kp|kr|kw|kg|la|lv|lb|ls|lr|ly|li|lt|lu|mo|mk|mg|mw|my|mv|ml|mt|mh|mq|mr|mu|yt|mx|fm|md|mc|mn|ms|ma|mz|mm|na|nr|np|nl|an|nc|nz|ni|ne|ng|nu|nf|mp|no|om|pk|pw|pa|pg|py|pe|ph|pn|pl|pt|pr|qa|re|ro|ru|rw|kn|lc|vc|ws|sm|st|sa|sn|sc|sl|sg|sk|si|sb|so|za|gs|es|lk|sh|pm|sd|sr|sj|sz|se|ch|sy|tw|tj|tz|th|bs|ky|tg|tk|to|tt|tn|tr|tm|tc|tv|ug|ua|ae|uk|us|um|uy|uz|vu|va|ve|vn|vg|vi|wf|eh|ye|yu|zm|zw|com|net|org|gov|edu|int|mil|biz|info|name|pro|jp)$/';
		if ( ! preg_match( $match, $accepting_userregist_email ) ) {
			$this->show_err_iihlms_accept_user_regist( esc_html__( 'メールアドレスの型式を確認してください。', 'imaoikiruhitolms' ) );
			return false;
		}

		// メールアドレス重複チェック.
		$user_id = email_exists( $accepting_userregist_email );
		if ( false !== $user_id ) {
			$this->show_err_iihlms_accept_user_regist( esc_html__( 'メールアドレス「', 'imaoikiruhitolms' ) . $accepting_userregist_email . esc_html__( '」はすでに登録されています。', 'imaoikiruhitolms' ) );
			return false;
		}

		// トークン生成.
		$rand     = random_bytes( 32 );
		$urltoken = hash( 'sha256', uniqid( $rand, true ) );

		$pre_user_table = $wpdb->prefix . 'iihlms_pre_user';
		$available      = 0;

		$wpdb->insert(
			$pre_user_table,
			array(
				'urltoken'        => $urltoken,
				'user_email'      => $accepting_userregist_email,
				'update_datetime' => current_time( 'mysql' ),
				'available'       => $available,
			),
			array(
				'%s',
				'%s',
				'%s',
				'%d',
			)
		);

		$admin_mail_name                                = $this->get_admin_mailname();
		$admin_mail_address                             = $this->get_admin_mailaddress();
		$iihlms_mailsubject_user_registration_reception = get_option( 'iihlms_mailsubject_user_registration_reception' );
		$iihlms_mailbody_user_registration_reception    = get_option( 'iihlms_mailbody_user_registration_reception' );

		// 予約語の置換.
		$mailbody_url                                = add_query_arg( array( 'iihlmsregisttoken' => $urltoken ), get_home_url() . '/' . IIHLMS_USERREGISTRATIONPAGE_NAME . '/' );
		$iihlms_mailbody_user_registration_reception = str_replace( '*USER_REGISTRATION_URL*', $mailbody_url, $iihlms_mailbody_user_registration_reception );

		// メール送信.
		$mail_subject = $iihlms_mailsubject_user_registration_reception;
		$mail_body    = $iihlms_mailbody_user_registration_reception;
		$headers[]    = 'From: ' . $admin_mail_name . ' <' . $admin_mail_address . '>';
		$ret          = wp_mail( $accepting_userregist_email, $mail_subject, $mail_body, $headers );

		if ( false === $ret ) {
			$this->show_err_iihlms_accept_user_regist( esc_html__( 'メール送信エラー', 'imaoikiruhitolms' ) );
			exit;
		}

		return true;
	}

	/**
	 * 新規ユーザー登録エラー表示
	 *
	 * @param string $err_msg エラーメッセージ.
	 * @return void
	 */
	public function show_err_iihlms_accept_user_regist( $err_msg ) {
		get_template_part( 'header' );
		echo '<style>body{background-color: #F2F9FA;}</style>';
		echo '<div class="iihlms-container-regist-wrap">';
		echo '<div class="iihlms-container-regist">';
		echo '<h2 class="iihlms-title-regist">' . esc_html( get_the_title() ) . '</h2>';
		echo '<p class="iihlms-regist-form">';
		echo esc_html( $err_msg );
		echo '</p>';
		echo '<p class="text-center"><button class="iihlms-regist-button" onclick="history.back(-1)">' . esc_html__( '戻る', 'imaoikiruhitolms' ) . '</button></p>';
		echo '</div>';
		echo '</div>';
		get_template_part( 'footer' );
	}

	/**
	 * メール送信元アドレス（管理者）を取得
	 *
	 * @return string
	 */
	public function get_admin_mailaddress() {
		$iihlms_admin_mailaddress = get_option( 'iihlms_admin_mailaddress', '' );
		return $iihlms_admin_mailaddress;
	}
	/**
	 * メール送信元名称（管理者）を取得
	 *
	 * @return string
	 */
	public function get_admin_mailname() {
		$iihlms_admin_mailname = get_option( 'iihlms_admin_mailname', '' );
		return $iihlms_admin_mailname;
	}
	/**
	 * ビジュアルエディタに補足説明を表示
	 *
	 * @return void
	 */
	public function iihlms_disp_after_title() {
		global $post;

		if ( 'iihlms_items' === get_post_type( $post->ID ) ) {
			echo '<div class="iihlms-disp-after-title-description">' . esc_html__( '講座の説明を入力してください。', 'imaoikiruhitolms' ) . '</div>';
		}
		if ( 'iihlms_courses' === get_post_type( $post->ID ) ) {
			echo '<div class="iihlms-disp-after-title-description">' . esc_html__( 'コースの説明を入力してください。', 'imaoikiruhitolms' ) . '</div>';
		}
		if ( 'iihlms_lessons' === get_post_type( $post->ID ) ) {
			echo '<div class="iihlms-disp-after-title-description">' . esc_html__( '埋め込みたい動画のHTMLコードを入力してください。', 'imaoikiruhitolms' ) . '</div>';
		}
	}
	/**
	 * テーマで使用するスクリプト読み込み
	 *
	 * @return void
	 */
	public function iihlms_load_scripts() {
		wp_enqueue_script( 'admin_validate_script1', IIHLMS_PLUGIN_URL . '/js/jquery-validate/jquery.validate.min.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'admin_validate_script2', IIHLMS_PLUGIN_URL . '/js/jquery-validate/additional-methods.min.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'admin_postcode_script1', 'https://yubinbango.github.io/yubinbango/yubinbango.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'admin_fotterfix_script', IIHLMS_PLUGIN_URL . '/js/footerFixed.js', array( 'jquery' ), '1.0.0', true );
	}
	/**
	 * ログイン画面カスタマイズ
	 *
	 * @param string $text テキスト.
	 * @return string
	 */
	public function change_login_text( $text ) {
		$text = str_replace( 'ユーザー名またはメールアドレス', 'ユーザー名', $text );
		$text = str_replace( 'パスワードをお忘れですか ?', 'パスワードを忘れた場合</a><hr class="iihlms-login-hr"><p id="iihlms-accepting-registration-nav">アカウントをお持ちでない場合</p><p id="iihlms-accepting-registration"><a href="' . get_home_url() . '/' . IIHLMS_ACCEPTINGUSERREGISTRATION_NAME . '/">新規ユーザー登録</a></p><p class="iihlms-login-footer">' . get_bloginfo() . '</p>', $text );

		return $text;
	}
	/**
	 * 新規ユーザー登録時、ユーザー名が使用済かチェック
	 *
	 * @return void
	 */
	public function check_signup_user_name_func_ajax() {
		if ( ! isset( $_POST['nonce'] ) ) {
			echo wp_json_encode( esc_html__( '異常を検出しました。', 'imaoikiruhitolms' ) );
			exit;
		}
		$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
		if ( ! wp_verify_nonce( $nonce, 'iihlms-ajax-nonce-check-signup-user-name' ) ) {
			echo wp_json_encode( esc_html__( '異常を検出しました。', 'imaoikiruhitolms' ) );
			exit;
		}

		if ( ! isset( $_POST['signup-user-name'] ) ) {
			echo wp_json_encode( esc_html__( '異常を検出しました。', 'imaoikiruhitolms' ) );
			exit;
		}
		$signup_user_name = sanitize_text_field( wp_unslash( $_POST['signup-user-name'] ) );
		if ( '' === $signup_user_name ) {
			echo wp_json_encode( esc_html__( '異常を検出しました。', 'imaoikiruhitolms' ) );
			exit;
		}
		$user_id = username_exists( $signup_user_name );
		if ( false !== $user_id ) {
			echo wp_json_encode( esc_html__( 'ユーザ名はすでに使用されています。他のユーザー名を入力してください。', 'imaoikiruhitolms' ) );
			exit;
		}

		echo wp_json_encode( 'true' );
		exit;
	}
	/**
	 * パスワードリセットページのtitleタグを変更
	 *
	 * @param string $title タイトル.
	 * @return string
	 */
	public function iihlms_change_title_tag( $title ) {
		global $pagenow;
		if ( ( 'wp-login.php' === $pagenow ) && ( isset( $_GET['action'] ) ) && ( strpos( sanitize_text_field( wp_unslash( $_GET['action'] ) ), 'lostpassword' ) !== false ) ) {
			$title = esc_html__( 'パスワードをリセット', 'imaoikiruhitolms' );
		}
		if ( ( 'wp-login.php' === $pagenow ) && ( isset( $_GET['checkemail'] ) ) && ( strpos( sanitize_text_field( wp_unslash( $_GET['checkemail'] ) ), 'confirm' ) !== false ) ) {
			$title = esc_html__( 'パスワードリセットメールを送信しました', 'imaoikiruhitolms' );
		}
		if ( ( 'wp-login.php' === $pagenow ) && ( isset( $_GET['action'] ) ) && ( strpos( sanitize_text_field( wp_unslash( $_GET['action'] ) ), 'resetpass' ) !== false ) ) {
			$title = esc_html__( 'パスワードをリセットしました', 'imaoikiruhitolms' );
		}
		return $title;
	}
	/**
	 * パスワードリセットリクエスト時のユーザー宛メールを変更（件名）
	 *
	 * @param string $title メール件名.
	 * @param string $user_login The username for the user.
	 * @param object $user_data WP_User object.
	 * @return string
	 */
	public function iihlms_change_mail_password_reset_user_subject( $title, $user_login, $user_data ) {
		$title = get_option( 'iihlms_mailsubject_change_mail_password_reset', '' );
		return $title;
	}
	/**
	 * パスワードリセットリクエスト時のユーザー宛メールを変更（本文）
	 *
	 * @param string $message メール本文.
	 * @param string $key The activation key.
	 * @param string $user_login The username for the user.
	 * @param object $user_data WP_User object.
	 * @return string
	 */
	public function iihlms_change_mail_password_reset_user_message( $message, $key, $user_login, $user_data ) {
		add_filter(
			'wp_mail_from_name',
			function() {
				return $this->get_admin_mailname();
			}
		);
		add_filter(
			'wp_mail_from',
			function() {
				return $this->get_admin_mailaddress();
			}
		);

		$message = get_option( 'iihlms_mailbody_change_mail_password_reset', '' );
		$message = str_replace( '*PASSWORD_RESET_URL*', wp_login_url() . "?action=rp&key=$key&login=" . rawurlencode( $user_login ), $message );
		return $message;
	}
	/**
	 * パスワードリセット完了時の管理者宛メールを変更
	 *
	 * @param array  $wp_password_change_notification_email Used to build wp_mail().
	 * @param object $user WP_User object.
	 * @param string $blogname The site title.
	 * @return string
	 */
	public function iihlms_change_mail_password_reset_done_admin( $wp_password_change_notification_email, $user, $blogname ) {
		global $user;

		$admin_mail_name    = $this->get_admin_mailname();
		$admin_mail_address = $this->get_admin_mailaddress();

		$name = esc_html( get_user_meta( $user->ID, 'iihlms_user_name1', true ) . get_user_meta( $user->ID, 'iihlms_user_name2', true ) );

		$to      = $admin_mail_address;
		$subject = get_option( 'iihlms_mailsubject_change_mail_password_reset_completed', '' );
		$message = get_option( 'iihlms_mailbody_change_mail_password_reset_completed', '' );
		$message = str_replace( '*NAME*', $name, $message );
		$headers = 'From: ' . $admin_mail_name . ' <' . $admin_mail_address . '>';

		$wp_password_change_notification_email['to']      = $to;
		$wp_password_change_notification_email['subject'] = $subject;
		$wp_password_change_notification_email['message'] = $message;
		$wp_password_change_notification_email['headers'] = $headers;

		return $wp_password_change_notification_email;
	}
	/**
	 * 管理画面で新規ユーザーを追加する際、「新規ユーザーにアカウントに関するメールを送信します。」に
	 * チェックを入れた時にユーザーに送信されるメールを変更
	 *
	 * @param array  $wp_new_user_notification_email Used to build wp_mail().
	 * @param object $user User object for new user.
	 * @param string $blogname The site title.
	 * @return string
	 */
	public function iihlms_change_mail_new_user( $wp_new_user_notification_email, $user, $blogname ) {
		$admin_mail_name    = $this->get_admin_mailname();
		$admin_mail_address = $this->get_admin_mailaddress();

		$user_name  = stripslashes( $user->user_login );
		$user_email = stripslashes( $user->user_email );
		$key        = get_password_reset_key( $user );
		$user_login = $user_name;

		$subject = get_option( 'iihlms_mailsubject_add_new_user', '' );
		$message = get_option( 'iihlms_mailbody_add_new_user', '' );
		$message = str_replace( '*USER_NAME*', $user_login, $message );
		$message = str_replace( '*USER_MAIL*', $user_login, $message );
		$message = str_replace( '*PASSWORD_RESET_URL*', wp_login_url() . "?action=rp&key=$key&login=" . rawurlencode( $user_login ), $message );
		$headers = 'From: ' . $admin_mail_name . ' <' . $admin_mail_address . '>';

		$wp_new_user_notification_email['subject'] = $subject;
		$wp_new_user_notification_email['message'] = $message;
		$wp_new_user_notification_email['headers'] = $headers;
		return $wp_new_user_notification_email;
	}
	/**
	 * 管理画面で新規ユーザーを追加する際、「新規ユーザーにアカウントに関するメールを送信します。」に
	 * チェックを入れた時に管理者に送信されるメールを変更
	 *
	 * @param array  $wp_new_user_notification_email_admin Used to build wp_mail().
	 * @param object $user User object for new user.
	 * @param string $blogname The site title.
	 * @return string
	 */
	public function iihlms_change_mail_new_user_admin( $wp_new_user_notification_email_admin, $user, $blogname ) {
		$admin_mail_name    = $this->get_admin_mailname();
		$admin_mail_address = $this->get_admin_mailaddress();

		$user_name  = stripslashes( $user->user_login );
		$user_email = stripslashes( $user->user_email );
		$user_login = $user_name;

		$to      = $admin_mail_address;
		$subject = get_option( 'iihlms_mailsubject_add_new_user_admin', '' );
		$message = get_option( 'iihlms_mailbody_add_new_user_admin', '' );
		$message = str_replace( '*USER_NAME*', $user_login, $message );
		$message = str_replace( '*USER_MAIL*', $user_email, $message );
		$headers = 'From: ' . $admin_mail_name . ' <' . $admin_mail_address . '>';

		$wp_new_user_notification_email_admin['subject'] = $subject;
		$wp_new_user_notification_email_admin['message'] = $message;
		$wp_new_user_notification_email_admin['headers'] = $headers;
		return $wp_new_user_notification_email_admin;
	}
	/**
	 * メールアドレス変更時、ユーザーに送信されるメールを変更
	 *
	 * @param array $email_change_email Used to build wp_mail().
	 * @param array $user The original user array.
	 * @param array $userdata The updated user array.
	 * @return string
	 */
	public function iihlms_change_email_change_email( $email_change_email, $user, $userdata ) {
		$admin_mail_name    = $this->get_admin_mailname();
		$admin_mail_address = $this->get_admin_mailaddress();

		$name = esc_html( get_user_meta( $userdata['ID'], 'iihlms_user_name1', true ) . get_user_meta( $userdata['ID'], 'iihlms_user_name2', true ) );

		$subject = get_option( 'iihlms_mailsubject_change_email', '' );
		$message = get_option( 'iihlms_mailbody_change_email', '' );
		$message = str_replace( '*NAME*', $name, $message );
		$message = str_replace( '*NEW_MAIL*', '###NEW_EMAIL###', $message );
		$message = str_replace( '*OLD_MAIL*', '###EMAIL###', $message );
		$headers = 'From: ' . $admin_mail_name . ' <' . $admin_mail_address . '>';

		$email_change_email['subject'] = $subject;
		$email_change_email['message'] = $message;
		$email_change_email['headers'] = $headers;

		return $email_change_email;
	}

	/**
	 * 決済の間隔を得る
	 *
	 * @param string $interval_unit_val データベースに登録した値.
	 * @return string 決済処理で使用する値.
	 */
	public function get_interval_unit_val( $interval_unit_val ) {
		if ( 'd' === $interval_unit_val ) {
			return 'DAY';
		}
		if ( 'w' === $interval_unit_val ) {
			return 'WEEK';
		}
		if ( 'm' === $interval_unit_val ) {
			return 'MONTH';
		}
		if ( 'y' === $interval_unit_val ) {
			return 'YEAR';
		}
		return '';
	}
	/**
	 * 決済の間隔を表示用の表記にする
	 *
	 * @param string $interval_unit_val データベースに登録した値.
	 * @return string 表示用の表記.
	 */
	public function get_interval_unit_for_disp( $interval_unit_val ) {
		if ( 'd' === $interval_unit_val ) {
			return esc_html__( '日', 'imaoikiruhitolms' );
		}
		if ( 'w' === $interval_unit_val ) {
			return esc_html__( '週', 'imaoikiruhitolms' );
		}
		if ( 'm' === $interval_unit_val ) {
			return esc_html__( '月', 'imaoikiruhitolms' );
		}
		if ( 'y' === $interval_unit_val ) {
			return esc_html__( '年', 'imaoikiruhitolms' );
		}
		return '';
	}
	/**
	 * 決済の間隔を表示用の表記にする
	 *
	 * @param string $interval_unit_val データベースに登録した値.
	 * @return string 表示用の表記.
	 */
	public function get_interval_unit_for_disp_long( $interval_unit_val ) {
		if ( 'd' === $interval_unit_val ) {
			return esc_html__( '日間', 'imaoikiruhitolms' );
		}
		if ( 'w' === $interval_unit_val ) {
			return esc_html__( '週間', 'imaoikiruhitolms' );
		}
		if ( 'm' === $interval_unit_val ) {
			return esc_html__( 'ヶ月', 'imaoikiruhitolms' );
		}
		if ( 'y' === $interval_unit_val ) {
			return esc_html__( '年間', 'imaoikiruhitolms' );
		}
		return '';
	}
	/**
	 * テストの合否を表示用の表記にする
	 *
	 * @param string $test_result_pass_fail_val データベースに登録した値.
	 * @return string 表示用の表記.
	 */
	public function get_test_result_pass_fail_for_disp( $test_result_pass_fail_val ) {
		if ( 'pass' === $test_result_pass_fail_val ) {
			return esc_html__( '合格', 'imaoikiruhitolms' );
		}
		if ( 'fail' === $test_result_pass_fail_val ) {
			return esc_html__( '不合格', 'imaoikiruhitolms' );
		}
		return '';
	}
	/**
	 * テストの設問数を得る
	 *
	 * @param string $test_id テストID.
	 * @return int 設問数.
	 */
	public function get_test_number_of_questions( $test_id ) {
		$iihlms_test_number_of_questions = get_post_meta( $test_id, 'iihlms_test_number_of_questions', true );

		return (int) $iihlms_test_number_of_questions;
	}
	/**
	 * テストの制限時間を表示用の表記にする
	 *
	 * @param string $test_id テストID.
	 * @return int 制限時間.
	 */
	public function get_test_time_limit_for_disp( $test_id ) {
		$iihlms_test_time_limit = get_post_meta( $test_id, 'iihlms_test_time_limit', true );

		$hours   = floor( $iihlms_test_time_limit / 3600 );
		$minutes = floor( ( $iihlms_test_time_limit / 60 ) % 60 );
		$seconds = $iihlms_test_time_limit % 60;

		if ( ( 0 === (int) $hours ) && ( 0 === (int) $minutes ) && ( 0 === (int) $seconds ) ) {
			$ret = esc_html__( '-', 'imaoikiruhitolms' );
		} elseif ( 0 === (int) $hours ) {
			$ret = $minutes . esc_html__( '分', 'imaoikiruhitolms' );
			if ( 0 !== (int) $seconds ) {
				$ret = $ret . $seconds . esc_html__( '秒', 'imaoikiruhitolms' );
			}
		} else {
			$ret = $hours . esc_html__( '時間', 'imaoikiruhitolms' );
			if ( 0 !== (int) $minutes ) {
				$ret = $ret . $minutes . esc_html__( '分', 'imaoikiruhitolms' );
			}
			if ( 0 !== (int) $seconds ) {
				$ret = $ret . $seconds . esc_html__( '秒', 'imaoikiruhitolms' );
			}
		}

		return $ret;
	}
	/**
	 * 数値を上限値、下限値で丸める
	 *
	 * @param int $intval 整形したい値.
	 * @param int $lower  下限値.
	 * @param int $upper  上限値.
	 * @return string 決済処理で使用する値.
	 */
	public function round_number_lower_and_upper( $intval, $lower, $upper ) {
		$value = min( max( $intval, $lower ), $upper );
		return $value;
	}
	/**
	 * 消費税率を取得
	 *
	 * @return int 消費税率.
	 */
	public function get_consumption_tax() {
		$iihlms_consumption_tax = get_option( 'iihlms_consumption_tax', IIHLMS_CONSUMPTION_TAX_INITIAL_VALUE );
		return absint( $iihlms_consumption_tax );
	}
	/**
	 * 指定した講座がサブスクリプションか
	 *
	 * @param string $itemid 講座ID.
	 * @return bool
	 */
	public function is_subscription( $itemid ) {
		$iihlms_payment_type = get_post_meta( $itemid, 'iihlms_payment_type', true );

		if ( 'subscription' === $iihlms_payment_type ) {
			return true;
		}
		return false;
	}

	/**
	 * Table order_cart_metaから値を取得
	 *
	 * @param string $order_cart_id カートID.
	 * @param string $meta_key メタキー.
	 * @return bool
	 */
	public function get_post_meta_order_cart_meta_table( $order_cart_id, $meta_key ) {
		global $wpdb;

		$order_cart_meta_table = $wpdb->prefix . 'iihlms_order_cart_meta';

		$meta_value = $wpdb->get_var(
			$wpdb->prepare(
				'
				SELECT meta_value
				FROM %1s
				WHERE 
					order_cart_id = %s
					AND meta_key = %s
				',
				$order_cart_meta_table,
				$order_cart_id,
				$meta_key
			)
		);

		if ( is_null( $meta_value ) ) {
			$meta_value = '';
		}

		return $meta_value;
	}
	/**
	 * Table order_metaから値を取得
	 *
	 * @param string $order_id 注文ID.
	 * @param string $meta_key メタキー.
	 * @return bool
	 */
	public function get_post_meta_order_meta_table( $order_id, $meta_key ) {
		global $wpdb;

		$order_meta_table = $wpdb->prefix . 'iihlms_order_meta';

		$meta_value = $wpdb->get_var(
			$wpdb->prepare(
				'
				SELECT meta_value
				FROM %1s
				WHERE 
					order_id = %s
					AND meta_key = %s
				',
				$order_meta_table,
				$order_id,
				$meta_key
			)
		);

		if ( is_null( $meta_value ) ) {
			$meta_value = '';
		}

		return $meta_value;
	}
	/**
	 * テスト結果一覧 列追加
	 *
	 * @param string $columns columns.
	 * @return $columns columns.
	 */
	public function add_iihlms_test_results_posts_columns( $columns ) {
		unset( $columns['date'] );
		$columns['userid_st']   = esc_html__( 'ユーザーID', 'imaoikiruhitolms' );
		$columns['username_st'] = esc_html__( 'ユーザー名', 'imaoikiruhitolms' );
		$columns['pass_fail']   = esc_html__( '合否', 'imaoikiruhitolms' );
		$columns['date']        = esc_html__( '日付', 'imaoikiruhitolms' );
		return $columns;
	}
	/**
	 * テスト結果一覧 列追加
	 *
	 * @param string $column_name column_name.
	 * @param string $post_id post_id.
	 * @return void
	 */
	public function custom_iihlms_test_results_posts_column( $column_name, $post_id ) {
		if ( 'userid_st' === $column_name ) {
			$column_value = get_post_meta( $post_id, 'iihlms_test_result_userid', true );
			echo esc_html( $column_value );
		}
		if ( 'username_st' === $column_name ) {
			$user_id      = get_post_meta( $post_id, 'iihlms_test_result_userid', true );
			$name1        = get_user_meta( $user_id, 'iihlms_user_name1', true );
			$name2        = get_user_meta( $user_id, 'iihlms_user_name2', true );
			$column_value = esc_html( $name1 . $name2 );
			echo esc_html( $column_value );
		}
		if ( 'pass_fail' === $column_name ) {
			$column_value = get_post_meta( $post_id, 'iihlms_test_result_pass_fail', true );
			echo esc_html( $this->get_test_result_pass_fail_for_disp( $column_value ) );
		}
	}
	/**
	 * テスト結果一覧 列追加
	 *
	 * @param string $sortable_column sortable_column.
	 * @return $sortable_column sortable_column.
	 */
	public function iihlms_test_results_sortable_columns( $sortable_column ) {
		$sortable_column['userid_st'] = 'userid_st';
		$sortable_column['pass_fail'] = 'pass_fail';
		return $sortable_column;
	}
	/**
	 * テスト結果一覧 列追加
	 *
	 * @param object $query query.
	 * @return void
	 */
	public function add_iihlms_test_results_posts_sort( $query ) {
		if ( $query->is_main_query() ) {
			$orderby = $query->get( 'orderby' );
			if ( 'userid_st' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_test_result_userid' );
				$query->set( 'orderby', 'meta_value_num' );
			}
			if ( 'pass_fail' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_test_result_pass_fail' );
				$query->set( 'orderby', 'meta_value' );
			}
		}
	}
	/**
	 * PayPal Webhook一覧 列追加
	 *
	 * @param string $columns columns.
	 * @return $columns columns.
	 */
	public function add_iihlms_wh_paypal_posts_columns( $columns ) {
		unset( $columns['title'] );
		unset( $columns['date'] );
		$columns['systemid']        = esc_html__( 'ID', 'imaoikiruhitolms' );
		$columns['event_st']        = esc_html__( 'イベント', 'imaoikiruhitolms' );
		$columns['summary']         = esc_html__( '概要', 'imaoikiruhitolms' );
		$columns['resource']        = esc_html__( 'リソース', 'imaoikiruhitolms' );
		$columns['webhookdatetime'] = esc_html__( '日時', 'imaoikiruhitolms' );
		return $columns;
	}
	/**
	 * PayPal Webhook一覧 列追加
	 *
	 * @param string $column_name column_name.
	 * @param string $post_id post_id.
	 * @return void
	 */
	public function custom_iihlms_wh_paypal_posts_column( $column_name, $post_id ) {
		if ( 'systemid' === $column_name ) {
			$column_value = get_post_meta( $post_id, 'iihlms_paypal_webhook_payment_system_id', true );
			echo esc_html( $column_value );
		}
		if ( 'event_st' === $column_name ) {
			$column_value = get_post_meta( $post_id, 'iihlms_paypal_webhook_event_type', true );
			echo esc_html( $column_value );
		}
		if ( 'summary' === $column_name ) {
			$column_value = get_post_meta( $post_id, 'iihlms_paypal_webhook_summary', true );
			echo esc_html( $column_value );
		}
		if ( 'resource' === $column_name ) {
			$column_value = get_post_meta( $post_id, 'iihlms_paypal_webhook_resource_type', true );
			echo esc_html( $column_value );
		}
		if ( 'webhookdatetime' === $column_name ) {
			$column_value = wp_date( 'Y/m/d H:i:s', strtotime( get_post_meta( $post_id, 'iihlms_paypal_webhook_create_time', true ) ) );
			echo esc_html( $column_value );
		}
	}
	/**
	 * PayPal Webhook一覧 列追加
	 *
	 * @param string $sortable_column sortable_column.
	 * @return $sortable_column sortable_column.
	 */
	public function iihlms_wh_paypal_posts_sortable_columns( $sortable_column ) {
		$sortable_column['systemid']        = 'systemid';
		$sortable_column['event_st']        = 'event_st';
		$sortable_column['summary']         = 'summary';
		$sortable_column['resource']        = 'resource';
		$sortable_column['webhookdatetime'] = 'webhookdatetime';
		return $sortable_column;
	}
	/**
	 * PayPal Webhook一覧 列追加
	 *
	 * @param object $query query.
	 * @return void
	 */
	public function add_iihlms_wh_paypal_posts_sort( $query ) {
		if ( $query->is_main_query() ) {
			$orderby = $query->get( 'orderby' );

			if ( 'systemid' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_paypal_webhook_payment_system_id' );
				$query->set( 'orderby', 'meta_value' );
			}
			if ( 'event_st' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_paypal_webhook_event_type' );
				$query->set( 'orderby', 'meta_value' );
			}
			if ( 'summary' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_paypal_webhook_summary' );
				$query->set( 'orderby', 'meta_value' );
			}
			if ( 'resource' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_paypal_webhook_resource_type' );
				$query->set( 'orderby', 'meta_value' );
			}
			if ( 'webhookdatetime' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_paypal_webhook_create_time' );
				$query->set( 'orderby', 'meta_value' );
			}
		}
	}
	/**
	 * Stripe Webhook一覧 列追加
	 *
	 * @param string $columns columns.
	 * @return $columns columns.
	 */
	public function add_iihlms_wh_stripe_posts_columns( $columns ) {
		unset( $columns['title'] );
		unset( $columns['date'] );
		$columns['id']          = esc_html__( 'ID', 'imaoikiruhitolms' );
		$columns['event']       = esc_html__( 'イベント', 'imaoikiruhitolms' );
		$columns['webhookdate'] = esc_html__( '日時', 'imaoikiruhitolms' );
		return $columns;
	}
	/**
	 * Stripe Webhook一覧 列追加
	 *
	 * @param string $column_name column_name.
	 * @param string $post_id post_id.
	 * @return void
	 */
	public function custom_iihlms_wh_stripe_posts_column( $column_name, $post_id ) {
		if ( 'id' === $column_name ) {
			$column_value = get_post_meta( $post_id, 'iihlms_stripe_webhook_id', true );
			echo esc_html( $column_value );
		}
		if ( 'event' === $column_name ) {
			$column_value = get_post_meta( $post_id, 'iihlms_stripe_webhook_type', true );
			echo esc_html( $column_value );
		}
		if ( 'webhookdate' === $column_name ) {
			$column_value = wp_date( 'Y/m/d H:i:s', get_post_meta( $post_id, 'iihlms_stripe_webhook_crated', true ) );
			echo esc_html( $column_value );
		}
	}
	/**
	 * Stripe Webhook一覧 列追加
	 *
	 * @param string $sortable_column sortable_column.
	 * @return $sortable_column sortable_column.
	 */
	public function iihlms_wh_stripe_posts_sortable_columns( $sortable_column ) {
		$sortable_column['id']          = 'id';
		$sortable_column['event']       = 'event';
		$sortable_column['webhookdate'] = 'webhookdate';
		return $sortable_column;
	}
	/**
	 * Stripe Webhook一覧 列追加
	 *
	 * @param object $query query.
	 * @return void
	 */
	public function add_iihlms_wh_stripe_posts_sort( $query ) {
		if ( $query->is_main_query() ) {
			$orderby = $query->get( 'orderby' );

			if ( 'id' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_stripe_webhook_id' );
				$query->set( 'orderby', 'meta_value' );
			}
			if ( 'event' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_stripe_webhook_type' );
				$query->set( 'orderby', 'meta_value' );
			}
			if ( 'webhookdate' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_stripe_webhook_crated' );
				$query->set( 'orderby', 'meta_value' );
			}
		}
	}

	/**
	 * PayPal 定期購入ログ 列追加
	 *
	 * @param string $columns columns.
	 * @return $columns columns.
	 */
	public function add_iihlms_wh_paypal_sp_posts_columns( $columns ) {
		unset( $columns['title'] );
		unset( $columns['date'] );

		$columns['created_stp']    = esc_html__( '受信日時', 'imaoikiruhitolms' );
		$columns['amount_stp']     = esc_html__( '金額', 'imaoikiruhitolms' );
		$columns['orderdate_stp']  = esc_html__( '注文日時', 'imaoikiruhitolms' );
		$columns['orderkey_stp']   = esc_html__( '注文番号', 'imaoikiruhitolms' );
		$columns['event_type_stp'] = esc_html__( '処理結果', 'imaoikiruhitolms' );
		$columns['itemid_stp']     = esc_html__( '講座ID', 'imaoikiruhitolms' );
		$columns['itemname_stp']   = esc_html__( '講座名', 'imaoikiruhitolms' );
		$columns['userid_stp']     = esc_html__( 'ユーザーID', 'imaoikiruhitolms' );
		$columns['username_stp']   = esc_html__( 'ユーザー名', 'imaoikiruhitolms' );
		return $columns;
	}
	/**
	 * PayPal 定期購入ログ 列追加
	 *
	 * @param string $column_name column_name.
	 * @param string $post_id post_id.
	 * @return void
	 */
	public function custom_iihlms_wh_paypal_sp_posts_column( $column_name, $post_id ) {
		global $wpdb;

		$order_table           = $wpdb->prefix . 'iihlms_order';
		$order_cart_table      = $wpdb->prefix . 'iihlms_order_cart';
		$order_cart_meta_table = $wpdb->prefix . 'iihlms_order_cart_meta';

		$event_type     = get_post_meta( $post_id, 'iihlms_paypal_receive_webhook_event_type', true );
		$webhook_id     = get_post_meta( $post_id, 'iihlms_paypal_webhook_id', true );
		$subscriptionid = get_post_meta( $post_id, 'iihlms_paypal_receive_webhook_subscription_id', true );
		$amount_paid    = get_post_meta( $post_id, 'iihlms_paypal_receive_webhook_amount', true );
		$created        = get_post_meta( $post_id, 'iihlms_paypal_receive_webhook_create_time', true );

		$subscription_item_id = '';
		$order_id             = '';
		$item_name            = '';
		$order_key            = '';
		$user_id              = '';
		$user_email           = '';
		$user_name1           = '';
		$user_name2           = '';
		$tel1                 = '';
		$payment_name         = '';
		$order_date_time      = '';

		$order_cart_id = $this->get_order_cart_id_from_webhook_resource_id( $subscriptionid );

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT *
				FROM %1s
				WHERE 
					order_cart_id = %d
				',
				$order_table,
				$order_cart_id,
			)
		);
		foreach ( $results as $result ) {
			$subscription_item_id = $result->item_id;
			$order_id             = $result->order_id;
			$item_name            = $result->item_name;
			$order_key            = $result->order_key;
			$user_id              = $result->user_id;
			$user_email           = $result->user_email;
			$user_name1           = $result->user_name1;
			$user_name2           = $result->user_name2;
			$tel1                 = $result->tel1;
			$payment_name         = $result->payment_name;
			$order_date_time      = $result->order_date_time;
		}

		if ( '' === get_post_meta( $post_id, 'iihlms_paypal_payment_item_id', true ) ) {
			update_post_meta( $post_id, 'iihlms_paypal_payment_item_id', $subscription_item_id );
		}
		if ( '' === get_post_meta( $post_id, 'iihlms_paypal_payment_order_id', true ) ) {
			update_post_meta( $post_id, 'iihlms_paypal_payment_order_id', $order_id );
		}
		if ( '' === get_post_meta( $post_id, 'iihlms_paypal_payment_item_name', true ) ) {
			update_post_meta( $post_id, 'iihlms_paypal_payment_item_name', $item_name );
		}
		if ( '' === get_post_meta( $post_id, 'iihlms_paypal_payment_order_key', true ) ) {
			update_post_meta( $post_id, 'iihlms_paypal_payment_order_key', $order_key );
		}
		if ( '' === get_post_meta( $post_id, 'iihlms_paypal_payment_order_date', true ) ) {
			update_post_meta( $post_id, 'iihlms_paypal_payment_order_date', $order_date_time );
		}
		if ( '' === get_post_meta( $post_id, 'iihlms_paypal_payment_user_id', true ) ) {
			update_post_meta( $post_id, 'iihlms_paypal_payment_user_id', $user_id );
		}
		if ( '' === get_post_meta( $post_id, 'iihlms_paypal_payment_user_name', true ) ) {
			update_post_meta( $post_id, 'iihlms_paypal_payment_user_name', $user_name1 . $user_name2 );
		}

		if ( 'created_stp' === $column_name ) {
			$column_value = get_post_time( $this->specify_date_time_format_hyphen, false, $post_id );
			echo esc_html( $column_value );
		}
		if ( 'amount_stp' === $column_name ) {
			if ( '' !== $amount_paid ) {
				$column_value = number_format( (int) $amount_paid );
			} else {
				$column_value = '';
			}
			echo esc_html( $column_value );
		}
		if ( 'orderdate_stp' === $column_name ) {
			$column_value = $order_date_time;
			echo esc_html( $column_value );
		}
		if ( 'orderkey_stp' === $column_name ) {
			$column_value = $order_key;
			echo esc_html( $column_value );
		}
		if ( 'event_type_stp' === $column_name ) {
			$event_type_for_disp = $this->get_paypal_webhook_event_type_for_disp( $event_type );
			if ( '' === $event_type_for_disp ) {
				$column_value = $event_type;
			} else {
				$column_value = $event_type_for_disp;
			}
			echo esc_html( $column_value );
			if ( 'BILLING.SUBSCRIPTION.PAYMENT.FAILED' === $event_type ) {
				echo '<span style="color: #f00;">';
				echo 'エラー';
				echo '</span>';
			}
		}
		if ( 'itemid_stp' === $column_name ) {
			$column_value = $subscription_item_id;
			$item_url     = get_edit_post_link( $subscription_item_id );
			echo '<a href="';
			echo esc_url( $item_url );
			echo '">';
			echo esc_html( $column_value );
			echo '</a>';
		}
		if ( 'itemname_stp' === $column_name ) {
			$column_value = $item_name;
			$item_url     = get_edit_post_link( $subscription_item_id );
			echo '<a href="';
			echo esc_url( $item_url );
			echo '">';
			echo esc_html( $column_value );
			echo '</a>';
		}
		if ( 'userid_stp' === $column_name ) {
			$column_value = $user_id;
			$user_url     = get_edit_user_link( $user_id );
			echo '<a href="';
			echo esc_url( $user_url );
			echo '">';
			echo esc_html( $column_value );
			echo '</a>';
		}
		if ( 'username_stp' === $column_name ) {
			$column_value = $user_name1 . $user_name2;
			$user_url     = get_edit_user_link( $user_id );
			echo '<a href="';
			echo esc_url( $user_url );
			echo '">';
			echo esc_html( $column_value );
			echo '</a>';
		}
	}
	/**
	 * PayPal 定期購入ログ 列追加
	 *
	 * @param string $sortable_column sortable_column.
	 * @return $sortable_column sortable_column.
	 */
	public function iihlms_wh_paypal_sp_posts_sortable_columns( $sortable_column ) {
		$sortable_column['created_stp']    = 'created_stp';
		$sortable_column['amount_stp']     = 'amount_stp';
		$sortable_column['orderdate_stp']  = 'orderdate_stp';
		$sortable_column['orderkey_stp']   = 'orderkey_stp';
		$sortable_column['event_type_stp'] = 'event_type_stp';
		$sortable_column['itemid_stp']     = 'itemid_stp';
		$sortable_column['itemname_stp']   = 'itemname_stp';
		$sortable_column['userid_stp']     = 'userid_stp';
		$sortable_column['username_stp']   = 'username_stp';
		return $sortable_column;
	}
	/**
	 * PayPal 定期購入ログ 列追加
	 *
	 * @param object $query query.
	 * @return void
	 */
	public function add_iihlms_wh_paypal_sp_posts_sort( $query ) {
		if ( $query->is_main_query() ) {
			$orderby = $query->get( 'orderby' );

			if ( 'created_stp' === $orderby ) {
				// $query->set( 'meta_key', 'iihlms_stripe_invoice_payment_created' );
				// $query->set( 'orderby', 'meta_value' );
				// $column_value = get_post_time( $this->specify_date_time_format_hyphen, false, $post_id );
				// echo esc_html( $column_value );
				$query->set( 'orderby', 'post_date' );
			}
			if ( 'amount_stp' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_paypal_receive_webhook_amount' );
				$query->set( 'orderby', 'meta_value_num' );
			}
			if ( 'orderdate_stp' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_paypal_payment_order_date' );
				$query->set( 'orderby', 'meta_value' );
			}
			if ( 'orderkey_stp' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_paypal_payment_order_key' );
				$query->set( 'orderby', 'meta_value' );
			}
			if ( 'event_type_stp' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_paypal_receive_webhook_event_type' );
				$query->set( 'orderby', 'meta_value' );
			}
			if ( 'itemid_stp' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_paypal_payment_item_id' );
				$query->set( 'orderby', 'meta_value_num' );
			}
			if ( 'itemname_stp' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_paypal_payment_item_name' );
				$query->set( 'orderby', 'meta_value' );
			}
			if ( 'userid_stp' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_paypal_payment_user_id' );
				$query->set( 'orderby', 'meta_value_num' );
			}
			if ( 'username_stp' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_paypal_payment_user_name' );
				$query->set( 'orderby', 'meta_value' );
			}
		}
	}


	/**
	 * Stripe 定期購入ログ 列追加
	 *
	 * @param string $columns columns.
	 * @return $columns columns.
	 */
	public function add_iihlms_wh_stripe_sp_posts_columns( $columns ) {
		unset( $columns['title'] );
		unset( $columns['date'] );

		$columns['created_st'] = esc_html__( '受信日時', 'imaoikiruhitolms' );
		$columns['amount']     = esc_html__( '金額', 'imaoikiruhitolms' );
		$columns['orderdate']  = esc_html__( '注文日時', 'imaoikiruhitolms' );
		$columns['orderkey']   = esc_html__( '注文番号', 'imaoikiruhitolms' );
		$columns['event_type'] = esc_html__( '処理結果', 'imaoikiruhitolms' );
		$columns['itemid']     = esc_html__( '講座ID', 'imaoikiruhitolms' );
		$columns['itemname']   = esc_html__( '講座名', 'imaoikiruhitolms' );
		$columns['userid']     = esc_html__( 'ユーザーID', 'imaoikiruhitolms' );
		$columns['username']   = esc_html__( 'ユーザー名', 'imaoikiruhitolms' );
		return $columns;
	}
	/**
	 * Stripe 定期購入ログ 列追加
	 *
	 * @param string $column_name column_name.
	 * @param string $post_id post_id.
	 * @return void
	 */
	public function custom_iihlms_wh_stripe_sp_posts_column( $column_name, $post_id ) {
		global $post;
		global $wpdb;

		$order_table           = $wpdb->prefix . 'iihlms_order';
		$order_cart_table      = $wpdb->prefix . 'iihlms_order_cart';
		$order_cart_meta_table = $wpdb->prefix . 'iihlms_order_cart_meta';

		$event_type      = get_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_event_type', true );
		$webhook_id      = get_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_webhook_id', true );
		$paymentintentid = get_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_paymentintentid', true );
		$subscriptionid  = get_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_subscriptionid', true );
		$amount_paid     = get_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_amount_paid', true );
		$created         = get_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_created', true );

		$subscription_item_id = '';
		$order_id             = '';
		$item_name            = '';
		$order_key            = '';
		$user_id              = '';
		$user_email           = '';
		$user_name1           = '';
		$user_name2           = '';
		$tel1                 = '';
		$payment_name         = '';
		$order_date_time      = '';

		$search_meta_key = 'iihlms_item_subscription_id_stripe';
		$order_cart_id   = $wpdb->get_var(
			$wpdb->prepare(
				'
				SELECT order_cart_id
				FROM %1s
				WHERE 
					meta_key = %s
					AND meta_value = %s
				',
				$order_cart_meta_table,
				$search_meta_key,
				$subscriptionid
			)
		);

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT *
				FROM %1s
				WHERE 
					order_cart_id = %d
				',
				$order_table,
				$order_cart_id,
			)
		);

		foreach ( $results as $result ) {
			$subscription_item_id = $result->item_id;
			$order_id             = $result->order_id;
			$item_name            = $result->item_name;
			$order_key            = $result->order_key;
			$user_id              = $result->user_id;
			$user_email           = $result->user_email;
			$user_name1           = $result->user_name1;
			$user_name2           = $result->user_name2;
			$tel1                 = $result->tel1;
			$payment_name         = $result->payment_name;
			$order_date_time      = $result->order_date_time;
		}

		if ( '' === get_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_item_id', true ) ) {
			update_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_item_id', $subscription_item_id );
		}
		if ( '' === get_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_order_id', true ) ) {
			update_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_order_id', $order_id );
		}
		if ( '' === get_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_item_name', true ) ) {
			update_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_item_name', $item_name );
		}
		if ( '' === get_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_order_key', true ) ) {
			update_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_order_key', $order_key );
		}
		if ( '' === get_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_order_date', true ) ) {
			update_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_order_date', $order_key );
		}
		if ( '' === get_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_user_id', true ) ) {
			update_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_user_id', $user_id );
		}
		if ( '' === get_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_user_name', true ) ) {
			update_post_meta( $post->ID, 'iihlms_stripe_invoice_payment_user_name', $user_name1 . $user_name2 );
		}

		if ( 'created_st' === $column_name ) {
			$column_value = get_post_time( $this->specify_date_time_format_hyphen, false, $post_id );
			echo esc_html( $column_value );
		}
		if ( 'amount' === $column_name ) {
			$column_value = number_format( (int) $amount_paid );
			echo esc_html( $column_value );
		}
		if ( 'orderkey' === $column_name ) {
			$column_value = $order_key;
			echo esc_html( $column_value );
		}
		if ( 'orderdate' === $column_name ) {
			$column_value = $order_date_time;
			echo esc_html( $column_value );
		}
		if ( 'event_type' === $column_name ) {
			$event_type_for_disp = $this->get_stripe_webhook_event_type_for_disp( $event_type );
			if ( '' === $event_type_for_disp ) {
				$column_value = $event_type;
			} else {
				$column_value = $event_type_for_disp;
			}
			echo esc_html( $column_value );
			if ( 'invoice.payment_failed' === $event_type ) {
				echo '<span style="color: #f00;">';
				echo 'エラー';
				echo '</span>';
			}
		}
		if ( 'itemid' === $column_name ) {
			$column_value = $subscription_item_id;
			$item_url     = get_edit_post_link( $subscription_item_id );
			echo '<a href="';
			echo esc_url( $item_url );
			echo '">';
			echo esc_html( $column_value );
			echo '</a>';
		}
		if ( 'itemname' === $column_name ) {
			$column_value = $item_name;
			$item_url     = get_edit_post_link( $subscription_item_id );
			echo '<a href="';
			echo esc_url( $item_url );
			echo '">';
			echo esc_html( $column_value );
			echo '</a>';
		}
		if ( 'userid' === $column_name ) {
			$column_value = $user_id;
			$user_url     = get_edit_user_link( $user_id );
			echo '<a href="';
			echo esc_url( $user_url );
			echo '">';
			echo esc_html( $column_value );
			echo '</a>';
		}
		if ( 'username' === $column_name ) {
			$column_value = $user_name1 . $user_name2;
			$user_url     = get_edit_user_link( $user_id );
			echo '<a href="';
			echo esc_url( $user_url );
			echo '">';
			echo esc_html( $column_value );
			echo '</a>';
		}
	}
	/**
	 * Stripe 定期購入ログ 列追加
	 *
	 * @param string $sortable_column sortable_column.
	 * @return $sortable_column sortable_column.
	 */
	public function iihlms_wh_stripe_sp_posts_sortable_columns( $sortable_column ) {
		$sortable_column['created_st'] = 'created_st';
		$sortable_column['amount']     = 'amount';
		$sortable_column['orderkey']   = 'orderkey';
		$sortable_column['orderdate']  = 'orderdate';
		$sortable_column['event_type'] = 'event_type';
		$sortable_column['itemid']     = 'itemid';
		$sortable_column['itemname']   = 'itemname';
		$sortable_column['userid']     = 'useride';
		$sortable_column['username']   = 'username';
		return $sortable_column;
	}
	/**
	 * Stripe 定期購入ログ 列追加
	 *
	 * @param object $query query.
	 * @return void
	 */
	public function add_iihlms_wh_stripe_sp_posts_sort( $query ) {
		if ( $query->is_main_query() ) {
			$orderby = $query->get( 'orderby' );

			if ( 'created_st' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_stripe_invoice_payment_created' );
				$query->set( 'orderby', 'meta_value' );
			}
			if ( 'amount' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_stripe_invoice_payment_amount_paid' );
				$query->set( 'orderby', 'meta_value_num' );
			}
			if ( 'orderkey' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_stripe_invoice_payment_order_key' );
				$query->set( 'orderby', 'meta_value' );
			}
			if ( 'orderdate' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_stripe_invoice_payment_order_date' );
				$query->set( 'orderby', 'meta_value' );
			}
			if ( 'event_type' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_stripe_invoice_payment_event_type' );
				$query->set( 'orderby', 'meta_value' );
			}
			if ( 'itemid' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_stripe_invoice_payment_item_id' );
				$query->set( 'orderby', 'meta_value_num' );
			}
			if ( 'itemname' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_stripe_invoice_payment_item_name' );
				$query->set( 'orderby', 'meta_value' );
			}
			if ( 'userid' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_stripe_invoice_payment_user_id' );
				$query->set( 'orderby', 'meta_value_num' );
			}
			if ( 'username' === $orderby ) {
				$query->set( 'meta_key', 'iihlms_stripe_invoice_payment_user_name' );
				$query->set( 'orderby', 'meta_value' );
			}
		}
	}

	/**
	 * Checkboxのサニタイズ
	 *
	 * @param object $input input.
	 * @return bool.
	 */
	public function iihlms_sanitize_checkbox( $input ) {
		if ( isset( $input ) ) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Google reCAPTCHAを使用するか
	 *
	 * @return bool.
	 */
	public function is_recaptcha_on() {
		$iihlms_use_recaptcha = get_option( 'iihlms_use_recaptcha' );
		if ( '1' === $iihlms_use_recaptcha ) {
			return true;
		}
		return false;
	}
	/**
	 * リソースIDからordercartidを取得
	 *
	 * @param string $webhook_resource_id webhook_resource_id.
	 * @return string.
	 */
	public function get_order_cart_id_from_webhook_resource_id( $webhook_resource_id ) {
		global $wpdb;

		$order_cart_meta_table = $wpdb->prefix . 'iihlms_order_cart_meta';
		$search_meta_key       = 'paypal_subscription_response_subscriptionid';

		$order_cart_id = $wpdb->get_var(
			$wpdb->prepare(
				'
				SELECT order_cart_id
				FROM %1s
				WHERE 
					meta_key = %s
					AND meta_value = %s
				',
				$order_cart_meta_table,
				$search_meta_key,
				$webhook_resource_id
			)
		);
		$num           = $wpdb->num_rows;
		if ( 0 === $num ) {
			return '';
		}
		return $order_cart_id;
	}
	/**
	 * Ordercartidから注文番号を取得
	 *
	 * @param string $ordercartid ordercartid.
	 * @return string.
	 */
	public function get_orderkey_from_ordercartid( $ordercartid ) {
		global $wpdb;

		$order_cart_table = $wpdb->prefix . 'iihlms_order_cart';

		$order_key = $wpdb->get_var(
			$wpdb->prepare(
				'
				SELECT order_key
				FROM %1s
				WHERE 
					order_cart_id = %s
				',
				$order_cart_table,
				$ordercartid
			)
		);

		$num = $wpdb->num_rows;
		if ( 0 === $num ) {
			return '';
		}
		return $order_key;
	}

	/**
	 * 記事保存時の処理
	 *
	 * @param array $data An array of slashed, sanitized, and processed post data.
	 * @param array $postarr An array of sanitized (and slashed) but otherwise unmodified post data.
	 * @return $data.
	 */
	public function replace_post_data( $data, $postarr ) {
		if ( ! is_array( $data ) ) {
			return $data;
		}
		if ( ! array_key_exists( 'post_type', $data ) ) {
			return $data;
		}
		if ( 'iihlms_items' === $data['post_type'] ) {
			$data['post_title'] = mb_strimwidth( $data['post_title'], 0, 120 );
		}
		return $data;
	}
	/**
	 * SESSION start
	 *
	 * @return void.
	 */
	public function iihlms_session_start() {
		if ( session_status() !== PHP_SESSION_ACTIVE ) {
			session_start();
		}
	}

	/**
	 * 請求開始日取得
	 *
	 * @param datetime $reference_date 基準日.
	 * @param string   $interval_unit 請求間隔単位.
	 * @param string   $interval_count 請求間隔数.
	 * @return datetime $billing_start_date.
	 */
	public function get_billing_start_date( $reference_date, $interval_unit, $interval_count ) {
		if ( 'd' === $interval_unit ) {
			$billing_start_date = $reference_date->add( new DateInterval( 'P' . $interval_count . 'D' ) );
		}
		if ( 'w' === $interval_unit ) {
			$billing_start_date = $reference_date->add( new DateInterval( 'P' . $interval_count . 'W' ) );
		}
		if ( 'm' === $interval_unit ) {
			$billing_start_date = $this->lmsjp_add_month( $reference_date, $interval_count );
		}
		return $billing_start_date;
	}
	/**
	 * 次回請求日取得
	 *
	 * @param datetime $billing_start_date 請求開始日.
	 * @param string   $interval_unit 請求間隔単位.
	 * @param string   $interval_count 請求間隔数.
	 * @return datetime $billing_date.
	 */
	public function get_next_billing_date( $billing_start_date, $interval_unit, $interval_count ) {

		if ( 0 === (int) $interval_count ) {
			return $billing_start_date;
		}

		$now_date     = current_datetime();
		$billing_date = $billing_start_date;

		while ( $billing_date->format( $this->specify_date_format_hyphen ) <= $now_date->format( $this->specify_date_format_hyphen ) ) {
			if ( 'd' === $interval_unit ) {
				$billing_date = $billing_date->add( new DateInterval( 'P' . $interval_count . 'D' ) );
			} elseif ( 'w' === $interval_unit ) {
				$billing_date = $billing_date->add( new DateInterval( 'P' . $interval_count . 'W' ) );
			} elseif ( 'm' === $interval_unit ) {
				$billing_date = $this->lmsjp_add_month( $billing_date, $interval_count );
			} else {
				return $billing_date;
			}
		}
		return $billing_date;
	}
	/**
	 * 月の加算（月末対応）
	 *
	 * @param DateTime $date 日付.
	 * @param int      $month_val 月数.
	 * @return DateTime 日付
	 */
	public function lmsjp_add_month( $date, $month_val ) {
		$date_month = $date->format( 'n' );

		$after_date       = $date->add( new DateInterval( 'P' . $month_val . 'M' ) );
		$after_date_month = $after_date->format( 'n' );

		$expect_month = ( $date_month + $month_val ) % 12;

		if ( $expect_month !== (int) $after_date_month ) {
			$after_date = $after_date->modify( 'last day of last month' );
		}

		return $after_date;
	}
	/**
	 * 指定した講座に関連するテストが存在するか
	 *
	 * @param string $itemid 講座ID.
	 * @return bool
	 */
	public function tests_associated_with_item_exists_check( $itemid ) {
		global $wpdb;

		$iihlms_item_test_relationship = get_post_meta( $itemid, 'iihlms_item_test_relationship', true );

		if ( '' === $iihlms_item_test_relationship ) {
			return false;
		}

		return true;
	}
	/**
	 * 指定したコースに関連するテストが存在するか
	 *
	 * @param string $courseid コースID.
	 * @return bool
	 */
	public function tests_associated_with_course_exists_check( $courseid ) {
		global $wpdb;

		$iihlms_course_test_relationship = get_post_meta( $courseid, 'iihlms_course_test_relationship', true );

		if ( '' === $iihlms_course_test_relationship ) {
			return false;
		}

		return true;
	}
	/**
	 * 指定したレッスンに関連するテストが存在するか
	 *
	 * @param string $lessonid レッスンID.
	 * @return bool
	 */
	public function tests_associated_with_lesson_exists_check( $lessonid ) {
		global $wpdb;

		$iihlms_lesson_test_relationship = get_post_meta( $lessonid, 'iihlms_lesson_test_relationship', true );

		if ( '' === $iihlms_lesson_test_relationship ) {
			return false;
		}

		return true;
	}

	/**
	 * 「テストに合格するまで次のレッスンに進めないようにする」の設定を確認し、ここから先に進んではいけないレッスンIDを取得する.
	 *
	 * @param string $course_id コースID.
	 * @param string $lesson_id レッスンID.
	 * @param string $user_id ユーザーID.
	 * @return string
	 */
	public function get_lesson_id_lesson_test_cant_proceed_until_pass( $course_id, $lesson_id, $user_id ) {
		global $wpdb;

		// 指定したレッスンが指定したコースに含まれているか.
		$included_flg = $this->lesson_included_specified_course_check( $lesson_id, $course_id );
		if ( false === $included_flg ) {
			return 'error';
		}
		// コースIDをキーに、登録されたレッスン一覧を取得.
		$course_lesson_related = get_post_meta( $course_id, 'iihlms_course_relation', true );
		if ( false === $course_lesson_related ) {
			return 'error';
		}
		$course_lesson_related = isset( $course_lesson_related ) ? (array) $course_lesson_related : array();

		$lesson_cant_proceed_until_pass_array = array();
		foreach ( $course_lesson_related as $key => $value ) {
			// テストのあるレッスン.
			if ( $this->tests_associated_with_lesson_exists_check( $value ) ) {
				// 次のレッスンに進めない設定をしているレッスン.
				$iihlms_lesson_test_cant_proceed_until_pass = get_post_meta( $value, 'iihlms_lesson_test_cant_proceed_until_pass', true );
				if ( 'yes' === $iihlms_lesson_test_cant_proceed_until_pass ) {
					array_push( $lesson_cant_proceed_until_pass_array, $value );
				}
			}
		}
		if ( empty( $lesson_cant_proceed_until_pass_array ) || ( '' === $lesson_cant_proceed_until_pass_array[0] ) ) {
			// 次のレッスンに進めない設定をしているレッスンがない.
			return 'nosetting';
		}
		// カレントユーザーのテスト.
		$search_meta_key = 'iihlms_test_result_userid';
		$results         = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT distinct post_id
				FROM $wpdb->postmeta
				WHERE 
					meta_key = %s
						AND meta_value = %d
				",
				$search_meta_key,
				$user_id
			)
		);

		$current_user_test_array      = array();
		$current_user_test_pass_array = array();
		$current_user_test_fail_array = array();
		foreach ( $results as $row ) {
			$post_status = get_post_status( $row->post_id );
			if ( 'publish' === $post_status ) {
				$iihlms_test_result_pass_fail = get_post_meta( $row->post_id, 'iihlms_test_result_pass_fail', true );
				if ( 'pass' === $iihlms_test_result_pass_fail ) {
					array_push( $current_user_test_pass_array, $row->post_id );
				} else {
					array_push( $current_user_test_fail_array, $row->post_id );
				}
				array_push( $current_user_test_array, $row->post_id );
			}
		}

		$iihlms_test_result_testid_pass_array = array();
		foreach ( $current_user_test_pass_array as $data ) {
			$iihlms_test_result_testid = get_post_meta( $data, 'iihlms_test_result_testid', true );
			array_push( $iihlms_test_result_testid_pass_array, $iihlms_test_result_testid );
		}
		$iihlms_test_result_testid_pass_array = array_unique( $iihlms_test_result_testid_pass_array );

		// 次のレッスンに進めない設定をしているレッスンが入っているのうち、カレントユーザーがテスト合格していないレッスン.
		$lesson_id_not_pass = '';
		foreach ( $lesson_cant_proceed_until_pass_array as $key => $value ) {
			$iihlms_lesson_test_relationship = get_post_meta( $value, 'iihlms_lesson_test_relationship', true );
			if ( ! in_array( $iihlms_lesson_test_relationship, $iihlms_test_result_testid_pass_array, true ) ) {
				$lesson_id_not_pass = $value;
				break;
			}
		}
		return $lesson_id_not_pass;
	}
	/**
	 * 「テストに合格するまで次のコースに進めないようにする」の設定を確認し、ここから先に進んではいけないコースIDを取得する
	 *
	 * @param string $item_id 講座ID.
	 * @param string $course_id コースID.
	 * @param string $user_id ユーザーID.
	 * @return string
	 */
	public function get_course_id_course_test_cant_proceed_until_pass( $item_id, $course_id, $user_id ) {
		global $wpdb;

		// 指定したコースが指定した講座に含まれているか.
		$included_flg = $this->course_included_specified_item_check( $course_id, $item_id );
		if ( false === $included_flg ) {
			return 'error';
		}
		// 講座IDをキーに、登録されたコース一覧を取得.
		$item_course_related = get_post_meta( $item_id, 'iihlms_item_relation', true );
		if ( false === $item_course_related ) {
			return 'error';
		}
		$item_course_related = isset( $item_course_related ) ? (array) $item_course_related : array();

		$course_cant_proceed_until_pass_array = array();
		foreach ( $item_course_related as $key => $value ) {
			// テストのあるコース.
			if ( $this->tests_associated_with_course_exists_check( $value ) ) {
				// 次のコースに進めない設定をしているコース.
				$iihlms_course_test_cant_proceed_until_pass = get_post_meta( $value, 'iihlms_course_test_cant_proceed_until_pass', true );
				if ( 'yes' === $iihlms_course_test_cant_proceed_until_pass ) {
					array_push( $course_cant_proceed_until_pass_array, $value );
				}
			}
		}
		if ( empty( $course_cant_proceed_until_pass_array ) || ( '' === $course_cant_proceed_until_pass_array[0] ) ) {
			// 次のコースに進めない設定をしているコースがない.
			return 'nosetting';
		}

		// カレントユーザーのテスト結果.
		$search_meta_key = 'iihlms_test_result_userid';
		$results         = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT distinct post_id
				FROM $wpdb->postmeta
				WHERE 
					meta_key = %s
						AND meta_value = %d
				",
				$search_meta_key,
				$user_id
			)
		);
		// カレントユーザーのテスト結果のうち、合格したものだけに絞る.
		$current_user_test_array      = array();
		$current_user_test_pass_array = array();
		$current_user_test_fail_array = array();
		foreach ( $results as $row ) {
			$post_status = get_post_status( $row->post_id );
			if ( 'publish' === $post_status ) {
				$iihlms_test_result_pass_fail = get_post_meta( $row->post_id, 'iihlms_test_result_pass_fail', true );
				if ( 'pass' === $iihlms_test_result_pass_fail ) {
					array_push( $current_user_test_pass_array, $row->post_id );
				} else {
					array_push( $current_user_test_fail_array, $row->post_id );
				}
				array_push( $current_user_test_array, $row->post_id );
			}
		}

		// カレントユーザーが合格したテストIDを得る.
		$iihlms_test_result_testid_pass_array = array();
		foreach ( $current_user_test_pass_array as $data ) {
			$iihlms_test_result_testid = get_post_meta( $data, 'iihlms_test_result_testid', true );
			array_push( $iihlms_test_result_testid_pass_array, $iihlms_test_result_testid );
		}
		$iihlms_test_result_testid_pass_array = array_unique( $iihlms_test_result_testid_pass_array );

		// カレントユーザーがテスト合格していないコース.
		$course_id_not_pass = '';
		foreach ( $course_cant_proceed_until_pass_array as $key => $value ) {
			$iihlms_course_test_relationship = get_post_meta( $value, 'iihlms_course_test_relationship', true );
			if ( ! in_array( $iihlms_course_test_relationship, $iihlms_test_result_testid_pass_array, true ) ) {
				$course_id_not_pass = $value;
				break;
			}
		}
		return $course_id_not_pass;
	}
	/**
	 * 「テストに合格するまで次のレッスンに進めないようにする」の設定を確認し、ここから先に進んではいけないレッスンIDを返す.
	 *
	 * @param string $course_id コースID.
	 * @param string $lesson_id レッスンID.
	 * @param string $user_id ユーザーID.
	 * @return string
	 */
	public function get_array_lesson_test_cant_proceed_until_pass( $course_id, $lesson_id, $user_id ) {

		// ここから先に進んではいけないレッスンID.
		$lesson_id_not_pass = $this->get_lesson_id_lesson_test_cant_proceed_until_pass( $course_id, $lesson_id, $user_id );
		if ( 'error' === $lesson_id_not_pass ) {
			return 'error';
		}
		if ( 'nosetting' === $lesson_id_not_pass ) {
			return 'ok';
		}
		if ( '' === $lesson_id_not_pass ) {
			return 'ok';
		}
		// コースIDをキーに、登録されたレッスン一覧を取得.
		$course_lesson_related = get_post_meta( $course_id, 'iihlms_course_relation', true );
		$course_lesson_related = isset( $course_lesson_related ) ? (array) $course_lesson_related : array();

		// $lesson_id_not_passより後のレッスンは表示NG.
		$lesson_id_not_pass_key = array_search( $lesson_id_not_pass, $course_lesson_related, true );
		for ( $i = 0; $i <= $lesson_id_not_pass_key; $i++ ) {
			unset( $course_lesson_related[ $i ] );
		}
		$course_lesson_related = array_values( $course_lesson_related );

		return $course_lesson_related;
	}
	/**
	 * 「テストに合格するまで次のレッスンに進めないようにする」の設定を確認し、表示して良いレッスンかチェックする.
	 *
	 * @param string $course_id コースID.
	 * @param string $lesson_id レッスンID.
	 * @param string $user_id ユーザーID.
	 * @return string
	 */
	public function check_lesson_test_cant_proceed_until_pass( $course_id, $lesson_id, $user_id ) {
		$cant_proceed_until_pass = $this->get_array_lesson_test_cant_proceed_until_pass( $course_id, $lesson_id, $user_id );
		if ( 'error' === $cant_proceed_until_pass ) {
			return 'error';
		}
		if ( 'ok' === $cant_proceed_until_pass ) {
			return 'ok';
		}
		if ( in_array( (string) $lesson_id, $cant_proceed_until_pass, true ) ) {
			return 'ng';
		}
		return 'ok';
	}
	/**
	 * 「テストに合格するまで次のコースに進めないようにする」の設定を確認し、ここから先に進んではいけないコースIDを返す.
	 *
	 * @param string $item_id 講座ID.
	 * @param string $course_id コースID.
	 * @param string $user_id ユーザーID.
	 * @return string
	 */
	public function get_array_course_test_cant_proceed_until_pass( $item_id, $course_id, $user_id ) {
		// ここから先に進んではいけないコースID.
		$course_id_not_pass = $this->get_course_id_course_test_cant_proceed_until_pass( $item_id, $course_id, $user_id );
		if ( 'error' === $course_id_not_pass ) {
			return 'error';
		}
		if ( 'nosetting' === $course_id_not_pass ) {
			return 'ok';
		}
		if ( '' === $course_id_not_pass ) {
			return 'ok';
		}
		// 講座IDをキーに、登録されたコース一覧を取得.
		$item_course_related = get_post_meta( $item_id, 'iihlms_item_relation', true );
		$item_course_related = isset( $item_course_related ) ? (array) $item_course_related : array();

		// $course_id_not_pass が見つかったら、それ以降のは表示したらダメ.
		$course_id_not_pass_key = array_search( $course_id_not_pass, $item_course_related, true );
		for ( $i = 0; $i <= $course_id_not_pass_key; $i++ ) {
			unset( $item_course_related[ $i ] );
		}
		$item_course_related = array_values( $item_course_related );

		return $item_course_related;
	}
	/**
	 * 「テストに合格するまで次のコースに進めないようにする」の設定を確認し、指定したコースを表示してよいかを返す.
	 *
	 * @param string $item_id 講座ID.
	 * @param string $course_id コースID.
	 * @param string $user_id ユーザーID.
	 * @return string
	 */
	public function check_course_test_cant_proceed_until_pass( $item_id, $course_id, $user_id ) {
		$cant_proceed_until_pass = $this->get_array_course_test_cant_proceed_until_pass( $item_id, $course_id, $user_id );
		if ( 'error' === $cant_proceed_until_pass ) {
			return 'error';
		}
		if ( 'ok' === $cant_proceed_until_pass ) {
			return 'ok';
		}
		if ( in_array( (string) $course_id, $cant_proceed_until_pass, true ) ) {
			return 'ng';
		}
		return 'ok';
	}

	/**
	 * 指定したテストに合格しているか.
	 *
	 * @param string $test_id テストID.
	 * @param string $user_id ユーザーID.
	 * @return bool
	 */
	public function check_whether_the_specified_test_is_passed( $test_id, $user_id ) {
		global $wpdb;

		// ユーザーのテスト結果.
		$search_meta_key = 'iihlms_test_result_userid';
		$results         = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT distinct post_id
				FROM $wpdb->postmeta
				WHERE 
					meta_key = %s
						AND meta_value = %d
				",
				$search_meta_key,
				$user_id
			)
		);
		// ユーザーのテスト結果のうち、合格したものだけに絞る.
		$current_user_test_array      = array();
		$current_user_test_pass_array = array();
		$current_user_test_fail_array = array();
		foreach ( $results as $row ) {
			$post_status = get_post_status( $row->post_id );
			if ( 'publish' === $post_status ) {
				$iihlms_test_result_pass_fail = get_post_meta( $row->post_id, 'iihlms_test_result_pass_fail', true );
				if ( 'pass' === $iihlms_test_result_pass_fail ) {
					array_push( $current_user_test_pass_array, $row->post_id );
				} else {
					array_push( $current_user_test_fail_array, $row->post_id );
				}
				array_push( $current_user_test_array, $row->post_id );
			}
		}

		// ユーザーが合格したテストIDを得る.
		$iihlms_test_result_testid_pass_array = array();
		foreach ( $current_user_test_pass_array as $data ) {
			$iihlms_test_result_testid = get_post_meta( $data, 'iihlms_test_result_testid', true );
			array_push( $iihlms_test_result_testid_pass_array, $iihlms_test_result_testid );
		}
		$iihlms_test_result_testid_pass_array = array_unique( $iihlms_test_result_testid_pass_array );

		if ( in_array( (string) $test_id, $iihlms_test_result_testid_pass_array, true ) ) {
			return true;
		}
		return false;
	}
	/**
	 * 指定したコースを開始した日付を得る.
	 *
	 * @param string $course_id コースID.
	 * @param string $user_id ユーザーID.
	 * @return bool
	 */
	public function get_course_start_date( $course_id, $user_id ) {
		global $wpdb;

		// コースに含まれるレッスンの一覧.
		$iihlms_course_relation         = get_post_meta( $course_id, 'iihlms_course_relation', true );
		$iihlms_course_relation_implode = implode( ',', $iihlms_course_relation );

		// レッスンIDをキーとし、受講完了かチェック.
		$user_activity_table = $wpdb->prefix . 'iihlms_user_activity';

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT min(registered_datetime) as course_start_datetime, lesson_id
				FROM %1s
				WHERE 
					user_id = %d
					AND lesson_id IN ( %2s )
				',
				$user_activity_table,
				$user_id,
				$iihlms_course_relation_implode
			)
		);

		$course_start_datetime = '';
		foreach ( $results as $result ) {
			$course_start_datetime = $result->course_start_datetime;
		}

		return $course_start_datetime;
	}

	/**
	 * トライアル期間の日数を返す.
	 *
	 * @param string $interval_count_val 間隔.
	 * @param string $interval_unit_val  値.
	 * @return int
	 */
	public function get_trial_days( $interval_count_val, $interval_unit_val ) {
		if ( '0' === $interval_unit_val ) {
			return 0;
		}
		if ( 'd' === $interval_unit_val ) {
			return (int) $interval_count_val;
		}
		if ( 'w' === $interval_unit_val ) {
			return (int) $interval_count_val * 7;
		}
		if ( 'm' === $interval_unit_val ) {
			$now_date_time      = current_datetime();
			$billing_start_date = $this->get_billing_start_date( $now_date_time, $interval_unit_val, $interval_count_val );
			$date_diff          = $now_date_time->diff( $billing_start_date );
			return (int) $date_diff->days;
		}
		return 0;
	}
	/**
	 * Stripe Webhook Eventの日本語表記を得る
	 *
	 * @param string $event_type イベントのタイプ.
	 * @return string 日本語表記.
	 */
	public function get_stripe_webhook_event_type_for_disp( $event_type ) {
		if ( 'invoice.payment_succeeded' === $event_type ) {
			return esc_html__( '支払成功', 'imaoikiruhitolms' );
		}
		if ( 'customer.subscription.deleted' === $event_type ) {
			return esc_html__( 'サブスクリプションキャンセル', 'imaoikiruhitolms' );
		}
		if ( 'invoice.payment_failed' === $event_type ) {
			return esc_html__( '支払失敗', 'imaoikiruhitolms' );
		}
		return '';
	}
	/**
	 * PayPal Webhook Eventの日本語表記を得る
	 *
	 * @param string $event_type イベントのタイプ.
	 * @return string 日本語表記.
	 */
	public function get_paypal_webhook_event_type_for_disp( $event_type ) {
		if ( 'PAYMENT.SALE.COMPLETED' === $event_type ) {
			return esc_html__( '支払成功', 'imaoikiruhitolms' );
		}
		if ( 'BILLING.SUBSCRIPTION.ACTIVATED' === $event_type ) {
			return esc_html__( 'サブスクリプション有効化', 'imaoikiruhitolms' );
		}
		if ( 'BILLING.SUBSCRIPTION.CANCELLED' === $event_type ) {
			return esc_html__( 'サブスクリプションキャンセル', 'imaoikiruhitolms' );
		}
		if ( 'BILLING.SUBSCRIPTION.SUSPENDED' === $event_type ) {
			return esc_html__( 'サブスクリプション一時停止', 'imaoikiruhitolms' );
		}
		if ( 'BILLING.SUBSCRIPTION.PAYMENT.FAILED' === $event_type ) {
			return esc_html__( '支払失敗', 'imaoikiruhitolms' );
		}
		if ( 'BILLING.SUBSCRIPTION.EXPIRED' === $event_type ) {
			return esc_html__( 'サブスクリプション終了', 'imaoikiruhitolms' );
		}
		return '';
	}
	/**
	 * 小数点以下を切り捨てた数値を得る
	 *
	 * @param double $num 数値.
	 * @return int 小数点以下を切り捨てた数値.
	 */
	public function iihlms_floor( $num ) {
		$num = (string) $num;
		$num = (int) $num;
		$num = floor( $num );
		return $num;
	}
	/**
	 * 消費税額を得る
	 *
	 * @param double $price 価格.
	 * @return int 税額.
	 */
	public function get_consumption_tax_value( $price ) {
		$retval = 0;

		$get_consumption_tax = $this->get_consumption_tax();
		if ( 0 === (int) $get_consumption_tax ) {
			return $retval;
		}

		$retval = $this->iihlms_floor( $price * $get_consumption_tax / 100 );
		return $retval;
	}
	/**
	 * クイック編集を非表示に.
	 *
	 * @param string[] $actions An array of row action links.
	 * @param WP_Post  $post The post object.
	 * @return string[] $actions.
	 */
	public function lmsjp_hide_quickedit( $actions, $post ) {
		if ( 'iihlms_test_results' === get_post_type( $post->id ) ) {
			unset( $actions['inline hide-if-no-js'] );
		}
		if ( 'iihlms_wh_paypal' === get_post_type( $post->id ) ) {
			unset( $actions['inline hide-if-no-js'] );
		}
		if ( 'iihlms_wh_paypal_sp' === get_post_type( $post->id ) ) {
			unset( $actions['inline hide-if-no-js'] );
		}
		if ( 'iihlms_wh_stripe' === get_post_type( $post->id ) ) {
			unset( $actions['inline hide-if-no-js'] );
		}
		if ( 'iihlms_wh_stripe_sp' === get_post_type( $post->id ) ) {
			unset( $actions['inline hide-if-no-js'] );
		}
		return $actions;
	}
	/**
	 * PayPal決済OK、注文処理実行.
	 *
	 * @param string $iihlmsapplyorderid 注文番号.
	 * @param string $iihlmsapplytransactionid トランザクションID.
	 * @return void
	 */
	public function iihlms_paypal_payment_success( $iihlmsapplyorderid, $iihlmsapplytransactionid ) {
		global $post;
		global $wpdb;

		// update.
		$order_cart_table         = $wpdb->prefix . 'iihlms_order_cart';
		$order_cart_meta_table    = $wpdb->prefix . 'iihlms_order_cart_meta';

		$order_cart_id = $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT order_cart_id
				FROM %1s
				WHERE
					meta_key = 'paypal_create_order_response_id'
					AND meta_value = %s
				",
				$order_cart_meta_table,
				$iihlmsapplyorderid
			)
		);

		// order_cart_tableからselectする.
		$results = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT *
				FROM %1s
				WHERE
					order_cart_id = %d
				',
				$order_cart_table,
				$order_cart_id
			)
		);
		foreach ( $results as $result ) {
			$order_cart_id           = $result->order_cart_id;
			$order_cart_user_id      = $result->user_id;
			$order_cart_item_id      = $result->item_id;
			$order_cart_item_name    = $result->item_name;
			$order_cart_price        = $result->price;
			$order_cart_tax          = $result->tax;
			$order_cart_user_email   = $result->user_email;
			$order_cart_user_name1   = $result->user_name1;
			$order_cart_user_name2   = $result->user_name2;
			$order_cart_user_name3   = $result->user_name3;
			$order_cart_user_name4   = $result->user_name4;
			$order_cart_zip          = $result->zip;
			$order_cart_prefectures  = $result->prefectures;
			$order_cart_address1     = $result->address1;
			$order_cart_address2     = $result->address2;
			$order_cart_address3     = $result->address3;
			$order_cart_address4     = $result->address4;
			$order_cart_tel1         = $result->tel1;
			$order_cart_tel2         = $result->tel2;
			$order_cart_fax          = $result->fax;
			$order_cart_payment_name = $result->payment_name;
			$order_cart_order_key    = $result->order_key;
			$order_cart_order_status = $result->order_status;
		}

		$order_status = 'paypal-payment-completed';

		$wpdb->update(
			$order_cart_table,
			array(
				'order_status'    => $order_status,
				'update_datetime' => current_time( 'mysql' ),
			),
			array(
				'order_cart_id' => $order_cart_id,
			),
			array(
				'%s',
				'%s',
			),
			array(
				'%d',
			),
		);

		$order_table = $wpdb->prefix . 'iihlms_order';
		// insert.
		$wpdb->insert(
			$order_table,
			array(
				'user_id'         => $order_cart_user_id,
				'item_id'         => $order_cart_item_id,
				'item_name'       => $order_cart_item_name,
				'user_email'      => $order_cart_user_email,
				'user_name1'      => $order_cart_user_name1,
				'user_name2'      => $order_cart_user_name2,
				'user_name3'      => $order_cart_user_name3,
				'user_name4'      => $order_cart_user_name4,
				'zip'             => $order_cart_zip,
				'prefectures'     => $order_cart_prefectures,
				'address1'        => $order_cart_address1,
				'address2'        => $order_cart_address2,
				'address3'        => $order_cart_address3,
				'address4'        => $order_cart_address4,
				'tel1'            => $order_cart_tel1,
				'tel2'            => $order_cart_tel2,
				'fax'             => $order_cart_fax,
				'payment_name'    => $order_cart_payment_name,
				'order_key'       => $order_cart_order_key,
				'order_status'    => $order_status,
				'price'           => $order_cart_price,
				'tax'             => $order_cart_tax,
				'order_date_time' => current_time( 'mysql' ),
				'order_cart_id'   => $order_cart_id,
			),
			array(
				'%d',
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%d',
				'%s',
				'%d',
			)
		);

		$order_date_time = $wpdb->get_var(
			$wpdb->prepare(
				'
				SELECT order_date_time
				FROM %1s
				WHERE
				user_id = %2d
				AND order_key = %3s
				',
				$order_table,
				$order_cart_user_id,
				$order_cart_order_key,
			)
		);
		$payment_name    = $this->get_payment_name( $order_cart_payment_name );

		$order_data  = esc_html__( '注文番号', 'imaoikiruhitolms' ) . '：' . $order_cart_order_key . PHP_EOL;
		$formatday   = new DateTimeImmutable( $order_date_time );
		$order_data .= esc_html__( '注文日', 'imaoikiruhitolms' ) . '：' . $formatday->format( $this->specify_date_format ) . PHP_EOL . PHP_EOL;

		$order_data .= esc_html__( 'お名前', 'imaoikiruhitolms' ) . '：' . $order_cart_user_name1 . $order_cart_user_name2 . esc_html__( '様', 'imaoikiruhitolms' ) . PHP_EOL;
		$order_data .= esc_html__( 'メールアドレス', 'imaoikiruhitolms' ) . '：' . $order_cart_user_email . PHP_EOL . PHP_EOL;

		$order_data .= esc_html__( '講座名', 'imaoikiruhitolms' ) . '：' . $order_cart_item_name . PHP_EOL;
		$order_data .= esc_html__( '価格', 'imaoikiruhitolms' ) . '：' . $order_cart_price . esc_html__( '円', 'imaoikiruhitolms' ) . '（' . esc_html__( '税込', 'imaoikiruhitolms' ) . '：' . ( $order_cart_price + $order_cart_tax ) . esc_html__( '円', 'imaoikiruhitolms' ) . '）' . PHP_EOL;

		$order_data .= esc_html__( 'お支払い方法', 'imaoikiruhitolms' ) . '：' . $payment_name . PHP_EOL;

		$admin_mail_name                          = $this->get_admin_mailname();
		$admin_mail_address                       = $this->get_admin_mailaddress();
		$iihlms_mailsubject_application_completed = get_option( 'iihlms_mailsubject_application_completed' );
		$iihlms_mailbody_application_completed    = get_option( 'iihlms_mailbody_application_completed' );

		$use_custom_email = get_post_meta( $order_cart_item_id, 'iihlms_use_custom_email', true );
		$custom_subject   = get_post_meta( $order_cart_item_id, 'iihlms_item_email_subject', true );
		$custom_body      = get_post_meta( $order_cart_item_id, 'iihlms_item_email_body', true );

		if ( '1' === $use_custom_email ) {
			if ( ! empty( $custom_subject ) ) {
				$iihlms_mailsubject_application_completed = $custom_subject;
			}
			if ( ! empty( $custom_body ) ) {
				$iihlms_mailbody_application_completed = $custom_body;
			}
		}

		// 予約語の置換.
		$iihlms_mailbody_application_completed = str_replace( '*NAME*', $order_cart_user_name1 . $order_cart_user_name2, $iihlms_mailbody_application_completed );
		$iihlms_mailbody_application_completed = str_replace( '*APPLICATION_DETAILS*', $order_data, $iihlms_mailbody_application_completed );

		// メール送信.
		$mail_subject = $iihlms_mailsubject_application_completed;
		$mail_body    = $iihlms_mailbody_application_completed;
		$headers[]    = 'From: ' . $admin_mail_name . ' <' . $admin_mail_address . '>';
		$ret          = wp_mail( $admin_mail_address, $mail_subject, $mail_body, $headers );
		$ret          = wp_mail( $order_cart_user_email, $mail_subject, $mail_body, $headers );

		// 画面遷移.
		wp_safe_redirect( add_query_arg( array( 'iihlmsapplyorderkey' => $order_cart_order_key ), get_home_url() . '/' . IIHLMS_APPLYRESULTPAGE_NAME . '/' ) );
		exit;
	}

	/**
	 * 無料講座申し込み.
	 *
	 * @param string $iihlms_apply_item_id 講座番号.
	 * @param string $iihlms_apply_type タイプ.
	 * @return void
	 */
	public function iihlms_apply_free_course( $iihlms_apply_item_id, $iihlms_apply_type ) {
		global $post;
		global $wpdb;

		if ( ! isset( $_POST['iihlms-apply-free-csrf'] ) ) {
			$this->show_err_iihlms_apply_page_content( esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' ) );
			exit;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['iihlms-apply-free-csrf'] ) ), 'iihlms-apply-free-csrf-action' ) ) {
			$this->show_err_iihlms_apply_page_content( esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' ) );
			exit;
		}
		$order_table           = $wpdb->prefix . 'iihlms_order';
		$order_cart_table      = $wpdb->prefix . 'iihlms_order_cart';
		$order_cart_meta_table = $wpdb->prefix . 'iihlms_order_cart_meta';

		// 講座の存在チェック.
		if ( ! $this->item_exists_check( $iihlms_apply_item_id ) ) {
			$this->show_err_iihlms_apply_page_content( esc_html__( '講座が存在しません。', 'imaoikiruhitolms' ) );
			exit;
		}
		// 指定した講座IDは購入済か.
		if ( $this->check_item_purchased( $iihlms_apply_item_id ) ) {
			if ( $this->check_item_within_expiration_date( $iihlms_apply_item_id ) ) {
				$this->show_err_iihlms_apply_page_content( esc_html__( '購入済の講座です。', 'imaoikiruhitolms' ) );
				exit;
			}
		}
		if ( '0' !== $this->get_tax_excluded_price_by_id( $iihlms_apply_item_id ) ) {
			$this->show_err_iihlms_apply_page_content( esc_html__( '指定した講座に異常があります。', 'imaoikiruhitolms' ) );
			exit;
		}
		if ( 'free' !== $iihlms_apply_type ) {
			$this->show_err_iihlms_apply_page_content( esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' ) );
			exit;
		}

		$user = wp_get_current_user();

		// 注文登録.
		$email        = $user->user_email;
		$name1        = get_user_meta( $user->ID, 'iihlms_user_name1', true );
		$name2        = get_user_meta( $user->ID, 'iihlms_user_name2', true );
		$tel          = get_user_meta( $user->ID, 'iihlms_user_tel', true );
		$item_data    = $this->get_item_data( $iihlms_apply_item_id );
		$price        = $item_data['price'];
		$tax          = $this->get_consumption_tax_value( $price );
		$item_name    = $item_data['title'];
		$payment      = 'free';
		$order_key    = $this->create_order_key();
		$order_status = 'free-completed';

		$wpdb->insert(
			$order_cart_table,
			array(
				'user_id'             => $user->ID,
				'item_id'             => $iihlms_apply_item_id,
				'item_name'           => $item_name,
				'price'               => $price,
				'tax'                 => $tax,
				'user_email'          => $email,
				'user_name1'          => $name1,
				'user_name2'          => $name2,
				'tel1'                => $tel,
				'payment_name'        => $payment,
				'order_key'           => $order_key,
				'order_status'        => $order_status,
				'registered_datetime' => current_time( 'mysql' ),
			),
			array(
				'%d',
				'%d',
				'%s',
				'%d',
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			)
		);
		$lastid = $wpdb->insert_id;

		$registered_datetime = $wpdb->get_var(
			$wpdb->prepare(
				'
				SELECT registered_datetime
				FROM %1s
				WHERE
					order_cart_id = %s
				',
				$order_cart_table,
				$lastid
			)
		);

		$wpdb->insert(
			$order_table,
			array(
				'user_id'         => $user->ID,
				'item_id'         => $iihlms_apply_item_id,
				'item_name'       => $item_name,
				'user_email'      => $email,
				'user_name1'      => $name1,
				'user_name2'      => $name2,
				'tel1'            => $tel,
				'payment_name'    => $payment,
				'order_key'       => $order_key,
				'order_status'    => $order_status,
				'price'           => $price,
				'tax'             => $tax,
				'order_date_time' => $registered_datetime,
				'order_cart_id'   => $lastid,
			),
			array(
				'%d',
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%d',
				'%s',
				'%d',
			)
		);

		$order_data  = esc_html__( '注文番号', 'imaoikiruhitolms' ) . '：' . $order_key . PHP_EOL;
		$formatday   = new DateTimeImmutable( $registered_datetime );
		$order_data .= esc_html__( '注文日', 'imaoikiruhitolms' ) . '：' . $formatday->format( $this->specify_date_format ) . PHP_EOL . PHP_EOL;

		$order_data .= esc_html__( 'お名前', 'imaoikiruhitolms' ) . '：' . $name1 . $name2 . esc_html__( '様', 'imaoikiruhitolms' ) . PHP_EOL;
		$order_data .= esc_html__( 'メールアドレス', 'imaoikiruhitolms' ) . '：' . $email . PHP_EOL . PHP_EOL;

		$order_data .= esc_html__( '講座名', 'imaoikiruhitolms' ) . '：' . $item_name . PHP_EOL;
		$order_data .= esc_html__( '価格', 'imaoikiruhitolms' ) . '：' . esc_html__( '無料', 'imaoikiruhitolms' ) . PHP_EOL;

		$admin_mail_name                          = $this->get_admin_mailname();
		$admin_mail_address                       = $this->get_admin_mailaddress();
		$iihlms_mailsubject_application_completed = get_option( 'iihlms_mailsubject_application_completed' );
		$iihlms_mailbody_application_completed    = get_option( 'iihlms_mailbody_application_completed' );

		$use_custom_email = get_post_meta( $iihlms_apply_item_id, 'iihlms_use_custom_email', true );
		$custom_subject   = get_post_meta( $iihlms_apply_item_id, 'iihlms_item_email_subject', true );
		$custom_body      = get_post_meta( $iihlms_apply_item_id, 'iihlms_item_email_body', true );

		if ( '1' === $use_custom_email ) {
			if ( ! empty( $custom_subject ) ) {
				$iihlms_mailsubject_application_completed = $custom_subject;
			}
			if ( ! empty( $custom_body ) ) {
				$iihlms_mailbody_application_completed = $custom_body;
			}
		}

		$order_cart_user_email = $email;

		// 予約語の置換.
		$iihlms_mailbody_application_completed = str_replace( '*NAME*', $name1 . $name2, $iihlms_mailbody_application_completed );
		$iihlms_mailbody_application_completed = str_replace( '*APPLICATION_DETAILS*', $order_data, $iihlms_mailbody_application_completed );

		// メール送信.
		$mail_subject = $iihlms_mailsubject_application_completed;
		$mail_body    = $iihlms_mailbody_application_completed;
		$headers[]    = 'From: ' . $admin_mail_name . ' <' . $admin_mail_address . '>';
		$ret          = wp_mail( $admin_mail_address, $mail_subject, $mail_body, $headers );
		$ret          = wp_mail( $order_cart_user_email, $mail_subject, $mail_body, $headers );

		// 画面遷移.
		wp_safe_redirect( add_query_arg( array( 'iihlmsapplyorderkey' => $order_key ), get_home_url() . '/' . IIHLMS_APPLYRESULTPAGE_NAME . '/' ) );
		exit;
	}
	/**
	 * 管理画面の通知.
	 *
	 * @return void
	 */
	public function iihlms_wp_loaded_functions() {
		// 会員種別一覧.
		if ( isset( $_POST['action-type'] ) && ( 'iihlms_form_membership_edit' === $_POST['action-type'] ) ) {
			if ( check_admin_referer( 'iihlms_form_membership_edit_csrf_action', 'iihlms_form_membership_edit_csrf' ) ) {
				$membership_display_message = false;
				foreach ( $_POST as $key => $value ) {
					if ( strpos( $key, 'membership_name' ) !== false ) {
						$membership_display_message = true;
					}
					if ( strpos( $key, 'membership_delete' ) !== false ) {
						$membership_display_message = true;
					}
				}
				if ( true === $membership_display_message ) {
					$message = esc_html__( '更新しました', 'imaoikiruhitolms' );
					$this->set_admin_notice_message( 'lmsjp-custom-admin-errors', 'success', $message, 5 );
				}
			}
		}
		// 会員種別追加.
		if ( isset( $_POST['action-type'] ) && ( 'iihlms-form-membership-add' === $_POST['action-type'] ) ) {
			if ( check_admin_referer( 'iihlms-form-membership-add-csrf-action', 'iihlms-form-membership-add-csrf' ) ) {
				$message = esc_html__( '追加しました', 'imaoikiruhitolms' );
				$this->set_admin_notice_message( 'lmsjp-custom-admin-errors', 'success', $message, 5 );
			}
		}
		// メール設定.
		if ( isset( $_POST['action-type'] ) && ( 'iihlms-form-mail-setting' === $_POST['action-type'] ) ) {
			if ( check_admin_referer( 'iihlms-form-mail-setting-csrf-action', 'iihlms-form-mail-setting-csrf' ) ) {
				$message = esc_html__( '更新しました', 'imaoikiruhitolms' );
				$this->set_admin_notice_message( 'lmsjp-custom-admin-errors', 'success', $message, 5 );
			}
		}
		// システム設定.
		if ( isset( $_POST['action-type'] ) && ( 'iihlms-system-setting' === $_POST['action-type'] ) ) {
			if ( check_admin_referer( 'iihlms-system-setting-csrf-action', 'iihlms-system-setting-csrf' ) ) {
				$message = esc_html__( '更新しました', 'imaoikiruhitolms' );
				$this->set_admin_notice_message( 'lmsjp-custom-admin-errors', 'success', $message, 5 );
			}
		}

		// reCAPTCHA設定.
		if ( isset( $_POST['action-type'] ) && ( 'iihlms-recaptcha-setting' === $_POST['action-type'] ) ) {
			if ( check_admin_referer( 'iihlms-recaptcha-setting-csrf-action', 'iihlms-recaptcha-setting-csrf' ) ) {
				$message = esc_html__( '更新しました', 'imaoikiruhitolms' );
				$this->set_admin_notice_message( 'lmsjp-custom-admin-errors', 'success', $message, 5 );
			}
		}

		// 支払い方法設定.
		if ( isset( $_POST['action-type'] ) && ( 'iihlms-form-payment-method-setting' === $_POST['action-type'] ) ) {
			if ( check_admin_referer( 'iihlms-form-payment-method-setting-csrf-action', 'iihlms-form-payment-method-setting-csrf' ) ) {
				$message = esc_html__( '更新しました', 'imaoikiruhitolms' );
				$this->set_admin_notice_message( 'lmsjp-custom-admin-errors', 'success', $message, 5 );
			}
		}
	}
	/**
	 * 注文ページエラー表示
	 *
	 * @return string
	 */
	public function iihlms_admin_login_page() {
		if ( current_user_can( self::CAPABILITY_ADMIN ) ) {
			return '/wp-admin';
		}
		return get_home_url();
	}
	/**
	 * Dropboxに保存した音声をaudioタグで表示するショートコード.
	 *
	 * @param array $atts Shortcode attributes (not used).
	 * @return string HTML output of audio files.
	 */
	public function render_audio_files( $atts ) {
		global $post;

		$audio_files = get_post_meta( $post->ID, 'iihlms_audio_files', true );

		if ( empty( $audio_files ) ) {
			return '';
		}

		$output = '<div class="audio-files">';
		foreach ( $audio_files as $audio ) {
			$audio_url = str_replace( 'dl=0', 'dl=1', $audio['url'] );
			$clean_url = strtok( $audio_url, '?' );
			$file_extension = pathinfo( $clean_url, PATHINFO_EXTENSION );

			$controlslist = isset( $audio['downloadable'] ) && $audio['downloadable'] ? ' controlslist="nodownload" oncontextmenu="return false;"' : '';

			$mime_type = '';
			switch ( strtolower( $file_extension ) ) {
				case 'mp3':
					$mime_type = 'audio/mpeg';
					break;
				case 'ogg':
					$mime_type = 'audio/ogg';
					break;
				case 'wav':
					$mime_type = 'audio/wav';
					break;
				default:
					$mime_type = '';
					break;
			}

			$output .= '<div class="audio-file">';
			$output .= '<p class="audio-file-name">' . esc_html( $audio['name'] ) . '</p>';
			$output .= '<audio controls' . $controlslist . '>';
			$output .= '<source src="' . esc_url( $audio_url ) . '"';
			if ( ! empty( $mime_type ) ) {
				$output .= ' type="' . esc_attr( $mime_type ) . '"';
			}
			$output .= '>';
			$output .= esc_html__( 'audioの再生をブラウザがサポートしていません', 'imaoikiruhitolms' );
			$output .= '</audio>';
			$output .= '</div>';
		}
		$output .= '</div>';

		return $output;
	}
}
