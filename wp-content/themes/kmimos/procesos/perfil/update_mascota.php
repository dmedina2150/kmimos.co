<?php 

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

	    $img_anterior = $db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$pet_id} AND meta_key = 'photo_pet';", "meta_value");
	    if( file_exists($raiz.$img_anterior) ){
	        unlink($raiz.$img_anterior);
	    }
	    if($img_anterior == ""){
	    	$img_portada = "INSERT INTO wp_postmeta VALUES (NULL, '{$pet_id}', 'photo_pet', '{$sub_path}{$portada}')";
	    }else{
	   		$img_portada = "UPDATE wp_postmeta SET meta_value = '{$sub_path}{$portada}' WHERE post_id = {$pet_id} AND meta_key = 'photo_pet';";
	    }
	}

	$pet_birthdate = date('Y-m-d', strtotime( str_replace("/", "-", $pet_birthdate)));

	kmimos_update_post_meta($pet_id, "pet_name", $pet_name);
	kmimos_update_post_meta($pet_id, "breed_pet", $pet_breed);
	kmimos_update_post_meta($pet_id, "colors_pet", $pet_colors);
	kmimos_update_post_meta($pet_id, "birthdate_pet", $pet_birthdate);
	kmimos_update_post_meta($pet_id, "size_pet", $pet_size);
	kmimos_update_post_meta($pet_id, "gender_pet", $pet_gender);
	kmimos_update_post_meta($pet_id, "pet_sterilized", $pet_sterilized);
	kmimos_update_post_meta($pet_id, "pet_sociable", $pet_sociable);
	kmimos_update_post_meta($pet_id, "aggressive_with_humans", $aggresive_humans);
	kmimos_update_post_meta($pet_id, "aggressive_with_pets", $aggresive_pets);
	kmimos_update_post_meta($pet_id, "about_pet", $pet_observations);
	kmimos_update_post_meta($pet_id, "pet_type", $pet_type);

	$sql  = "UPDATE wp_posts SET post_title = '{$pet_name}' WHERE ID = {$pet_id};";
	$sql .= "UPDATE wp_term_relationships SET term_taxonomy_id = '{$pet_type}' 	WHERE object_id = {$pet_id};";

	$sql .= $img_portada;

	$db->query_multiple( utf8_decode($sql) );

	$respuesta = array(
		"status" => "OK",
		"sql"	 => $sql
	);
?>