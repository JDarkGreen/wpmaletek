var tableObj={};
var imgAreaCoords=null;
var imgAreaSelect=null;
var formCallback={
    "Config":function(form,data){},
    "Item":function(form,data){},
    "ItemModule":function(form,data){},
    "User":function(form,data){},
    "Local":function(form,data){},
    "Model":function(form,data){},
    "Locker":function(form,data){
        $('#adminFormLocker .loadReserve').html('No definido...');
        
        $('#adminFormLocker #varLocal').autocomplete({
            minLength:0,
            source: MALETEKPL_RSV_BACKEND_CONTROLLER+'?controller=Local&action=selector',
            select: function(event,ui){
                $('#adminFormLocker #idLocal').val(ui.item.id);
                loadLocalLocker(ui.item.id);
            }
        }).bind('focus', function(){ $(this).autocomplete("search",''); } ); 
        
        loadLocalLocker( $('#adminFormLocker #idLocal').val() );
        
    },
    "Reserve":function(form,data){
        $('#adminFormReserve .loadReserve').html('No definido...');
        
        $('#adminFormReserve #varUser').autocomplete({
            minLength:0,
            source: MALETEKPL_RSV_BACKEND_CONTROLLER+'?controller=User&action=selector',
            select: function(event,ui){
                $('#adminFormReserve #idUserReserve').val(ui.item.id);
                loadReserveUser(ui.item.id);
            }
        }).bind('focus', function(){ $(this).autocomplete("search",''); } ); 
        
        loadReserveUser( $('#adminFormReserve #idUserReserve').val() );
        
    }
};

function loadLocalLocker(idLocal){
    if(idLocal){
        $('#adminFormLocker .loadLocker').html('Cargando...');
    }
    
    
    
}
function loadReserveUser(idUser){
    if(idUser){
        $('#adminFormReserve .loadReserve').html('Cargando...');
    }
    
    $.ajax({
        dataType: "json",
        methid:'POST',
        url: MALETEKPL_RSV_BACKEND_CONTROLLER+'?controller=Reserve&action=loadUser',
        data: {idUser:idUser},
        success: function(data){
            $('#adminFormReserve #idLocal').html(data.local.varName);
            
            var idModule=$('<select name="idModule" id="idReserveModule" class="input-text loadInput"></select>');
            $.each(data.sector,function(indexI,dataI){
                idModule.append('<optgroup label="'+indexI+'"></optgroup>');
                $.each(dataI,function(indexJ,dataJ){
                    idModule.append('<option value="'+indexJ+'">'+dataJ+'</option>');
                });
            });
            $('#adminFormReserve #idModule').html(idModule);
            
            if(data.module){
                var dataModule=data.module;
                idModule.change(function(){
                    loadLockerSel( data.module[ $(this).val() ] );
                });
                if(data.reserve){
                    idModule.val(data.reserve.idModule);
                }
                idModule.change();
            }
            
        }
    });    
}

function loadLockerSel(dataSel){
    
    var idLocker=$('<select name="idLocker" id="idReserveLocker" class="input-text loadInput"></select>');
    $.each(dataSel,function(indexM,dataM){
        if(dataM=='0'){
            idLocker.append('<option value="'+indexM+'">Locker N°'+indexM+'</option>');
        }
        else if(dataM=='2'){ 
            idLocker.append('<option value="'+indexM+'" selected="true" >Locker N°'+indexM+'</option>');
        }
    });
    $('#adminFormReserve #idLocker').html(idLocker);
}

