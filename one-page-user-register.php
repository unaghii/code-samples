<?php
/**
* Template for a one-page user register form. I would have preferred to have
* the actual registering code separate but it was important for the client he
* saw everything in one page in case his team wanted to easily add another field.
*
* Client: a user pooling website
*
*/
	global $theme_prefix;

	if ( $_SERVER['REQUEST_METHOD'] == "POST" ) {
		if( !email_exists( $_POST['txt_email'] ) && !username_exists( $_POST['txt_username'] ) ) {
			$user_ID = wp_insert_user(array(
				'user_login'		=> $_POST['txt_username'],
				'user_pass'			=> $_POST['txt_password'],
				'first_name'		=> $_POST['txt_name'],
				'last_name'			=> $_POST['txt_lastname'],
				'user_email'		=> $_POST['txt_email'],
				'role'				=> 'pesquisas'
			));

			if ( !is_wp_error( $user_ID ) ) {
				update_user_option( $user_ID, 'show_admin_bar_front', false );

				// Manually login user
				wp_signon( array (
					'user_login'	=> $_POST['txt_username'],
					'user_password'	=> $_POST['txt_password'],
					'remember'		=> true
				) );
				wp_set_auth_cookie( $user_ID );

				$page_referrer = $_POST['page_referrer'];
				if ( strpos( $page_referrer, "<<EDITED>>.com" ) === false ){
					$page_referrer = '/meu-perfil/';
				}

				// Display message
				get_header();
				echo '
					<h1 class="page-title">'. __('Cadastro realizado!', 'lang') .'</h1>
					<div class="alerts success">
						<p>'. __('Cadastro realizado com sucesso! Prossiga para a página <a href="/meu-perfil/"><strong>Meu Perfil</strong></a> e preencha o seu perfil com informações complementares ou volte para a página que estava anteriormente. <br />Você será redirecionado para a próxima página em 5 segundos. Caso isso não aconteça <a href="' . $page_referrer . '"><strong>clique aqui</strong></a>.', 'lang') .'</p>
					</div>
					<script>
						window.setTimeout(function(){
							window.location.href = "'. $page_referrer .'";
						}, 5000);
					</script>
				';

				// Email user
				$message = '
					<!DOCTYPE html>
					<html lang="en">
					<head>
						<meta charset="UTF-8">
						<title><<EDITED>></title>
					</head>
					<body style="background:#F1F1F1">
						<table width="500" cellpadding="0" cellspacing="0" style="margin:0px auto;border-radius:5px;">
							<thead style="background:#333;text-align:center;">
								<tr>
									<td>
										<img src="'. get_template_directory_uri() .'/assets/images/img_logo.png" alt="<<EDITED>>" width="270" height="auto" style="margin:40px 0;">
									</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="padding:20px;background:#FFFFFF;font-family:Arial, Verdana, sans-serif;font-size:14px;color:#333;">
										<h1 style="text-align:center;">Cadastro feito com sucesso!</h1>
										<p>Olá, <strong>'. $_POST['txt_name'] .'</strong>,</p>
										<p>Parabéns! Seu cadastro no site <strong><<EDITED>></strong> foi efeituado com sucesso. Aguarde enquanto nossa equipe analisa seu perfil.</p>
										<p>Importante: Nao se esqueça de sempre manter o seu perfil atualizado.</p>
										<p>Qualquer duvida, entre em <a href="mailto:contato@<<EDITED>>.com.br">contato conosco</a>.<br />
										<strong>Equipe <<EDITED>></strong></p>
									</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td style="padding:10px 0 0;font-family:Arial, Verdana, sans-serif;font-size:11px;text-align:center;color:#999;">
										<p>© ' . date("Y") . ' <<EDITED>>. Todos os direitos reservados.</p>
									</td>
								</tr>
							</tfoot>
						</table>
					</body>
					</html>
				';

				$mailer 	= get_option('admin_email');
				$receiver 	= $_POST['txt_email'];
				$subject	= "Novo cadastro no <<EDITED>>";
				$headers 	= "From: <<EDITED>> <". $mailer .">\nContent-type: text/html; charset=utf-8";
				wp_mail( $receiver, $subject, $message, $headers );
			}
		} else {
			get_header();
			echo '
				<h1 class="page-title">'. __('Cadastro', 'lang') .'</h1>
				<div class="alerts error">
					<p>'. __('Já existe um usuário cadastrado com este email ou usuário.', 'lang') .'</p>
				</div>
			';
		}
	} else {
		get_header();
	}
