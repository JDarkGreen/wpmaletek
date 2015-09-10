<?php 
global $wpdb;

$sql = "SELECT * FROM wp_maletek_reservalocker_local ORDER BY varName";
$locals = $wpdb->get_results($sql) or die(mysql_error());

?>
<script type="text/javascript">
    var MALETEKPL__FRONTEND_CONTROLLER="<?php echo MALETEKPL_RSV_FRONTEND_CONTROLLER ?>";
    var MALETEKPL__PLUGIN_URL="<?php echo MALETEKPL_RSV_PLUGIN_URL ?>";
</script>
<div id="reservaLocker">
    <div id="reservaLocker-sidebar">
        <?php if( !isset($_SESSION) || !isset($_SESSION['reservalockerUser']) ){?>            
        <div id="reservaLocker-identify">
            
            <div id="reservaLocker-access">
                <a href="#reservaLocker-login" class="selected">Iniciar sesión</a>
                <a href="#reservaLocker-register">Registrate</a>            
            </div>
            <div id="reservaLocker-login" class="sidebar-tab">
                <p>
                    Bienvenido al módulo de reserva de Lockers de Maletek. Ingresa el correo y contraseña con los que te registraste.
                </p>
                <form class="ajaxForm" method="post" action="<?php echo MALETEKPL_RSV_FRONTEND_CONTROLLER ?>?controller=user&action=login">

                    <input type="text" name="email" class="input-text" placeholder="Correo electrónico" />
                    <input type="password" name="password" class="input-text" placeholder="Password" />

                    <input type="submit" name="login" value="Iniciar Sesión" class="input-button" />

                </form>

            </div>
            <div id="reservaLocker-register" class="sidebar-tab">
                <p>Ingresa los siguientes datos para reservar tu locker.</p>

                <form class="ajaxForm" method="post" action="<?php echo MALETEKPL_RSV_FRONTEND_CONTROLLER ?>?controller=user&action=register">
                    <input type="text" name="name" class="input-text" placeholder="Nombre completo" />
                    <input type="text" name="email" class="input-text" placeholder="Correo electrónico" />
                    <input type="text" name="phone" class="input-text" placeholder="Teléfono o Celular" />
                    <select name="place" class="input-select" >
                        <option value="0">- Instituto / Sede / Distrito -</option>
                        <?php if($locals){?>
                            <?php foreach($locals as $l){?>                        
                        <option value="<?php echo $l->id ?>"><?php echo $l->varName.' /  '.$l->varSubName.' / '.$l->varPlace ?></option>
                            <?php }?>
                        <?php }?>
                    </select>
                    <input type="text" name="dni" class="input-text" placeholder="DNI / Carnét de Extranjería" />
                    <input type="text" name="code" class="input-text" placeholder="Código de Estudiante" />
                    <input type="text" name="career" class="input-text" placeholder="Carrera" />
                    <input type="text" name="level" class="input-text" placeholder="Ciclo" />
                    <input type="password" name="password" class="input-text" placeholder="Contraseña" />                
                    <input type="password" name="re-password" class="input-text" placeholder="Repetir Contraseña" />

                    <input type="submit" name="register" value="Registrame" class="input-button" />

                </form>

            </div>
            
        </div>
        <?php }?>
        <div id="reservaLocker-profile"></div>        
        
    </div>
    <div id="reservaLocker-content">
        <div id="reservaLocker-slide"></div>
    </div>
    <div class="clear"></div>
</div>
<div id="reservaLockerPopup">
    <div id="reservaLockerPopup-dialog">
        <div id="reservaLockerPopup-close">X</div>
        <div id="reservaLockerPopup-content"></div>
    </div>
</div>