$(document).ready(function(){
    
    $('#reserveTabTrigger').click(function(){
        loadReserveTree();
    });
    $('#reserveTabTrigger').click();
    
    
    $('.input-autocomplete').each(function(){
        var item=$(this);
        item.autocomplete({
            minLength:0,
            source: MALETEKPL_RSV_BACKEND_CONTROLLER+'?controller='+item.attr('itemref')+'&action='+item.attr('itemtype'),
            select: function(event,ui){
                item.siblings('#'+item.attr('itemid')).val(ui.item.id);
            }
        }).bind('focus', function(){ $(this).autocomplete("search",''); } );
    });
    
    var adminForm=$('.adminForm');
    var ajaxFormSet={
        //target:'#statusProcess',
        beforeSubmit:function(formData, jqForm, options) {
            jqForm.find('.statusForm').html('<div class="updated">Espere por favor...<div>');
            jqForm.find('.field').hide();
            
            if(imgAreaSelect!=null){
                imgAreaSelect.cancelSelection();
            }
        },
        success : function(responseText, statusText, xhr, $form) {
            var responseText=$(responseText);
            $form.find('.statusForm').html(responseText);
            $form.find('.field').show();
            
            if(!responseText.hasClass('error')){
                tableObj[ $form.attr('itemid') ].draw();
                //$form.clearForm();
            }
        }
    };
    
    adminForm.each(function(){
        var form=$(this);
        form.validate({
            submitHandler: function(frm) {
                
                $(frm).ajaxSubmit(ajaxFormSet);
                
                return false;
            }
        });
    });    
    
    var adminDelete=$('.adminDelete');
    adminDelete.each(function(){
        var form=$(this);
        form.ajaxForm({
            //target:'#statusProcess',
            beforeSubmit:function(formData, jqForm, options) {
                $('.fancybox-close').click();
                $('.statusTab').html('<div class="updated">Espere por favor...</div>');
             
            },
            success : function(responseText, statusText, xhr, $form) {
                var responseText=$(responseText);
                $('.statusTab').html(responseText);
                $form.clearForm();
                if(!responseText.hasClass('error')){
                    tableObj[ $form.attr('itemid') ].draw();
                }
            }
        });
    }); 
    
    //fancybox
    $('.addReg').click(function(){
        $('.loadBlock').html('<div id="loadBlockLoading"></div>');
           
        var itemref=$(this).attr('itemref');
        var form=$('#adminForm'+itemref);
        form.find('.loadInput').val('');
        form.find('.field').show();
        form.find('.statusForm').html('');
        $('#adminForm'+itemref+'Trigger').click();
        
        formCallback[ itemref ](form,null);
    });
    
    
    var tableSetting={
        "bJQueryUI": true,
        "bRetrieve": true, 
        "bDestroy":true,
        'bProcessing':true,
        'bServerSide':true,
        'drawCallback': function(settings){
            tableCallback();
        },
        //"bStateSave": true,
        "bSortClasses": false,
        "aaSorting": [[ 0, "desc" ]],
        "iDisplayLength": 10,
        "aLengthMenu": [[10, 30, 50, 100, -1], [10, 30, 50, 100,'Todos']],
        "sPaginationType": "full_numbers",
        //"aoColumns": eval(aoColumns),
        "oLanguage":{
            "sProcessing":   "<div>Espere por favor...</div>",
            "sLengthMenu":   "Registros _MENU_",
            "sZeroRecords":  "No existen regitros",
            "sInfo":         "Registros _START_° - _END_° / _TOTAL_",
            "sInfoEmpty":    "No existen regitros",
            "sInfoFiltered": "( registros máximos _MAX_ )",
            "sInfoPostFix":  "",
            "sSearch":       "Buscar :",
            "sUrl":          "",
            "oPaginate": {
                "sFirst":    "&laquo;",
                "sPrevious": "&ltrif;",
                "sNext":     "&rtrif;",
                "sLast":     "&raquo;"
            }
        }
    };
    
    $('.tableAdmin').each(function(){
        //tableSetting.ajax={"url": $(this).attr('itemref'),"type": "POST"};
        tableSetting.sServerMethod='POST';
        tableSetting.sAjaxSource=$(this).attr('itemref');
        tableObj[ $(this).attr('id') ]=$(this).DataTable(tableSetting);        
    });
    
    $('a.fancybox').fancybox();
    $('a.fancybox').addClass('enable');
        
    $('.closeReg').click(function(){
       $('.fancybox-close').click();
    });
    
    $('#adminTabs').tabs();
    
    
    //oaod config
    var adminFormConfig=$('#adminFormConfig');
    $.ajax({
        type:"POST",
        dataType:"json",
        data:{
            controller  :'config',
            action      :'load'
        },
        url:MALETEKPL_RSV_BACKEND_CONTROLLER,
        success:function(data,textStatus){   
            adminFormConfig.find('.statusForm').html('');
            
            if(data.error===undefined){
                
                adminFormConfig.find('.loadInput').each(function(){
                    var input=$(this);
                    if(input.is('div')){
                        input.html( data[ input.attr('id') ] );
                    }
                    else{
                        input.val( data[ input.attr('id') ] );
                    }
                });
                
            }
            else{
                alert(data.error);
            }
        },
        error:function(jqXHR,textStatus,errorThrown){
            alert(textStatus);
        }
    }); 
});

