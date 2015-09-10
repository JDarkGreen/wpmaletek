<?php
/*
	Template Name: Info Contact Page
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
					<div class="col-xs-12">
						<div class="content-contact text-center">
							<h4><strong><?php the_title(); ?></strong></h4>

							<!-- info de contacto -->
							<ul>
							<?php if (!empty($options['direccion'])) : ?>
								<li>
									<span>Dirección:</span>
								  	<?php echo $options['direccion']; ?>
								</li>
							<?php endif; ?>
							<?php if (!empty($options['telefono'])) : ?>
								<li>
									<span>Teléfono:</span>
									<?php 
										$array_tel = explode( ',' , $options['telefono']);
										for ($i=0; $i < count($array_tel) ; $i++) { 
											echo "<p class='content-contact__item--width'>" . $array_tel[$i] . "</p>";
										}
									?>
								</li>
							<?php endif; ?>
							<?php if (!empty($options['celular'])) : ?>
								<li>
									<span>Celular:</span>
									<?php 
										$array_cel = explode( ',' , $options['celular']);
										for ($i=0; $i < count($array_cel) ; $i++) { 
											echo "<p>" . $array_cel[$i] . "</p>";
										}
									?>
								</li>
							<?php endif; ?>

							
							<?php if (!empty($options['contact_email']) || !empty($options['venta_email'])) : ?>
								<li><span>Correo electrónico:</span>
									<?php echo $options['contact_email']; ?>
									<?php echo $options['venta_email']; ?>
								</li>
							<?php endif; ?>
							</ul>
							<!-- /informacion de contacto -->
							
							<!-- /redirect to formulary contact -->
							<a href="<?php echo home_url('contactanos'); ?>" class="btn btn-contactar text-uppercase"><?php _e('contactar', THEMEDOMAIN); ?></a>
						</div> <!-- /content-contact -->
					</div>
				</div> <!-- /row -->
			</div> <!-- /container- -->
		</section><!-- end main-container -->

<?php get_footer(); ?>