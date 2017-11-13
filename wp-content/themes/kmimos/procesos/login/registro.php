<?php  

    include("../../../../../vlz_config.php");
    include("../../../../../wp-load.php");

    include("../funciones/db.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );
    
    date_default_timezone_set('America/Bogota');
    extract($_POST);

    if( preg_match("/[\+]{1,}/", $email) || !filter_var($email, FILTER_VALIDATE_EMAIL) ){
        $fields = [ 'name'=>'email', 'msg'=>"Formato de E-mail invalido"];
        echo "Formato de E-mail invalido";
        exit();
    }

    $existen = $db->get_var( "SELECT ID FROM wp_users WHERE user_email = '{$email}'" );

    if( $existen+0 > 0 ){
        echo "E-mail ya registrado";
        exit;
    }else{
        $hoy = date("Y-m-d H:i:s");

        $new_user = "
            INSERT INTO wp_users VALUES (
                NULL,
                '".$email."',
                '".md5($password)."',
                '".$email."',
                '".$email."',
                '',
                '".$hoy."',
                '',
                0,
                '".$name." ".$lastname."'
            );
        ";

        $db->query( utf8_decode( $new_user ) );
        $user_id = $db->insert_id();

        print_r($user_id);

        if (!isset($_SESSION)) {
            session_start();
        }

        if(array_key_exists('wlabel',$_SESSION) || $referido=='Volaris' || $referido=='Vintermex'){
            $wlabel='';

            if(array_key_exists('wlabel',$_SESSION)){
                $wlabel=$_SESSION['wlabel'];

            }else if($referido=='Volaris'){
                $wlabel='volaris';

            }else if($referido=='Vintermex'){
                $wlabel='viajesintermex';
            }

            if ($wlabel!=''){
                $query_wlabel = "INSERT INTO wp_usermeta VALUES (NULL, '".$user_id."', '_wlabel', '".$wlabel."');";
                $db->query( utf8_decode( $query_wlabel ) );
            }
        }

        $name_photo = "";
        $user_photo = 0;
        if( $img_profile != "" ){
            $user_photo = 1;
            $name_photo = $img_profile;

            $dir = "../../../../uploads/avatares_clientes/".$user_id."/";
            @mkdir($dir);

            $path_origen = "../../../../../imgs/Temp/".$img_profile;
            $path_destino = $dir.$img_profile;
            if( file_exists($path_origen) ){
                if( copy($path_origen, $path_destino) ){
                    unlink($path_origen);
                }
            }                  
        }

        $sql = " INSERT INTO wp_usermeta VALUES ";

        if( $social_facebook_id != '' ){
            $sql .= "(NULL, {$user_id}, 'facebook_auth_id', '{$social_facebook_id}'),";
        }

        if( $social_google_id != '' ){
            $sql .= "(NULL, {$user_id}, 'google_auth_id', '{$social_google_id}'  ),";
        }

        $sql .= "
                (NULL, {$user_id}, 'user_pass',           '{$password}'),
                (NULL, {$user_id}, 'user_mobile',         '{$movil}'),
                (NULL, {$user_id}, 'user_phone',          '{$movil}'),
                (NULL, {$user_id}, 'user_gender',         '{$gender}'),
                (NULL, {$user_id}, 'user_country',        'México'),

                (NULL, {$user_id}, 'user_photo',          '{$user_photo}'),
                (NULL, {$user_id}, 'name_photo',          '{$name_photo}'),

                (NULL, {$user_id}, 'description',          ''),

                (NULL, {$user_id}, 'nickname',            '{$email}'),
                (NULL, {$user_id}, 'first_name',          '{$name}'),
                (NULL, {$user_id}, 'last_name',           '{$lastname}'),
                (NULL, {$user_id}, 'user_age',           '{$age}'),
                (NULL, {$user_id}, 'user_smoker',           '{$smoker}'),
                (NULL, {$user_id}, 'user_referred',       '{$referido}'),
                (NULL, {$user_id}, 'rich_editing',        'true'),
                (NULL, {$user_id}, 'comment_shortcuts',   'false'),
                (NULL, {$user_id}, 'admin_color',         'fresh'),
                (NULL, {$user_id}, 'use_ssl',             '0'),
                (NULL, {$user_id}, 'show_admin_bar_front', 'false'),
                (NULL, {$user_id}, 'wp_capabilities',     'a:1:{s:10:\"subscriber\";b:1;}'),
                (NULL, {$user_id}, 'wp_user_level',       '0');
        ";
        $db->multi_query( utf8_decode( $sql ) );

        if (!isset($_SESSION)) { session_start(); }
        $_SESSION["nuevo_registro"] = "YES";

        //MESSAGE
        $mail_file = realpath('../../template/mail/registro.php');

        $message_mail = file_get_contents($mail_file);

        $message_mail = str_replace('[name]', $name.' '.$lastname, $message_mail);
        $message_mail = str_replace('[email]', $email, $message_mail);
        $message_mail = str_replace('[pass]', $password, $message_mail);
        $message_mail = str_replace('[url]', site_url(), $message_mail);
        $message_mail = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $message_mail);

        $message = get_email_html($message_mail, false);
        wp_mail( $email, "Kmimos México Gracias por registrarte! Kmimos la NUEVA forma de cuidar a tu perro!", $message);

        //USER LOGIN
        $user = get_user_by( 'id', $user_id );
        wp_set_current_user($user_id, $user->user_login);
        wp_set_auth_cookie($user_id);

    }

    exit;

?>