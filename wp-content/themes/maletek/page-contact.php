<?php
/*
	Template Name: Contact Page
*/
?>
<?php
	$options = get_option('maletek_custom_settings');

	// Function for email address validation
	function isEmail($verify_email) {
		return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i", $verify_email));
	}

	$error_name = false;
	$error_email = false;
	$error_ruc = false;
	$error_asunto = false;
	$error_company = false;
	$error_tel = false;
	$error_message = false;

	if (isset($_POST['contact_submit'])) {
		$name = '';
		$email = '';
		$ruc = '';
		$asunto = '';
		$cargo = '';
		$company = '';
		$tel = '';
		$cel = '';
		$message = '';
		$receiver_email = '';

		// Get the name
		if (trim(esc_attr($_POST['contact_name'])) === '') {
			$error_name = true;
		} else {
			$name = trim(esc_attr($_POST['contact_name']));
		}

		// Get the email
		if (trim(esc_attr($_POST['contact_email'])) === '' || !isEmail($_POST['contact_email']) || !is_email($_POST['contact_email'])) {
			$error_email = true;
		} else {
			$email = trim(esc_attr($_POST['contact_email']));
		}

		if (trim(esc_attr($_POST['contact_ruc'])) === '') {
			$error_ruc = true;
		} else {
			$ruc = trim(esc_attr($_POST['contact_ruc']));
		}

		if ($_POST['contact_asunto'] === '') {
			$error_asunto = true;
		} else {
			$asunto = $_POST['contact_asunto'];
		}

		if (trim(esc_attr($_POST['contact_company'])) === '') {
			$error_company = true;
		} else {
			$company = trim(esc_attr($_POST['contact_company']));
		}

		if (trim(esc_attr($_POST['contact_tel'])) === '') {
			$error_tel = true;
		} else {
			$tel = trim(esc_attr($_POST['contact_tel']));
		}

		$cel = esc_attr($_POST['contact_cel']);
		$cargo = esc_attr($_POST['contact_cargo']);

		// Get the message
		if (trim($_POST['contact_message']) === '') {
			$error_message = true;
		} else {
			$message = stripslashes(trim($_POST['contact_message']));
		}

		// Check if we have errors
		if (!$error_name && !$error_email && !$error_ruc && !$error_asunto && !$error_company && !$error_tel && !$error_message) {
			// Get the receiver email from the backend
			$receiver_email = $options['contact_email'];

			/*
			if ($asunto === '1') {
				$receiver_email = 'maletek@maletek.com.pe';
			}*/

			if ($asunto === '2') {
				$receiver_email = $options['venta_email'];
			}

			if ($asunto === '3') {
				$receiver_email = $options['publi_email'];
			}

			/*
			if ($asunto === '4') {
				$receiver_email = 'maletek@maletek.com.pe';
				//$receiver_email = 'jolupeza@outlook.com';
			}*/

			$text_asunto = '';
			switch ($asunto) {
				case '1':
					$text_asunto = 'Alquiler de lockers';
					break;

				case '2':
					$text_asunto = 'Venta de lockers';
					break;

				case '3':
					$text_asunto = 'Publilockers';
					break;

				case '4':
					$text_asunto = 'Consultas';
					break;
			}

			/*
			// If none is specified, get the WP admin email
			if (!isset($receiver_email) || $receiver_email == '') {
				$receiver_email = get_option('admin_email');
			}*/

			$subject = 'Has sido contactado por '. $name;
      		$body = "Has sido contacto por $name. Sus datos son:" . PHP_EOL . PHP_EOL;
      		if ($cel != '') { $body .= "Cargo: $cargo " . PHP_EOL . PHP_EOL; }
      		$body .= "RUC o DNI: $ruc" . PHP_EOL . PHP_EOL;
      		$body .= "Empresa: $company " . PHP_EOL . PHP_EOL;
      		$body .= "Teléfono: $tel" . PHP_EOL . PHP_EOL;
      		if ($cel != '') { $body .= " Celular: $cel " . PHP_EOL . PHP_EOL; }
      		$body .= "Asunto: $text_asunto" . PHP_EOL . PHP_EOL;
     	 	$body .= "Su mensaje es: " . $message . PHP_EOL . PHP_EOL;
      		$body .= "Puedes contactar con $name via email a la dirección $email";
      		$body .= PHP_EOL . PHP_EOL;

      		$headers = "From: $email" . PHP_EOL;
      		//if ($cc != '' && !empty($cc)) { $headers .= "Cc: $cc" . PHP_EOL; }
      		$headers .= "Reply-To: $email" . PHP_EOL;
      		$headers .= "MIME-Version: 1.0" . PHP_EOL;
      		$headers .= "Content-type: text/plain; charset=utf-8" . PHP_EOL;
      		$headers .= "Content-Transfer-Encoding: quoted-printable" . PHP_EOL;

      		if (mail($receiver_email, $subject, $body, $headers)) {
        		$email_sent = true;
      		} else {
        		$email_sent_error = true;
      		}
		}
	}
