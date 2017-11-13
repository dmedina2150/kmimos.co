    <style>
        .content_rating{ width: 100%; text-align: center;}
        .fila_rate div { display: inline-block; text-align: center; height: 30px; vertical-align: top; height: 100px;}
        .fila_rate div img { width: 30px; margin-top: -30px; }
        .fila_rate div input.huesito { display: none; }
        .reset_rate { margin-right: 40px; display: none !important;}
    </style>
    <div class="content_rating">

    <?php

    global $current_user;
    $user_id = $current_user->ID;

    global $wpdb;

    global $redirect_to;

    print_r($_GET);
    print_r($_POST);
    date_default_timezone_set('America/Bogota');


?>

    
     <?php $CONTENIDO="
     ".

       
        if($user_id==0) {
           $CONTENIDO.="  <h1>Debes iniciar sesión</h1>";
            wp_redirect(get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode($_SERVER['REQUEST_URI']));
        }

       $post_id = vlz_get_page();
           
        
        if($_POST['funcion'] =='rate'){

        
            $time = current_time('mysql');
            $agent = ($_SERVER[‘HTTP_USER_AGENT’]!='')? $_SERVER[‘HTTP_USER_AGENT’] : 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)';
            $servicio = get_post(get_post_meta($post_id,'_booking_product_id',true));
            $petsitter_id = $servicio->post_parent;
            $data = array(
                'comment_post_ID' => $petsitter_id,
                'comment_author' => $current_user->display_name,
                'comment_author_email' => $current_user->user_email,
                'comment_content' => sanitize_text_field($_POST['comentarios']),
                'comment_type' => '',
                'comment_parent' => 0,
                'user_id' => $user_id,
                'comment_author_IP' => $_SERVER[‘REMOTE_ADDR’],
                'comment_agent' => $agent,
                'comment_date' => $time,
                'comment_approved' => 1,
            );
            $comment_id = wp_insert_comment($data);
            update_post_meta($post_id, 'customer_comment', $comment_id);
            update_comment_meta( $comment_id, 'care', $_POST['cuidado'] );
            update_comment_meta( $comment_id, 'punctuality', $_POST['puntualidad'] );
            update_comment_meta( $comment_id, 'cleanliness', $_POST['limpieza'] );
            update_comment_meta( $comment_id, 'trust', $_POST['confianza'] );
            vlz_actualizar_ratings($petsitter_id);

            $CONTENIDO.="<h1>Valoración Enviada</h1><p>Gracias por regalarnos tu evaluación, es sumamente importante para Kmimos y todos los que somos parte de esta comunidad saber lo que opinas del servicio que has recibido.</p>
             <a class='kmi_link' href='".get_home_url()."/perfil-usuario/?ua=invoices'>Volver a mis reservas</a>";

        
        }elseif(isset($post_id)){
            if($post_id==''){

               $CONTENIDO.="Selecciona el cuidador que deseas valorar
                return false;";

            }else{
              $reserva = get_post($post_id);
                if(get_post_meta($post_id, 'customer_comment', true) != '') {

                   $CONTENIDO.="<h1>La reserva ya ha sido valorada</h1>;
                    <a class='kmi_link' href='".get_home_url()."/perfil-usuario/?ua=invoices'>Volver a mis reservas</a>";

                }else{
                    if($reserva->post_author != $user_id) {

                       $CONTENIDO.="<h1>La reserva seleccionada no perteneca al usuario</h1>;
                    <a class='kmi_link' href='".get_home_url()."/perfil-usuario/?ua=invoices'>Volver a mis reservas</a>";

                    }else{

                       $CONTENIDO.=" ".$desde = get_post_meta($post_id,'_booking_start',true);
                        $hasta = get_post_meta($post_id,'_booking_end',true);
                        $lleno = get_home_url()."/wp-content/themes/kmimos/images/new/icon/icon-hueso-color.svg";
                        $vacio = "<img src='".get_home_url()."/wp-content/themes/kmimos/images/new/icon/icon-hueso.svg'>";
                        $petsitter = get_post( $post_id );
                        

                       
          $CONTENIDO.="  
                        <div style ='  width: 100%; max-width: 600px; text-align: left;'>
                            <h1>Valoración del servicio:</h1>
                            <h2>$servicio->post_title</h2>
                            <h5>
                                  Desde: ".substr($desde,6,2)."/".substr($desde,4,2)."/".substr($desde,0,4). "
                                , Hasta: ".substr($hasta,6,2)."/".substr($hasta,4,2)."/".substr($hasta,0,4)
                            ."</h5>
                                                
                             
                            <p>Te pedimos evalúes del 1 al 5 con huesitos, los siguientes rubros. Considerando que 1 es la calificación más baja y 5 la más alta.</p>
                               

                            <form method='post' action=www.google.com id='request_form' class='lbl-ui' style='width: 100%; max-width: 600px; margin: 0 auto;'>

                                <h3>Sección de cuidado:</h3>
                                <p><strong>¿Cómo consideras que fue el cuidado que recibió tu peludo?</strong></p>
                                <div class='fila_rate' data-section='cuidado'>";
                               
                                    
                                  
                                for($i=1;$i<=5;$i++){ 

                           $CONTENIDO.="<div class='select_rate cuidado' data-rate='$i' data-section='cuidado'>
                                            <label for='cuidado_$i'><h5> $i</h5></label><br>
                                            <input type='radio' id='cuidado_$i' name='cuidado' value='$i' class='huesito'><br>$vacio
                                        </div>";
                                       
                                    } 
                                    
                               $CONTENIDO.= "</div>
                                
                                <hr>
                                <h3>Sección de Puntualidad</h3>
                                <p><strong>¿Cómo calificarías la puntualidad de tu cuidador a la hora de recoger o entregar a tu mascota?</strong></p>
                                <div class='fila_rate' data-section='puntualidad'>";
                                     
                                    for($i=1;$i<=5;$i++){ 

                              $CONTENIDO.= "<div class='select_rate puntualidad' data-rate='$i' data-section='puntualidad'>
                                            <label for='puntualidad_$i'><h5>$i</h5></label><br>
                                            <input type='radio' id='puntualidad_$i;' name='puntualidad' value='$i' class='huesito'><br>
                                             $vacio
                                        </div>";                                  
                                    } 
                                   
                               $CONTENIDO.= "</div><hr>
                                <h3>Sección de Limpieza</h3>
                                <p><strong>¿Cuál es el nivel de higiene y seguridad que consideras que tiene el hogar del cuidador?</strong></p> 
                                <div class='fila_rate' data-section='limpieza'>
                                    <div class='reset_rate limpieza' data-section='limpieza'>
                                        
                                    </div>";


                                     
                                    for($i=1;$i<=5;$i++){ 

                                      $CONTENIDO.= "<div class='select_rate limpieza' data-rate=' $i' data-section='limpieza'>
                                            <label for='limpieza_$i'><h5>$i</h5></label><br>
                                            <input type='radio' id='limpieza_$i' name='limpieza' value='$i' class='huesito'><br>
                                             $vacio
                                        </div>";
                                        
                                    } 

                                $CONTENIDO.="</div>
                                <hr>
                                <h3>Sección de Confianza</h3>
                                <p><strong>¿Qué tan confiable consideras que tu cuidador es?</strong></p>
                                <div class='fila_rate' data-section='confianza'>
                                    <div class='reset_rate confianza' data-section='confianza'>
                                        
                                    </div>";
                                    
                                    for($i=1;$i<=5;$i++){ 

                                      $CONTENIDO.=  "<div class='select_rate confianza' data-rate=' $i' data-section='confianza'>
                                            <label for='confianza_$i'><h5>$i</h5></label><br>
                                            <input type='radio' id='confianza_$i' name='confianza' value='$i' class='huesito'><br>
                                            $vacio
                                        </div>";
                                        
                                    } 

                               $CONTENIDO.= "</div>
                                <hr>
                                <h3>Cuéntale a todos lo que más te gusto de tu cuidador y si lo recomendarías</h3>
                               
                                <p><strong>Comentarios:</strong></p>

                                <textarea id='comentarios' name='comentarios' style='width: 100%; max-width: 600px; height: 120px;'></textarea>
                                <br>
                                <input type='hidden' name='funcion' value='rate'>
                                <input type='hidden' name='id' value='$post_id'>
                                <input type='submit' id='request_button' class='km-btn-primary boton_aplicar_filtros' value='Enviar valoración'>
                               
                            </form>

                            </div>";
                  
                     }

                } 
            }   
        
                
     }
        
     
    $CONTENIDO.="</div>


    <script>
        jQuery.noConflict(); 
        jQuery(document).ready(document).ready(function() {
            var values = {cuidado:'0', puntualidad:'0',limpieza:'0', confianza:'0'};
            jQuery('.select_rate').on('mouseout',function(){
                var section = jQuery(this).attr('data-section');
                if(values[section]=='0'){
                    jQuery('.select_rate.'+section+' > img').each(function(index){
                        jQuery(this).attr('src',' ".get_home_url()."/wp-content/themes/kmimos/images/new/icon/icon-hueso.svg');
                    });
                }
            });

            jQuery('.fila_rate').on('mouseout',function(){
                var section = jQuery(this).attr('data-section');
                if(values[section]=='0'){
                    jQuery('.select_rate.'+section+' > img').each(function(index){
                        jQuery(this).attr('src',' ".get_home_url()."/wp-content/themes/kmimos/images/new/icon/icon-hueso.svg');
                    });
                }
            });

            jQuery('.reset_rate').on('click',function(){
                var section = jQuery(this).attr('data-section');
                jQuery('.select_rate.'+section+' > img').each(function(index){
                    jQuery(this).attr('src',' ".get_home_url()."/wp-content/themes/kmimos/images/new/icon/icon-hueso.svg');
                });
                values[section]='0';
            });

            jQuery('.select_rate').on('click',function(){
                var rate = jQuery(this).attr('data-rate');
                var section = jQuery(this).attr('data-section');
                console.log('Seleccionando '+rate+' en la seccion '+section);
                jQuery('.select_rate.'+section+' > img').each(function(index){
                    jQuery(this).attr('src',' ".get_home_url()."/wp-content/themes/kmimos/images/new/icon/icon-hueso.svg');
                });

            jQuery('#'+section+'_'+rate).prop('checked',true);
                values[section]=rate;
                jQuery('.select_rate.'+section+' > img').each(function(index){
                    if(index < rate) jQuery(this).attr('src',' ".get_home_url()."/wp-content/themes/kmimos/images/new/icon/icon-hueso-color.svg');
                    else jQuery(this).attr('src',' ".get_home_url()."/wp-content/themes/kmimos/images/new/icon/icon-hueso.svg');
                });
            });

            jQuery('.select_rate').on('mouseover',function(){
                var rate = jQuery(this).attr('data-rate');
                var section = jQuery(this).attr('data-section');

                if(values[section]=='0'){
                    console.log('Activando hasta '+rate+' en la seccion '+section+', seleccionado '+values[section]);
                    jQuery('.select_rate.'+section+' > img').each(function(index){
                        if(index < rate) jQuery(this).attr('src','".get_home_url()."/wp-content/themes/kmimos/images/new/icon/icon-hueso-color.svg');
                        else jQuery(this).attr('src',' ".get_home_url()."/wp-content/themes/kmimos/images/new/icon/icon-hueso.svg');
                    });
                }
            });
        });
    </script>";