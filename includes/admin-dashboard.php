<?php
/**
 * Imaoikiruhito LMS admin-dashboard.php
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
<div class="wrap">
	<h2><?php echo esc_html__( '概要', 'imaoikiruhitolms' ); ?></h2>
	<ul>
	<li><?php echo esc_html__( '管理者のみ管理画面に入れます。その他のユーザーは管理画面に入れません。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( 'プラグインを有効化した際に、複数のテーブルと固定ページが作成されます。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( 'プラグインで作成した固定ページは、削除せずそのままとしてください。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '固定ページを誤って削除した場合、プラグインを一度無効化し、再度有効化すればまた固定ページが作成されます。', 'imaoikiruhitolms' ); ?></li>
	</ul>

	<h3><?php echo esc_html__( 'クレジット決済設定', 'imaoikiruhitolms' ); ?></h3>
	<?php
	$listener_url = get_home_url() . '/?iihlms-api=iihlms-api-paypal';
	?>
	<ul>
	<li><?php echo esc_html__( 'まずは', 'imaoikiruhitolms' ); ?><a href="<?php echo esc_url( admin_url( 'admin.php?page=iihlms_payment_method_setting' ) ); ?>"><?php echo esc_html__( '支払い方法設定', 'imaoikiruhitolms' ); ?></a><?php echo esc_html__( 'を行ってください。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( 'PayPal：', 'imaoikiruhitolms' ); ?></li>
	<li><a href="<?php echo esc_url( 'https://dashboard.stripe.com/settings/billing/automatic' ); ?>" target="_blank"><?php echo esc_html__( 'PayPal Developer', 'imaoikiruhitolms' ); ?></a><?php echo esc_html__( 'にて、クライアントIDとシークレットID、Webhookを取得してください。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( 'Webhookを追加する際、Webhook URLには', 'imaoikiruhitolms' ); ?><br>
	<?php echo esc_url( $listener_url ); ?><br>
	<?php echo esc_html__( 'を指定してください。', 'imaoikiruhitolms' ); ?><br>
	<?php echo esc_html__( 'Event typesはAll Eventsとしてください。', 'imaoikiruhitolms' ); ?><br>
	</li>

	<li><?php echo esc_html__( 'テスト時には動作環境に「テスト環境（Sandbox）」を選択してください。', 'imaoikiruhitolms' ); ?></li>
	<?php
	if ( defined( 'IIHLMS_ADDITION' ) ) {
		?>
		<li><?php echo esc_html__( 'Stripe：', 'imaoikiruhitolms' ); ?></li>
		<li><?php echo esc_html__( '公開可能キーとシークレットキーを入力してください。', 'imaoikiruhitolms' ); ?></li>
		<li><?php echo esc_html__( 'Webhook署名シークレットを入力してください。', 'imaoikiruhitolms' ); ?></li>
		<li><a href="<?php echo esc_url( 'https://dashboard.stripe.com/settings/billing/automatic' ); ?>" target="_blank"><?php echo esc_html__( 'Stripeの管理画面', 'imaoikiruhitolms' ); ?></a><?php echo esc_html__( 'にて、「有効期限が切れるカードについてメールを送信」を有効にしておくと便利です。', 'imaoikiruhitolms' ); ?></li>
		<?php
	}
	?>
	</ul>

	<h3><?php echo esc_html__( '講座', 'imaoikiruhitolms' ); ?></h3>
	<ul>
	<li><?php echo esc_html__( 'ビジュアルエディタに講座の説明を入力してください。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '価格を税抜で入力してください。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '「講座とコースの関連」で、右側に移動したコースがこの講座に関連するコースとなります。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '「コースの順番を指定してください」のところで、コースの順番を入れ替えることができます。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '「この講座を閲覧・購入できるのを以下の会員ステータスに限定する」を指定した場合、その会員ステータスを付与されたユーザーのみ、該当講座の閲覧・購入が可能となります。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '「この講座を購入した人に対し以下の会員ステータスを自動付与する」を指定した場合、該当講座を購入した際に指定した会員ステータスが付与されます。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '「指定したコースを受講完了後、この講座を閲覧・購入できるよう限定する」を指定した場合、指定したコースを受講完了したユーザーのみ、該当講座の閲覧・購入が可能となります。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( 'アイキャッチに16:9の画像を指定してください。', 'imaoikiruhitolms' ); ?></li>
	<?php
	if ( defined( 'IIHLMS_ADDITION' ) ) {
		?>
		<li><?php echo esc_html__( '支払い方法で、「一括」か「サブスクリプション」を選んでください。', 'imaoikiruhitolms' ); ?></li>
		<li><?php echo esc_html__( '決済設定状況(サブスクリプション)で、PayPalやStripe上のサブスクリプション設定状況を確認できます。講座の公開時、更新時に自動的に設定されます。', 'imaoikiruhitolms' ); ?></li>
		<li><?php echo esc_html__( 'テストを使用する場合、「この講座に紐付けするテスト」を指定してください。', 'imaoikiruhitolms' ); ?></li>
		<li><?php echo esc_html__( '「指定したテストに合格した後、この講座を閲覧できるよう限定する」を指定した場合、指定したテストに合格した場合のみ、該当講座の閲覧・購入が可能となります。', 'imaoikiruhitolms' ); ?></li>
		<?php
	}
	?>
	</ul>

	<h3><?php echo esc_html__( 'コース', 'imaoikiruhitolms' ); ?></h3>
	<ul>
	<li><?php echo esc_html__( 'ビジュアルエディタにコースの説明を入力してください。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '「コースとレッスンの関連」で、右側に移動したレッスンがこの講座に関連するコースとなります。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '「レッスンの順番を指定してください」のところで、レッスンの順番を入れ替えることができます。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '「コースの補足」に、コースの補足内容を入力してください。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '「教材」に、コースの教材を入力してください。入力したリンクにはアイコンが追加された状態で表示されます。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '「このコースはログイン不要で誰でもアクセス可能にする」で「する」を選択すると、未ログインでも本コースを閲覧できるようになります。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '「進めないようにしたレッスンをコース画面で一覧表示するか」で「する」を選択すると、進めないようにしたレッスンをコース画面で一覧表示します。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( 'アイキャッチに16:9の画像を指定してください。', 'imaoikiruhitolms' ); ?></li>
	<?php
	if ( defined( 'IIHLMS_ADDITION' ) ) {
		?>
		<li><?php echo esc_html__( 'テストを使用する場合、「このコースに紐付けするテスト」を指定してください。', 'imaoikiruhitolms' ); ?></li>
		<?php
	}
	?>
	</ul>

	<h3><?php echo esc_html__( 'レッスン', 'imaoikiruhitolms' ); ?></h3>
	<ul>
	<li><?php echo esc_html__( 'ビジュアルエディタに埋め込みたい動画のHTMLコードを入力してください。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( 'YoutubeとVimeoで動作確認をしています。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '動画の表示サイズは自動的に調整されます。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '「動画URL」に埋め込みたい動画のURLを入力することもできます。「動画URL」に入力した場合、ビジュアルエディタの内容は無視されます。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '「動画URL」には、YoutubeのURLか、Dropboxの共有URLを入力できます。Dropboxの場合、「アクセスできるユーザー」は「リンクを知る全ユーザー」、「ダウンロードを無効にする」は「オフ」にしてください。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '「音声」に埋め込みたい音声のURLを入力することができます。Dropboxの共有URLを入力する場合、「アクセスできるユーザー」は「リンクを知る全ユーザー」、「ダウンロードを無効にする」は「オフ」にしてください。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '「レッスンの補足」に、レッスンの補足内容を入力してください。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '「教材」に、レッスンの教材を入力してください。入力したリンクにはアイコンが追加された状態で表示されます。', 'imaoikiruhitolms' ); ?></li>
	<?php
	if ( defined( 'IIHLMS_ADDITION' ) ) {
		?>
		<li><?php echo esc_html__( 'テストを使用する場合、「このレッスンに紐付けするテスト」を指定してください。', 'imaoikiruhitolms' ); ?></li>
		<?php
	}
	?>
	</ul>

	<h3><?php echo esc_html__( 'システム設定', 'imaoikiruhitolms' ); ?></h3>
	<ul>
	<li><?php echo esc_html__( '会員種別一覧で、会員種別名称の修正や削除ができます。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '会員種別追加で、会員種別の追加ができます。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( 'メール設定で、各メールの内容を変更できます。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( 'システム設定で、消費税率等を設定できます。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( 'reCAPTCHAで、reCAPTCHAを設定できます。', 'imaoikiruhitolms' ); ?></li>
	</ul>

	<h3><?php echo esc_html__( '支払い方法設定', 'imaoikiruhitolms' ); ?></h3>
	<ul>
	<li><?php echo esc_html__( '使用する支払い方法を設定できます。', 'imaoikiruhitolms' ); ?></li>
	</ul>

	<h3><?php echo esc_html__( '注文一覧', 'imaoikiruhitolms' ); ?></h3>
	<ul>
	<li><?php echo esc_html__( '注文の一覧を確認できます。', 'imaoikiruhitolms' ); ?></li>
	</ul>

	<h3><?php echo esc_html__( '注文履歴', 'imaoikiruhitolms' ); ?></h3>
	<ul>
	<li><?php echo esc_html__( '注文の履歴を確認できます。', 'imaoikiruhitolms' ); ?></li>
	</ul>

	<?php
	if ( defined( 'IIHLMS_ADDITION' ) ) {
		?>
		<h3><?php echo esc_html__( 'テスト', 'imaoikiruhitolms' ); ?></h3>
		<ul>
		<li><?php echo esc_html__( 'テストを登録・編集できます。', 'imaoikiruhitolms' ); ?></li>
		</ul>
		<?php
	}
	?>

	<?php
	if ( defined( 'IIHLMS_ADDITION' ) ) {
		?>
		<h3><?php echo esc_html__( 'テスト結果', 'imaoikiruhitolms' ); ?></h3>
		<ul>
		<li><?php echo esc_html__( 'テスト結果を確認できます。', 'imaoikiruhitolms' ); ?></li>
		</ul>
		<?php
	}
	?>

	<?php
	if ( defined( 'IIHLMS_ADDITION' ) ) {
		?>
		<h3><?php echo esc_html__( '証明書', 'imaoikiruhitolms' ); ?></h3>
		<ul>
		<li><?php echo esc_html__( '証明書を登録・編集できます。', 'imaoikiruhitolms' ); ?></li>
		<li><?php echo esc_html__( '登録した証明書を、テストに関連付けして使用します。テスト編集画面にて、テストと証明書を関連付けることができます。', 'imaoikiruhitolms' ); ?></li>
		</ul>
		<?php
	}
	?>

	<?php
	if ( defined( 'IIHLMS_ADDITION' ) ) {
		?>
		<h3><?php echo esc_html__( '定期購入一覧', 'imaoikiruhitolms' ); ?></h3>
		<ul>
		<li><?php echo esc_html__( 'サブスクリプションの一覧を確認できます。', 'imaoikiruhitolms' ); ?></li>
		</ul>
		<?php
	}
	?>

	<?php
	if ( defined( 'IIHLMS_ADDITION' ) ) {
		?>
		<h3><?php echo esc_html__( '定期購入履歴', 'imaoikiruhitolms' ); ?></h3>
		<ul>
		<li><?php echo esc_html__( 'サブスクリプションの履歴を確認できます。', 'imaoikiruhitolms' ); ?></li>
		</ul>
		<?php
	}
	?>

	<?php
	if ( defined( 'IIHLMS_ADDITION' ) ) {
		?>
		<h3><?php echo esc_html__( 'PayPal定期購入ログ', 'imaoikiruhitolms' ); ?></h3>
		<ul>
		<li><?php echo esc_html__( 'PayPalサブスクリプションの履歴を確認できます。', 'imaoikiruhitolms' ); ?></li>
		</ul>
		<?php
	}
	?>

	<?php
	if ( defined( 'IIHLMS_ADDITION' ) ) {
		?>
		<h3><?php echo esc_html__( 'Stripe定期購入ログ', 'imaoikiruhitolms' ); ?></h3>
		<ul>
		<li><?php echo esc_html__( 'Stripeサブスクリプションの履歴を確認できます。', 'imaoikiruhitolms' ); ?></li>
		</ul>
		<?php
	}
	?>

	<h3><?php echo esc_html__( 'ユーザー - 新規追加', 'imaoikiruhitolms' ); ?></h3>
	<ul>
	<li><?php echo esc_html__( 'Imaoikiruhito LMS以下の項目を入力してください。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '姓、名についてはImaoikiruhito LMS以下の項目を使用しています。デフォルト（メールとサイトの間）の姓、名は使用していません。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '一般ユーザーの権限グループは「購読者」としてください。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '管理ユーザーの権限グループは「管理者」としてください。', 'imaoikiruhitolms' ); ?></li>
	</ul>

	<h3><?php echo esc_html__( 'ユーザー - 編集', 'imaoikiruhitolms' ); ?></h3>
	<ul>
	<li><?php echo esc_html__( '「購入済の講座変更」にて、ユーザーが使用可能な講座を変更することができます。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '例えば、無料特典で特定のユーザーに講座閲覧できる権限を渡したい時に使用できます。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '銀行振込しかできない人への対応も本機能を使用すれば可能です。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '何らかの事情で決済のみ実行され講座の購入状態が更新されなかった場合、本機能を使用してカバーすることができます。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '本機能は決済サービスとは連動していません。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '例えばサブスクリプションの講座を誤って未購入の状態に変更した場合、決済サービスにおいてはそのまま請求が継続します。サブスクリプションの講座を購入済の状態に変更すれば元通りです。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '例えばサブスクリプションの講座を誤って購入済の状態に変更した場合、決済サービスにおいて請求が発生しません。サブスクリプションの講座を未購入の状態に変更すれば元通りです。', 'imaoikiruhitolms' ); ?></li>
	</ul>

	<h3><?php echo esc_html__( '新規ユーザー登録', 'imaoikiruhitolms' ); ?></h3>
	<ul>
	<li><?php echo esc_html__( 'ログイン画面下部の「新規ユーザー登録」を押すと、新規ユーザー登録画面が表示されます。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '「新規ユーザー登録」で入力したメールアドレスに、24時間有効な登録用URLが送信されます。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( 'メール内のURLから、ユーザーを登録することができます。', 'imaoikiruhitolms' ); ?></li>
	<li><?php echo esc_html__( '会員種別は未登録の状態となります。', 'imaoikiruhitolms' ); ?></li>
	</ul>
</div>
