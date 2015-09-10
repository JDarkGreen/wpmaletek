var tableObj={};
var imgAreaCoords=null;
var imgAreaSelect=null;
var formCallback={
    "Config":function(form,data){
        
    },
    "Item":function(form,data){
        
    },
    "ItemPosition":function(form,data){
        $('#loadPosLoading').show();
        if(imgAreaSelect!=null){
            imgAreaSelect.setOptions({ show: false });
        }
        $('#loadPosImg').attr('src',data.imgPosition).load(function(){
            
            if(imgAreaSelect===null){
                imgAreaSelect=$('#loadPosImg').imgAreaSelect({ 
                    instance: true, 
                    handles: true, 
                    parent: '#adminFormItemPosition',
                    onSelectEnd: function (img, selection) {
                        imgAreaCoords.val('{x1:'+selection.x1+',y1:'+selection.y1+',x2:'+selection.x2+',y2:'+selection.y2+'}');
                    }
                });
            }
            
            $('.editPos').click(function(){
                var obj=$(this);
                $('.editPos.active').removeClass('active');
                obj.addClass('active');
                
                imgAreaCoords=$('#loadPosArea'+obj.attr('itemid')+'');
                
                var coord=imgAreaCoords.val();
                if(coord===''){
                    coord={x1:50,y1:50,x2:100,y2:100};
                    imgAreaCoords.val('{x1:50,y1:50,x2:100,y2:100}');
                }
                else{
                    eval('coord='+coord+';');
                }
                imgAreaSelect.setSelection(coord.x1, coord.y1, coord.x2, coord.y2, true);
                imgAreaSelect.setOptions({ show: true });
                imgAreaSelect.update();

            });
            $('.editPos:first').click();
            
            $('#loadPosLoading').hide();
        });
    },
    "Color":function(form,data){},
    "Request":function(form,data){    
        var idReg=form.find('#idReg').val();
        $('#requestScheme').html('<div id="loadBlockLoading"></div>');
        $('#requestScheme').load(MALETEKPL__BACKEND_CONTROLLER+'?controller=request&action=scheme&id='+idReg,function(){
            $('#idItem').change(function(){
                $('#schemaBlock .itemBlock:visible').hide();
                $('#item-'+$(this).val()).show();
            });
            $('#item-'+$('#idItem').val()).show();
            
            $('#requestScheme .item-color').click(function(){
                $('#requestScheme .item-color').removeClass('selected');
                $(this).addClass('selected');
                return false;
            });
            $('#schemaBlock .itemBlock .locker').click(function(){
                if($('#requestScheme .item-color.selected').length==1){
                    var background=$('#requestScheme .item-color.selected').attr('itemref');
                    var idColor=$('#requestScheme .item-color.selected').attr('itemid');
                    
                    if(idColor>0){
                        $(this).css('background', 'url('+background+')' );
                    }
                    else{
                        $(this).css('background', 'none' );
                    }
                    $(this).attr('itemref',idColor);
                    
                    var varContent=new Array();
                    $('#schemaBlock .itemBlock:visible .locker').each(function(){
                        var color=$(this).attr('itemref');
                        varContent.push( '"'+$(this).attr('itemid')+'":'+(color?color:'0') );
                    });
                    form.find('#varContent').val( '{'+varContent.toString()+'}' );  
                }                
                return false;
            });
        });
    }
};

$(document).ready(function(){
    
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
        url:MALETEKPL__BACKEND_CONTROLLER,
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
            url:MALETEKPL__BACKEND_CONTROLLER,
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
}