?>
<?php get_header(); ?>

		<section class="main-container">
			<div class="container">
				<div class="row">
					<div class="col-xs-4 hidden-xs">
						<div class="content-contact">
							<h4>informacion<span> de contacto</span></h4>
							<!-- info de contacto -->
							<ul>
							<?php /*if (!empty($options['direccion'])) : ?>
								<li>
									<span>Dirección:</span>
								  	<?php echo $options['direccion']; ?>
								</li>
							<?php endif; */ ?>
							<?php if (!empty($options['telefono'])) : ?>
								<li>
									<span>Teléfono:</span>
									<?php echo $options['telefono']; ?>
								</li>
							<?php endif; ?>
							<?php if (!empty($options['fax'])) : ?>
								<li>
									<span>Celular:</span><?php echo $options['fax']; ?>
								</li>
							<?php endif; ?>
							<?php if (!empty($options['contact_email'])) : ?>
								<li><span>Correo electrónico:</span><?php echo $options['contact_email']; ?></li>
							<?php endif; ?>
							</ul>
							<!-- /informacion de contacto -->
						</div> <!-- /content-contact -->
					</div>

					<div class="col-xs-12 col-sm-8">

						<!--figure class="map-container" id="img-map"></figure><! end map-container -->

	           		<?php if (have_posts()) : while(have_posts()) : the_post(); ?>

						<div class="contact-form">
							<h3>Formulario <span>de contacto</span></h3>

						<?php if (isset($email_sent) && $email_sent == TRUE) : ?>

							<h4 class="text-success"><?php _e('¡Correcto!', THEMEDOMAIN); ?></h3>
							<p><?php _e('Has enviado correctamente el mensaje. Nos pondremos en contacto con usted lo antes posible.', THEMEDOMAIN); ?></p>

						<?php elseif (isset($email_sent_error) && $email_sent_error == true) : ?>

			                <h4 class="text-danger"><?php _e('¡Hubo un error!', THEMEDOMAIN); ?></h3>
			                <p><?php _e('Por desgracia, no se pudo enviar el correo electrónico en este momento. Por favor, inténtalo de nuevo.', THEMEDOMAIN); ?></p>

			            <?php else : ?>

							<?php the_content(); ?>

		                	<!-- Form Change the "action" attribute to your back-end URL -->
		                	<?php $sr_only = (is_ie() && get_browser_version() <= 9) ? '' : 'sr-only'; ?>
		                	
			                <form id="registrationForm" method="post" action="<?php the_permalink(); ?>">
			                	<div class="row">
			                		<div class="col-xs-12 col-sm-6">
										<div class="form-group <?php if ($error_name) echo 'has-error'; ?>">
											<label class="<?php echo $sr_only; ?>" for="contact_name">Nombre Completo (*)</label>
											<input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Nombre Completo (*)" value="<?php if (isset($_POST['contact_name'])) echo esc_attr($_POST['contact_name']); ?>" />
										</div>

										<div class="form-group <?php if ($error_email) echo 'has-error'; ?>">
											<label class="<?php echo $sr_only; ?>" for="contact_email">Correo Eletrónico (*)</label>
											<input type="email" class="form-control" id="contact_email" name="contact_email" placeholder="Correo Eletrónico (*)" value="<?php if (isset($_POST['contact_email'])) echo esc_attr($_POST['contact_email']); ?>" />
										</div>

										<div class="form-group <?php if ($error_ruc) echo 'has-error'; ?>">
											<label class="<?php echo $sr_only; ?>" for="contact_ruc">RUC o DNI (*)</label>
											<input type="text" class="form-control" id="contact_ruc" name="contact_ruc" placeholder="R.U.C. o D.N.I. (*)" value="<?php if (isset($_POST['contact_ruc'])) echo esc_attr($_POST['contact_ruc']); ?>" />
										</div>

										<div class="form-group <?php if ($error_asunto) echo 'has-error'; ?>">
											<label class="<?php echo $sr_only; ?>" for="contact_asunto">Asunto</label>
											<select class="form-control" name="contact_asunto">
												<option value="">-- Seleccione el asunto --</option>
												<option value="1">Alquiler de lockers</option>
											  	<option value="2">Venta de lockers</option>
											  	<option value="3">Publilockers</option>
									  			<option value="4">Consultas</option>
											</select>
										</div>

										<div class="form-group <?php if ($error_company) echo 'has-error'; ?>">
											<label class="<?php echo $sr_only; ?>" for="contact_company">Empresa (*)</label>
											<input type="text" class="form-control" id="contact_company" name="contact_company" placeholder="Empresa (*)" value="<?php if (isset($_POST['contact_company'])) echo esc_attr($_POST['contact_company']); ?>" />
										</div>
			                		</div>

			                		<div class="col-xs-12 col-sm-6">
										<div class="form-group">
											<label class="<?php echo $sr_only; ?>" for="contact_cargo">Cargo</label>
											<input type="tel" class="form-control" id="contact_cargo" name="contact_cargo" placeholder="Cargo" value="<?php if (isset($_POST['contact_cargo'])) echo esc_attr($_POST['contact_cargo']); ?>" />
										</div>

										<div class="form-group <?php if ($error_tel) echo 'has-error'; ?>">
											<label class="<?php echo $sr_only; ?>" for="contact_tel">Teléfono (*)</label>
											<input type="tel" class="form-control" id="contact_tel" name="contact_tel" placeholder="Teléfono (*)" value="<?php if (isset($_POST['contact_tel'])) echo esc_attr($_POST['contact_tel']); ?>" />
										</div>

										<div class="form-group">
											<label class="<?php echo $sr_only; ?>" for="contact_cel">Celular</label>
											<input type="tel" class="form-control" id="contact_cel" name="contact_cel" placeholder="Celular" value="<?php if (isset($_POST['contact_cel'])) echo esc_attr($_POST['contact_cel']); ?>" />
										</div>

										<div class="form-group <?php if ($error_message) echo 'has-error'; ?>">
											<label class="<?php echo $sr_only; ?>" for="contact_message">Describe el uso que deseas dar al locker...</label>
											<textarea class="form-control" id="contact_message" name="contact_message" placeholder="Describe el uso que deseas dar al locker..." rows="4"><?php if (isset($_POST['contact_message'])) echo stripslashes($_POST['contact_message']); ?></textarea>
										</div>

										<input type="hidden" id="contact_submit" name="contact_submit" value="true" />

										<div class="form-group">
											<button type="submit" class="btn btn-enviar" title="enviar datos"><?php _e('Enviar', THEMEDOMAIN); ?></button>
										</div>
			                		</div>
			                	</div>
			                </form>
							<!-- end of form -->

						<?php endif; ?>

						</div><!-- /contac-form -->

					<?php endwhile; else : ?>
						<article class="no-posts">

							<h1><?php _e('No page was found.', THEMEDOMAIN); ?></h1>

						</article>
					<?php endif; ?>
					</div>
				</div> <!-- /row -->
			</div> <!-- /container- -->
		</section><!-- end main-container -->

<?php get_footer(); ?>