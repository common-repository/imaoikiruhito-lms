<?php
/**
 * Imaoikiruhito LMS admin-paypal-setting.php
 * Description: 支払い方法設定
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
<form action="" method="post" name="iihlms-form-payment-method-setting" id="iihlms-form-payment-method-setting">
	<?php
	global $wpdb;

	if ( isset( $_POST['action-type'] ) && ( 'iihlms-form-payment-method-setting' === $_POST['action-type'] ) ) {
		if ( check_admin_referer( 'iihlms-form-payment-method-setting-csrf-action', 'iihlms-form-payment-method-setting-csrf' ) ) {
			if ( isset( $_POST['iihlms-payment-method-setting-hidden'] ) ) {
				if ( ! empty( $_POST['iihlms-payment-method-setting'] ) ) {
					$post_iihlms_payment_method_setting_data = array();
					$post_iihlms_payment_method_setting_data = array_map( 'sanitize_text_field', wp_unslash( $_POST['iihlms-payment-method-setting'] ) );
					update_option( 'iihlms_payment_method_setting', $post_iihlms_payment_method_setting_data );
				} else {
					delete_option( 'iihlms_payment_method_setting' );
				}
			}
			$iihlms_payment_method_setting = get_option( 'iihlms_payment_method_setting', array() );

			if ( in_array( 'paypal', $iihlms_payment_method_setting, true ) ) {
				// PayPal設定.
				if ( isset( $_POST['iihlms-paypal-clientid'] ) ) {
					update_option( 'iihlms_paypal_clientid', sanitize_text_field( wp_unslash( $_POST['iihlms-paypal-clientid'] ) ) );
				}
				if ( isset( $_POST['iihlms-paypal-secretid'] ) ) {
					update_option( 'iihlms_paypal_secretid', sanitize_text_field( wp_unslash( $_POST['iihlms-paypal-secretid'] ) ) );
				}
				if ( isset( $_POST['iihlms-paypal-liveorsandbox'] ) ) {
					update_option( 'iihlms_paypal_liveorsandbox', sanitize_text_field( wp_unslash( $_POST['iihlms-paypal-liveorsandbox'] ) ) );
				}

				if ( ! empty( $_POST['iihlms-paypal-webhook-id'] ) ) {
					$input_webhook_id = sanitize_text_field( wp_unslash( $_POST['iihlms-paypal-webhook-id'] ) );
					if ( $this->iihlms_verify_paypal_webhook_id( $input_webhook_id ) ) {
						update_option( 'iihlms_paypal_webhook_id', $input_webhook_id );
					} else {
						echo '<div class="error"><p>';
						echo esc_html__( '指定したWebhook IDが存在しないか、Webhook URLがこのサイトのURLと異なります。', 'imaoikiruhitolms' ) . '</h1>';
						echo '</p></div>';
					}
				} else {
					update_option( 'iihlms_paypal_webhook_id', '' );
				}
			}

			if ( in_array( 'stripe', $iihlms_payment_method_setting, true ) ) {
				// Stripe設定.
				if ( isset( $_POST['iihlms-public-key-stripe'] ) ) {
					update_option( 'iihlms_public_key_stripe', sanitize_text_field( wp_unslash( $_POST['iihlms-public-key-stripe'] ) ) );
				}
				if ( isset( $_POST['iihlms-secret-key-stripe'] ) ) {
					update_option( 'iihlms_secret_key_stripe', sanitize_text_field( wp_unslash( $_POST['iihlms-secret-key-stripe'] ) ) );
				}
				if ( isset( $_POST['iihlms-webhook-secret-stripe'] ) ) {
					update_option( 'iihlms_webhook_secret_stripe', sanitize_text_field( wp_unslash( $_POST['iihlms-webhook-secret-stripe'] ) ) );
				}
			}
		}
	}

	$iihlms_payment_method_setting = get_option( 'iihlms_payment_method_setting', array() );

	echo '<h1 class="wp-heading-inline iihlms-heading">' . esc_html__( '支払い方法設定', 'imaoikiruhitolms' ) . '</h1>';
	echo '<hr class="wp-header-end">';

	echo '<h2 class="iihlms-heading">' . esc_html__( '使用する支払い方法', 'imaoikiruhitolms' ) . '</h2>';

	echo '<input type="hidden" name="iihlms-payment-method-setting-hidden" value="1">';

	echo '<label class="iihlms-checkbox-label" for=';
	echo '"iihlms-payment-method-setting-paypal';
	echo '">';
	echo '<input type="checkbox" name="iihlms-payment-method-setting[]';
	echo '" id="iihlms-payment-method-setting-paypal';
	echo '" value="paypal';
	echo '" ';
	checked( in_array( 'paypal', $iihlms_payment_method_setting, true ) );
	echo '>';
	echo 'PayPal';
	echo '</label>';

	echo '<label class="iihlms-checkbox-label" for=';
	echo '"iihlms-payment-method-setting-stripe';
	echo '">';
	echo '<input type="checkbox" name="iihlms-payment-method-setting[]';
	echo '" id="iihlms-payment-method-setting-stripe';
	echo '" value="stripe';
	echo '" ';
	checked( in_array( 'stripe', $iihlms_payment_method_setting, true ) );
	echo '>';
	echo 'Stripe';
	echo '</label>';

	$iihlms_payment_method_paypal_disabled = '';
	$iihlms_payment_method_stripe_disabled = '';
	echo '<style>';
	if ( in_array( 'paypal', $iihlms_payment_method_setting, true ) ) {
		echo '#iihlms-payment-method-paypal-wrap{ display: block; }';
	} else {
		echo '#iihlms-payment-method-paypal-wrap{ display: none; }';
		$iihlms_payment_method_paypal_disabled = 'disabled';
	}
	if ( in_array( 'stripe', $iihlms_payment_method_setting, true ) ) {
		echo '#iihlms-payment-method-stripe-wrap{ display: block; }';
	} else {
		echo '#iihlms-payment-method-stripe-wrap{ display: none; }';
		$iihlms_payment_method_stripe_disabled = 'disabled';
	}
	echo '</style>';

	echo '<div id="iihlms-payment-method-paypal-wrap">';
	echo '<h2 class="iihlms-heading">' . esc_html__( 'PayPal', 'imaoikiruhitolms' ) . '</h2>';

	$iihlms_paypal_clientid      = get_option( 'iihlms_paypal_clientid', '' );
	$iihlms_paypal_secretid      = get_option( 'iihlms_paypal_secretid', '' );
	$iihlms_paypal_webhook_id    = get_option( 'iihlms_paypal_webhook_id', '' );
	$iihlms_paypal_liveorsandbox = get_option( 'iihlms_paypal_liveorsandbox', 'PayPalSandbox' );
	?>

	<table class="form-table" role="presentation">
	<tbody>
	<tr>
	<th scope="row"><?php echo esc_html__( 'クライアントID', 'imaoikiruhitolms' ); ?></th>
	<td><input type="text" size="100" <?php echo esc_attr( $iihlms_payment_method_paypal_disabled ); ?> name="iihlms-paypal-clientid" id="iihlms-paypal-clientid" value="<?php echo esc_attr( $iihlms_paypal_clientid ); ?>">
	<p class="description"></p>
	</td>
	</tr>
	<tr>
	<th scope="row"><?php echo esc_html__( 'シークレットID', 'imaoikiruhitolms' ); ?></th>
	<td><input type="text" size="100" <?php echo esc_attr( $iihlms_payment_method_paypal_disabled ); ?> name="iihlms-paypal-secretid" id="iihlms-paypal-secretid" value="<?php echo esc_attr( $iihlms_paypal_secretid ); ?>">
	<p class="description"></p>
	</td>
	</tr>
	<tr>
	<th scope="row"><?php echo esc_html__( 'WebhookID', 'imaoikiruhitolms' ); ?></th>
	<td><input type="text" size="100" <?php echo esc_attr( $iihlms_paypal_webhook_id ); ?> name="iihlms-paypal-webhook-id" id="iihlms-paypal-webhook-id" value="<?php echo esc_attr( $iihlms_paypal_webhook_id ); ?>">
	<p class="description"></p>
	</td>
	</tr>
	<tr>
	<th scope="row"><?php echo esc_html__( '動作環境', 'imaoikiruhitolms' ); ?></th>
	<td>
		<fieldset>
		<label><input type="radio" <?php echo esc_attr( $iihlms_payment_method_paypal_disabled ); ?> name="iihlms-paypal-liveorsandbox" value="PayPalLive"<?php checked( $iihlms_paypal_liveorsandbox, 'PayPalLive' ); ?>> <span class="date-time-text format-i18n"><?php echo esc_html__( '本番環境', 'imaoikiruhitolms' ); ?></span></label><br>
		<label><input type="radio" <?php echo esc_attr( $iihlms_payment_method_paypal_disabled ); ?> name="iihlms-paypal-liveorsandbox" value="PayPalSandbox"<?php checked( $iihlms_paypal_liveorsandbox, 'PayPalSandbox' ); ?>> <span class="date-time-text format-i18n"><?php echo esc_html__( 'テスト環境（Sandbox）', 'imaoikiruhitolms' ); ?></span></label><br>
		</fieldset>
	</td>
	</tr>

	</tbody>
	</table>
	<?php
	echo '</div>';

	echo '<div id="iihlms-payment-method-stripe-wrap">';
	echo '<h2 class="iihlms-heading">' . esc_html__( 'Stripe', 'imaoikiruhitolms' ) . '</h2>';

	$iihlms_public_key_stripe     = get_option( 'iihlms_public_key_stripe', '' );
	$iihlms_secret_key_stripe     = get_option( 'iihlms_secret_key_stripe', '' );
	$iihlms_webhook_secret_stripe = get_option( 'iihlms_webhook_secret_stripe', '' );
	?>

	<table class="form-table" role="presentation">
	<tbody>

	<tr>
	<th scope="row"><?php echo esc_html__( '公開可能キー', 'imaoikiruhitolms' ); ?></th>
	<td><input type="text" size="100" <?php echo esc_attr( $iihlms_payment_method_stripe_disabled ); ?> name="iihlms-public-key-stripe" id="iihlms-public-key-stripe" value="<?php echo esc_attr( $iihlms_public_key_stripe ); ?>">
	<p class="description"></p>
	</td>
	</tr>
	<tr>
	<th scope="row"><?php echo esc_html__( 'シークレットキー', 'imaoikiruhitolms' ); ?></th>
	<td><input type="text" size="100" <?php echo esc_attr( $iihlms_payment_method_stripe_disabled ); ?> name="iihlms-secret-key-stripe" id="iihlms-secret-key-stripe" value="<?php echo esc_attr( $iihlms_secret_key_stripe ); ?>">
	<p class="description"></p>
	</td>
	</tr>
	<tr>
	<th scope="row"><?php echo esc_html__( 'Webhook署名シークレット', 'imaoikiruhitolms' ); ?></th>
	<td><input type="text" size="100" <?php echo esc_attr( $iihlms_payment_method_stripe_disabled ); ?> name="iihlms-webhook-secret-stripe" id="iihlms-webhook-secret-stripe" value="<?php echo esc_attr( $iihlms_webhook_secret_stripe ); ?>">
	<p class="description">
	Webhookオンラインエンドポイント
	<?php
	$listener_url = get_home_url() . '/?iihlms-api=iihlms-api-stripe';
	echo esc_url( $listener_url );
	?>
	を<a href="https://dashboard.stripe.com/webhooks" target="_blank">Stripeのダッシュボード</a>から登録することで、Webhook署名シークレットを取得できます。URLにlocalhostが含まれる場合登録できません。
	</p>
	</td>
	</tr>

	</tbody>
	</table>
	<?php
	echo '</div>';

	echo '<input type="hidden" name="action-type" value="iihlms-form-payment-method-setting">';
	wp_nonce_field( 'iihlms-form-payment-method-setting-csrf-action', 'iihlms-form-payment-method-setting-csrf' );
	?>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_html__( '設定を更新', 'imaoikiruhitolms' ); ?>"></p>
	</form>
</div>
