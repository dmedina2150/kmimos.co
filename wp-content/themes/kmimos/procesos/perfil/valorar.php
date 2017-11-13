<?php

    date_default_timezone_set('America/Bogota');
    $hoy = date("Y-m-d H:i:s");

    $email = $db->get_var("SELECT user_email FROM wp_users WHERE ID = {$user_id}");
    $nombre = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = 'first_name'");
    $apellido = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = 'last_name'");

    $sql = "
    	INSERT INTO 
    		wp_comments 
    	VALUES (
    		NULL, 
	    	'{$petsitter_id}', 
	    	'{$nombre} {$apellido}', 
	    	'{$email}', 
	    	'', 
	    	'', 
	    	'{$hoy}', 
	    	'{$hoy}', 
	    	'{$comentarios}', 
	    	'0', 
	    	'1', 
	    	'', 
	    	'', 
	    	'0', 
	    	'{$user_id}'
	   	)
	;";

	$db->query( utf8_decode($sql) );
	$coment_id = $db->insert_id();

	$sql  = "INSERT INTO wp_commentmeta VALUES (NULL, '{$coment_id}', 'care', '{$cuidado}'); ";
	$sql .= "INSERT INTO wp_commentmeta VALUES (NULL, '{$coment_id}', 'punctuality', '{$puntualidad}'); ";
	$sql .= "INSERT INTO wp_commentmeta VALUES (NULL, '{$coment_id}', 'cleanliness', '{$limpieza}'); ";
	$sql .= "INSERT INTO wp_commentmeta VALUES (NULL, '{$coment_id}', 'trust', '{$confianza}'); ";

	$sql .= "INSERT INTO wp_postmeta VALUES (NULL, '{$post_id}', 'customer_comment', '{$coment_id}'); ";

	$db->query_multiple( utf8_decode($sql) );

	$respuesta = array(
		"status" => "OK"
	);
?>