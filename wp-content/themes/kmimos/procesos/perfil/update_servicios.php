<?php
    
    date_default_timezone_set('America/Bogota');

    include("../funciones/servicios.php");

	$tams = array(
		"pequenos",
		"medianos",
		"grandes",
		"gigantes"
	);

	$hospedaje = array();
	$base_hospedaje = array();
	foreach ($tams as $value) {
		$hospedaje[$value] = $_POST['hospedaje_'.$value]+0;
		if( ($_POST['hospedaje_'.$value]+0) > 0 ){
			$base_hospedaje[$value] = $_POST['hospedaje_'.$value]+0;
		}
	}
	if( count($base_hospedaje) > 0 ){
		$base_hospedaje = min($base_hospedaje);
	}else{
		$base_hospedaje = 0;
	}
  	
	$adicionales = array();

	$transportacion_sencilla = false;
    $adicionales['transportacion_sencilla'] = array(
        "corto" => "Cortas",
        "medio" => "Medias",
        "largo" => "Largas"
    );
	foreach ($adicionales['transportacion_sencilla'] as $key => $value) {
		if( $_POST["transportacion_sencilla_".$key]+0 > 0 ){
			$transportacion_sencilla = true;

            //ADDITIONAL STATUS
            $adicionales['status_transportacion_sencilla'] = "1";
		}
		$adicionales['transportacion_sencilla'][$key] = $_POST["transportacion_sencilla_".$key]+0;
	}

	$transportacion_redonda = false;
    $adicionales['transportacion_redonda'] = array(
        "corto" => "Cortas",
        "medio" => "Medias",
        "largo" => "Largas"
    );
	foreach ($adicionales['transportacion_redonda'] as $key => $value) {
		if( $_POST["transportacion_redonda_".$key]+0 > 0 ){
			$transportacion_redonda = true;

            //ADDITIONAL STATUS
            $adicionales['status_transportacion_redonda'] = "1";
		}
		$adicionales['transportacion_redonda'][$key] = $_POST["transportacion_redonda_".$key]+0;
	}


    $adicionales_extra = array(
        "bano"                      => $_POST["adicional_bano"]+0,
        "corte"                     => $_POST["adicional_corte"]+0,
        "limpieza_dental"           => $_POST["adicional_limpieza_dental"]+0,
        "acupuntura"                => $_POST["adicional_acupuntura"]+0,
        "visita_al_veterinario"     => $_POST["adicional_visita_al_veterinario"]+0
    );

    foreach ($adicionales_extra as $key => $value) {
    	if( $value+0 > 0 ){
    		$adicionales[$key] = $value+0;

            //ADDITIONAL STATUS
            $adicionales['status_'.$key] = "1";
    	}
    }
	
	$adicionales['corte'] 					= $_POST["adicional_corte"]+0;
	$adicionales['limpieza_dental'] 		= $_POST["adicional_limpieza_dental"]+0;
	$adicionales['acupuntura'] 				= $_POST["adicional_acupuntura"]+0;
	$adicionales['visita_al_veterinario'] 	= $_POST["adicional_visita_al_veterinario"]+0;

  	$servicios_extras = array(
        "hospedaje"                     => "Precios de Hospedaje",
    	"guarderia"						=> "Precios de Guardería",
    	"paseos"						=> "Precios de Paseos",
    	"adiestramiento_basico"			=> "Precios de Entrenamiento Básico",
    	"adiestramiento_intermedio"		=> "Precios de Entrenamiento Intermedio",
    	"adiestramiento_avanzado"		=> "Precios de Entrenamiento Avanzado"
    );

  	$bases = array();
    foreach ($servicios_extras as $key => $value) {
    	
		$temp = array();
		$base_temp = array();
		foreach ($tams as $value) {
			$temp[$value] = $_POST[$key.'_'.$value]+0;
			if( ($_POST[$key.'_'.$value]+0) > 0 ){
				$base_temp[$value] = $_POST[$key.'_'.$value]+0;
			}
		}
		if( count($base_temp) > 0 ){
			$base_temp = min($base_temp);
		}else{
			$base_temp = 0;
		}

		if( max($temp) > 0 ){
    		$adicionales[$key] = $temp;
			$bases[$key] = $base_temp;

            //ADDITIONAL STATUS
            if(isset($_POST['status_'.$key])){
                $adicionales['status_'.$key] = $_POST['status_'.$key];
            }
		}

    }

    $imgs_product = array(
        "hospedaje"                 => "55477",
        "guarderia"                 => "55478",
        "adiestramiento_basico"     => "55479",
        "adiestramiento_intermedio" => "55479",
        "adiestramiento_avanzado"   => "55479",
        "paseos"                    => "55479"
    );

    $addons = "";

	$sql = "UPDATE cuidadores SET adicionales = '".serialize($adicionales)."', hospedaje = '".serialize($hospedaje)."', hospedaje_desde = '".$base_hospedaje."' WHERE user_id = ".$user_id.";";
	$db->query($sql);

	$cuidador = $db->get_row("SELECT * FROM cuidadores WHERE user_id = {$user_id}");
	$status_global = "pending";
	if( $cuidador->activo == 1 ){
		$status_global = "publish";
	} 
    $cuidador_post = $db->get_row("SELECT * FROM wp_posts WHERE ID = {$cuidador->id_post}");

    $servicios_extras_titulos = array(
        "hospedaje"                     => "Hospedaje",
        "guarderia"                     => "Guardería",
        "paseos"                        => "Paseos",
        "adiestramiento_basico"         => "Entrenamiento Básico",
        "adiestramiento_intermedio"     => "Entrenamiento Intermedio",
        "adiestramiento_avanzado"       => "Entrenamiento Avanzado"
    );
    $order_menu = array(
        "pequenos"  => "0",
        "medianos"  => "1",
        "grandes"   => "2",
        "gigantes"  => "3"
    );
    $cats = array(
        "paseos"                    => 2601,
        "adiestramiento_basico"     => 2602,
        "adiestramiento_intermedio" => 2606,
        "adiestramiento_avanzado"   => 2607,
        "guarderia"                 => 2599,
        "hospedaje"                 => 2598
    );
    $tamanos = array(
        "pequenos"  => "Pequeñas",
        "medianos"  => "Medianas",
        "grandes"   => "Grandes",
        "gigantes"  => "Gigantes"
    );
    
  	foreach ($servicios_extras as $nombre => $value) {
       
  		$extra = $db->get_var("SELECT ID FROM wp_posts WHERE post_author = '{$user_id}' AND post_name LIKE '%{$nombre}%' AND post_type = 'product'");
        
	  	if( $extra != false ){
	  		$bases[$nombre] *= 1.2;

            if( $bases[$nombre] > 0 && ( $_POST['status_'.$nombre] == 1 || $nombre == "hospedaje" ) ){
                $status = $status_global;
            }else{
                $status = "unpublish";
            }

	  		$sql = "UPDATE wp_postmeta SET meta_value = '{$bases[$nombre]}' WHERE post_id = '{$extra}' AND (meta_key = '_price' OR meta_key = '_wc_booking_base_cost');";
	  		$db->query($sql);
            
            $sql = "UPDATE wp_posts SET post_status = '{$status}' WHERE ID = '{$extra}';";
            $db->query($sql);

	  		foreach ($tams as $tamano) {
	  			$valor = ($_POST[$nombre.'_'.$tamano]+0);
  				$valor *= 1.2;
	  			if( $valor > 0 ){
	  				$status = $status_global;
	  				$base_variante = $valor-$bases[$nombre];
	  			}else{
	  				$status = "unpublish";
	  				$base_variante = 0;
	  			}

	  			$sql = "UPDATE wp_posts SET post_excerpt = 'Precio: \${$valor} c/u', post_status = '{$status}' WHERE post_parent = '{$extra}' AND post_name LIKE '%{$tamano}%' AND post_type = 'bookable_person';";
	  			$db->query( utf8_decode( $sql ) );

                $sql = "SELECT ID FROM wp_posts WHERE post_parent = '{$extra}' AND post_name LIKE '%{$tamano}%' AND post_type = 'bookable_person';";
	  			$id_variante = $db->get_var($sql);

	  			$sql = "UPDATE wp_postmeta SET meta_value = '{$base_variante}' WHERE post_id = '{$id_variante}' AND meta_key = 'block_cost';";
	  			$db->query($sql);
	  		}

            $db->query("UPDATE wp_postmeta SET meta_value = '{$addons}' WHERE post_id = {$extra} AND meta_key = '_product_addons';");
            $db->query("UPDATE wp_postmeta SET meta_value = '{$imgs_product[$nombre]}' WHERE post_id = {$extra} AND meta_key = '_thumbnail_id';");
	  	}else{

            $hoy = date("Y-m-d H:i:s");
            $nom = $cuidador_post->post_title;

            $bases[$nombre] *= 1.2;

            if( $bases[$nombre] > 0 ){
                $status = $status_global;
            }else{
                $status = "unpublish";
            }

            $sql = sql_producto(array(
                "user"          => $user_id,
                "hoy"           => $hoy,
                "titulo"        => $servicios_extras_titulos[$nombre]." - ".$nom,
                "descripcion"   => descripciones($nombre),
                "slug"          => $nombre."-".$user_id,
                "cuidador"      => $cuidador->id_post,
                "status"        => $status
            ));

            $db->query( ($sql) );
            $servicio_id = $db->insert_id(); 

            $sql = sql_cats(array( 
                "servicio"   => $servicio_id,
                "categoria"  => $cats[$nombre]
            ));
            $db->query( $sql );

            $sql_metas = sql_meta_producto(array(
                "id_servicio"   => $servicio_id,
                "precio"        => $bases[$nombre],
                "cuidador_post" => $cuidador->id_post,
                "cantidad"      => $cuidador->mascotas_permitidas,
                "img"           => $imgs_product[$nombre],
                "slug"          => $nombre
            ));

            $db->query( ($sql_metas) );

            foreach ($tams as $tamano) {
                $valor = ($_POST[$nombre.'_'.$tamano]+0);
                $valor *= 1.2;
                if( $valor > 0 ){
                    $status = $status_global;
                    $base_variante = $valor-$bases[$nombre];
                }else{
                    $status = "unpublish";
                    $base_variante = 0;
                }

                $sql = sql_variante(array(
                    "user"      => $user_id,
                    "hoy"       => $hoy,
                    "titulo"    => $tamanos[$tamano],
                    "precio"    => $valor,
                    "slug"      => $user_id."-".$nombre."-".$tamano,
                    "servicio"  => $servicio_id,
                    "menu"      => $order_menu[$tamano],
                    "status"    => $status                
                ));
                $db->query( utf8_decode($sql) );

                $sql = sql_meta_variante(array(
                    "bookable_person_id" => $db->insert_id(),
                    "bloque"             => $base_variante
                ));
                $db->query( ($sql) );
            }

            $db->query("UPDATE wp_postmeta SET meta_value = '{$servicio_id}' WHERE post_id = {$extra} AND meta_key = '_product_addons';");

	  	}

  	}

    $respuesta = array(
        "status" => "OK"
    );
?>