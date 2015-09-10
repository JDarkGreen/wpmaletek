var currentColor=null;
var currentIdColor=null;
var schemeColor=null;
var itemId=null;
var printRequest=null;
jQuery(document).ready(function(){
    
    jQuery.post(MALETEKPL__FRONTEND_CONTROLLER+'?controller=request&action=loader',function(data){        
        jQuery('#pintaLockerLoader').html(data);
        
        jQuery('#pintaLockerControl a.color').click(function(){
            jQuery('#pintaLockerControl a').removeClass('selected');
            jQuery(this).addClass('selected');
            
            currentColor=jQuery(this).attr('href');
            currentIdColor=parseInt(jQuery(this).attr('itemid'));
            return false;
        });
        
        jQuery('#pintaLockerGallery .locker').click(function(){
            //if(currentIdColor===null){
            //    alert('Debe elegir un color antes de selecionar el locker');
            //}
            //else{
                var obj=jQuery(this);
                obj.css('background','url('+currentColor+')');
                obj.attr('itemref',currentIdColor);
                
                schemeColor=new Array();
                jQuery('#pintaLockerGallery .locker:visible').each(function(){
                    var color=jQuery(this).attr('itemref');
                    schemeColor.push( '"'+jQuery(this).attr('itemid')+'":'+(color?color:'0') );
                });
                
                //refresh print
                //if(itemId && schemeColor){
                if(itemId){    
                    printRequest=MALETEKPL__PLUGIN_URL+'frontend/views/print.php?itemId='+itemId+
                                '&schemeColor={'+schemeColor.toString()+'}';
                    jQuery('.printRequest').attr('href',printRequest).addClass('available');
                }                
            
            //}            
            return false;
        });
        
        
        jQuery('.applyAll').click(function(){
            if(currentIdColor===null){
                alert('Debe elegir un color antes de selecionar el locker');
            }
            else{
                var allLocker=jQuery('#pintaLockerGallery .locker:visible');
                allLocker.css('background','url('+currentColor+')');
                allLocker.attr('itemref',currentIdColor);
            }
            return false;
        });
        jQuery('.applyRequest').click(function(){
            jQuery('#pintaLockerPopup').show();
            jQuery('#pintaLockerRequest').show();
            jQuery('#pintaLockerMessage').hide().attr('class','');
            jQuery('#pintaLockerMessage .text').html('');
            return false;
        });
        jQuery('.printRequest').click(function(){
           if(!jQuery(this).hasClass('available')){
                //return false;
           }            
        });
        
        jQuery('#pintaLockerBack').click(function(){
            jQuery('#pintaLockerRequest').show();
            jQuery('#pintaLockerMessage').hide().attr('class','');
            jQuery('#pintaLockerMessage .text').html('');
            return false;
        });
        jQuery('#pintaLockerRequestClose').click(function(){
            jQuery('#pintaLockerPopup').hide();
            return false;
        });
        
        jQuery('#pintaLockerThumb').carouFredSel({
            items   : 6,
            auto    : false,
            prev    : jQuery('#pintaLockerThumb-prev'),
            next    : jQuery('#pintaLockerThumb-next')
        });
        jQuery('#pintaLockerThumb a').click(function(){
            var obj=jQuery(this);
            if(!obj.hasClass('selected')){
                jQuery('#pintaLockerThumb a.selected').removeClass('selected');
                obj.addClass('selected');
                jQuery('#pintaLockerGallery .slide:visible').hide();
                jQuery( obj.attr('href') ).show();
                itemId=parseInt( obj.attr('itemid') );
                schemeColor=null;
                jQuery('.printRequest').removeClass('available');
                
                //print
                printRequest=MALETEKPL__PLUGIN_URL+'frontend/views/print.php?itemId='+itemId+
                                '&schemeColor={}';
                jQuery('.printRequest').attr('href',printRequest).addClass('available');
            }
            return false;
        });
        jQuery('#pintaLockerThumb a:first').click();
                
        jQuery('#pintaLockerRequest').submit(function(){
            var form=jQuery(this);
            
            if(schemeColor!=null){
                form.find('#varContent').val('{'+schemeColor.toString()+'}');
            }
            else{
                form.find('#varContent').val('');
            }
            
            form.find('#idItem').val(itemId);

            var dataForm=form.serialize();  

            jQuery.ajax({
               type: "POST",
                url: MALETEKPL__FRONTEND_CONTROLLER+'?controller=request&action=save', 
                data: dataForm,
                contentType: "application/x-www-form-urlencoded", 
                beforeSend: function() {  
                    jQuery('#pintaLockerRequest').hide();
                    jQuery('#pintaLockerMessage').show().attr('class','info');
                    jQuery('#pintaLockerMessage .text').html('Espere por favor...');
                },
                dataType: "json",
                success: function(data){
                    if(data.status=="info"){
                        form.find('.input-text').val('');
                    }
                    jQuery('#pintaLockerMessage').show().attr('class',data.status);
                    jQuery('#pintaLockerMessage .text').html(data.message);
                }
            });
               
            return false;
        });   
        
    });
});
