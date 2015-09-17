var j = jQuery.noConflict();
var indicador = 0;
var sliderInt = 1;
var sliderNext = 2;
(function(a) {
    j(document).on("ready", function() {
        defineSizeReserva();

        var root = _root_;

         //Call to Slidebar Plugin
        var mySlidebars = new j.slidebars({
            scrollLock: false,// true or false
            siteClose : false // true or false
        });

        //Click for open and close slidebar
        j('#openMenu').on('click',function(){
            mySlidebars.slidebars.toggle('left');
            j(this).toggleClass('bg-trans');
            //j('body').bind('touchmove', function(e){e.preventDefault()});
        });

        //For touch swipe carousels Bootstrap left and right
        j(".carousel-inner").swiperight(function() {
            j(this).parent().carousel('prev');
        });
        j(".carousel-inner").swipeleft(function() {
            j(this).parent().carousel('next');
        }); 

        /* Select Quantity WOOCOMMERCE */
        /* Mostrar la primera opcion por defecto del select*/
        j(".quantity_select").find("option").first().append(' ');

        var c = setInterval('moveSliderReserva("right")', 5000);
        var b = 0;

        j(".container-sliderReserva .slide").hover(function() {
            clearInterval(c);
        }, function() {
            c = setInterval('moveSliderReserva("right")', 5000);
        });

        //VALIDAR EL REGISTRO
        j("#registrationForm").bootstrapValidator({
            feedbackIcons: {
                valid: "glyphicon glyphicon-ok",
                invalid: "glyphicon glyphicon-remove",
                validating: "glyphicon glyphicon-refresh"
            },
            live: "enabled",
            fields: {
                //Registro en el formulario de contacto 
                contact_name: {
                    trigger: "blur",
                    validators: {
                        notEmpty: {
                            message: "Campo requerido"
                        },
                        regexp: {
                            regexp: /^[a-zA-ZñÑ\s\W]/,
                            message: "Sólo puede contener caracteres alfabeticos"
                        }
                    }
                },
                contact_email: {
                    trigger: "blur",
                    validators: {
                        notEmpty: {
                            message: "Campo requerido"
                        },
                        emailAddress: {
                            message: "Email no es valido"
                        }
                    }
                },
                contact_ruc: {
                    validators: {
                        notEmpty: {
                            message: "Campo requerido"
                        },
                        integer: {
                            message: "Este campo debe contener sólo números"
                        },
                        stringLength: {
                            min: 8,
                            max: 11,
                            message: "Este campo debe contener más de 8 y menor de 11 caracteres"
                        }
                    }
                },
                contact_asunto: {
                    validators: {
                        notEmpty: {
                            message: "Campo requerido"
                        }
                    }
                },
                contact_company: {
                    validators: {
                        notEmpty: {
                            message: "Campo requerido"
                        }
                    }
                },
                contact_tel: {
                    validators: {
                        notEmpty: {
                            message: "Campo requerido"
                        },
                        stringLength: {
                            min: 7,
                            message: "Este campo debe tener como minimo 7 caracteres"
                        }
                    }
                },
                contact_message: {
                    trigger: "blur",
                    validators: {
                        notEmpty: {
                            message: "Campo requerido"
                        }
                    }
                }
            }
        });

        j("#sl_webside").on("change", function(d) {
            d.preventDefault();
            if (jQuery(this).val() === "2") {
                window.location = "http://maletek.cl";
            } else {
                if (jQuery(this).val() === "1") {
                    window.location = "http://localhost/maletek";
                }
            }
        });

        j("body").on("click", "#send_email", function(d) {
            d.preventDefault();
            j(this).submit();
        });

        j(".fancybox").fancybox();

        j(".btn-consultar").hover(function() {
            img = j(this).find("img");
            src = img.attr("src");
            srcHover = img.data("imghover");
            img.attr("src", srcHover);
        }, function() {
            img.attr("src", src);
        });

        j("#myModal").on("shown.bs.modal", function(d) {
            j("body").css("padding-right", "0");
        });

        /******************************************************************************************************/
        //Asignar la clase activa al elemento padre dentro de la lista de categoria actual
        j('.products-categories-menu').find('a').each(function(){
            if ( j(this).hasClass('active') ) {
                j(this).parent().addClass('active');
            }
        });

        /******************************************************************************************************/
        /************************************     WOOCOMMERCE      ********************************************/
        /******************************************************************************************************/

        //VALIDAR REGISTRO DE USUARIO 
        j('#register_form_wc').bootstrapValidator({
            feedbackIcons: {
                valid: "glyphicon glyphicon-ok",
                invalid: "glyphicon glyphicon-remove",
                validating: "glyphicon glyphicon-refresh"
            },
            live: "enabled",
            fields: {
                firstname: {
                     trigger: "blur",
                    validators:{
                        notEmpty :{
                            message : "Campo requerido"
                        },
                        regexp: {
                            regexp: /^[a-zA-ZñÑ\s\W]/,
                            message: "Sólo puede contener caracteres alfabeticos"
                        }
                    }
                },
                lastname: {
                     trigger: "blur",
                    validators:{
                       notEmpty :{
                            message : "Campo requerido"
                        },
                        regexp: {
                            regexp: /^[a-zA-ZñÑ\s\W]/,
                            message: "Sólo puede contener caracteres alfabeticos"
                        }
                    }
                },
                email: {
                    trigger: "blur",
                    validators: {
                        notEmpty: {
                            message: "Campo requerido"
                        },
                        emailAddress: {
                            message: "Email no es valido"
                        }
                    }
                },
                password:{
                     trigger: "blur",
                    validators:{
                        notEmpty: {
                            message: 'Campo requerido'
                        },
                        stringLength: {
                            min:8,
                            max:10,
                            message: 'Mínimo 8 carácteres , máximo 10'
                        }
                    }
                },
                conf_password:{
                    validators:{
                        notEmpty: {
                            message: 'Campo requerido'
                        },
                        identical: {
                            field: 'password',
                            message: 'Los campos de contraseña no son iguales'
                        }
                    }
                }
            }
        })
        .on('success.field.fv', function(e, data) {
            if (data.fv.getInvalidFields().length > 0) {    // There is invalid field
                data.fv.disableSubmitButtons(true);
            }
        });

        //ID DEL SELECT OCULTO QUE UTILIZAREMOS PARA LAS VARIACIONES DEL PRODUCTO ( en este caso tipo de cierre )
        try{
            var sl_var_product = '#' + _sl_clousure_type_ ;
        }catch( e ){
            console.log('error de variable');
        }

        /* Funcion para activar las opciones (como un checkbox ) de cada producto por tipo de cierre */
        j("#js-chkbox-type-closeures li").first().children('a').addClass('active');

        //Mostrar el boton o la seccion de carrito de compra con .change()
        // Setear el primer valor del tipo de cierre pero que no sea "escoger una opcion"
        //j( sl_var_product + ' option:eq(1)').attr('selected','selected').change();

        //Cambiar el estado del tipo de cierre elegido ademas de setear el select del tipo de cierre para poder enviar los 
        //datos al carrito de compra
        j("#js-chkbox-type-closeures li a").on('click',function(event){
            event.preventDefault();
            j('#js-chkbox-type-closeures a').each(function(){
                j(this).removeClass('active');
            });
            j(this).addClass('active');
            
            // Setear el select oculto con la opcion elegida "el tipo de cierre"
            //j( sl_var_product ).val( j(this).attr('data-attr') ).change();

            //Cambiar el valor de vacio del input cierre para agregarlos en el carrito y setear al producto
            j('#input-tipo-cierre').val( j(this).attr('data-attr') );
             
        });

        //VAMOS A el input para que no acepte valores
         j('.btn_qty_input').parent().find('input').keypress(function(e){
            if (e.charCode >= 0 ) {
                return false;
            }
         });

        //VAMOS A CUSTOMIZAR EL INPUT DEL CARRITO PARA AUMENTAR O DECRECER LA CANTIDAD
        j('.btn_qty_input').on('click',function(){

            var btn_qty_input = j(this);
            //antiguo valor 
            var oldValue      = btn_qty_input.parent().find("input").val();
            //minimo valor
            var minvalue      = btn_qty_input.parent().find("input").data('minvalue');
            //maximo valor permitido
            var maxvalue      = btn_qty_input.parent().find("input").data('maxvalue');

            if ( btn_qty_input.data('qty') == "+" ) {

                //No permitir mas del valor maximo 
                if ( oldValue < maxvalue ) {
                    var newVal = parseFloat(oldValue) + 1;
                } else {
                    newVal = maxvalue;
                }

            } else {
                // No permitir menos del valor minimo 
                if (oldValue > minvalue ) {
                    var newVal = parseFloat(oldValue) - 1;
                } else {
                    newVal = minvalue;
                }
            }

            //Devolver nuevo valor al input
            btn_qty_input.parent().find("input").val(newVal);

             //Actualizar el carrito
            //j('input#update_cart').click();
        });

        //version mobile
        j('.quantity_select').find('select').on('change',function(e){
           var input_name = j(this).data('cart');
           j('input[name="'+ input_name + '"]').val( j(this).val()  );
           //Actualizar el carrito 
            //j('input#update_cart').click();

        });


        //Mostrar el mensaje de compra adquirida o noticias solamente durante 4 segundos
        if ( j('.woocommerce-message').hasClass('woocommerce-message--red') ) { 
            setTimeout(function(){
                j('.woocommerce-message').fadeOut(800);
            }, 4000); 
        };

        /*************************************************************************************/
        /*-- Cambiamos el select por rangos en el modulo de productos y lo filtramos por ajax-*/
        /*************************************************************************************/

        //variable select de rango
        var sl_rango   = j('#id_rango');
        var mnu_hidden = sl_rango.find('ul');

        //Prevenimos acciones por defecto
        sl_rango.find('a').each(function(){
            j(this).on('click',function(e){
                e.preventDefault();
            });
        });

        //valor para deslizar menu
        var active_menu = true;

        sl_rango.on('click',function(){ //al hacer clicj en el menu oculto
            if (active_menu == true) {
                mnu_hidden.slideDown(); //abrimos el menu oculto
                active_menu = false;
            }else{
                mnu_hidden.slideUp(); //abrimos el menu oculto
                active_menu = true;
            }
        });

        //Acciones despues de hacer click en el menu oculto
        mnu_hidden.find('a').on('click',function(){
            //cambiamos el valor de la variable;
            active_menu = false;

            //removemos la clase hide de todos los elementos padre
            mnu_hidden.find('li').removeClass('hide');
            //conseguimos su texto para reemplazarlo luego
            var text  = j(this).text(); 
            //cambiamos el valor del select
                j('#js-mnu-number-doors__menu').fadeOut().fadeIn('800').text(text);
                //ocultamos elemento padre
                j(this).parent('li').addClass('hide');

            //seteamos el valor en el input rango para enviar luego al carrito
            j('#input-rango').val( text );

            //Llamamos a la funcion ajax
            var producto =  j('#js-mnu-number-doors__menu').data('product');
            var message  =  j('#js-mnu-number-doors__menu').data('message');
            var rango    =  j(this).data('rango');

            getAjaxLockers( producto , rango , message );
        });


        //Funcion Ajax
        function getAjaxLockers( product , rango  , message )
        {
            //Setear las variables producto y rango
            var product   = product; var rango = rango; var message = message;

            //Contenedor donde se colocará el contenido;
            var container = j('#js-sec__configurations-products__content');

            //Ocultamos contenedor
            container.fadeOut(function(){
                j(this).html('');
                //Mostramos loader
                container.parent().find("p.loading").fadeIn();
            });

            //Enviarlas mediante jpost
            j.post( MyAjax.url, {
                nonce   : MyAjax.nonce,
                action  : 'get_lockers_byfilter',
                product : product,
                rango   : rango,
                message : message, 
            }, function(data) {

                var html = '';      

                if ( data.result )
                {
                   html = data.content;
                }
                else
                {
                   html = "no se encontraron configuraciones para este rango. Elija otro";
                }   

                //Ocultamos loader
                container.parent().find("p.loading").fadeOut();

                //Mostramos la informacion
                container.html( html ).fadeIn(); 

                //seteamos el valor en el input configuraciones para enviar luego al carrito
                j('#configurations').val( html );


            }, 'json');
        } //fin de funcion ajax

        /******************************************************************************************************/
        /*******************************    CARRITO              **********************************************/

        //Antes de subir el formulario
        j( ".variations_form" ).submit(function( event ) {

            //Elemento clickeado - obtener el modelo 
            var nmodel  = j(document.activeElement).data('nmodel');
            //Elemento clickeado - obtener id el modelo 
            var idmodel = j(document.activeElement).data('idmodel');

            //Seter el modelo;
            j('#input-modelo').val( nmodel );

            //Setear el id del modelo
            j('#input-id-modelo').val( idmodel );


        });


        /* Display modal after cotize order */


        /* Abrir el modal del login si no esta registrado */
        j('#js_open_login').on('click',function(){
            j('#modal__login_wc').modal('show');
        });


        /* Nota funcionamiento IMPORTANTE - este codigo envia parametros nuevos para actualizar la 
            orden de compra y enviar funciones ajax */

        //Select de cada item del carrito
        j('.js_rango').on('change',function(e){

            var the_product = j(this).data('product'); //el producto a enviar
            var the_rango   = j(this).val(); // el rango a enviar 

            //El selector modelo el cual va a cambiar
            var sl_model = j(this).parent('.product-rango').parent('.cart_item').find('.product-model select');

            sl_model.html("<option value='update'>Actualizando...</option>");
                
            //Enviamos la parametros por ajax
            j.post( MyAjax.url, {
                nonce   : MyAjax.nonce,
                action  : 'get_models_byrango',
                product : the_product,
                rango   : the_rango,
            }, function(data) {

                var html = "";     

                if ( data.result ) 
                {  
                    html = data.content; 

                    
                }else
                {
                    html = "<option value='none'>Elejir Otro Rango</option>";
                }

                //Actualizamos la informacion 
                sl_model
                    .html( html )
                    .change();

            }, 'json');

        });

        j('.js_modelo').on('change',function(e){

            //Conseguimos el id del modelo de la opcion seleccionada
            var the_id_model = j(this).find(":selected").attr("data-idmodel");

            //El contenedor imagen modelo el cual va a cambiar
            var img_model = j(this).parent('.product-model').parent('.cart_item').find('.product-thumbnail figure');

            //El input oculto el cual cambiaremos su valor 
            var input_hidden_img = j(this).parent('.product-model').parent('.cart_item').find('.product-thumbnail input');

            //Mostrar estado de actualizacion 
            img_model.html("<p>Actualizando...</p>");

            //Enviamos la parametros por ajax
            j.post( MyAjax.url, {
                nonce   : MyAjax.nonce,
                action  : 'get_img_bymodel',
                idmodel : the_id_model,
            }, function(data) {

                var html = "";     

                if ( data.result ) 
                {  
                    html = data.content;

                    console.log(img_model);

                }else{
                    html = "Image not show";
                }

                //Actualizamos la informacion 
                //De la figura
                img_model.html( html );
                //Del input
                input_hidden_img.val( html );


            }, 'json');

        });

        

    




        /******************************************************************************************************/
        /************************************  SECTION NOVEDADES  *********************************************/
        /******************************************************************************************************/

        //AJAX

        /* En web*/
        j('.mnu-filter-blog li a').on('click',function(e)
        {
            e.preventDefault();

            j('.mnu-filter-blog li a').removeClass('active');

            var $this = j(this);
            var filter = $this.data('category');

            $this.addClass('active');
            
            if (filter === 'last-news') {
                filter = null;
            } 
            if (filter === 'search'){
                filter = null;
                j('.container-search').toggle( "fold", 700 );

            } else{
                loadPost( filter , null , 1 );
            }
        });

        /* En mobile */
        j('#sl-filter-blog').on('change',function(e)
        {
            e.preventDefault();
            var $this = j(this);
            var filter = $this.attr('value');

            if (filter === 'last-news') {
                filter = null;
            } 

            loadPost( filter, null , 1 );
        });

        //FUNCION DE BUSQUEDA AL PRESIONAR  ENTER EN INPUT
        j('.nav-xs__button--search').on('click',function(){
            j('.container-search').toggle( "blind", 800 );
        });


        j('#search-blog').keydown(function( event ){
            if ( event.which == 13 ) {
                var value = j(this).val();
                loadPost( null , value , 1 );
            }
        });

        //PAGINATOR
        j('body').on('click','a.paginator-button', function(e)
        {
            e.preventDefault();
            var page    = j(this).data('paginator');
            var filter  = j('.mnu-filter-blog li').find('a.active').data('category');
            var current = parseInt( j('#currrent_post_page').val() );

            page   = (page === 'Siguiente' ) ? current + 1 : current - 1;
            filter = ( filter === 'last-news') ? null : filter;

            loadPost( filter , null , page );
        });


        /* Function loadPost */
        function loadPost( filter , value , page )
        {
            var container = j('.container-content-blog');
            var noinfo    = j('.container-content-blog--no-info');
            var nofound = '<p class="alert alert-info text-center">No se encontró post.</p>';    

            if ( noinfo.css( 'display' ) != 'none' )
            {
                noinfo.fadeOut('slow', function() {
                    container.html('');
                    
                    //j('#js-loader-blog').fadeIn();
                   j('.container__search').addClass('hidden');
                   j('#js-loader-blog').removeClass('hidden');
                });
            }
            else
            {
                container.fadeOut('slow', function() {
                    container.html('');
                    
                    //j('#js-loader-blog').fadeIn();
                    j('.container__search').addClass('hidden');
                    j('#js-loader-blog').removeClass('hidden');
                });
            }   

            j.post( MyAjax.url, {
                nonce   : MyAjax.nonce,
                action  : 'get_posts_byfilter',
                filter  : filter,
                value   : value,
                page    : page
            }, function(data) {

                //j('#js-loader-blog').fadeOut();
                j('#js-loader-blog').addClass('hidden');

                var html = '';      

                if ( data.result )
                {
                   html = data.content;
                }
                else
                {
                   html = nofound;
                }      

                container.fadeIn('3000', function() { j(this).html( html ); });

            }, 'json');
        } 

        j('.mnu-filter-blog li').first().find('a').addClass('active').click();
        j('#js-loader-blog').fadeIn();


    /***************************************************************************************************************/
        
    });
    


})(jQuery);
j(window).on("resize",function(){

    defineSizeHome();
});

