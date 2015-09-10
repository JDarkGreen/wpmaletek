<?php 
global $wpdb;
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
?>
<link media="all" type="text/css" href="<?php echo MALETEKPL_RSV_PLUGIN_URL ?>js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<link media="all" type="text/css" href="<?php echo MALETEKPL_RSV_PLUGIN_URL ?>js/fancybox/jquery.fancybox.css" rel="stylesheet">
<link media="all" type="text/css" href="<?php echo MALETEKPL_RSV_PLUGIN_URL ?>css/backend.css" rel="stylesheet">
<script type="text/javascript">
    var MALETEKPL_RSV_BACKEND_CONTROLLER="<?php echo MALETEKPL_RSV_BACKEND_CONTROLLER ?>";
    var MALETEKPL_RSV_PLUGIN_URL="<?php echo MALETEKPL_RSV_PLUGIN_URL ?>";
</script>
<div class="wrap">
    <h2>Reserva Locker</h2>
    <div class="statusTab media-upload-form " ></div>
    <div id="adminTabs">
        <ul>
            <!---li><a id="reserveTabTrigger" href="#reserveTab">Reservar</a></li---->
            <li><a href="#reserveTabList">Reservas</a></li>
            <li><a href="#userTab">Usuarios</a></li>
            <!---li><a href="#lockerTab">Ofertas de Lockers</a></li--->
            <li><a href="#itemTab">Áreas/Sectores</a></li>
            <li><a href="#localTab">Institutos</a></li>            
            <li><a href="#modelTab">Modelos</a></li>
            <li><a href="#configTab">Configuraciones</a></li>
        </ul>
        
        <!---div id="reserveTab">
            <?php #include_once 'reserveTab.php'; ?>
        </div--->
        <div id="reserveTabList">
            <?php include_once 'reserveListTab.php'; ?>
        </div>
        <div id="userTab">
            <?php include_once 'userTab.php'; ?>
        </div>
        <div id="lockerTab">
            <?php #include_once 'lockerTab.php'; ?>
        </div>
        <div id="itemTab">
            <?php include_once 'itemTab.php'; ?>
        </div>
        <div id="localTab">
            <?php include_once 'localTab.php'; ?>
        </div>
        <div id="modelTab">
            <?php include_once 'modelTab.php'; ?>
        </div>
        <div id="configTab">
            <?php include_once 'configTab.php'; ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo MALETEKPL_RSV_PLUGIN_URL ?>js/jquery.js" ></script>
<script type="text/javascript" src="<?php echo MALETEKPL_RSV_PLUGIN_URL ?>js/jquery-ui/jquery-ui.min.js" ></script>
<script type="text/javascript" src="<?php echo MALETEKPL_RSV_PLUGIN_URL ?>js/fancybox/jquery.fancybox.js" ></script>
<script type="text/javascript" src="<?php echo MALETEKPL_RSV_PLUGIN_URL ?>js/jquery.dataTables.js" ></script>
<script type="text/javascript" src="<?php echo MALETEKPL_RSV_PLUGIN_URL ?>js/jquery.form.js" ></script>
<script type="text/javascript" src="<?php echo MALETEKPL_RSV_PLUGIN_URL ?>js/jquery.validate.js" ></script>
<script type="text/javascript" src="<?php echo MALETEKPL_RSV_PLUGIN_URL ?>js/backend.js?v2" ></script>


