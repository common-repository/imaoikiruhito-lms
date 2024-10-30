<?php
/**
 * Imaoikiruhito LMS admin-order-list.php
 * Description: 注文一覧
 * Author: Imaoikiruhito
 * Author URI: https://www.imaoikiruhito.com/
 * License: GPLv2
 *
 * @package Imaoikiruhito LMS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
$disp = 'list';
if ( isset( $_GET['action'] ) ) {
	if ( ! isset( $_GET['iihlms_nonce'] ) ) {
		echo esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' );
		exit;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['iihlms_nonce'] ) ), 'iihlms_nonce_orderlistpage_action' ) ) {
		echo esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' );
		exit;
	}
	if ( 'edit' === $_GET['action'] ) {
		$this->order_detail_page();
		$disp = 'detail';
	}
}
if ( 'list' === $disp ) {
	?>
<div id="iihlms-wrap" class="wrap">
	<?php
	echo '<h1 class="wp-heading-inline">' . esc_html__( '注文一覧', 'imaoikiruhitolms' ) . '</h1>';
	echo '<hr class="wp-header-end">';
	global $wpdb;

	if ( ! isset( $_GET['iihlmspageoffset'] ) ) {
		$iihlmspageoffset = 1;
	} else {
		if ( ! isset( $_GET['iihlms_nonce'] ) ) {
			$iihlmspageoffset = 1;
		} else {
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['iihlms_nonce'] ) ), 'iihlms_nonce_orderlistpage_action' ) ) {
				echo esc_html__( 'アクセスに異常を検出しました。', 'imaoikiruhitolms' );
				exit;
			}
			$iihlmspageoffset = absint( wp_unslash( $_GET['iihlmspageoffset'] ) );
			if ( 0 === $iihlmspageoffset ) {
				$iihlmspageoffset = 1;
			}
		}
	}

	$order_table   = $wpdb->prefix . 'iihlms_order';
	$disp_per_page = 50;
	$offset        = $disp_per_page * ( $iihlmspageoffset - 1 );

	$status_search_array = array(
		'paypal-payment-completed',
		// 'paypal-subscription-registration-completed',
		// 'manual-deletion-by-administrator',
		// 'manual-assignment-by-administrator',
		// 'paypal-subscription-cancelled',
		'free-completed',
		'stripe-payment-completed',
		// 'stripe-subscription-registration-completed',
		// 'stripe-subscription-cancelled',
	);

	$max_row_count = $wpdb->get_var(
		$wpdb->prepare(
			'
			SELECT count(order_id) count
			FROM %1s
			WHERE order_status IN ( %s, %s, %s )
			',
			$order_table,
			$status_search_array[0],
			$status_search_array[1],
			$status_search_array[2],
		)
	);

	$results = $wpdb->get_results(
		$wpdb->prepare(
			'
			SELECT *
			FROM %1s
			WHERE order_status IN ( %s, %s, %s )
			ORDER BY order_id DESC
			LIMIT %d OFFSET %d
			',
			$order_table,
			$status_search_array[0],
			$status_search_array[1],
			$status_search_array[2],
			$disp_per_page,
			$offset,
		)
	);

	$total_page = (int) ceil( $max_row_count / $disp_per_page );
	if ( 1 === $iihlmspageoffset ) {
		$prev_page = 1;
	} else {
		$prev_page = $iihlmspageoffset - 1;
	}
	if ( $total_page < $iihlmspageoffset + 1 ) {
		$next_page = $total_page;
	} else {
		$next_page = $iihlmspageoffset + 1;
	}

	echo '<form action="" method="GET">';
	echo '<input type="hidden" name="page" value="iihlms_order_list_page">';
	echo '<div class="tablenav top">';
	echo '<div class="tablenav-pages"><span class="displaying-num">' . (int) $max_row_count . esc_html__( '件', 'imaoikiruhitolms' ) . '</span>';
	if ( 1 === $iihlmspageoffset ) {
		echo '<span class="pagination-links"><a class="first-page button disabled" href="' . esc_url( add_query_arg( 'iihlmspageoffset', 1, wp_nonce_url( admin_url( 'admin.php?page=iihlms_order_list_page' ), 'iihlms_nonce_orderlistpage_action', 'iihlms_nonce' ) ) ) . '"><span class="screen-reader-text">' . esc_html__( '最初の固定ページ', 'imaoikiruhitolms' ) . '</span><span aria-hidden="true">«</span></a>';
		echo '<a class="prev-page button disabled" href="' . esc_url( add_query_arg( 'iihlmspageoffset', absint( $prev_page ), wp_nonce_url( admin_url( 'admin.php?page=iihlms_order_list_page' ), 'iihlms_nonce_orderlistpage_action', 'iihlms_nonce' ) ) ) . '"><span class="screen-reader-text">' . esc_html__( '前のページ', 'imaoikiruhitolms' ) . '</span><span aria-hidden="true">‹</span></a>';
	} else {
		echo '<span class="pagination-links"><a class="first-page button" href="' . esc_url( add_query_arg( 'iihlmspageoffset', 1, wp_nonce_url( admin_url( 'admin.php?page=iihlms_order_list_page' ), 'iihlms_nonce_orderlistpage_action', 'iihlms_nonce' ) ) ) . '"><span class="screen-reader-text">' . esc_html__( '最初の固定ページ', 'imaoikiruhitolms' ) . '</span><span aria-hidden="true">«</span></a>';
		echo '<a class="prev-page button" href="' . esc_url( add_query_arg( 'iihlmspageoffset', absint( $prev_page ), wp_nonce_url( admin_url( 'admin.php?page=iihlms_order_list_page' ), 'iihlms_nonce_orderlistpage_action', 'iihlms_nonce' ) ) ) . '"><span class="screen-reader-text">' . esc_html__( '前のページ', 'imaoikiruhitolms' ) . '</span><span aria-hidden="true">‹</span></a>';
	}
	echo '<span class="paging-input"><label for="current-page-selector" class="screen-reader-text">' . esc_html__( '現在のページ', 'imaoikiruhitolms' ) . '</label><input class="current-page" id="current-page-selector" type="text" name="iihlmspageoffset" value="' . esc_attr( $iihlmspageoffset ) . '" size="1" aria-describedby="table-paging"><span class="tablenav-paging-text"> / <span class="total-pages">' . esc_html( $total_page ) . '</span></span></span>';
	if ( $total_page < $iihlmspageoffset + 1 ) {
		echo '<a class="next-page button disabled" href="' . esc_url( add_query_arg( 'iihlmspageoffset', absint( $next_page ), wp_nonce_url( admin_url( 'admin.php?page=iihlms_order_list_page' ), 'iihlms_nonce_orderlistpage_action', 'iihlms_nonce' ) ) ) . '"><span class="screen-reader-text">' . esc_html__( '次のページ', 'imaoikiruhitolms' ) . '</span><span aria-hidden="true">›</span></a>';
		echo '<a class="last-page button disabled" href="' . esc_url( add_query_arg( 'iihlmspageoffset', absint( $total_page ), wp_nonce_url( admin_url( 'admin.php?page=iihlms_order_list_page' ), 'iihlms_nonce_orderlistpage_action', 'iihlms_nonce' ) ) ) . '"><span class="screen-reader-text">' . esc_html__( '最後のページ', 'imaoikiruhitolms' ) . '</span><span aria-hidden="true">»</span></a></span>';
	} else {
		echo '<a class="next-page button" href="' . esc_url( add_query_arg( 'iihlmspageoffset', absint( $next_page ), wp_nonce_url( admin_url( 'admin.php?page=iihlms_order_list_page' ), 'iihlms_nonce_orderlistpage_action', 'iihlms_nonce' ) ) ) . '"><span class="screen-reader-text">' . esc_html__( '次のページ', 'imaoikiruhitolms' ) . '</span><span aria-hidden="true">›</span></a>';
		echo '<a class="last-page button" href="' . esc_url( add_query_arg( 'iihlmspageoffset', absint( $total_page ), wp_nonce_url( admin_url( 'admin.php?page=iihlms_order_list_page' ), 'iihlms_nonce_orderlistpage_action', 'iihlms_nonce' ) ) ) . '"><span class="screen-reader-text">' . esc_html__( '最後のページ', 'imaoikiruhitolms' ) . '</span><span aria-hidden="true">»</span></a></span>';
	}
	echo '</div>';
	echo '</div>';
	echo '</form>';

	echo '<table class="wp-list-table widefat fixed striped table-view-list">';
	echo '<thead>';
	echo '<tr>';
	echo '<th class="manage-column"><span>' . esc_html__( 'ID', 'imaoikiruhitolms' ) . '</span></th><th class="manage-column"><span>' . esc_html__( '登録日時', 'imaoikiruhitolms' ) . '</span></th><th class="manage-column"><span>' . esc_html__( '支払方法', 'imaoikiruhitolms' ) . '</span></th><th class="manage-column"><span>' . esc_html__( '注文番号', 'imaoikiruhitolms' ) . '</span></th><th class="manage-column"><span>' . esc_html__( '金額(税込)', 'imaoikiruhitolms' ) . '</span></th><th class="manage-column"><span>' . esc_html__( '講座名', 'imaoikiruhitolms' ) . '</span></th><th class="manage-column"><span>' . esc_html__( 'ユーザーID', 'imaoikiruhitolms' ) . '</span></th><th class="manage-column"><span>' . esc_html__( 'ユーザー名', 'imaoikiruhitolms' ) . '</span></th>';
	echo '<th class="manage-column"><span>' . esc_html__( 'ステータス', 'imaoikiruhitolms' ) . '</span></th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
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
		$user_name1           = $result_data->user_name1;
		$user_name2           = $result_data->user_name2;
		$order_status         = $result_data->order_status;
		$order_cart_id        = $result_data->order_cart_id;
		$expiration_date_time = $result_data->expiration_date_time;

		if ( 'paypal-subscription' === $payment_name ) {
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
		}

		if ( 'stripe-subscription' === $payment_name ) {
			$iihlms_item_subscription_price = $wpdb->get_var(
				$wpdb->prepare(
					"
					SELECT meta_value
					FROM %1s
					WHERE 
						order_cart_id = %s
						AND meta_key = 'iihlms_item_subscription_price_stripe'
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
						AND meta_key = 'iihlms_item_subscription_tax_stripe'
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
						AND meta_key = 'iihlms_item_subscription_interval_count_stripe'
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
						AND meta_key = 'iihlms_item_subscription_interval_unit_stripe'
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
						AND meta_key = 'iihlms_item_subscription_total_cycles_stripe'
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
						AND meta_key = 'iihlms_item_subscription_payment_failure_threshold_stripe'
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
						AND meta_key = 'iihlms_item_subscription_trial_price_stripe'
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
						AND meta_key = 'iihlms_item_subscription_trial_tax_stripe'
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
						AND meta_key = 'iihlms_item_subscription_trial_interval_count_stripe'
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
						AND meta_key = 'iihlms_item_subscription_trial_interval_unit_stripe'
					",
					$order_cart_meta_table,
					$order_cart_id
				)
			);
		}

		echo '<tr>';
		echo '<td>';
		$query_array = array(
			'action' => 'edit',
			'iihlms-orderid' => esc_attr( $order_id ),
		);
		echo '<a href="' . esc_url( add_query_arg( $query_array, wp_nonce_url( admin_url( 'admin.php?page=iihlms_order_list_page' ), 'iihlms_nonce_orderlistpage_action', 'iihlms_nonce' ) ) ) . '">';
		echo esc_html( $order_id );
		echo '</a>';
		echo '</td>';
		echo '<td>';
		echo esc_html( $order_date_time );
		echo '</td>';
		echo '<td>';
		echo esc_html( $this->get_payment_name( $payment_name ) );
		echo '</td>';
		echo '<td>';
		echo esc_html( $order_key );
		echo '</td>';
		echo '<td>';
		if ( 'paypal-subscription' === $payment_name ) {
			echo esc_html( '&yen;' . number_format( $iihlms_item_subscription_price + $iihlms_item_subscription_tax ) );
			echo esc_html( '(' . $iihlms_item_subscription_interval_count . $this->get_interval_unit_for_disp( $iihlms_item_subscription_interval_unit ) . esc_html__( '毎', 'imaoikiruhitolms' ) );
			if ( $iihlms_item_subscription_total_cycles > 0 ) {
				echo esc_html__( '、', 'imaoikiruhitolms' );
				echo esc_html( $iihlms_item_subscription_total_cycles );
				echo esc_html__( '回', 'imaoikiruhitolms' );
			}
			echo esc_html( ')' );
			echo '<br>';
			echo esc_html( '&yen;' . number_format( $iihlms_item_subscription_trial_price + $iihlms_item_subscription_trial_tax ) );
			echo '(';
			echo esc_html( $iihlms_item_subscription_trial_interval_count );
			echo esc_html( $this->get_interval_unit_for_disp( $iihlms_item_subscription_trial_interval_unit ) );
			echo esc_html__( 'トライアル', 'imaoikiruhitolms' );
			echo ')';
		} else {
			echo esc_html( '&yen;' . number_format( $price + $tax_data ) );
		}
		echo '</td>';
		echo '<td>';
		echo esc_html( $item_name );
		echo '</td>';
		echo '<td>';
		echo esc_html( $user_id );
		echo '</td>';
		echo '<td>';
		echo esc_html( $user_name1 . $user_name2 );
		echo '</td>';
		echo '<td>';
		echo esc_html( $this->get_order_status_name( $order_status ) );
		echo '</td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '<tfoot>';
	echo '<tr>';
	echo '<th class="manage-column"><span>' . esc_html__( 'ID', 'imaoikiruhitolms' ) . '</span></th><th class="manage-column"><span>' . esc_html__( '登録日時', 'imaoikiruhitolms' ) . '</span></th><th class="manage-column"><span>' . esc_html__( '支払方法', 'imaoikiruhitolms' ) . '</span></th><th class="manage-column"><span>' . esc_html__( '注文番号', 'imaoikiruhitolms' ) . '</span></th><th class="manage-column"><span>' . esc_html__( '金額(税込)', 'imaoikiruhitolms' ) . '</span></th><th class="manage-column"><span>' . esc_html__( '講座名', 'imaoikiruhitolms' ) . '</span></th><th class="manage-column"><span>' . esc_html__( 'ユーザーID', 'imaoikiruhitolms' ) . '</span></th><th class="manage-column"><span>' . esc_html__( 'ユーザー名', 'imaoikiruhitolms' ) . '</span></th>';
	echo '<th class="manage-column"><span>' . esc_html__( 'ステータス', 'imaoikiruhitolms' ) . '</span></th>';
	echo '</tr>';
	echo '</tfoot>';
	echo '</table>';
	?>
</div>
	<?php
}
?>