function defineSizeHome() {
    j(".container-sliderHome .slide").each(function(a, b) {
        j(b).css({
            "background-image": "url('" + j(b).data("background") + "')",
            height: (j(".container-sliderHome").width() * 0.34) + "px",
            width: (j(".container-sliderHome").width()) + "px"
        });
    });
    j(".container-sliderHome .container-slide").css({
        "margin-left": -(indicador * j(".container-sliderHome").width()) + "px"
    });
}

function moveSliderHome(a) {
    var b = jQuery(".container-sliderHome .slide").length;
    indicador = (a == "right") ? indicador + 1 : indicador - 1;
    indicador = (indicador >= b) ? 0 : indicador;
    indicador = (indicador < 0) ? b - 1 : indicador;
    jQuery(".container-sliderHome .container-slide").animate({
        "margin-left": -(indicador * jQuery(".container-sliderHome").width()) + "px"
    });
}

function defineSizeReserva() {
    j(".container-sliderReserva .slide").each(function(a, b) {
        j(b).css({
            "background-image": "url('" + j(b).data("background") + "')",
            height: (j(".container-sliderReserva").width() * 0.67) + "px",
            width: (j(".container-sliderReserva").width()) + "px"
        });
    });
    j(".container-sliderReserva .container-slide").css({
        "margin-left": -(indicador * j(".container-sliderReserva").width()) + "px"
    });
}

function moveSliderReserva(a) {
    var b = jQuery(".container-sliderReserva .slide").length;
    indicador = (a == "right") ? indicador + 1 : indicador - 1;
    indicador = (indicador >= b) ? 0 : indicador;
    indicador = (indicador < 0) ? b - 1 : indicador;
    jQuery(".container-sliderReserva .container-slide").animate({
        "margin-left": -(indicador * jQuery(".container-sliderReserva").width()) + "px"
    });
}

function showLightbox() {
    document.getElementById("over").style.display = "block";
    document.getElementById("fade").style.display = "block";
}

function hideLightbox() {
    document.getElementById("over").style.display = "none";
    document.getElementById("fade").style.display = "none";
}
