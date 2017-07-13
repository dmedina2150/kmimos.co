<?php

    require('../../../wp-load.php');

	$info = kmimos_get_info_syte();

    add_filter( 'wp_mail_from_name', function( $name ) {
        global $info;
        return $info["titulo"];
    });
    add_filter( 'wp_mail_from', function( $email ) {
        global $info;
        return $info["email"]; 
    });

    $email_admin = $info["email"];

	extract($_GET);

    global $wpdb;

    $id = $o;

    $metas_solicitud = get_post_meta($id); 

    /*	Datos del cuidador 	*/
	    $cuidador_post 	= $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = '".$metas_solicitud['requested_petsitter'][0]."'");
		$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id_post = '".$metas_solicitud['requested_petsitter'][0]."'");
		$email_cuidador = $cuidador->email;

    /*	Datos del cliente 	*/
    	$cliente = $metas_solicitud['requester_user'][0];
		$metas_cliente = get_user_meta($cliente);

		$nombre = $metas_cliente["first_name"][0];
		$apellido = $metas_cliente["last_name"][0];

		$nom = $nombre." ".$apellido;
		$dir = $metas_cliente["user_address"][0];

		$telf = $metas_cliente["user_phone"][0];
		if( $telf == "" ){
			$telf = $metas_cliente["user_mobile"][0];
		}
		if( $telf == "" ){
			$telf = "No registrado";
		}

		if( $dir == "" ){
			$dir = "No registrada";
		}

		$user = get_user_by( 'id', $cliente );

		$email_cliente = $user->data->user_email;

	/*	Sugeridos 	*/

		$sql = "
	        SELECT 
                DISTINCT id,
                ROUND ( ( 6371 * 
                    acos(
                        cos(
                            radians({$cuidador->latitud})
                        ) * 
                        cos(
                            radians(latitud)
                        ) * 
                        cos(
                            radians(longitud) - 
                            radians({$cuidador->longitud})
                        ) + 
                        sin(
                            radians({$cuidador->latitud})
                        ) * 
                        sin(
                            radians(latitud)
                        )
                    )
                ), 2 ) as DISTANCIA,
                id_post,
                hospedaje_desde
            FROM 
                cuidadores
            WHERE
                id_post != {$cuidador->id_post} AND
                portada = 1 AND
                activo = 1
            ORDER BY DISTANCIA ASC
            LIMIT 0, 4
	    ";

	    $sugeridos = $wpdb->get_results($sql); 

	    $str_sugeridos = "";

	    foreach ($sugeridos as $key => $cuidador) {

			$name_photo = get_user_meta($cuidador->user_id, "name_photo", true);
			$cuidador_id = $cuidador->id;

			if( empty($name_photo)  ){ $name_photo = "0"; }
			if( file_exists("../../uploads/cuidadores/avatares/".$cuidador_id."/{$name_photo}") ){
				$img = get_home_url()."/wp-content/uploads/cuidadores/avatares/".$cuidador_id."/{$name_photo}";
			}elseif( file_exists("../../uploads/cuidadores/avatares/".$cuidador_id."/0.jpg") ){
				$img = get_home_url()."/wp-content/uploads/cuidadores/avatares/".$cuidador_id."/0.jpg";
			}else{
				$img = get_home_url()."/wp-content/themes/pointfinder".'/images/noimg.png';
			}

	        $post = get_post($cuidador->id_post);

	        $str_sugeridos_img .= '
                <div style="display: inline-block; width: 49%; text-align: center; min-width: 239px;">
                    <a href="'.get_home_url().'/petsitters/'.$post->post_name.'/" target="_blank" style="display: block; margin: 5px; text-decoration: none;">
                        <div style="background: #e4e4e4; border: solid 1px #CCC;">
                            <img src="'.$img.'" height="130" style="max-width: 100%; max-height: 100%; padding: 3px;" />
                        </div>
                        <div style="
                            text-align: left;
                            font-size: 16px;
                            font-weight: 600;
                            color: #00d2b7;
                            padding: 5px;
                            border: solid 1px #CCC;
                            border-top: 0;
                            border-bottom: 0;">
                            '.$post->post_title.'
                        </div>
                        <div style="
                            text-align: right;
                            font-size: 13px;
                            font-weight: 600;
                            color: #7d7d7d;
                            padding: 0px 5px 10px;
                            border: solid 1px #CCC;
                            border-top: 0;">
                            Hospedaje desde<br>
                            MXN $'.$cuidador->hospedaje_desde.'
                        </div>
                    </a>
                </div>
            ';
	        
	    }

	    $lista_cercanos = '
            <ol class="lista" style="padding-left: 10px;"> 
                <li align="justify" style="font-size: 12px;">
                    Revisa estas recomendaciones y pícale a cualquiera de ellas para ver más detalles sobre su perfil.
                    <div style="overflow: hidden; text-align: center; margin: 0px auto; max-width: 600px;">'.$str_sugeridos_img.'</div>
                </li>
                <li align="justify" style="padding-bottom: 10px; font-size: 12px;">En caso de que alguna de estas opciones no se adecúe a tus necesidades, por favor ingresa a <strong><a style="text-decoration: none; color: #3d68b9;" href="'.get_home_url().'/busqueda">Kmimos Colombia</a></strong> en donde podrás encontrar cientos de cuidadores que seguro te encantarán.</li>
                <li align="justify" style="font-size: 12px;">Para asistencia personalizada por favor márcanos a nuestros números. '.$info["telefono"].'.</li>
            </ol>
        ';

	echo "
		<style>
    		html, body{ margin: 0px; min-height: 100%; padding: 0px; font-size: 12px; }
    		.body{ margin: 0px; min-height: 100%; padding: 0px; }
    		body * { font-size: 12px; }
    	</style>
	";

	$styles = "
		<style>
			.undoreset div p {
			    margin: 1em 0px;
			}
		</style>
	";

	$status = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = $id AND meta_key = 'request_status';");
	if( $status != 1 ){
		$estado = array(
			2 => "Confirmada",
			3 => "Cancelada",
			4 => "Cancelada"
		);
		$msg = $styles.'
				<p>Hola <strong>'.$cuidador_post->post_title.'</strong></p>
				<p align="justify">Te notificamos que la solicitud N° <strong>'.$id.'</strong> ya ha sido '.$estado[$status].' anteriormente.</p>
				<p align="justify">Por tal motivo ya no es posible realizar cambios en el estatus de la misma.</p>
		';
   		echo kmimos_get_email_html("La solicitud ya fue ".$estado[$status]." anteriormente.", $msg, "", false, true);

   		exit;
	}
	
	if($s == "0"){
		$wpdb->query("UPDATE wp_postmeta SET meta_value = '3' WHERE post_id = $id AND meta_key = 'request_status';");
		$wpdb->query("UPDATE wp_posts SET post_status = 'draft' WHERE ID = '{$id}';");

		$msg = $styles.'
	    	<p><strong>Solicitud para conocer cuidador Num. ('.$id.')</strong></p>
			<p>Hola <strong>'.$cuidador_post->post_title.'</strong></p>
			<p align="justify">Te notificamos que la solicitud para conocerte N° <strong>'.$id.'</strong> ha sido cancelada exitosamente de acuerdo a tu petición.</p>
			<p align="justify">Si tienes alguna duda o comentario de la cancelación con todo gusto puedes contactarnos.</p>
			<p style="text-align: center;">
	            <a 
	            	href="'.get_home_url().'/"
	            	style="
	            		padding: 10px;
					    background: #59c9a8;
					    color: #fff;
					    font-weight: 400;
					    font-size: 17px;
					    font-family: Roboto;
					    border-radius: 3px;
					    border: solid 1px #1f906e;
					    display: block;
					    width: 200px;
					    margin: 0px auto;
					    text-align: center;
					    text-decoration: none;
	            	"
	            >Ir a Kmimos</a>
	        </p>
	    ';
	    
   		echo $msg_cuidador = kmimos_get_email_html("Solicitud Cancelada Exitosamente!", $msg, "", true, true);
   		wp_mail( $email_cuidador, "Solicitud Cancelada", $msg_cuidador);

		$msg = $styles.'
	    	<p><strong>Solicitud para conocer cuidador Num. ('.$id.')</strong></p>
			<p>Hola <strong>Administrador</strong>,</p>
			<p align="justify">Te notificamos que el cuidador <strong>'.$cuidador_post->post_title.'</strong> ha cancelado la solicitud para conocerlo N° <strong>'.$id.'</strong>.</p>'
			.'
				<p align="justify">
					Esta son las sugerencias que se le enviaron al cliente:
				</p>
			'
			.$lista_cercanos;
	    
   		$msg_admin = kmimos_get_email_html("Solicitud Cancelada por Cuidador - ".$cuidador_post->post_title, $msg, "", true, true);
   		wp_mail( $mail_admin, "Cancelación de Solicitud", $msg_admin, kmimos_mails_administradores());

   		$msg = $styles.'
   			<div style="padding-right: 10px;">
		    	<p><strong>Solicitud para conocer cuidador Num. ('.$id.')</strong></p>
				<p>Hola <strong>'.$nom.'</strong>,</p>
				<p align="justify">Te notificamos que el cuidador <strong>'.$cuidador_post->post_title.'</strong> ha cancelado la solicitud para conocerle N° <strong>'.$id.'</strong>.</p>
				<p align="justify">
					Sabemos lo importante que es para ti encontrar el lugar adecuado para que cuiden a tu peludo, 
					por lo que te compartimos estas opciones con características similares a tu búsqueda original. Solo debes seguir los siguientes pasos:
				</p>
				'.$lista_cercanos.'
				<p align="justify">Si tienes alguna duda o comentario de la cancelación con todo gusto puedes contactarnos.</p>
			</div>
	    ';
	    
   		$msg_cliente = kmimos_get_email_html("Solicitud Cancelada", $msg, "", true, true);
   		wp_mail( $user->data->user_email, "Solicitud Cancelada", $msg_cliente, kmimos_mails_administradores());

    } else {
		$wpdb->query("UPDATE wp_postmeta SET meta_value = '2' WHERE post_id = $id AND meta_key = 'request_status';");
		$wpdb->query("UPDATE wp_posts SET post_status = 'publish' WHERE ID = '{$id}';");

		$msg = $styles.'
	    	<p><strong>Confirmación de Solicitud para Conocerte (N°. '.$id.')</strong></p>
			<p>Hola <strong>'.$cuidador_post->post_title.'</strong></p>
			<p align="justify">Siguiendo tu petición hacia el Staff Kmimos, la solicitud para conocerte del cliente <strong>'.$nom.'</strong> ha sido confirmada.</p>
			<p style="text-align: center;">
	            <a 
	            	href="'.get_home_url().'/"
	            	style="
	            		padding: 10px;
					    background: #59c9a8;
					    color: #fff;
					    font-weight: 400;
					    font-size: 17px;
					    font-family: Roboto;
					    border-radius: 3px;
					    border: solid 1px #1f906e;
					    display: block;
					    width: 200px;
					    margin: 0px auto;
					    text-align: center;
					    text-decoration: none;
	            	"
	            >Ir a Kmimos</a>
	        </p>
	    ';

   		echo $msg_cuidador = kmimos_get_email_html("Confirmación de Solicitud para Conocerte", $msg, "", true, true);
   		wp_mail( $email_cuidador, "Confirmación de Solicitud para Conocerte", $msg_cuidador);

		$msg_admin = $styles.'
	    	<p><strong>Confirmación de Solicitud para Conocerte (N°. '.$id.')</strong></p>
			<p>Hola <strong>Administrador</strong>,</p>
			<p align="justify">Te notificamos que el cuidador <strong>'.$cuidador_post->post_title.'</strong> ha <strong>Confirmado</strong> la solicitud para conocerle N° <strong>'.$id.'</strong>.</p>';

   		$msg_admin = kmimos_get_email_html("Confirmación de Solicitud para Conocer Cuidador", $msg_admin, "", true, true);
   		wp_mail( $mail_admin, "Confirmación de Solicitud para Conocer Cuidador", $msg_admin, kmimos_mails_administradores());

		$msg_cliente = $styles.'
			<p align="center">¡Todo está listo <strong>'.$nom.'</strong>!</p>
			<p align="justify">Tu solicitud para conocer al cuidador <strong>'.$cuidador_post->post_title.'</strong> ha sido confirmada por &eacute;l.</p>';

		$msg_cliente = kmimos_get_email_html("Confirmación de Solicitud para Conocer Cuidador", $msg_cliente, "", true, true);
   		wp_mail( $user->data->user_email, "Confirmación de Solicitud para Conocer Cuidador", $msg_cliente);


    }

?>