function tableCallback(){
    
    $('a.fancybox:not(.enable)').fancybox()
    $('a.fancybox:not(.enable)').addClass('enable');
    
    $('.editReg:not(.enable)').addClass('enable').click(function(){
        var controller=$(this).attr('itemref');
        var form=$('#adminForm'+controller);
        $('#adminForm'+controller+'Trigger').click();
        
        form.find('.statusForm').html('<div class="updated">Espere por favor...<div>');
        form.find('.loadBlock').html('<div id="loadBlockLoading"></div>');
        form.find('.loadInput').val('');
        form.find('.field').show();
        
        //posit
        $('#loadPosLoading').show();
        if(imgAreaSelect!=null){
            imgAreaSelect.cancelSelection();
        }
        
        $.ajax({
            type:"POST",
            dataType:"json",
            data:{
                controller  :controller,
                action      :'load',
                id          :$(this).attr('itemid')
            },
            url:MALETEKPL_RSV_BACKEND_CONTROLLER,
            success:function(data,textStatus){   
                form.find('.statusForm').html('');
                form.find('.field').show();
        
                if(data.error===undefined){
                    form.find('.loadInput').each(function(){
                        var input=$(this);
                        if(input.is('div')){
                            input.html( data[ input.attr('id') ] );
                        }
                        else{
                            input.val( data[ input.attr('id') ] );
                        }
                    });
                                            
                    form.find('.loadImage').each(function(){
                        var input=$(this);
                        input.siblings('img').remove();
                        if(input.val()){
                            input.after('<img width="200" src="'+input.attr('itemref')+input.val()+'" />');
                        }
                    });
                }
                else{
                    alert(data.error);
                }
                formCallback[ controller ](form,data);
            },
            error:function(jqXHR,textStatus,errorThrown){
                alert(textStatus);
            }
        });        
    
        return false;
    });
    $('.deleteReg:not(.enable)').addClass('enable').click(function(){
        var obj=$(this);
        $('#adminDelete'+obj.attr('itemref')+' #idReg').val( obj.attr('itemid') );
        $('#triggerDelete'+obj.attr('itemref')).click();
        return false;
    });
    
    
    $('.reserveMail:not(.enable)').addClass('enable').click(function(){
        var obj=$(this);
                        
        $.ajax({
            type:"POST",
            dataType:"json",
            data:{
                controller  :obj.attr('itemref'),
                action      :'message',
                id          :obj.attr('itemid')
            },
            url:MALETEKPL_RSV_BACKEND_CONTROLLER,
            success:function(data,textStatus){   
                if(data.error===undefined){
                    alert(data.message);
                }
                else{
                    alert(data.error);
                }
            },
            error:function(jqXHR,textStatus,errorThrown){
                alert(textStatus);
            }
        });  
        
        return false;
    });
}

function loadReserveTree(){
    var reserveTree=$('#reserveTree');
    reserveTree.load(MALETEKPL_RSV_BACKEND_CONTROLLER+'?controller=reserve&action=tree',function(){
        $('#reserveTree a.group').click(function(){
            var list=$(this).next('ul');
            if(list.length===1){
                list.fadeToggle();
                $(this).find('.ui-icon').toggleClass('ui-icon-triangle-1-s');
            }
            return false;
        });
        
        var reserveModule=$('#reserveModule'); 
        $('#reserveTree a.link').click(function(){
            reserveModule.html('Espere por favor...');
            reserveModule.load(MALETEKPL_RSV_BACKEND_CONTROLLER+'?controller=reserve&action=module&idModule='+$(this).attr('itemid'),function(){
                                
            });
        });
    });
}