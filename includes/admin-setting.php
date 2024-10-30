<?php
/**
 * Imaoikiruhito LMS admin-setting.php
 * Description: ダッシュボード
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
	echo '<h1 class="wp-heading-inline iihlms-heading">' . esc_html__( '設定', 'imaoikiruhitolms' ) . '</h1>';
	echo '<hr class="wp-header-end">';
	global $wpdb;

	// 会員種別一覧.
	if ( isset( $_POST['action-type'] ) && ( 'iihlms_form_membership_edit' === $_POST['action-type'] ) ) {
		if ( check_admin_referer( 'iihlms_form_membership_edit_csrf_action', 'iihlms_form_membership_edit_csrf' ) ) {
			$membership_name_arg = array();
			$membership_del_arg  = array();
			foreach ( $_POST as $key => $value ) {
				if ( strpos( $key, 'membership_name' ) !== false ) {
					$i                         = str_replace( 'membership_name', '', $key );
					$membership_name_arg[ $i ] = $value;
				}
				if ( strpos( $key, 'membership_delete' ) !== false ) {
					$i = str_replace( 'membership_delete', '', $key );
					array_push( $membership_del_arg, $i );
				}
			}

			// 会員種別更新.
			if ( ! empty( $membership_name_arg ) ) {
				$membership_table = $wpdb->prefix . 'iihlms_membership';
				$membership_ids   = array_keys( $membership_name_arg );
				$membership_ids   = implode( ',', $membership_ids );
				$membership_count = count( $membership_name_arg );

				$results = $wpdb->get_results(
					$wpdb->prepare(
						'
						SELECT iihlms_membership_id
						FROM %1s
						WHERE 
							iihlms_membership_id IN ( %2s )
						',
						$membership_table,
						$membership_ids
					)
				);
				$number  = count( $results );
				if ( $number === $membership_count ) {
					foreach ( $membership_name_arg as $key => $value ) {
						$wpdb->update(
							$membership_table,
							array(
								'membership_name' => $value,
							),
							array(
								'iihlms_membership_id' => $key,
							),
							array( '%s' ),
							array( '%d' ),
						);
					}
				}
			}

			// 会員種別削除.
			if ( ! empty( $membership_del_arg ) ) {
				$membership_table     = $wpdb->prefix . 'iihlms_membership';
				$membership_del_ids   = implode( ',', $membership_del_arg );
				$membership_del_count = count( $membership_del_arg );

				$results = $wpdb->get_results(
					$wpdb->prepare(
						'
						SELECT iihlms_membership_id
						FROM %1s
						WHERE 
							iihlms_membership_id IN ( %2s )
						',
						$membership_table,
						$membership_del_ids
					)
				);
				$number  = count( $results );
				if ( $number === $membership_del_count ) {
					foreach ( $membership_del_arg as $key => $value ) {
						$wpdb->delete(
							$membership_table,
							array(
								'iihlms_membership_id' => $value,
							),
							array( '%d' ),
						);
					}
				}
			}
		}
	}

	// 会員種別追加.
	if ( isset( $_POST['action-type'] ) && ( 'iihlms-form-membership-add' === $_POST['action-type'] ) ) {
		if ( check_admin_referer( 'iihlms-form-membership-add-csrf-action', 'iihlms-form-membership-add-csrf' ) ) {
			if ( isset( $_POST['iihlms-membership-name'] ) ) {
				$iihlms_membership_name = sanitize_text_field( wp_unslash( $_POST['iihlms-membership-name'] ) );
				$membership_table       = $wpdb->prefix . 'iihlms_membership';
				$wpdb->insert(
					$membership_table,
					array(
						'membership_name' => $iihlms_membership_name,
					),
					array(
						'%s',
					)
				);
			}
		}
	}

	// メール設定.
	if ( isset( $_POST['action-type'] ) && ( 'iihlms-form-mail-setting' === $_POST['action-type'] ) ) {
		if ( check_admin_referer( 'iihlms-form-mail-setting-csrf-action', 'iihlms-form-mail-setting-csrf' ) ) {
			// メール設定（送信元メールアドレス）.
			if ( isset( $_POST['iihlms-admin-mailaddress'] ) ) {
				update_option( 'iihlms_admin_mailaddress', sanitize_email( wp_unslash( $_POST['iihlms-admin-mailaddress'] ) ) );
			}

			// メール設定（送信元名称）.
			if ( isset( $_POST['iihlms-admin-mailname'] ) ) {
				update_option( 'iihlms_admin_mailname', sanitize_text_field( wp_unslash( $_POST['iihlms-admin-mailname'] ) ) );
			}

			// メール件名（講座購入完了）.
			if ( isset( $_POST['iihlms-mailsubject-application-completed'] ) ) {
				update_option( 'iihlms_mailsubject_application_completed', sanitize_text_field( wp_unslash( $_POST['iihlms-mailsubject-application-completed'] ) ) );
			}

			// メール本文（講座購入完了）.
			if ( isset( $_POST['iihlms-mailbody-application-completed'] ) ) {
				update_option( 'iihlms_mailbody_application_completed', sanitize_textarea_field( wp_unslash( $_POST['iihlms-mailbody-application-completed'] ) ) );
			}

			// メール件名（サブスクリプション講座購入完了）.
			if ( isset( $_POST['iihlms-mailsubject-subscription-application-completed'] ) ) {
				update_option( 'iihlms_mailsubject_subscription_application_completed', sanitize_text_field( wp_unslash( $_POST['iihlms-mailsubject-subscription-application-completed'] ) ) );
			}

			// メール本文（サブスクリプション講座購入完了）.
			if ( isset( $_POST['iihlms-mailbody-subscription-application-completed'] ) ) {
				update_option( 'iihlms_mailbody_subscription_application_completed', sanitize_textarea_field( wp_unslash( $_POST['iihlms-mailbody-subscription-application-completed'] ) ) );
			}

			// メール件名（サブスクリプション講座解約）.
			if ( isset( $_POST['iihlms-mailsubject-subscription-cancellation-completed'] ) ) {
				update_option( 'iihlms_mailsubject_subscription_cancellation_completed', sanitize_text_field( wp_unslash( $_POST['iihlms-mailsubject-subscription-cancellation-completed'] ) ) );
			}

			// メール本文（サブスクリプション講座解約）.
			if ( isset( $_POST['iihlms-mailbody-subscription-cancellation-completed'] ) ) {
				update_option( 'iihlms_mailbody_subscription_cancellation_completed', sanitize_textarea_field( wp_unslash( $_POST['iihlms-mailbody-subscription-cancellation-completed'] ) ) );
			}

			// メール件名（サブスクリプション講座支払い失敗通知受信時）.
			if ( isset( $_POST['iihlms-mailsubject-subscription-payment-failed'] ) ) {
				update_option( 'iihlms_mailsubject_subscription_payment_failed ', sanitize_text_field( wp_unslash( $_POST['iihlms-mailsubject-subscription-payment-failed'] ) ) );
			}

			// メール本文（サブスクリプション講座支払い失敗通知受信時）.
			if ( isset( $_POST['iihlms-mailbody-subscription-payment-failed'] ) ) {
				update_option( 'iihlms_mailbody_subscription_payment_failed', sanitize_textarea_field( wp_unslash( $_POST['iihlms-mailbody-subscription-payment-failed'] ) ) );
			}

			// メール件名（サブスクリプション講座支払い失敗上限による解約）.
			if ( isset( $_POST['iihlms-mailsubject-subscription-suspended-cancellation-completed'] ) ) {
				update_option( 'iihlms_mailsubject_subscription_suspended_cancellation_completed', sanitize_text_field( wp_unslash( $_POST['iihlms-mailsubject-subscription-suspended-cancellation-completed'] ) ) );
			}

			// メール本文（サブスクリプション講座支払い失敗上限による解約）.
			if ( isset( $_POST['iihlms-mailbody-subscription-suspended-cancellation-completed'] ) ) {
				update_option( 'iihlms_mailbody_subscription_suspended_cancellation_completed', sanitize_textarea_field( wp_unslash( $_POST['iihlms-mailbody-subscription-suspended-cancellation-completed'] ) ) );
			}

			// メール件名（期限付きサブスクリプション講座が期限を迎え解約）.
			if ( isset( $_POST['iihlms-mailsubject-subscription-expired-cancellation-completed'] ) ) {
				update_option( 'iihlms_mailsubject_subscription_expired_cancellation_completed', sanitize_text_field( wp_unslash( $_POST['iihlms-mailsubject-subscription-expired-cancellation-completed'] ) ) );
			}

			// メール本文（期限付きサブスクリプション講座が期限を迎え解約）.
			if ( isset( $_POST['iihlms-mailbody-subscription-expired-cancellation-completed'] ) ) {
				update_option( 'iihlms_mailbody_subscription_expired_cancellation_completed', sanitize_textarea_field( wp_unslash( $_POST['iihlms-mailbody-subscription-expired-cancellation-completed'] ) ) );
			}

			// メール件名（ユーザー登録受付）.
			if ( isset( $_POST['iihlms-mailsubject-user-registration-reception'] ) ) {
				update_option( 'iihlms_mailsubject_user_registration_reception', sanitize_text_field( wp_unslash( $_POST['iihlms-mailsubject-user-registration-reception'] ) ) );
			}

			// メール本文（ユーザー登録受付）.
			if ( isset( $_POST['iihlms-mailbody-user-registration-reception'] ) ) {
				update_option( 'iihlms_mailbody_user_registration_reception', sanitize_textarea_field( wp_unslash( $_POST['iihlms-mailbody-user-registration-reception'] ) ) );
			}

			// メール件名（ユーザー登録完了）.
			if ( isset( $_POST['iihlms-mailsubject-user-registration-completed'] ) ) {
				update_option( 'iihlms_mailsubject_user_registration_completed', sanitize_text_field( wp_unslash( $_POST['iihlms-mailsubject-user-registration-completed'] ) ) );
			}
			// メール本文（ユーザー登録完了）.
			if ( isset( $_POST['iihlms-mailbody-user-registration-completed'] ) ) {
				update_option( 'iihlms_mailbody_user_registration_completed', sanitize_textarea_field( wp_unslash( $_POST['iihlms-mailbody-user-registration-completed'] ) ) );
			}

			// メール件名（ユーザー情報修正）.
			if ( isset( $_POST['iihlms-mailsubject-userpage-change'] ) ) {
				update_option( 'iihlms_mailsubject_userpage_change', sanitize_text_field( wp_unslash( $_POST['iihlms-mailsubject-userpage-change'] ) ) );
			}
			// メール本文（ユーザー情報修正）.
			if ( isset( $_POST['iihlms-mailbody-userpage-change'] ) ) {
				update_option( 'iihlms_mailbody_userpage_change', sanitize_textarea_field( wp_unslash( $_POST['iihlms-mailbody-userpage-change'] ) ) );
			}

			// メール件名（パスワードリセット受付）.
			if ( isset( $_POST['iihlms-mailsubject-change-mail-password-reset'] ) ) {
				update_option( 'iihlms_mailsubject_change_mail_password_reset', sanitize_text_field( wp_unslash( $_POST['iihlms-mailsubject-change-mail-password-reset'] ) ) );
			}
			// メール本文（パスワードリセット受付）.
			if ( isset( $_POST['iihlms-mailbody-change-mail-password-reset'] ) ) {
				update_option( 'iihlms_mailbody_change_mail_password_reset', sanitize_textarea_field( wp_unslash( $_POST['iihlms-mailbody-change-mail-password-reset'] ) ) );
			}

			// メール件名（パスワードリセット完了）.
			if ( isset( $_POST['iihlms-mailsubject-change-mail-password-reset-completed'] ) ) {
				update_option( 'iihlms_mailsubject_change_mail_password_reset_completed', sanitize_text_field( wp_unslash( $_POST['iihlms-mailsubject-change-mail-password-reset-completed'] ) ) );
			}
			// メール本文（パスワードリセット完了）.
			if ( isset( $_POST['iihlms-mailbody-change-mail-password-reset-completed'] ) ) {
				update_option( 'iihlms_mailbody_change_mail_password_reset_completed', sanitize_textarea_field( wp_unslash( $_POST['iihlms-mailbody-change-mail-password-reset-completed'] ) ) );
			}

			// メール件名（管理画面で新規ユーザーを追加時、ユーザーに送信）.
			if ( isset( $_POST['iihlms-mailsubject-add-new-user'] ) ) {
				update_option( 'iihlms_mailsubject_add_new_user', sanitize_text_field( wp_unslash( $_POST['iihlms-mailsubject-add-new-user'] ) ) );
			}
			// メール本文（管理画面で新規ユーザーを追加時、ユーザーに送信）.
			if ( isset( $_POST['iihlms-mailbody-add-new-user'] ) ) {
				update_option( 'iihlms_mailbody_add_new_user', sanitize_textarea_field( wp_unslash( $_POST['iihlms-mailbody-add-new-user'] ) ) );
			}

			// メール件名（管理画面で新規ユーザーを追加時、管理者に送信）.
			if ( isset( $_POST['iihlms-mailsubject-add-new-user-admin'] ) ) {
				update_option( 'iihlms_mailsubject_add_new_user_admin', sanitize_text_field( wp_unslash( $_POST['iihlms-mailsubject-add-new-user-admin'] ) ) );
			}
			// メール本文（管理画面で新規ユーザーを追加時、管理者に送信）.
			if ( isset( $_POST['iihlms-mailbody-add-new-user-admin'] ) ) {
				update_option( 'iihlms_mailbody_add_new_user_admin', sanitize_textarea_field( wp_unslash( $_POST['iihlms-mailbody-add-new-user-admin'] ) ) );
			}

			// メール件名（メールアドレス変更時、ユーザーに送信）.
			if ( isset( $_POST['iihlms-mailsubject-change-email'] ) ) {
				update_option( 'iihlms_mailsubject_change_email', sanitize_text_field( wp_unslash( $_POST['iihlms-mailsubject-change-email'] ) ) );
			}
			// メール本文（メールアドレス変更時、ユーザーに送信）.
			if ( isset( $_POST['iihlms-mailbody-change-email'] ) ) {
				update_option( 'iihlms_mailbody_change_email', sanitize_textarea_field( wp_unslash( $_POST['iihlms-mailbody-change-email'] ) ) );
			}
		}
	}

	// システム設定.
	if ( isset( $_POST['action-type'] ) && ( 'iihlms-system-setting' === $_POST['action-type'] ) ) {
		if ( check_admin_referer( 'iihlms-system-setting-csrf-action', 'iihlms-system-setting-csrf' ) ) {
			// 消費税率.
			if ( isset( $_POST['iihlms-consumption-tax'] ) ) {
				update_option( 'iihlms_consumption_tax', absint( sanitize_textarea_field( wp_unslash( $_POST['iihlms-consumption-tax'] ) ) ) );
			}
		}
	}

	// reCAPTCHA設定.
	if ( isset( $_POST['action-type'] ) && ( 'iihlms-recaptcha-setting' === $_POST['action-type'] ) ) {
		if ( check_admin_referer( 'iihlms-recaptcha-setting-csrf-action', 'iihlms-recaptcha-setting-csrf' ) ) {
			// reCAPTCHAを使用する.
			if ( isset( $_POST['use_recaptcha'] ) ) {
				update_option( 'iihlms_use_recaptcha', $this->iihlms_sanitize_checkbox( sanitize_textarea_field( wp_unslash( $_POST['use_recaptcha'] ) ) ) );
			} else {
				delete_option( 'iihlms_use_recaptcha' );
			}
			// サイトキー.
			if ( isset( $_POST['recaptcha_sitekey'] ) ) {
				$recaptcha_sitekey = sanitize_textarea_field( wp_unslash( $_POST['recaptcha_sitekey'] ) );
				update_option( 'iihlms_recaptcha_sitekey', $recaptcha_sitekey );
			} else {
				delete_option( 'iihlms_recaptcha_sitekey' );
			}
			// シークレットキー.
			if ( isset( $_POST['recaptcha_secretkey'] ) ) {
				$recaptcha_secretkey = sanitize_textarea_field( wp_unslash( $_POST['recaptcha_secretkey'] ) );
				update_option( 'iihlms_recaptcha_secretkey', $recaptcha_secretkey );
			} else {
				delete_option( 'iihlms_recaptcha_secretkey' );
			}
		}
	}
	?>

	<div id="iihlms-tabs">
		<ul>
		<li><a href="#tabs-1"><?php echo esc_html__( '会員種別一覧', 'imaoikiruhitolms' ); ?></a></li>
		<li><a href="#tabs-2"><?php echo esc_html__( '会員種別追加', 'imaoikiruhitolms' ); ?></a></li>
		<li><a href="#tabs-3"><?php echo esc_html__( 'メール設定', 'imaoikiruhitolms' ); ?></a></li>
		<li><a href="#tabs-4"><?php echo esc_html__( 'システム設定', 'imaoikiruhitolms' ); ?></a></li>
		<li><a href="#tabs-5"><?php echo esc_html__( 'reCAPTCHA', 'imaoikiruhitolms' ); ?></a></li>
		<?php
		echo esc_html( apply_filters( 'iihlms_addition_setting_t6_1', '' ) );
		?>
		</ul>

		<div id="tabs-1">
		<h2><?php echo esc_html__( '会員種別一覧', 'imaoikiruhitolms' ); ?></h2>
		<?php
		$membership_table = $wpdb->prefix . 'iihlms_membership';
		$search_key       = 0;
		$results          = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT *
				FROM %1s
				WHERE iihlms_membership_id >= %d
				',
				$membership_table,
				$search_key
			)
		);
		echo '<form action="#tabs-1" method="post" name="iihlms_form_membership_edit" id="iihlms_form_membership_edit">';
		echo '<table class="wp-list-table widefat fixed striped table-view-list">';
		echo '<thead>';
		echo '<tr>';
		echo '<th class="manage-column"><span>' . esc_html__( 'ID', 'imaoikiruhitolms' ) . '</span></th><th class="manage-column"><span>' . esc_html__( '会員種別名称', 'imaoikiruhitolms' ) . '</span></th><th class="manage-column"><span>' . esc_html__( '削除', 'imaoikiruhitolms' ) . '</span></th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach ( $results as $result_data ) {
			$membership_id = $result_data->iihlms_membership_id;
			$name          = $result_data->membership_name;
			echo '<tr>';
			echo '<td>';
			echo esc_html( $membership_id );
			echo '</td>';
			echo '<td><input type="text" required name="membership_name' . esc_attr( $membership_id );
			echo '" id="membership_name' . esc_attr( $membership_id );
			echo '" value="';
			echo esc_attr( $name );
			echo '">';
			echo '</td>';
			echo '<td>';
			echo '<input type="checkbox" name="membership_delete' . esc_attr( $membership_id );
			echo '" id="membership_delete' . esc_attr( $membership_id );
			echo '" value="1">';
			echo '</td>';
			echo '</tr>';
		}
		echo '<tbody>';
		echo '<tfoot>';
		echo '<tr>';
		echo '<th class="manage-column"><span>' . esc_html__( 'ID', 'imaoikiruhitolms' ) . '</span></th><th class="manage-column"><span>' . esc_html__( '会員レベル', 'imaoikiruhitolms' ) . '</span></th><th class="manage-column"><span>' . esc_html__( '削除', 'imaoikiruhitolms' ) . '</span></th>';
		echo '</tr>';
		echo '</tfoot>';
		echo '</table>';
		echo '<input type="hidden" name="action-type" value="iihlms_form_membership_edit">';
		wp_nonce_field( 'iihlms_form_membership_edit_csrf_action', 'iihlms_form_membership_edit_csrf' );
		echo '<p class="submit"><input type="button" name="submitbtn" id="submitbtn" class="button button-primary" value="' . esc_html__( '会員種別を更新', 'imaoikiruhitolms' ) . '"></p>';
		echo '</form>';
		?>

		<script>
			document.iihlms_form_membership_edit.submitbtn.addEventListener('click', function() {
				let searchTxt = 'membership_delete';
				let checkCount = 0;
				inputObject = document.getElementsByTagName( 'input' );
				objRegex = new RegExp( searchTxt );
				for( i=0; i < inputObject.length; i++ ) {
					if( inputObject[i].id.match( objRegex ) ) {
						if( inputObject[i].checked ) {
							checkCount++;
						}
					}
				}
				if( checkCount > 0 ){
					let result = window.confirm('<?php echo esc_html__( '会員種別を削除すると、元に戻せません。削除してもよろしいですか？', 'imaoikiruhitolms' ); ?>');
					if( result === false ) {
						return;
					}
				}

				document.iihlms_form_membership_edit.submit();
			})
		</script>
		</div>

		<div id="tabs-2">
		<h2><?php echo esc_html__( '会員種別追加', 'imaoikiruhitolms' ); ?></h2>

		<form action="#tabs-1" method="post" name="iihlms-form-membership-add" id="iihlms-form-membership-add">
		<table class="form-table" role="presentation">
		<tr>
		<th><?php echo esc_html__( '会員種別名称', 'imaoikiruhitolms' ); ?></th>
		<td>
		<input type="text" name="iihlms-membership-name" id="iihlms-membership-name" value="" class="regular-text" required>
		</td>
		</tr>
		</table>
		<?php echo '<input type="hidden" name="action-type" value="iihlms-form-membership-add">'; ?>
		<?php wp_nonce_field( 'iihlms-form-membership-add-csrf-action', 'iihlms-form-membership-add-csrf' ); ?>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_html__( '会員種別を追加', 'imaoikiruhitolms' ); ?>"></p>
		</form>
		</div>

		<div id="tabs-3">
		<h2><?php echo esc_html__( 'メール設定', 'imaoikiruhitolms' ); ?></h2>

		<?php
		$iihlms_admin_mailaddress = get_option( 'iihlms_admin_mailaddress' );
		$iihlms_admin_mailname    = get_option( 'iihlms_admin_mailname' );
		?>
		<form action="#tabs-3" method="post" name="iihlms-form-mail-setting" id="iihlms-form-mail-setting">
		<table class="form-table" role="presentation">
		<tbody>
		<tr>
		<th scope="row"><?php echo esc_html__( '送信元メールアドレス', 'imaoikiruhitolms' ); ?></th>
		<td><input type="text" size="100" name="iihlms-admin-mailaddress" id="iihlms-admin-mailaddress" value="<?php echo esc_attr( $iihlms_admin_mailaddress ); ?>">
		<p class="description"></p>
		</td>
		</tr>
		<tr>
		<th scope="row"><?php echo esc_html__( '送信元名称', 'imaoikiruhitolms' ); ?></th>
		<td><input type="text" size="100" name="iihlms-admin-mailname" id="iihlms-admin-mailname" value="<?php echo esc_attr( $iihlms_admin_mailname ); ?>">
		<p class="description"></p>
		</td>
		</tr>
		</tbody>
		</table>

		<?php
		$iihlms_mailsubject_application_completed = get_option( 'iihlms_mailsubject_application_completed' );
		$iihlms_mailbody_application_completed    = get_option( 'iihlms_mailbody_application_completed' );
		?>
		<h2><?php echo esc_html__( 'メール設定（講座購入完了）', 'imaoikiruhitolms' ); ?></h2>
		<table class="form-table" role="presentation">
		<tbody>
		<tr>
		<th scope="row"><?php echo esc_html__( '件名', 'imaoikiruhitolms' ); ?></th>
		<td><input type="text" size="100" name="iihlms-mailsubject-application-completed" id="iihlms-mailsubject-application-completed" value="<?php echo esc_attr( $iihlms_mailsubject_application_completed ); ?>"></td>
		</tr>
		<tr>
		<th scope="row"><?php echo esc_html__( '本文', 'imaoikiruhitolms' ); ?></th>
		<td><textarea rows="12" cols="103" name="iihlms-mailbody-application-completed" id="iihlms-mailbody-application-completed" ><?php echo esc_textarea( $iihlms_mailbody_application_completed ); ?></textarea><br>
		<?php echo esc_html__( '使用可能な予約語', 'imaoikiruhitolms' ); ?><br>
		*NAME*　<?php echo esc_html__( 'ユーザーの氏名を表示します', 'imaoikiruhitolms' ); ?><br>
		*APPLICATION_DETAILS*　<?php echo esc_html__( '申込内容を表示します', 'imaoikiruhitolms' ); ?><br>
		</td>
		</tr>
		</tbody>
		</table>

		<?php
		$iihlms_mailsubject_subscription_application_completed = get_option( 'iihlms_mailsubject_subscription_application_completed' );
		$iihlms_mailbody_subscription_application_completed    = get_option( 'iihlms_mailbody_subscription_application_completed' );
		?>
		<h2><?php echo esc_html__( 'メール設定（サブスクリプション講座購入完了）', 'imaoikiruhitolms' ); ?></h2>
		<table class="form-table" role="presentation">
		<tbody>
		<tr>
		<th scope="row"><?php echo esc_html__( '件名', 'imaoikiruhitolms' ); ?></th>
		<td><input type="text" size="100" name="iihlms-mailsubject-subscription-application-completed" id="iihlms-mailsubject-subscription-application-completed" value="<?php echo esc_attr( $iihlms_mailsubject_subscription_application_completed ); ?>"></td>
		</tr>
		<tr>
		<th scope="row"><?php echo esc_html__( '本文', 'imaoikiruhitolms' ); ?></th>
		<td><textarea rows="12" cols="103" name="iihlms-mailbody-subscription-application-completed" id="iihlms-mailbody-subscription-application-completed" ><?php echo esc_textarea( $iihlms_mailbody_subscription_application_completed ); ?></textarea><br>
		<?php echo esc_html__( '使用可能な予約語', 'imaoikiruhitolms' ); ?><br>
		*NAME*　<?php echo esc_html__( 'ユーザーの氏名を表示します', 'imaoikiruhitolms' ); ?><br>
		*APPLICATION_DETAILS*　<?php echo esc_html__( '申込内容を表示します', 'imaoikiruhitolms' ); ?><br>
		</td>
		</tr>
		</tbody>
		</table>

		<?php
		$iihlms_mailsubject_subscription_cancellation_completed = get_option( 'iihlms_mailsubject_subscription_cancellation_completed' );
		$iihlms_mailbody_subscription_cancellation_completed    = get_option( 'iihlms_mailbody_subscription_cancellation_completed' );
		?>
		<h2><?php echo esc_html__( 'メール設定（サブスクリプション講座解約）', 'imaoikiruhitolms' ); ?></h2>
		<table class="form-table" role="presentation">
		<tbody>
		<tr>
		<th scope="row"><?php echo esc_html__( '件名', 'imaoikiruhitolms' ); ?></th>
		<td><input type="text" size="100" name="iihlms-mailsubject-subscription-cancellation-completed" id="iihlms-mailsubject-subscription-cancellation-completed" value="<?php echo esc_attr( $iihlms_mailsubject_subscription_cancellation_completed ); ?>"></td>
		</tr>
		<tr>
		<th scope="row"><?php echo esc_html__( '本文', 'imaoikiruhitolms' ); ?></th>
		<td><textarea rows="12" cols="103" name="iihlms-mailbody-subscription-cancellation-completed" id="iihlms-mailbody-subscription-cancellation-completed" ><?php echo esc_textarea( $iihlms_mailbody_subscription_cancellation_completed ); ?></textarea><br>
		<?php echo esc_html__( '使用可能な予約語', 'imaoikiruhitolms' ); ?><br>
		*NAME*　<?php echo esc_html__( 'ユーザーの氏名を表示します', 'imaoikiruhitolms' ); ?><br>
		*CANCELLATION_DETAILS*　<?php echo esc_html__( 'キャンセル内容を表示します', 'imaoikiruhitolms' ); ?><br>
		</td>
		</tr>
		</tbody>
		</table>

		<?php
		$iihlms_mailsubject_subscription_payment_failed = get_option( 'iihlms_mailsubject_subscription_payment_failed' );
		$iihlms_mailbody_subscription_payment_failed    = get_option( 'iihlms_mailbody_subscription_payment_failed' );
		?>
		<h2><?php echo esc_html__( 'メール設定（サブスクリプション講座支払い失敗通知受信時）', 'imaoikiruhitolms' ); ?></h2>
		<table class="form-table" role="presentation">
		<tbody>
		<tr>
		<th scope="row"><?php echo esc_html__( '件名', 'imaoikiruhitolms' ); ?></th>
		<td><input type="text" size="100" name="iihlms-mailsubject-subscription-payment-failed" id="iihlms-mailsubject-subscription-payment-failed" value="<?php echo esc_attr( $iihlms_mailsubject_subscription_payment_failed ); ?>"></td>
		</tr>
		<tr>
		<th scope="row"><?php echo esc_html__( '本文', 'imaoikiruhitolms' ); ?></th>
		<td><textarea rows="12" cols="103" name="iihlms-mailbody-subscription-payment-failed" id="iihlms-mailbody-subscription-payment-failed" ><?php echo esc_textarea( $iihlms_mailbody_subscription_payment_failed ); ?></textarea><br>
		<?php echo esc_html__( '使用可能な予約語', 'imaoikiruhitolms' ); ?><br>
		*NAME*　<?php echo esc_html__( 'ユーザーの氏名を表示します', 'imaoikiruhitolms' ); ?><br>
		*PAYMENT_FAILED_DETAILS*　<?php echo esc_html__( '決済に失敗した内容を表示します', 'imaoikiruhitolms' ); ?><br>
		*PAYMENT_URL*　<?php echo esc_html__( '利用可能な場合、決済URLを表示します', 'imaoikiruhitolms' ); ?><br>
		</td>
		</tr>
		</tbody>
		</table>

		<?php
		$iihlms_mailsubject_subscription_suspended_cancellation_completed = get_option( 'iihlms_mailsubject_subscription_suspended_cancellation_completed' );
		$iihlms_mailbody_subscription_suspended_cancellation_completed    = get_option( 'iihlms_mailbody_subscription_suspended_cancellation_completed' );
		?>
		<h2><?php echo esc_html__( 'メール設定（サブスクリプション講座支払い失敗上限による解約）', 'imaoikiruhitolms' ); ?></h2>
		<table class="form-table" role="presentation">
		<tbody>
		<tr>
		<th scope="row"><?php echo esc_html__( '件名', 'imaoikiruhitolms' ); ?></th>
		<td><input type="text" size="100" name="iihlms-mailsubject-subscription-suspended-cancellation-completed" id="iihlms-mailsubject-subscription-suspended-cancellation-completed" value="<?php echo esc_attr( $iihlms_mailsubject_subscription_suspended_cancellation_completed ); ?>"></td>
		</tr>
		<tr>
		<th scope="row"><?php echo esc_html__( '本文', 'imaoikiruhitolms' ); ?></th>
		<td><textarea rows="12" cols="103" name="iihlms-mailbody-subscription-suspended-cancellation-completed" id="iihlms-mailbody-subscription-suspended-cancellation-completed" ><?php echo esc_textarea( $iihlms_mailbody_subscription_suspended_cancellation_completed ); ?></textarea><br>
		<?php echo esc_html__( '使用可能な予約語', 'imaoikiruhitolms' ); ?><br>
		*NAME*　<?php echo esc_html__( 'ユーザーの氏名を表示します', 'imaoikiruhitolms' ); ?><br>
		*CANCELLATION_DETAILS*　<?php echo esc_html__( 'キャンセル内容を表示します', 'imaoikiruhitolms' ); ?><br>
		</td>
		</tr>
		</tbody>
		</table>

		<?php
		$iihlms_mailsubject_user_registration_reception = get_option( 'iihlms_mailsubject_user_registration_reception' );
		$iihlms_mailbody_user_registration_reception    = get_option( 'iihlms_mailbody_user_registration_reception' );
		?>
		<h2><?php echo esc_html__( 'メール設定（ユーザー登録受付）', 'imaoikiruhitolms' ); ?></h2>
		<table class="form-table" role="presentation">
		<tbody>
		<tr>
		<th scope="row"><?php echo esc_html__( '件名', 'imaoikiruhitolms' ); ?></th>
		<td><input type="text" size="100" name="iihlms-mailsubject-user-registration-reception" id="iihlms-mailsubject-user-registration-reception" value="<?php echo esc_attr( $iihlms_mailsubject_user_registration_reception ); ?>"></td>
		</tr>
		<tr>
		<th scope="row"><?php echo esc_html__( '本文', 'imaoikiruhitolms' ); ?></th>
		<td><textarea rows="12" cols="103" name="iihlms-mailbody-user-registration-reception" id="iihlms-mailbody-user-registration-reception" ><?php echo esc_textarea( $iihlms_mailbody_user_registration_reception ); ?></textarea><br>
		<?php echo esc_html__( '使用可能な予約語', 'imaoikiruhitolms' ); ?><br>
		*USER_REGISTRATION_URL*　<?php echo esc_html__( '24時間有効なユーザー登録URLを表示します', 'imaoikiruhitolms' ); ?><br>
		</td>
		</tr>
		</tbody>
		</table>

		<?php
		$iihlms_mailsubject_user_registration_completed = get_option( 'iihlms_mailsubject_user_registration_completed' );
		$iihlms_mailbody_user_registration_completed    = get_option( 'iihlms_mailbody_user_registration_completed' );
		?>
		<h2><?php echo esc_html__( 'メール設定（ユーザー登録完了）', 'imaoikiruhitolms' ); ?></h2>
		<table class="form-table" role="presentation">
		<tbody>
		<tr>
		<th scope="row"><?php echo esc_html__( '件名', 'imaoikiruhitolms' ); ?></th>
		<td><input type="text" size="100" name="iihlms-mailsubject-user-registration-completed" id="iihlms-mailsubject-user-registration-completed" value="<?php echo esc_attr( $iihlms_mailsubject_user_registration_completed ); ?>"></td>
		</tr>
		<tr>
		<th scope="row"><?php echo esc_html__( '本文', 'imaoikiruhitolms' ); ?></th>
		<td><textarea rows="12" cols="103" name="iihlms-mailbody-user-registration-completed" id="iihlms-mailbody-user-registration-completed" ><?php echo esc_textarea( $iihlms_mailbody_user_registration_completed ); ?></textarea><br>
		<?php echo esc_html__( '使用可能な予約語', 'imaoikiruhitolms' ); ?><br>
		*NAME*　<?php echo esc_html__( 'ユーザーの氏名を表示します', 'imaoikiruhitolms' ); ?><br>
		*SIGNUP_USER_NAME*　<?php echo esc_html__( 'ユーザー名を表示します', 'imaoikiruhitolms' ); ?><br>
		*USER_MAIL*　<?php echo esc_html__( 'ユーザーのメールアドレスを表示します', 'imaoikiruhitolms' ); ?>
		</td>
		</tr>
		</tbody>
		</table>

		<?php
		$iihlms_mailsubject_userpage_change = get_option( 'iihlms_mailsubject_userpage_change' );
		$iihlms_mailbody_userpage_change    = get_option( 'iihlms_mailbody_userpage_change' );
		?>
		<h2><?php echo esc_html__( 'メール設定（ユーザー情報修正）', 'imaoikiruhitolms' ); ?></h2>
		<table class="form-table" role="presentation">
		<tbody>
		<tr>
		<th scope="row"><?php echo esc_html__( '件名', 'imaoikiruhitolms' ); ?></th>
		<td><input type="text" size="100" name="iihlms-mailsubject-userpage-change" id="iihlms-mailsubject-userpage-change" value="<?php echo esc_attr( $iihlms_mailsubject_userpage_change ); ?>"></td>
		</tr>
		<tr>
		<th scope="row"><?php echo esc_html__( '本文', 'imaoikiruhitolms' ); ?></th>
		<td><textarea rows="12" cols="103" name="iihlms-mailbody-userpage-change" id="iihlms-mailbody-userpage-change" ><?php echo esc_textarea( $iihlms_mailbody_userpage_change ); ?></textarea><br>
		<?php echo esc_html__( '使用可能な予約語', 'imaoikiruhitolms' ); ?><br>
		*NAME*　<?php echo esc_html__( 'ユーザーの氏名を表示します', 'imaoikiruhitolms' ); ?><br>
		</td>
		</tr>
		</tbody>
		</table>

		<?php
		$iihlms_mailsubject_change_mail_password_reset = get_option( 'iihlms_mailsubject_change_mail_password_reset' );
		$iihlms_mailbody_change_mail_password_reset    = get_option( 'iihlms_mailbody_change_mail_password_reset' );
		?>
		<h2><?php echo esc_html__( 'メール設定（パスワードリセット受付）', 'imaoikiruhitolms' ); ?></h2>
		<table class="form-table" role="presentation">
		<tbody>
		<tr>
		<th scope="row"><?php echo esc_html__( '件名', 'imaoikiruhitolms' ); ?></th>
		<td><input type="text" size="100" name="iihlms-mailsubject-change-mail-password-reset" id="iihlms-mailsubject-change-mail-password-reset" value="<?php echo esc_attr( $iihlms_mailsubject_change_mail_password_reset ); ?>"></td>
		</tr>
		<tr>
		<th scope="row"><?php echo esc_html__( '本文', 'imaoikiruhitolms' ); ?></th>
		<td><textarea rows="12" cols="103" name="iihlms-mailbody-change-mail-password-reset" id="iihlms-mailbody-change-mail-password-reset" ><?php echo esc_textarea( $iihlms_mailbody_change_mail_password_reset ); ?></textarea><br>
		<?php echo esc_html__( '使用可能な予約語', 'imaoikiruhitolms' ); ?><br>
		*PASSWORD_RESET_URL*　<?php echo esc_html__( 'パスワードリセットURLを表示します', 'imaoikiruhitolms' ); ?><br>
		</td>
		</tr>
		</tbody>
		</table>

		<?php
		$iihlms_mailsubject_change_mail_password_reset_completed = get_option( 'iihlms_mailsubject_change_mail_password_reset_completed' );
		$iihlms_mailbody_change_mail_password_reset_completed    = get_option( 'iihlms_mailbody_change_mail_password_reset_completed' );
		?>
		<h2><?php echo esc_html__( 'メール設定（パスワードリセット完了）', 'imaoikiruhitolms' ); ?></h2>
		<table class="form-table" role="presentation">
		<tbody>
		<tr>
		<th scope="row"><?php echo esc_html__( '件名', 'imaoikiruhitolms' ); ?></th>
		<td><input type="text" size="100" name="iihlms-mailsubject-change-mail-password-reset-completed" id="iihlms-mailsubject-change-mail-password-reset-completed" value="<?php echo esc_attr( $iihlms_mailsubject_change_mail_password_reset_completed ); ?>"></td>
		</tr>
		<tr>
		<th scope="row"><?php echo esc_html__( '本文', 'imaoikiruhitolms' ); ?></th>
		<td><textarea rows="12" cols="103" name="iihlms-mailbody-change-mail-password-reset-completed" id="iihlms-mailbody-change-mail-password-reset-completed" ><?php echo esc_textarea( $iihlms_mailbody_change_mail_password_reset_completed ); ?></textarea><br>
		<?php echo esc_html__( '使用可能な予約語', 'imaoikiruhitolms' ); ?><br>
		*NAME*　<?php echo esc_html__( 'ユーザーの氏名を表示します', 'imaoikiruhitolms' ); ?><br>
		</td>
		</tr>
		</tbody>
		</table>

		<?php
		$iihlms_mailsubject_add_new_user = get_option( 'iihlms_mailsubject_add_new_user' );
		$iihlms_mailbody_add_new_user    = get_option( 'iihlms_mailbody_add_new_user' );
		?>
		<h2><?php echo esc_html__( 'メール設定（管理画面で新規ユーザーを追加時、ユーザーに送信）', 'imaoikiruhitolms' ); ?></h2>
		<table class="form-table" role="presentation">
		<tbody>
		<tr>
		<th scope="row"><?php echo esc_html__( '件名', 'imaoikiruhitolms' ); ?></th>
		<td><input type="text" size="100" name="iihlms-mailsubject-add-new-user" id="iihlms-mailsubject-add-new-user" value="<?php echo esc_attr( $iihlms_mailsubject_add_new_user ); ?>"></td>
		</tr>
		<tr>
		<th scope="row"><?php echo esc_html__( '本文', 'imaoikiruhitolms' ); ?></th>
		<td><textarea rows="12" cols="103" name="iihlms-mailbody-add-new-user" id="iihlms-mailbody-add-new-user" ><?php echo esc_textarea( $iihlms_mailbody_add_new_user ); ?></textarea><br>
		<?php echo esc_html__( '使用可能な予約語', 'imaoikiruhitolms' ); ?><br>
		*USER_NAME*　<?php echo esc_html__( 'ユーザー名を表示します', 'imaoikiruhitolms' ); ?><br>
		*PASSWORD_RESET_URL*　<?php echo esc_html__( 'パスワードリセットURLを表示します', 'imaoikiruhitolms' ); ?><br>
		</td>
		</tr>
		</tbody>
		</table>

		<?php
		$iihlms_mailsubject_add_new_user_admin = get_option( 'iihlms_mailsubject_add_new_user_admin' );
		$iihlms_mailbody_add_new_user_admin    = get_option( 'iihlms_mailbody_add_new_user_admin' );
		?>
		<h2><?php echo esc_html__( 'メール設定（管理画面で新規ユーザーを追加時、管理者に送信）', 'imaoikiruhitolms' ); ?></h2>
		<table class="form-table" role="presentation">
		<tbody>
		<tr>
		<th scope="row"><?php echo esc_html__( '件名', 'imaoikiruhitolms' ); ?></th>
		<td><input type="text" size="100" name="iihlms-mailsubject-add-new-user-admin" id="iihlms-mailsubject-add-new-user-admin" value="<?php echo esc_attr( $iihlms_mailsubject_add_new_user_admin ); ?>"></td>
		</tr>
		<tr>
		<th scope="row"><?php echo esc_html__( '本文', 'imaoikiruhitolms' ); ?></th>
		<td><textarea rows="12" cols="103" name="iihlms-mailbody-add-new-user-admin" id="iihlms-mailbody-add-new-user-admin" ><?php echo esc_textarea( $iihlms_mailbody_add_new_user_admin ); ?></textarea><br>
		<?php echo esc_html__( '使用可能な予約語', 'imaoikiruhitolms' ); ?><br>
		*USER_NAME*　<?php echo esc_html__( 'ユーザー名を表示します', 'imaoikiruhitolms' ); ?><br>
		*USER_MAIL*　<?php echo esc_html__( 'ユーザーのメールアドレスを表示します', 'imaoikiruhitolms' ); ?><br>
		</td>
		</tr>
		</tbody>
		</table>

		<?php
		$iihlms_mailsubject_change_email = get_option( 'iihlms_mailsubject_change_email' );
		$iihlms_mailbody_change_email    = get_option( 'iihlms_mailbody_change_email' );
		?>
		<h2><?php echo esc_html__( 'メール設定（メールアドレス変更時、ユーザーに送信）', 'imaoikiruhitolms' ); ?></h2>
		<table class="form-table" role="presentation">
		<tbody>
		<tr>
		<th scope="row"><?php echo esc_html__( '件名', 'imaoikiruhitolms' ); ?></th>
		<td><input type="text" size="100" name="iihlms-mailsubject-change-email" id="iihlms-mailsubject-change-email" value="<?php echo esc_attr( $iihlms_mailsubject_change_email ); ?>"></td>
		</tr>
		<tr>
		<th scope="row"><?php echo esc_html__( '本文', 'imaoikiruhitolms' ); ?></th>
		<td><textarea rows="12" cols="103" name="iihlms-mailbody-change-email" id="iihlms-mailbody-change-email" ><?php echo esc_textarea( $iihlms_mailbody_change_email ); ?></textarea><br>
		<?php echo esc_html__( '使用可能な予約語', 'imaoikiruhitolms' ); ?><br>
		*NAME*　<?php echo esc_html__( 'ユーザーの氏名を表示します', 'imaoikiruhitolms' ); ?><br>
		*NEW_MAIL*　<?php echo esc_html__( '変更後のメールアドレスを表示します', 'imaoikiruhitolms' ); ?><br>
		*OLD_MAIL*　<?php echo esc_html__( '変更前のメールアドレスを表示します', 'imaoikiruhitolms' ); ?><br>
		</td>
		</tr>
		</tbody>
		</table>

		<?php echo '<input type="hidden" name="action-type" value="iihlms-form-mail-setting">'; ?>
		<?php wp_nonce_field( 'iihlms-form-mail-setting-csrf-action', 'iihlms-form-mail-setting-csrf' ); ?>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="メール設定を更新"></p>
		</form>
		</div>

		<div id="tabs-4">
		<h2><?php echo esc_html__( 'システム設定', 'imaoikiruhitolms' ); ?></h2>
		<?php
		$iihlms_consumption_tax = get_option( 'iihlms_consumption_tax', IIHLMS_CONSUMPTION_TAX_INITIAL_VALUE );
		?>
		<form action="#tabs-4" method="post" name="iihlms-system-setting" id="iihlms-system-setting4">
		<table class="form-table" role="presentation">
		<tr>
		<th><?php echo esc_html__( '消費税率', 'imaoikiruhitolms' ); ?></th>
		<td>
		<input type="text" name="iihlms-consumption-tax" id="iihlms-consumption-tax" value="<?php echo esc_attr( $iihlms_consumption_tax ); ?>" class="regular-text" required>
		</td>
		</tr>
		</table>
		<?php echo '<input type="hidden" name="action-type" value="iihlms-system-setting">'; ?>
		<?php wp_nonce_field( 'iihlms-system-setting-csrf-action', 'iihlms-system-setting-csrf' ); ?>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_html__( '更新', 'imaoikiruhitolms' ); ?>"></p>
		</form>
		</div>

		<div id="tabs-5">
		<h2><?php echo esc_html__( 'reCAPTCHA設定', 'imaoikiruhitolms' ); ?></h2>
		<?php
		$iihlms_use_recaptcha       = get_option( 'iihlms_use_recaptcha', false );
		$iihlms_recaptcha_sitekey   = get_option( 'iihlms_recaptcha_sitekey', '' );
		$iihlms_recaptcha_secretkey = get_option( 'iihlms_recaptcha_secretkey', '' );
		?>
		<form action="#tabs-5" method="post" name="iihlms-system-setting" id="iihlms-system-setting5">
		<table class="form-table" role="presentation">
		<tr>
		<th scope="row"><?php echo esc_html__( 'reCAPTCHA', 'imaoikiruhitolms' ); ?></th>
		<td><label for="use_recaptcha">
		<input name="use_recaptcha" type="checkbox" id="use_recaptcha" <?php checked( $iihlms_use_recaptcha ); ?> value="1"><?php echo esc_html__( 'reCAPTCHAを使用する', 'imaoikiruhitolms' ); ?></label>
		</td>
		</tr>
		<tr>
		<th scope="row"><?php echo esc_html__( 'サイトキー', 'imaoikiruhitolms' ); ?></th>
		<td>
		<input name="recaptcha_sitekey" type="text" id="recaptcha_sitekey" size="100" value="<?php echo esc_attr( $iihlms_recaptcha_sitekey ); ?>">
		</td>
		</tr>
		<tr>
		<th scope="row"><?php echo esc_html__( 'シークレットキー', 'imaoikiruhitolms' ); ?></th>
		<td>
		<input name="recaptcha_secretkey" type="text" id="recaptcha_secretkey" size="100" value="<?php echo esc_attr( $iihlms_recaptcha_secretkey ); ?>">
		</td>
		</tr>
		</table>
		<?php echo '<input type="hidden" name="action-type" value="iihlms-recaptcha-setting">'; ?>
		<?php wp_nonce_field( 'iihlms-recaptcha-setting-csrf-action', 'iihlms-recaptcha-setting-csrf' ); ?>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_html__( '更新', 'imaoikiruhitolms' ); ?>"></p>
		</form>
		</div>
		<?php
		echo esc_html( apply_filters( 'iihlms_addition_setting_t6_2', '' ) );
		?>

	</div>

<script>
(function($) {
	$( '#iihlms-tabs' ).tabs();
	$( '#iihlms-tabs .ui-tabs-nav' ).removeClass( 'ui-corner-all' );
})(jQuery);
</script>
<style>
.ui-widget-content {
	border: none;
	background: none;
}
.ui-widget.ui-widget-content {
	border: none;
}
.ui-widget-header {
background: none;
border: none;
border-bottom: 1px solid #ddd;
}
.ui-tabs .ui-tabs-panel {
padding: 0;
}

</style>

</div>
