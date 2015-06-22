<div class="ui fixed main menu">
	<div class="container">
		<div class="title item">
			<a href="<?= base_url() ?>"><img src="<?= base_url() ?>resources/img/icon.png" alt="MSF"></a>
		</div>
		<div class="right menu">
<?php
	if ( $this->authentication->is_signed_in() ) {
		$picture = ( isset( $account_details->picture ) && trim( $account_details->picture ) ? base_url().RES_DIR.$account_details->picture : base_url( ) .'resources/img/user.png' );
?>
			<div class="ui item">
				<a href="<?= base_url() ?>"><?= ucfirst( strtolower( preg_replace( '/([\-_\.]+)/', ' ', $account->username ) ) ) ?> <?= showPhoto( $account_details->picture, array( 'class' => 'ui avatar image' ) ) ?></a>
			</div>
			<div class="ui item sign-out">
				<a href="<?= base_url() ?>account/sign_out">Cerrar Sesión</a>
			</div>
<?php
	}
	/*
?>
			<div class="ui language dropdown item hoverable" id="languages"
			     data-content="Select Language">
				<i class="world icon"></i>

				<div class="menu">
					<div class="item" data-percent="100" data-value="en"
					     data-english="English">English
					</div>
					<div class="item" data-percent="30" data-value="fa"
					     data-english="Persian">پارسی
					</div>
					<div class="item" data-percent="31" data-value="el"
					     data-english="Greek">ελληνικά
					</div>
					<div class="item" data-percent="29" data-value="fr"
					     data-english="French">Français
					</div>
					<div class="item" data-percent="17" data-value="de"
					     data-english="German">Deutsch
					</div>
					<div class="item" data-percent="12" data-value="lt"
					     data-english="Lithuanian">Lietuvių
					</div>
					<div class="item" data-percent="12" data-value="da"
					     data-english="Danish">dansk
					</div>
					<div class="item" data-percent="17" data-value="es"
					     data-english="Spanish">Español
					</div>
					<div class="item" data-percent="14" data-value="id"
					     data-english="Indonesian">Indonesian
					</div>
					<div class="item" data-percent="14" data-value="it"
					     data-english="Italian">Italiano
					</div>
					<div class="item" data-percent="10" data-value="tr"
					     data-english="Turkish">Türkçe
					</div>
					<div class="item" data-percent="7" data-value="nl"
					     data-english="Dutch">Nederlands
					</div>
					<div class="item" data-percent="6" data-value="hu"
					     data-english="Hungarian">Magyar
					</div>
					<div class="item" data-percent="7" data-value="pt_BR"
					     data-english="Portuguese">Português
					</div>
					<div class="item" data-percent="7" data-value="vi"
					     data-english="Vietnamese">tiếng Việt
					</div>
					<div class="item" data-percent="5" data-value="ar"
					     data-english="Arabic">العربية
					</div>
					<div class="item" data-percent="5" data-value="zh"
					     data-english="Chinese">简体中文
					</div>
					<div class="item" data-percent="4" data-value="sv"
					     data-english="Swedish">svenska
					</div>
					<div class="item" data-precent="2" data-value="pl"
					     data-english="Polish">polski
					</div>
					<div class="item" data-percent="0" data-value="ru"
					     data-english="Russian">Русский
					</div>
					<div class="item" data-percent="0" data-value="ja"
					     data-english="Japanese">日本語
					</div>
					<div class="item" data-percent="0" data-value="kr"
					     data-english="Korean">한국어
					</div>
				</div>
			</div>
	<?php
	*/
	?>
		</div>
	</div>
</div>
<div class="main-content">
