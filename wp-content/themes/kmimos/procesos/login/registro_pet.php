<?php  
    include("../../../../../vlz_config.php");
    include("../../../../../wp-load.php");
    if(file_exists($config)){
        include_once($config);
    }

    extract($_POST);
	
    $conn = new mysqli($host, $user, $pass, $db);

	$errores = array();

    $result = 0;
    if( !$conn->connect_error) {
        $userid=trim($userid);
        $existen = $conn->query( "SELECT * FROM wp_users WHERE ID = '{$userid}'" );
        if( $existen->num_rows > 0 ){

            // Pets - Photo
            $photo_pet = "";
            if( $img_pet != "" ){
                $tmp_user_id = ($userid);

                if( !is_dir(realpath( "../../../../" )."/uploads/mypet/") ){
                    mkdir(realpath( "../../../../" )."/uploads/mypet/");
                }
                $dir = realpath( "../../../../" )."/uploads/mypet/".$tmp_user_id."/";
                mkdir($dir);

                $path_origen = realpath( "../../../../../" )."/imgs/Temp/".$img_pet;
                $path_destino = $dir.$img_pet;
                if( file_exists($path_origen) ){
                    if( copy($path_origen, $path_destino) ){
                        unlink($path_origen);
                        $photo_pet='/wp-content/uploads/mypet/'.$tmp_user_id.'/'.$img_pet;
                    }
                }                  
            }
            // END Pets - Photo


            $args = array(
                'post_title'    => wp_strip_all_tags($name_pet),
                'post_status'   => 'publish',
                'post_author'   => $userid,
                'post_type'     => 'pets'
            );
            $pet_id = wp_insert_post( $args );

            $sql = "
                INSERT INTO wp_postmeta VALUES
                    (NULL, {$pet_id}, 'name_pet',           '{$name_pet}'),
                    (NULL, {$pet_id}, 'photo_pet',         '{$photo_pet}'),
                    (NULL, {$pet_id}, 'type_pet',         '{$type_pet}'),
                    (NULL, {$pet_id}, 'breed_pet',          '{$race_pet}'),
                    (NULL, {$pet_id}, 'colors_pet',        '{$color_pet}'),
                    (NULL, {$pet_id}, 'birthdate_pet',          '{$date_birth}'),
                    (NULL, {$pet_id}, 'gender_pet',           '{$gender_pet}'),
                    (NULL, {$pet_id}, 'size_pet',           '{$size_pet}'),
                    (NULL, {$pet_id}, 'pet_sterilized',           '{$pet_sterilized}'),
                    (NULL, {$pet_id}, 'pet_sociable',           '{$pet_sociable}'),
                    (NULL, {$pet_id}, 'aggressive_with_humans',           '{$aggresive_humans}'),
                    (NULL, {$pet_id}, 'aggressive_with_pets',           '{$aggresive_pets}'),
                    (NULL, {$pet_id}, 'rich_editing',        'true'),
                    (NULL, {$pet_id}, 'comment_shortcuts',   'false'),
                    (NULL, {$pet_id}, 'admin_color',         'fresh'),
                    (NULL, {$pet_id}, 'use_ssl',             '0'),
                    (NULL, {$pet_id}, 'show_admin_bar_front', 'false'),
                    (NULL, {$pet_id}, 'wp_capabilities',     'a:1:{s:10:\"subscriber\";b:1;}'),
                    (NULL, {$pet_id}, 'about_pet',           ''),
                    (NULL, {$pet_id}, 'owner_pet',           '{$user_id}'),
                    (NULL, {$pet_id}, 'wp_user_level',       '0');
            ";
            $conn->query( utf8_decode( $sql ) );

            $sql = "INSERT INTO wp_term_relationships VALUES ({$pet_id},{$type_pet},'0');";
            $conn->query( utf8_decode( $sql ) );


            echo 1;
            exit;
        }else{
            echo 0.1;
        }
    }
    echo 0;
