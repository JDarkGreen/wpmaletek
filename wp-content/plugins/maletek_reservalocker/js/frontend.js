jQuery(document).ready(function(){
        
    jQuery('#reservaLockerPopup-close').click(function(){
        jQuery('#reservaLockerPopup').fadeOut();
    });
    
    if(jQuery('#reservaLocker-identify').length===1){
        jQuery('#reservaLocker-access a').click(function(){
            if(!jQuery( jQuery(this).attr('href') ).is(':visible')){
                jQuery('.sidebar-tab:visible').hide();
                jQuery( jQuery(this).attr('href') ).show();

                jQuery('#reservaLocker-access a').removeClass('selected');
                jQuery(this).addClass('selected');
            }        
            return false;
        });
        
        jQuery('#reservaLocker-login .ajaxForm').ajaxForm({
            beforeSubmit: function(formData, jqForm, options){
                reservaLockerPopup('<div class="loading">Espere por favor...</div>');
            },
            success:       function(responseText, statusText, xhr, $form){
                responseText=jQuery.trim(responseText);
                if(responseText==='OK'){
                    loadProfile();
                }
                else{
                    reservaLockerPopup(responseText);
                }
            }
        });

        jQuery('#reservaLocker-register .ajaxForm').ajaxForm({
            beforeSubmit: function(formData, jqForm, options){
                reservaLockerPopup('<div class="loading">Espere por favor...</div>');
            },
            success:       function(responseText, statusText, xhr, $form){
                if(responseText==='OK'){
                    loadProfile();
                }
                else{
                    reservaLockerPopup(responseText);
                }
            }
        });
    }
    else{
        reservaLockerPopup('<div class="loading">Espere por favor...</div>');
        loadProfile();
    }    
});

