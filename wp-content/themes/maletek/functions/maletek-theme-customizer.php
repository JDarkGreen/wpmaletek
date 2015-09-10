<?php
/***********************************************************************************************/
/* Add a menu option to link to the customizer */
/***********************************************************************************************/
add_action('admin_menu', 'display_custom_options_link');
function display_custom_options_link() {
	add_theme_page('Maletek Opciones', 'Maletek Opciones', 'edit_theme_options', 'customize.php');
}

/***********************************************************************************************/
/* Add options in the theme customizer page */
/***********************************************************************************************/
add_action('customize_register', 'maletek_customize_register');
function maletek_customize_register($wp_customize) {
	// Logo
	$wp_customize->add_section('maletek_logo', array(
		'title' => __('Logo', THEMEDOMAIN),
		'description' => __('Cambiar logo del sitio web.', THEMEDOMAIN),
		'priority' => 35
	));

	$wp_customize->add_setting('maletek_custom_settings[logo]', array(
		'default' => IMAGES . '/logo.png',
		'type' => 'option'
	));

	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'logo', array(
		'label' => __('Sube tu logo', THEMEDOMAIN),
		'section' => 'maletek_logo',
		'settings' => 'maletek_custom_settings[logo]'
	)));

	// Links Redes Sociales
	$wp_customize->add_section('maletek_social', array(
		'title' => __('Links Redes Sociales', THEMEDOMAIN),
		'description' => __('Mostrar links a redes sociales.', THEMEDOMAIN),
		'priority' => 36
	));

	$wp_customize->add_setting('maletek_custom_settings[display_social_link]', array(
		'default' => 0,
		'type' => 'option'
	));

	$wp_customize->add_control('maletek_custom_settings[display_social_link]', array(
		'label' => __('¿Mostrar links?', THEMEDOMAIN),
		'section' => 'maletek_social',
		'settings' => 'maletek_custom_settings[display_social_link]',
		'type' => 'checkbox'
	));

	$wp_customize->add_setting('maletek_custom_settings[facebook]', array(
		'default' => '',
		'type' => 'option'
	));

	$wp_customize->add_control('maletek_custom_settings[facebook]', array(
		'label' => __('Facebook', THEMEDOMAIN),
		'section' => 'maletek_social',
		'settings' => 'maletek_custom_settings[facebook]',
		'type' => 'text'
	));

	$wp_customize->add_setting('maletek_custom_settings[twitter]', array(
		'default' => '',
		'type' => 'option'
	));

	$wp_customize->add_control('maletek_custom_settings[twitter]', array(
		'label' => __('Twitter', THEMEDOMAIN),
		'section' => 'maletek_social',
		'settings' => 'maletek_custom_settings[twitter]',
		'type' => 'text'
	));

	$wp_customize->add_setting('maletek_custom_settings[youtube]', array(
		'default' => '',
		'type' => 'option'
	));

	$wp_customize->add_control('maletek_custom_settings[youtube]', array(
		'label' => __('Youtube', THEMEDOMAIN),
		'section' => 'maletek_social',
		'settings' => 'maletek_custom_settings[youtube]',
		'type' => 'text'
	));

	// Contact Email
	$wp_customize->add_section('maletek_contact_email', array(
		'title' => __('Email de contacto', THEMEDOMAIN),
		'description' => __('Establezca la dirección de correo electrónico del receptor del formulario de contacto.', THEMEDOMAIN),
		'priority' => 37
	));

	$wp_customize->add_setting('maletek_custom_settings[contact_email]', array(
		'default' => '',
		'type' => 'option'
	));

	$wp_customize->add_control('maletek_custom_settings[contact_email]', array(
		'label' => __('Correo electrónico', THEMEDOMAIN),
		'section' => 'maletek_contact_email',
		'settings' => 'maletek_custom_settings[contact_email]',
		'type' => 'text'
	));

	// Venta Email
	$wp_customize->add_section('maletek_venta_email', array(
		'title' => __('Venta email', THEMEDOMAIN),
		'description' => __('Establezca la dirección de correo electrónico del receptor para el asunto "Ventas".', THEMEDOMAIN),
		'priority' => 38
	));

	$wp_customize->add_setting('maletek_custom_settings[venta_email]', array(
		'default' => '',
		'type' => 'option'
	));

	$wp_customize->add_control('maletek_custom_settings[venta_email]', array(
		'label' => __('Correo electrónico', THEMEDOMAIN),
		'section' => 'maletek_venta_email',
		'settings' => 'maletek_custom_settings[venta_email]',
		'type' => 'text'
	));

	// Publilockers Email
	$wp_customize->add_section('maletek_publi_email', array(
		'title' => __('Publilockers email', THEMEDOMAIN),
		'description' => __('Establezca la dirección de correo electrónico del receptor para el asunto "Publilockers".', THEMEDOMAIN),
		'priority' => 39
	));

	$wp_customize->add_setting('maletek_custom_settings[publi_email]', array(
		'default' => '',
		'type' => 'option'
	));

	$wp_customize->add_control('maletek_custom_settings[publi_email]', array(
		'label' => __('Correo electrónico', THEMEDOMAIN),
		'section' => 'maletek_publi_email',
		'settings' => 'maletek_custom_settings[publi_email]',
		'type' => 'text'
	));

	// Datos de la organización
	$wp_customize->add_section('maletek_informacion', array(
		'title' => __('Datos de la empresa', THEMEDOMAIN),
		'description' => __('Asignamos datos informativos de la organización.', THEMEDOMAIN),
		'priority' => 40
	));

	$wp_customize->add_setting('maletek_custom_settings[direccion]', array(
		'default' => __('Av. San Pablo Carriquiry 455 Of. 8 <br /> San Isidro. <br /> Lima - Perú', THEMEDOMAIN),
		'type' => 'option'
	));

	$wp_customize->add_control('maletek_custom_settings[direccion]', array(
		'label' => __('Dirección: ', THEMEDOMAIN),
		'section' => 'maletek_informacion',
		'settings' => 'maletek_custom_settings[direccion]',
		'type' => 'text'
	));

	$wp_customize->add_setting('maletek_custom_settings[telefono]', array(
		'default' => '(51 1) 225 - 3355',
		'type' => 'option'
	));

	$wp_customize->add_control('maletek_custom_settings[telefono]', array(
		'label' => __('Teléfono: (si hay más de uno colocar entre comas ) ', THEMEDOMAIN),
		'section' => 'maletek_informacion',
		'settings' => 'maletek_custom_settings[telefono]',
		'type' => 'text'
	));

	$wp_customize->add_setting('maletek_custom_settings[fax]', array(
		'default' => '(51 1) 226 - 1765',
		'type' => 'option'
	));

	$wp_customize->add_control('maletek_custom_settings[fax]', array(
		'label' => __('Fax: ', THEMEDOMAIN),
		'section' => 'maletek_informacion',
		'settings' => 'maletek_custom_settings[fax]',
		'type' => 'text'
	));

	/*Celular*/

	$wp_customize->add_setting('maletek_custom_settings[celular]', array(
		'default' => '(511) 999 042 357',
		'type' => 'option'
	));

	$wp_customize->add_control('maletek_custom_settings[celular]', array(
		'label' => __('Celular: (si hay más de uno colocar entre comas ) ', THEMEDOMAIN),
		'section' => 'maletek_informacion',
		'settings' => 'maletek_custom_settings[celular]',
		'type' => 'text'
	));

	/* Top Ad
	$wp_customize->add_section('adaptive_ad', array(
		'title' => __('Top Ad', 'adaptive-framework'),
		'description' => __('Allows you to upload an ad banner to display on the top of the page.', 'adaptive-framework'),
		'priority' => 36
	));

	$wp_customize->add_setting('adaptive_custom_settings[display_top_ad]', array(
		'default' => 0,
		'type' => 'option'
	));

	$wp_customize->add_control('adaptive_custom_settings[display_top_ad]', array(
		'label' => __('Display the Top Ad?', 'adaptive-framework'),
		'section' => 'adaptive_ad',
		'settings' => 'adaptive_custom_settings[display_top_ad]',
		'type' => 'checkbox'
	));

	$wp_customize->add_setting('adaptive_custom_settings[top_ad]', array(
		'default' => IMAGES . '/demo/ad-468x60.gif',
		'type' => 'option'
	));

	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'top_ad', array(
		'label' => __('Upload the Top Banner Image', 'adaptive-framework'),
		'section' => 'adaptive_ad',
		'settings' => 'adaptive_custom_settings[top_ad]'
	)));

	$wp_customize->add_setting('adaptive_custom_settings[top_ad_link]', array(
		'default' => 'http://webdesign.tutsplus.com',
		'type' => 'option'
	));

	$wp_customize->add_control('adaptive_custom_settings[top_ad_link]', array(
		'label' => __('Link to the Target Website', 'adaptive-framework'),
		'section' => 'adaptive_ad',
		'settings' => 'adaptive_custom_settings[top_ad_link]',
		'type' => 'text'
	)); */
}