<?php 

    date_default_timezone_set('America/Bogota');
    $hoy = date("Y-m-d H:i:s");

	$slug = time();
	$sql_1  = "INSERT INTO wp_posts VALUES (NULL, '".$user_id."', '".$hoy."', '".$hoy."', '', '".$pet_name."', '', 'publish', 'closed', 'closed', '', '".$slug."', '', '', '".$hoy."', '".$hoy."', '', '0', '/producto/".$slug."/', '0', 'pets', '', '0' );";
	$db->query( utf8_decode($sql_1) );
	$pet_id = $db->insert_id();

	if($portada != ""){
		$tmp_user_id = ($user_id);
	    $sub_path = "/wp-content/uploads/mypet/{$tmp_user_id}/";
	    $dir = $raiz.$sub_path;
	    @mkdir($dir);
	    $path_origen = $raiz."/imgs/Temp/".$portada;
	    $path_destino = $dir.$portada;
	    if( file_exists($path_origen) ){
	        copy($path_origen, $path_destino);
	        unlink($path_origen);
	    }
	    
	    $img_portada = "INSERT INTO wp_postmeta VALUES (NULL, '{$pet_id}', 'photo_pet', '{$sub_path}{$portada}'); ";
	}

	$pet_birthdate = date('Y-m-d', strtotime( str_replace("/", "-", $pet_birthdate)));

	$sql  = "INSERT INTO wp_postmeta VALUES (NULL, '{$pet_id}', 'name_pet', '{$pet_name}'); ";
	$sql .= "INSERT INTO wp_postmeta VALUES (NULL, '{$pet_id}', 'breed_pet', '{$pet_breed}'); ";
	$sql .= "INSERT INTO wp_postmeta VALUES (NULL, '{$pet_id}', 'colors_pet', '{$pet_colors}'); ";
	$sql .= "INSERT INTO wp_postmeta VALUES (NULL, '{$pet_id}', 'birthdate_pet', '{$pet_birthdate}'); ";
	$sql .= "INSERT INTO wp_postmeta VALUES (NULL, '{$pet_id}', 'size_pet', '{$pet_size}'); ";
	$sql .= "INSERT INTO wp_postmeta VALUES (NULL, '{$pet_id}', 'gender_pet', '{$pet_gender}'); ";
	$sql .= "INSERT INTO wp_postmeta VALUES (NULL, '{$pet_id}', 'pet_sterilized', '{$pet_sterilized}'); ";
	$sql .= "INSERT INTO wp_postmeta VALUES (NULL, '{$pet_id}', 'pet_sociable', '{$pet_sociable}'); ";
	$sql .= "INSERT INTO wp_postmeta VALUES (NULL, '{$pet_id}', 'aggressive_with_humans', '{$aggresive_humans}'); ";
	$sql .= "INSERT INTO wp_postmeta VALUES (NULL, '{$pet_id}', 'aggressive_with_pets', '{$aggresive_pets}'); ";
	$sql .= "INSERT INTO wp_postmeta VALUES (NULL, '{$pet_id}', 'about_pet', '{$pet_observations}'); ";
	$sql .= "INSERT INTO wp_postmeta VALUES (NULL, '{$pet_id}', 'owner_pet', '{$user_id}'); ";

	$sql .= $img_portada;

	$db->query_multiple( utf8_decode($sql) );

	$respuesta = array(
		"status" => "OK",
		"sql"	 => $sql_1." - ".$sql
	);
?>