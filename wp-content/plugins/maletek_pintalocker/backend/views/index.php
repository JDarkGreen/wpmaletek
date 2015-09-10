<?php 
global $wpdb;
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
?>
<link media="all" type="text/css" href="<?php echo MALETEKPL__PLUGIN_URL ?>js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<link media="all" type="text/css" href="<?php echo MALETEKPL__PLUGIN_URL ?>js/fancybox/jquery.fancybox.css" rel="stylesheet">
<link media="all" type="text/css" href="<?php echo MALETEKPL__PLUGIN_URL ?>css/backend.css" rel="stylesheet">
<script type="text/javascript">
    var MALETEKPL__BACKEND_CONTROLLER="<?php echo MALETEKPL__BACKEND_CONTROLLER ?>";
</script>
<div class="wrap">
    <h2>Pinta Locker</h2>
    <div class="statusTab media-upload-form " ></div>
    <div id="adminTabs">
        <ul>
            <li><a href="#requestTab">Solicitudes</a></li>
            <li><a href="#itemTab">Lockers</a></li>
            <li><a href="#colorTab">Colores</a></li>
            <li><a href="#configTab">Configuraciones</a></li>
        </ul>
        <div id="requestTab">
            <?php include_once 'requestTab.php'; ?>
        </div>
        <div id="itemTab">
            <?php include_once 'itemTab.php'; ?>
        </div>
        <div id="colorTab">
            <?php include_once 'colorTab.php'; ?>
        </div>
        <div id="configTab">
            <?php include_once 'configTab.php'; ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo MALETEKPL__PLUGIN_URL ?>js/jquery.js" ></script>
<script type="text/javascript" src="<?php echo MALETEKPL__PLUGIN_URL ?>js/jquery-ui/jquery-ui.min.js" ></script>
<script type="text/javascript" src="<?php echo MALETEKPL__PLUGIN_URL ?>js/fancybox/jquery.fancybox.js" ></script>
<script type="text/javascript" src="<?php echo MALETEKPL__PLUGIN_URL ?>js/jquery.dataTables.js" ></script>
<script type="text/javascript" src="<?php echo MALETEKPL__PLUGIN_URL ?>js/jquery.form.js" ></script>
<script type="text/javascript" src="<?php echo MALETEKPL__PLUGIN_URL ?>js/jquery.validate.js" ></script>
<script type="text/javascript" src="<?php echo MALETEKPL__PLUGIN_URL ?>js/jquery.canvasAreaDraw/jquery.canvasAreaDraw.js" ></script>
<script type="text/javascript" src="<?php echo MALETEKPL__PLUGIN_URL ?>js/backend.js" ></script>