?>
	<?php if(have_posts()): while(have_posts()): the_post(); ?>

		<?php if($_SERVER['REQUEST_METHOD'] != "POST"){ ?>
			<div id="register-facebook-wrapper" class="full-width-wrapper">
				<?php
					if(class_exists('NextendSocialLogin', false)){
						echo NextendSocialLogin::renderButtonsWithContainer();
					}
				?>
			</div>

			<h1 class="page-title"><?php _e('Ou cadastre-se', 'lang'); ?></h1>
			<div class="entry">
				<?php the_content(); ?>
			</div>

			<form id="user-register-form" class="forms" method="post" action="http://<?php echo $_SERVER[HTTP_HOST].$_SERVER[R<<EDITED>>_URI]; ?>" onsubmit="return validateUserRegister(this);">
				<p class="alerts"></p>

				<p class="mandatory-fields-p"><?php _e('Os campos com * asterisco são obrigatórios', 'lang'); ?></p>

				<div class="line-container">
					<p>
						<label for="txt_name"><?php _e('Nome', 'lang'); ?> *</label>
						<input type="text" id="txt_name" name="txt_name" data-error="<?php _e('Digite seu nome.', 'lang'); ?>">
					</p>
					<p class="right-aligned">
						<label for="txt_lastname"><?php _e('Sobrenome', 'lang'); ?> *</label>
						<input type="text" id="txt_lastname" name="txt_lastname" data-error="<?php _e('Digite seu sobrenome.', 'lang'); ?>">
					</p>
				</div>
				<p>
					<label for="txt_username"><?php _e('Usuário', 'lang'); ?> *</label>
					<input type="text" id="txt_username" name="txt_username" data-error="<?php _e('Digite seu usuário.', 'lang'); ?>">
				</p>
				<p>
					<label for="txt_email"><?php _e('Email', 'lang'); ?> *</label>
					<input type="text" id="txt_email" name="txt_email" data-error="<?php _e('Digite um email válido.', 'lang'); ?>">
				</p>
				<p>
					<label for="txt_password"><?php _e('Senha', 'lang'); ?> *</label>
					<input type="password" id="txt_password" name="txt_password" data-error="<?php _e('Digite uma senha.', 'lang'); ?>">
				</p>
				<p>
					<label for="txt_terms" class="smaller-text">
						<input type="checkbox" name="txt_terms" id="txt_terms" data-error="<?php _e('Você deve concordar com os termos de uso.', 'lang'); ?>"> <?php _e('Li e concordo com os <a href="/termos-de-uso/" target="_blank" title="Termos de uso">termos de uso</a>.', 'lang'); ?>
					</label>
				</p>

				<input type="hidden" name="page_referrer" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">

				<input type="submit" class="button submit-form-btn" value="<?php _e('Criar nova conta', 'lang'); ?>">

				<p class="register-link-p"><a class="fancybox" href="#login-form-wrapper" title="<?php _e('Faça login', 'lang'); ?>"><?php _e('Já é cadastrado? Clique aqui para acessar sua conta', 'lang'); ?></a></p>
			</form>
		<?php } ?>

	<?php endwhile; else: ?>
		<p class="msg-info"><?php _e('Nenhum dado encontrado.', 'lang'); ?></p>
	<?php endif; ?>
<?php get_footer(); ?>