function reservaLockerPopup(responseText){
    if(jQuery('#reservaLockerPopup').is(':hidden')){
        jQuery('#reservaLockerPopup').fadeIn();
    }
    
    if(responseText==="ERROR"){
        responseText='Sesión no iniciada';
    }
    else if(responseText==="ERROR-POST"){
        responseText='Información inválida';
    }
    else if(responseText==="ERROR-RESERVE"){
        responseText='Ya tiene una reserva registrada.';
    }
    else if(responseText==="ERROR-INSERT"){
        responseText='NO se pudo realizar la reserva, intentelo de nuevo más tarde.';
    }
    else if(responseText==="ERROR-MAIL"){
        responseText='Hubo un error al enviar el Email, por favor solicite una copia de este desde el formulario de contacto.';
    }
    else if(responseText==="ERROR-DISABLE"){
        responseText='El locker ya no esta disponible.';
    }
    
    
    jQuery('#reservaLockerPopup-content').html('<h3>'+responseText+'</h3>');
}
function loadProfile(){
    //load profile
    var data={
        controller:'user',
        action:'profile'
    };
    jQuery.post(MALETEKPL__FRONTEND_CONTROLLER,data,function(responseText){ 
        if(responseText.indexOf("ERROR")>=0){
            reservaLockerPopup(responseText);
        }
        else{
            jQuery('#reservaLocker-profile').html(responseText);
            jQuery('#reservaLocker-identify').remove();
            loadItems();
            
            jQuery('#reservaLocker-editButton').click(function(){
                reservaLockerPopup('<div class="loading">Espere por favor...</div>');
                loadEdit();
            });
            
        }
        
    });
}
function loadItems(){    
    //load items  
    var data={
        controller:'item',
        action:'load'
    };
    jQuery.post(MALETEKPL__FRONTEND_CONTROLLER,data,function(responseText){ 
        if(responseText.indexOf("ERROR")>=0){
            reservaLockerPopup(responseText);
        }
        else{
            jQuery('#reservaLocker-content').html(responseText);
            jQuery('#reservaLockerPopup-close').click();
            
            jQuery('#reservaLocker-locker .popupDialog-close').click(function(){
                jQuery('#reservaLocker-locker').fadeOut();
            });
            jQuery('#reservaLocker-area .tab a').click(function(){
                jQuery('#reservaLocker-area .tab a').removeClass('selected');
                jQuery(this).addClass('selected');
                
                jQuery('#reservaLocker-area .tab-block:visible').hide();
                jQuery( jQuery(this).attr('href') ).show();
                return false;
            });
            jQuery('#reservaLocker-area .tab a:first').click();
            
            jQuery('#reservaLocker-area .tab-block a').click(function(){
                jQuery('#reservaLocker-locker').fadeIn();                
                jQuery('#reservaLocker-locker .popupContent').html('<div class="loading">Espere por favor...</div>');
                
                loadSector( jQuery(this).attr('itemid') );
                
                return false;
            });
        }
        
    });
}
function loadSector(id){ 
    var data={
        controller:'item',
        action:'sector',
        idSector:id
    };
    jQuery.post(MALETEKPL__FRONTEND_CONTROLLER,data,function(responseText){ 
        jQuery('#reservaLocker-locker .popupContent').html(responseText);
        
        //cntrol
        if(jQuery('.popupDialog-reserveForm').length>1){
            jQuery('#reservaLocker-locker .popupDialog-lockerPrev').click(function(){
                var objD=jQuery(this).parents('.popupDialog-reserveForm');
                
                var objP=objD.prev();
                if(objP.length===0){
                    objP=jQuery('.popupDialog-reserveForm:last');
                }
                objD.hide();
                objP.show();
                return false;
            });
            jQuery('#reservaLocker-locker .popupDialog-lockerNext').click(function(){
                var objD=jQuery(this).parents('.popupDialog-reserveForm');
                var objP=objD.next();
                if(objP.length===0){
                    objP=jQuery('.popupDialog-reserveForm:first');
                }
                objD.hide();
                objP.show();
                return false;
            });
        }
        else{
            jQuery('#reservaLocker-locker .popupDialog-lockerPrev').remove();
            jQuery('#reservaLocker-locker .popupDialog-lockerNext').remove();            
        }
        
        
        jQuery('.popupDialog-reserveForm').each(function(){
            
            var reserveForm=jQuery(this);
            
             //locker
            reserveForm.find('.locker').click(function(){
                var objItem=jQuery(this);                
                if(!objItem.hasClass('selected') && !objItem.hasClass('disable')){
                   reserveForm.find('.locker').removeClass('selected');
                   reserveForm.find('.lockerNumber').val(objItem.attr('itemid'));
                   objItem.addClass('selected');
                } 
            });            
            reserveForm.find('.locker:first').click();
            
            //share
            reserveForm.find('.shareSel').change(function(){
               if(jQuery(this).val()==='1'){
                   reserveForm.find('.shareData').show();
               } 
               else{
                   reserveForm.find('.shareData').hide();
               }
            });
            
            var reserveFormText=reserveForm.find('.popupDialog-text');
            reserveFormText.ajaxForm({
                beforeSubmit: function(formData, jqForm, options){
                    reserveFormText.hide();
                    reserveForm.find('.popupDialog-message').html('<div class="loading">Espere por favor...</div>')
                    reserveForm.find('.popupDialog-message').show();
                },
                success:       function(responseText, statusText, xhr, $form){
                    responseText=jQuery.trim(responseText);
                    if(responseText.indexOf("ERROR")>=0){
                        reservaLockerPopup(responseText);
                        reserveFormText.show();
                        reserveForm.find('.popupDialog-message').hide();
                    }
                    else{
                        reserveForm.find('.popupDialog-message').html(responseText);
                        jQuery('#reservaLocker-locker .popupDialog-lockerPrev').remove();
                        jQuery('#reservaLocker-locker .popupDialog-lockerNext').remove();            
                    }
                }
            });
            
        });
        
        
    }); 
}

function loadEdit(){
    var data={
        controller:'user',
        action:'edit'
    };
    jQuery.post(MALETEKPL__FRONTEND_CONTROLLER,data,function(responseText){ 
        if(responseText.indexOf("ERROR")>=0){
            reservaLockerPopup(responseText);
        }
        else{
            jQuery('#reservaLocker-profile').html(responseText);
            jQuery('#reservaLocker-content').html('<div id="reservaLocker-slide"></div>');
            jQuery('#reservaLocker-identify').remove();
            jQuery('#reservaLockerPopup-close').click();
            
            jQuery('#reservaLocker-profile .ajaxForm').ajaxForm({
                beforeSubmit: function(formData, jqForm, options){
                    reservaLockerPopup('<div class="loading">Espere por favor...</div>');
                },
                success:       function(responseText, statusText, xhr, $form){
                    if(responseText.indexOf("ERROR")>=0){
                        reservaLockerPopup(responseText);
                    }
                    else{
                        loadProfile();
                    }
                }
            });            
        }
        
    });
}
