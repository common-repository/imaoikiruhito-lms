<?php
/**
 * Imaoikiruhito LMS admin-order-detail.php
 * Description: 注文詳細
 * Author: Imaoikiruhito
 * Author URI: https://www.imaoikiruhito.com/
 * License: GPLv2
 *
 * @package Imaoikiruhito LMS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
?>
<div id="iihlms-wrap" class="wrap">
	<?php
	global $wpdb;

	if ( ! isset( $_GET['iihlms_nonce'] ) ) {
		echo esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' );
		exit;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['iihlms_nonce'] ) ), 'iihlms_nonce_orderlistpage_action' ) ) {
		echo esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' );
		exit;
	}
	if ( ! isset( $_GET['action'] ) ) {
		echo esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' );
		exit;
	}
	if ( 'edit' !== $_GET['action'] ) {
		echo esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' );
		exit;
	}
	if ( ! isset( $_GET['iihlms-orderid'] ) ) {
		echo esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' );
		exit;
	} else {
		$order_id = sanitize_text_field( wp_unslash( $_GET['iihlms-orderid'] ) );
	}
	echo '<h1 class="wp-heading-inline">' . esc_html__( '注文詳細', 'imaoikiruhitolms' ) . '</h1>';
	echo '<hr class="wp-header-end">';

	$order_table = $wpdb->prefix . 'iihlms_order';

	$results = $wpdb->get_results(
		$wpdb->prepare(
			'
			SELECT *
			FROM %1s
			WHERE order_id = %d
			',
			$order_table,
			$order_id
		)
	);
	if ( 0 === $wpdb->num_rows ) {
		echo esc_html__( 'データがありません。', 'imaoikiruhitolms' );
		exit;
	}

	$order_cart_table      = $wpdb->prefix . 'iihlms_order_cart';
	$order_cart_meta_table = $wpdb->prefix . 'iihlms_order_cart_meta';
	foreach ( $results as $result_data ) {
		$order_id             = $result_data->order_id;
		$order_date_time      = $result_data->order_date_time;
		$payment_name         = $result_data->payment_name;
		$order_key            = $result_data->order_key;
		$price                = $result_data->price;
		$tax_data             = $result_data->tax;
		$item_name            = $result_data->item_name;
		$item_id              = $result_data->item_id;
		$user_id              = $result_data->user_id;
		$user_email_o         = $result_data->user_email;
		$user_name1           = $result_data->user_name1;
		$user_name2           = $result_data->user_name2;
		$user_tel             = $result_data->tel1;
		$order_status         = $result_data->order_status;
		$order_cart_id        = $result_data->order_cart_id;
		$expiration_date_time = $result_data->expiration_date_time;

		$iihlms_item_subscription_price = $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT *
				FROM %1s
				WHERE 
					order_cart_id = %s
					AND meta_key = 'iihlms_item_subscription_price'
				",
				$order_cart_meta_table,
				$order_cart_id
			)
		);

		$iihlms_item_subscription_price = $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT meta_value
				FROM %1s
				WHERE 
					order_cart_id = %s
					AND meta_key = 'iihlms_item_subscription_price_paypal'
				",
				$order_cart_meta_table,
				$order_cart_id
			)
		);

		$iihlms_item_subscription_tax = $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT meta_value
				FROM %1s
				WHERE 
					order_cart_id = %s
					AND meta_key = 'iihlms_item_subscription_tax_paypal'
				",
				$order_cart_meta_table,
				$order_cart_id
			)
		);

		$iihlms_item_subscription_interval_count = $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT meta_value
				FROM %1s
				WHERE 
					order_cart_id = %s
					AND meta_key = 'iihlms_item_subscription_interval_count_paypal'
				",
				$order_cart_meta_table,
				$order_cart_id
			)
		);

		$iihlms_item_subscription_interval_unit = $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT meta_value
				FROM %1s
				WHERE 
					order_cart_id = %s
					AND meta_key = 'iihlms_item_subscription_interval_unit_paypal'
				",
				$order_cart_meta_table,
				$order_cart_id
			)
		);

		$iihlms_item_subscription_total_cycles = $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT meta_value
				FROM %1s
				WHERE 
					order_cart_id = %s
					AND meta_key = 'iihlms_item_subscription_total_cycles_paypal'
				",
				$order_cart_meta_table,
				$order_cart_id
			)
		);

		$iihlms_item_subscription_payment_failure_threshold = $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT meta_value
				FROM %1s
				WHERE 
					order_cart_id = %s
					AND meta_key = 'iihlms_item_subscription_payment_failure_threshold_paypal'
				",
				$order_cart_meta_table,
				$order_cart_id
			)
		);

		$iihlms_item_subscription_trial_price = $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT meta_value
				FROM %1s
				WHERE 
					order_cart_id = %s
					AND meta_key = 'iihlms_item_subscription_trial_price_paypal'
				",
				$order_cart_meta_table,
				$order_cart_id
			)
		);

		$iihlms_item_subscription_trial_tax = $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT meta_value
				FROM %1s
				WHERE 
					order_cart_id = %s
					AND meta_key = 'iihlms_item_subscription_trial_tax_paypal'
				",
				$order_cart_meta_table,
				$order_cart_id
			)
		);

		$iihlms_item_subscription_trial_interval_count = $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT meta_value
				FROM %1s
				WHERE 
					order_cart_id = %s
					AND meta_key = 'iihlms_item_subscription_trial_interval_count_paypal'
				",
				$order_cart_meta_table,
				$order_cart_id
			)
		);

		$iihlms_item_subscription_trial_interval_unit = $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT meta_value
				FROM %1s
				WHERE 
					order_cart_id = %s
					AND meta_key = 'iihlms_item_subscription_trial_interval_unit_paypal'
				",
				$order_cart_meta_table,
				$order_cart_id
			)
		);

		echo '<table class="form-table" role="presentation">';
		echo '<tbody>';
		echo '<tr>';
		echo '<th scope="row">' . esc_html__( 'ID', 'imaoikiruhitolms' ) . '</th>';
		echo '<td>' . esc_html( $order_id );
		echo '<p class="description"></p>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th scope="row">' . esc_html__( '注文番号', 'imaoikiruhitolms' ) . '</th>';
		echo '<td>' . esc_html( $order_key );
		echo '<p class="description"></p>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th scope="row">' . esc_html__( '注文日時', 'imaoikiruhitolms' ) . '</th>';
		echo '<td>' . esc_html( $order_date_time );
		echo '<p class="description"></p>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th scope="row">' . esc_html__( '支払方法', 'imaoikiruhitolms' ) . '</th>';
		echo '<td>' . esc_html( $this->get_payment_name( $payment_name ) );
		echo '<p class="description"></p>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th scope="row">' . esc_html__( '状態', 'imaoikiruhitolms' ) . '</th>';
		echo '<td>' . esc_html( $this->get_order_status_name( $order_status ) );
		echo '<p class="description"></p>';
		echo '</td>';
		echo '</tr>';
		echo '</tbody>';
		echo '</table>';

		echo '<hr>';
		echo '<table class="form-table" role="presentation">';
		echo '<tbody>';
		echo '<tr>';
		echo '<th scope="row">' . esc_html__( 'ユーザーID', 'imaoikiruhitolms' ) . '</th>';
		echo '<td>';
		echo '<a href="' . esc_url( add_query_arg( 'user_id', absint( $user_id ), admin_url( 'user-edit.php' ) ) ) . '">';
		echo esc_html( $user_id );
		echo '</a>';
		echo '<p class="description"></p>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th scope="row">' . esc_html__( 'メールアドレス', 'imaoikiruhitolms' ) . '</th>';
		echo '<td>' . esc_html( $user_email_o );
		echo '<p class="description"></p>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th scope="row">' . esc_html__( '姓', 'imaoikiruhitolms' ) . '</th>';
		echo '<td>' . esc_html( $user_name1 );
		echo '<p class="description"></p>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th scope="row">' . esc_html__( '名', 'imaoikiruhitolms' ) . '</th>';
		echo '<td>' . esc_html( $user_name2 );
		echo '<p class="description"></p>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th scope="row">' . esc_html__( '電話番号', 'imaoikiruhitolms' ) . '</th>';
		echo '<td>' . esc_html( $user_tel );
		echo '<p class="description"></p>';
		echo '</td>';
		echo '</tr>';
		echo '</tbody>';
		echo '</table>';
		echo '<hr>';

		echo '<table class="form-table" role="presentation">';
		echo '<tbody>';
		echo '<tr>';
		echo '<th scope="row">' . esc_html__( '講座名', 'imaoikiruhitolms' ) . '</th>';
		echo '<td>' . esc_html( $item_name );
		echo '<p class="description"></p>';
		echo '</td>';
		echo '</tr>';
		if ( 'paypal-subscription' !== $payment_name ) {
			echo '<tr>';
			echo '<th scope="row">' . esc_html__( '料金', 'imaoikiruhitolms' ) . '</th>';
			echo '<td>';
			echo esc_html( '&yen;' . number_format( $price + $tax_data ) );
			echo '<p class="description"></p>';
			echo '</td>';
			echo '</tr>';
		}
		if ( 'paypal-subscription' === $payment_name ) {
			echo '<tr>';
			echo '<th scope="row">' . esc_html__( 'トライアル開始', 'imaoikiruhitolms' ) . '</th>';
			echo '<td>';
			if ( '0' === $iihlms_item_subscription_trial_interval_count ) {
				echo esc_html__( '指定無し', 'imaoikiruhitolms' );
			} else {
				$formatday = new DateTimeImmutable( $order_date_time );
				echo esc_html( $formatday->format( $this->specify_date_format ) );
			}
			echo '</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th scope="row">' . esc_html__( 'トライアル期間', 'imaoikiruhitolms' ) . '</th>';
			echo '<td>';
			if ( '0' === $iihlms_item_subscription_trial_interval_count ) {
				echo esc_html__( '指定無し', 'imaoikiruhitolms' );
			} else {
				echo esc_html( $iihlms_item_subscription_trial_interval_count );
				echo esc_html( $this->get_interval_unit_for_disp( $iihlms_item_subscription_trial_interval_unit ) );
			}
			echo '</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th scope="row">' . esc_html__( 'トライアル料金', 'imaoikiruhitolms' ) . '</th>';
			echo '<td>';
			echo esc_html( '&yen;' . number_format( $iihlms_item_subscription_trial_price + $iihlms_item_subscription_trial_tax ) );
			echo '</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th scope="row">' . esc_html__( 'サブスクリプション請求開始', 'imaoikiruhitolms' ) . '</th>';
			echo '<td>';
			$date               = new DateTimeImmutable( $order_date_time );
			$billing_start_date = $this->get_billing_start_date( $date, $iihlms_item_subscription_trial_interval_unit, $iihlms_item_subscription_trial_interval_count );
			echo esc_html( $billing_start_date->format( $this->specify_date_format ) );
			echo '</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th scope="row">' . esc_html__( '請求間隔', 'imaoikiruhitolms' ) . '</th>';
			echo '<td>';
			echo esc_html( $iihlms_item_subscription_interval_count ) . esc_html( $this->get_interval_unit_for_disp_long( $iihlms_item_subscription_interval_unit ) );
			echo '</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th scope="row">' . esc_html__( '合計請求数', 'imaoikiruhitolms' ) . '</th>';
			echo '<td>';
			if ( '0' === $iihlms_item_subscription_total_cycles ) {
				echo esc_html__( '指定無し', 'imaoikiruhitolms' );
			} else {
				echo esc_html( $iihlms_item_subscription_total_cycles );
			}
			echo '</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th scope="row">' . esc_html__( '料金', 'imaoikiruhitolms' ) . '</th>';
			echo '<td>';
			$iihlms_item_subscription_price_tax_included = $iihlms_item_subscription_price + $iihlms_item_subscription_tax;
			echo esc_html( $iihlms_item_subscription_price ) . esc_html__( '円', 'imaoikiruhitolms' ) . '（' . esc_html__( '税込', 'imaoikiruhitolms' ) . '：' . esc_html( $iihlms_item_subscription_price_tax_included ) . esc_html__( '円', 'imaoikiruhitolms' ) . '）';
			echo '<p class="description"></p>';
			echo '</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th scope="row">' . esc_html__( '有効期限', 'imaoikiruhitolms' ) . '</th>';
			echo '<td>';
			if ( '0000-00-00 00:00:00' !== $expiration_date_time ) {
				$formatday = new DateTimeImmutable( $expiration_date_time );
				echo esc_html( $formatday->format( $this->specify_date_format ) );
			} else {
				echo esc_html__( '指定無し', 'imaoikiruhitolms' );
			}
			echo '<p class="description"></p>';
			echo '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
	}
	?>
	</div>
</div>
