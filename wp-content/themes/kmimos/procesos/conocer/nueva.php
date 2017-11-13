<?php
	
	    include(realpath("../../../../../vlz_config.php"));
	    include(realpath("../../../../../wp-load.php"));

    	date_default_timezone_set('America/Bogota');

	    extract($_POST);

	    /*
	        Data General
	    */

	    $user_id = $current_user->ID;

	    $cuidador_post   = get_post($post_id);
	    $nombre_cuidador = $cuidador_post->post_title;


	    $datos_cuidador  = get_user_meta($cuidador_post->post_author);
	    $telf_cuidador = $datos_cuidador["user_phone"][0];
	    if( $telf_cuidador == "" ){
	        $telf_cuidador = $datos_cuidador["user_mobile"][0];
	    }
	    if( $telf_cuidador == "" ){
	        $telf_cuidador = "No registrado";
	    }

	    $datos_cliente = get_user_meta($user_id);

	    $cliente_web  = $datos_cliente['first_name'][0];
	    $cliente  = $datos_cliente['first_name'][0].' '.$datos_cliente['last_name'][0];

	    $telf_cliente = $datos_cliente["user_phone"][0];
	    if( isset($datos_cliente["user_mobile"][0]) ){
	        $separador = (!empty($telf_cliente))? ' / ': "";
	        $telf_cliente .= $separador . $datos_cliente["user_mobile"][0];
	    }
	    if( $telf_cliente == "" ){
	        $telf_cliente = "No registrado";
	    }

	    $inicio = "08";
	    $fin    = "22";
	    $rango  = 6;

	    $hora_actual = strtotime("now");
	    $xhora_actual = date("H", $hora_actual);

	    if( ($xhora_actual-$rango) < $inicio ){
	        $hoy = date("d-m-Y", $hora_actual);
	        $hoy = explode("-", $hoy);
	        $hoy = strtotime($hoy[0]."-".$hoy[1]."-".$hoy[2]." ".$inicio.":00:00");
	        $ayer = date("d-m-Y", strtotime("-1 day"));
	        $ayer = explode("-", $ayer);
	        $ayer = strtotime($ayer[0]."-".$ayer[1]."-".$ayer[2]." ".$fin.":00:00");
	        $exceso = $hoy-($hora_actual-($rango*3600));
	        $fecha_cancelacion = $ayer-$exceso;
	    }else{
	        $fecha_cancelacion = ($hora_actual-($rango*3600));
	    }

	    $new_post = array(
	        'post_type'     =>  'request',
	        'post_status'   =>  'pending',
	        'post_title'    =>  'Solicitud conocer cuidador "'.$nombre_cuidador.'" del '.date('d-m-Y H:i'),
	        'post_date'     =>  date("Y-m-d H:i:s"),
	        'post_modified' =>  date("Y-m-d H:i:s")
	    );

		//VALIDATE TOKEN
		$request_id = 0;
		$request_id = wp_insert_post($new_post);
		$new_postmeta = array(
			'request_type'          => 1,
			'request_status'        => 1,
			'requester_user'        => $user_id,
			'requested_petsitter'   => $post_id,
			'request_date'          => date('d-m-Y'),
			'request_time'          => date('H:i:s'),
			'request_next'          => $rango,
			'next_time'             => date("d-m-Y H:i:s", $fecha_cancelacion),
			'meeting_when'          => $_POST['meeting_when'],
			'meeting_time'          => $_POST['meeting_time'],
			'meeting_where'         => $_POST['meeting_where'],
			'pet_ids'               => serialize($pet_ids),
			'service_start'         => date('d-m-Y', $_POST['service_start']),
			'service_end'           => date('d-m-Y', $_POST['service_end']),
		);

		foreach($new_postmeta as $key => $value){
			update_post_meta($request_id, $key, $value);
		}

		$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id_post = '".$post_id."'");

		$email_cuidador = $cuidador->email;
		$email_cliente  = $current_user->user_email;

		$email_admin    = get_option( 'admin_email' );

		$metas_cuidador = get_user_meta($cuidador->user_id);

		$telf_cuidador = $metas_cuidador["user_phone"][0];
		 if( isset($metas_cuidador["user_mobile"][0]) ){
			$separador = (!empty($telf_cuidador))? ' / ': "";
			$telf_cuidador .= $separador . $metas_cuidador["user_mobile"][0];
		}
		if( $telf_cuidador == "" ){
			$telf_cuidador = "No registrado";
		}

		$asunto     = 'Solicitud para conocer a cuidador';
		$headers[]  = 'From: Kmimos México <kmimos@kmimos.la>';

		$saludo_admin   = '<p><strong>Hola,</strong></p>';
		$service_id     = $_POST['type_service'];
		$service        = get_term( $service_id, 'product_cat' );

		$mascotas = $wpdb->get_results("SELECT * FROM wp_posts WHERE ID IN ( '".implode("','", $pet_ids)."' )");
		$detalles_mascotas = "";

		$comportamientos_array = array(
			"pet_sociable"           => "Sociables",
			"pet_sociable2"          => "No sociables",
			"aggressive_with_pets"   => "Agresivos con perros",
			"aggressive_with_humans" => "Agresivos con humanos",
		);
		$tamanos_array = array(
			"Pequeño",
			"Mediano",
			"Grande",
			"Gigante"
		);
		if( count($mascotas) > 0 ){
			foreach ($mascotas as $key => $mascota) {
				$data_mascota = get_post_meta($mascota->ID);

				$temp = array();
				foreach ($data_mascota as $key => $value) {

					switch ($key) {
						case 'pet_sociable':
							if( $value[0] == 1 ){
								$temp[] = "Sociable";
							}else{
								$temp[] = "No sociable";
							}
						break;
						case 'aggressive_with_pets':
							if( $value[0] == 1 ){
								$temp[] = "Agresivo con perros";
							}
						break;
						case 'aggressive_with_humans':
							if( $value[0] == 1 ){
								$temp[] = "Agresivo con humanos";
							}
						break;
					}

				}

				$nacio = strtotime(date($data_mascota['birthdate_pet'][0]));
				$diff = abs(strtotime(date('Y-m-d')) - $nacio);
				$years = floor($diff / (365*60*60*24));
				$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
				$edad = $years.' año(s) '.$months.' mes(es)';

				$raza = $wpdb->get_var("SELECT nombre FROM razas WHERE id=".$data_mascota['breed_pet'][0]);

				$detalles_mascotas .= "
		            <tr style='font-size: 12px;'>
						<td style='font-weight: 600; vertical-align: top; padding: 7px 0px;'>
							<img src='[URL_IMGS]/dog.png' style='width: 17px; padding: 0px 10px;' /> ".$data_mascota['name_pet'][0]."
						</td>
						<td style='padding: 7px; vertical-align: top;'>
		                    ".$raza."
						</td>
						<td style='padding: 7px; vertical-align: top;'>
		                    ".$edad."
						</td>
						<td style=' padding: 7px; vertical-align: top;'>
		                    ".$tamanos_array[ $data_mascota['size_pet'][0] ]."
						</td>
						<td style='padding: 7px; vertical-align: top;'>
		                    ".implode("<br>", $temp)."
						</td>
					</tr>
				";
			}
		}else{
			$detalles_mascotas .= "
				<tr style='font-weight: 400;'>
					<td colspan='5'>No tiene mascotas registradas.</td>
				</tr>
			";
		}
		$detalles_mascotas .= '';

		/*
			Cuidador
		*/

		$info = kmimos_get_info_syte();

		$cuidador_file = realpath('../../template/mail/conocer/cuidador/nueva.php');
        $mensaje_cuidador = file_get_contents($cuidador_file);

        $fin = strtotime( str_replace("/", "-", $_POST['service_end']) );

        $detalles_mascotas = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $detalles_mascotas);

        $mensaje_cuidador = str_replace('[ACEPTAR]', get_home_url().'/perfil-usuario/solicitudes/confirmar/'.$request_id, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[CANCELAR]', get_home_url().'/perfil-usuario/solicitudes/cancelar/'.$request_id, $mensaje_cuidador);

        $mensaje_cuidador = str_replace('[name]', $cliente, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[avatar]', kmimos_get_foto($user_id), $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[nombre_usuario]', $nombre_cuidador, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[telefonos]', $telf_cliente, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[email]', $email_cliente, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[id_solicitud]', $request_id, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[fecha]', $_POST['meeting_when'], $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[hora]', $_POST['meeting_time'], $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[lugar]', $_POST['meeting_where'], $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[desde]', date("d/m", strtotime( str_replace("/", "-", $_POST['service_start']) )), $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[hasta]', date("d/m", $fin), $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[anio]', date("Y", $fin), $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[MASCOTAS]', $detalles_mascotas, $mensaje_cuidador);

		$mensaje_cuidador = get_email_html($mensaje_cuidador, false);

		wp_mail( $email_cuidador,  $asunto, $mensaje_cuidador);

	/*
		Cliente
	*/

		$cliente_file = realpath('../../template/mail/conocer/cliente/nueva.php');
        $mensaje_cliente = file_get_contents($cliente_file);

        $mensaje_cliente = str_replace('[name]', $cliente_web, $mensaje_cliente);
        $mensaje_cliente = str_replace('[avatar]', kmimos_get_foto($cuidador->user_id), $mensaje_cliente);
        $mensaje_cliente = str_replace('[nombre_usuario]', $nombre_cuidador, $mensaje_cliente);
        $mensaje_cliente = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cliente);
        $mensaje_cliente = str_replace('[telefonos]', $telf_cuidador, $mensaje_cliente);
        $mensaje_cliente = str_replace('[email]', $email_cuidador, $mensaje_cliente);
        $mensaje_cliente = str_replace('[id_solicitud]', $request_id, $mensaje_cliente);
        $mensaje_cliente = str_replace('[fecha]', $_POST['meeting_when'], $mensaje_cliente);
        $mensaje_cliente = str_replace('[hora]', $_POST['meeting_time'], $mensaje_cliente);
        $mensaje_cliente = str_replace('[lugar]', $_POST['meeting_where'], $mensaje_cliente);
        $mensaje_cliente = str_replace('[desde]', date("d/m", strtotime( str_replace("/", "-", $_POST['service_start']) )), $mensaje_cliente);
        $mensaje_cliente = str_replace('[hasta]', date("d/m", $fin), $mensaje_cliente);
        $mensaje_cliente = str_replace('[anio]', date("Y", $fin), $mensaje_cliente);

		$mensaje_cliente = get_email_html($mensaje_cliente, false);

		wp_mail( $email_cliente, $asunto, $mensaje_cliente);

	/*
		Enviando E-mails Administradores
	*/

		$admin_file = realpath('../../template/mail/conocer/admin/nueva.php');
        $mensaje_admin = file_get_contents($admin_file);

        /* Generales */

	        $mensaje_admin = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_admin);
	        $mensaje_admin = str_replace('[id_solicitud]', $request_id, $mensaje_admin);
	        $mensaje_admin = str_replace('[fecha]', $_POST['meeting_when'], $mensaje_admin);
	        $mensaje_admin = str_replace('[hora]', $_POST['meeting_time'], $mensaje_admin);
	        $mensaje_admin = str_replace('[lugar]', $_POST['meeting_where'], $mensaje_admin);
	        $mensaje_admin = str_replace('[desde]', date("d/m", strtotime( str_replace("/", "-", $_POST['service_start']) )), $mensaje_admin);
	        $mensaje_admin = str_replace('[hasta]', date("d/m", $fin), $mensaje_admin);
	        $mensaje_admin = str_replace('[anio]', date("Y", $fin), $mensaje_admin);
       		$mensaje_admin = str_replace('[MASCOTAS]', $detalles_mascotas, $mensaje_admin);

        /* Cliente */

	        $mensaje_admin = str_replace('[nombre_cliente]', $cliente_web, $mensaje_admin);
	        $mensaje_admin = str_replace('[avatar_cliente]', kmimos_get_foto($user_id), $mensaje_admin);
	        $mensaje_admin = str_replace('[telefonos_cliente]', $telf_cliente, $mensaje_admin);
	        $mensaje_admin = str_replace('[email_cliente]', $email_cliente, $mensaje_admin);

        /* Cuidador */
        
	        $mensaje_admin = str_replace('[avatar_cuidador]', kmimos_get_foto($cuidador->user_id), $mensaje_admin);
	        $mensaje_admin = str_replace('[nombre_cuidador]', $nombre_cuidador, $mensaje_admin);
	        $mensaje_admin = str_replace('[telefonos_cuidador]', $telf_cuidador, $mensaje_admin);
	        $mensaje_admin = str_replace('[email_cuidador]', $email_cuidador, $mensaje_admin);

		$mensaje_admin = get_email_html($mensaje_admin, false);

		kmimos_mails_administradores_new($asunto, $mensaje_admin);

		$data = array(
			'n_solicitud' => $request_id,
			'nombre' => $nombre_cuidador,
			'telefono' => $telf_cuidador,
			'email' => $email_cuidador
		);

		echo json_encode($data);


