<?php

/**********************************************************************************************************************************
*
* User Dashboard Actions
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/

//Get form type
global $current_user;
global $redirect_to;

if($redirect_to != '') {
    header('Location: '.$redirect);
}

if(isset($_GET['ua']) && $_GET['ua']!=''){
    $ua_action = esc_attr($_GET['ua']);
}
$setup4_membersettings_dashboard = PFSAIssetControl('setup4_membersettings_dashboard','','');

if(isset($ua_action)){
	$setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
	$pfmenu_perout = PFPermalinkCheck();

	if(is_user_logged_in()){

 		if($setup4_membersettings_dashboard != 0){

				if ($ua_action == 'profile') {
					get_template_part('admin/estatemanagement/includes/pages/dashboard/form','profilereq');
				}
				$setup3_pointposttype_pt1 = 'petsitters';
				$setup4_membersettings_paymentsystem = PFSAIssetControl('setup4_membersettings_paymentsystem','','1');
				$setup4_submitpage_status_old = PFSAIssetControl('setup4_submitpage_status_old','','0');

				$current_user = wp_get_current_user();
				$user_id = $current_user->ID;

				if (is_page($setup4_membersettings_dashboard)) {

					
					/**
					*Start: Menu
					**/
						$sidebar_output = '';
						$item_count = $favorite_count = $review_count = 0;

						global $wpdb;

						$item_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM  $wpdb->postmeta AS pm INNER JOIN $wpdb->posts AS p ON p.ID=pm.post_id WHERE pm.meta_value = %s and pm.meta_key = %d and post_status = %s",$user_id,"owner_pet","publish")  );
						$item_count = (empty($item_count)) ? 0 : $item_count ;

						$favorite_count = pfcalculatefavs($user_id);

						/** Prepare Menu Output **/
						$setup4_membersettings_favorites = PFSAIssetControl('setup4_membersettings_favorites','','1');
						$setup11_reviewsystem_check = PFREVSIssetControl('setup11_reviewsystem_check','','0');
						$setup4_membersettings_frontend = PFSAIssetControl('setup4_membersettings_frontend','','0');
						$setup4_membersettings_loginregister = PFSAIssetControl('setup4_membersettings_loginregister','','1');
						

						$setup29_dashboard_contents_my_page_menuname = PFSAIssetControl('setup29_dashboard_contents_my_page_menuname','','');
						$setup29_dashboard_contents_inv_page_menuname = PFSAIssetControl('setup29_dashboard_contents_inv_page_menuname','','');
						$setup29_dashboard_contents_favs_page_menuname = PFSAIssetControl('setup29_dashboard_contents_favs_page_menuname','','');
						$setup29_dashboard_contents_profile_page_menuname = PFSAIssetControl('setup29_dashboard_contents_profile_page_menuname','','');
						$setup29_dashboard_contents_submit_page_menuname = PFSAIssetControl('setup29_dashboard_contents_submit_page_menuname','','');
						$setup29_dashboard_contents_rev_page_menuname = PFSAIssetControl('setup29_dashboard_contents_rev_page_menuname','','');
                        $setup29_dashboard_contents_back_end_menuname = 'Panel de Control';
                        $setup29_dashboard_contents_shop_menuname = 'Descripción del Cuidador';
                        $setup29_dashboard_contents_issues_menuname = 'Mis Asuntos Pendientes';
                        $setup29_dashboard_contents_vendor_shop_menuname = 'Mis Servicios';
                        $setup29_dashboard_contents_vendor_sales_menuname = 'Mis Ventas';
                        $setup29_dashboard_contents_vendor_purchases_menuname = 'Mis Compras';
                        $setup29_dashboard_contents_vendor_pictures_menuname= 'Mis Fotos';
                        $setup29_dashboard_contents_pets_list_menuname = 'Mis Mascotas';
                        $setup29_dashboard_contents_vendor_bookings_menuname = 'Mis Reservas';
						$setup29_dashboard_contents_caregiver_menuname = 'Mis Solicitudes';
						$setup_invoices_sh = PFASSIssetControl('setup_invoices_sh','','1');

						$pfmenu_output = '';

						$user_name_field = get_user_meta( $user_id, 'first_name', true ).' '.get_user_meta( $user_id, 'last_name', true );
						if ($user_name_field == ' ') {$user_name_field = $current_user->user_login;}

						$user_photo_field_output = kmimos_get_foto($user_id);
						$pfmenu_output .= '<li class="pf-dash-userprof"><img src="'.$user_photo_field_output.'" class="pf-dash-userphoto" style="width: 70px; height: 70px;"/><span class="pf-dash-usernamef">'.$user_name_field.'</span></li>';
                        
                        /*
                        *   Muestra botón para ver el perfíl del usuario
                        */
                        if($_GET['ua']=='profile'){
                            $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-406"></i> '. $setup29_dashboard_contents_profile_page_menuname.'</a></li>';
                        }
                        else {
                            $pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=profile"><i class="pfadmicon-glyph-406"></i> '. $setup29_dashboard_contents_profile_page_menuname.'</a></li>';
                        }
                        /*
                        *   Muestra botón para ver las mascotas del usuario
                        */
                        $pets = kmimos_get_my_pets($current_user->ID);
                        if ($_GET['ua']=='mypets'){
                            $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-871"></i> '. $setup29_dashboard_contents_pets_list_menuname.'</li>';
                        }
                        else {
                            $class = ($_GET['ua']=='mypet' || $_GET['ua']=='newpet')? ' class="selected_option"':'';
                            $pfmenu_output .= '<li'.$class.'><a  href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mypets"><i class="pfadmicon-glyph-871"></i> '. $setup29_dashboard_contents_pets_list_menuname.'</a></li>';
                        }
                        /*
                        *   Muestra botón para ver los cuidadores favoritos del usuario
                        */
                        if($_GET['ua']=='favorites'){
                            $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-375"></i> '. $setup29_dashboard_contents_favs_page_menuname.'</a></li>';
                        }
                        else {
                            $class = ($_GET['ua']=='myfavorite')? ' class="selected_option"':'';
                            $pfmenu_output .= '<li'.$class.'><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=favorites"><i class="pfadmicon-glyph-375"></i> '. $setup29_dashboard_contents_favs_page_menuname.'</a></li>';
                        }
                        /*
                        *   Muestra botón para ver las compras del usuario
                        */
                        if($_GET['ua']=='invoices'){
                            $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-33"></i>Historial</a></li>';
                        } else {
                            $class = ($_GET['ua']=='invoices')? ' class="selected_option"':'';
                            $pfmenu_output .= '<li'.$class.'><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=invoices"><i class="pfadmicon-glyph-33"></i>Historial</a></li>';
                        }

                        /*
                        *   Si el usuario es administrador muestra botón para acceder al back-panel
                        */
                        $user_info = get_userdata($current_user->ID);
                        $user_roles = $user_info->roles;

						//SOLICITUDES DE CONOCER AL CUIDADOR POR EL CLIENTE
						if(!in_array('vendor',$user_roles)){
							if($_GET['ua']=='caregiver'){
								$pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-33"></i>'.$setup29_dashboard_contents_caregiver_menuname.'</a></li>';
							} else {
								$class = ($_GET['ua']=='caregiver')? ' class="selected_option"':'';
								$pfmenu_output .= '<li'.$class.'><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=caregiver"><i class="pfadmicon-glyph-33"></i>'.$setup29_dashboard_contents_caregiver_menuname.'</a></li>';
							}
						}

                        if(current_user_can( 'manage_options' )){
                            $pfmenu_output .= '<li class="negative">ADMINISTRADOR</li>';
                            $pfmenu_output .= '<li><a href="'.get_home_url().'/wp-admin" target="_blank"><i class="pfadmicon-glyph-421"></i> '. $setup29_dashboard_contents_back_end_menuname.'</a></li>';
                        }
                        /* --- */
                        /*
                        *   Si el usuario es un Cuidador muestra las opciones de los cuidadores
                        */
                        if(in_array('vendor',$user_roles)){
                            /*
                            *   Muestra la portada de la tienda del cuidador
                            */
                            $pfmenu_output .= '<li class="negative">CUIDADOR</li>';
                            
                            if ($_GET['ua']=='myshop'){
                                $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-664"></i> '.$setup29_dashboard_contents_shop_menuname.'</li>';
                            } else {
                                $pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myshop"><i class="pfadmicon-glyph-664"></i> '. $setup29_dashboard_contents_shop_menuname.'</a></li>';
                            }
                           
                            /*
                            *   Muestra los servicios ofrecidos por el cuidador
                            */
                            if ($_GET['ua']=='myservices'){
                                $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-453"></i> '. $setup29_dashboard_contents_vendor_shop_menuname.'</li>';
                            }
                            else {
                                $class = ($_GET['ua']=='myservice' || $_GET['ua']=='newservice')? ' class="selected_option"':'';
                                $pfmenu_output .= '<li'.$class.'><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myservices"><i class="pfadmicon-glyph-453"></i> '. $setup29_dashboard_contents_vendor_shop_menuname.'</a></li>';
                            }
                            
                            /* 
                             * Muestra la galería de fotos del cuidador
                             * Italo Gallery
                             */
                            if ($_GET['ua']=='mypictures'){
                                $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-82"></i> '. $setup29_dashboard_contents_vendor_pictures_menuname.'</li>';
                            }
                            else {
                                $pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mypictures"><i class="pfadmicon-glyph-82"></i> '. $setup29_dashboard_contents_vendor_pictures_menuname.'</a></li>';
                            }
                            
                            
                            /*
                            *   Muestra el listado de las reservas activas del cuidador
                            */
                            if ($_GET['ua']=='mybookings'){
                                $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-28"></i> '. $setup29_dashboard_contents_vendor_bookings_menuname.'</li>';//<span class="pfbadge">'.$bookings['count'].'</span>
                            }
                            else {
                                $class = ($_GET['ua']=='mybooking')? ' class="selected_option"':'';
                                $pfmenu_output .= '<li'.$class.'><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mybookings"><i class="pfadmicon-glyph-28"></i> '. $setup29_dashboard_contents_vendor_bookings_menuname.'</a></li>';//<span class="pfbadge">'.$bookings['count'].'</span>
                            }

							//SOLICITUDES DE CONOCER AL CUIDADOR
							if($_GET['ua']=='caregiver'){
								$pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-33"></i>'.$setup29_dashboard_contents_caregiver_menuname.'</a></li>';
							} else {
								$class = ($_GET['ua']=='caregiver')? ' class="selected_option"':'';
								$pfmenu_output .= '<li'.$class.'><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=caregiver"><i class="pfadmicon-glyph-33"></i>'.$setup29_dashboard_contents_caregiver_menuname.'</a></li>';
							}
                            /*
                            *   Muestra el listado de las ventas del cuidador
                            */
  /*                          $sales_count = kmimos_get_my_sales_count($current_user->ID);
                            if ($_GET['ua']=='mysales'){
                                $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-265"></i> '. $setup29_dashboard_contents_vendor_sales_menuname.'<span class="pfbadge">'.$sales_count.'</span></li>';
                            }
                            else {
                                $pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mysales"><i class="pfadmicon-glyph-265"></i> '. $setup29_dashboard_contents_vendor_sales_menuname.'<span class="pfbadge">'.$sales_count.'</span></a></li>';
                            }
*/
                        } else {
                            /*
                            *   Si no es cuidador, muestra botón para postularse como cuidador
                            
                            if ($_GET['ua']=='newitem'){
                                $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-475"></i> '. $setup29_dashboard_contents_submit_page_menuname.'</a></li>';
                            }
                            else {
                                $pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=newitem"><i class="pfadmicon-glyph-475"></i> '. $setup29_dashboard_contents_submit_page_menuname.'</a></li>';
                            }*/
                        }
/*
						$pfmenu_output .= ($setup4_membersettings_frontend == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems"><i class="pfadmicon-glyph-460"></i> '. $setup29_dashboard_contents_my_page_menuname.'<span class="pfbadge">'.$item_count.'</span></a></li>' : '' ;

						$pfmenu_output .= ($setup4_membersettings_frontend == 1 && $setup_invoices_sh == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=invoices"><i class="pfadmicon-glyph-33"></i> '. $setup29_dashboard_contents_inv_page_menuname.'</a></li>' : '' ;
*/
						$pfmenu_output .= ($setup11_reviewsystem_check == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=reviews"><i class="pfadmicon-glyph-377"></i> '. $setup29_dashboard_contents_rev_page_menuname.'</a></li>' : '';
                        
						$pfmenu_output .= '<li><a href="'.esc_url(wp_logout_url( home_url() )).'"><i class="pfadmicon-glyph-476"></i> '. esc_html__('Cerrar Sesión','pointfindert2d').'</a></li>';
						
						
						$sidebar_output .= '
							<div class="pfuaformsidebar ">
							<ul class="pf-sidebar-menu">
								'.$pfmenu_output.'
							</ul>
							</div>

							<div class="sidebar-widget"></div>
						';
					/** 
					*End: Menu
					**/
					



					/**
					*Start: Page Start Actions / Divs etc...
					**/
						switch ($ua_action) {
							case 'purchaseplan':
								$case_text = 'purchaseplan';
							break;
							case 'renewplan':
								$case_text = 'renewplan';
							break;
							case 'upgradeplan':
								$case_text = 'upgradeplan';
							break;
							case 'profile':
								$case_text = 'profile';
							break;
							case 'favorites':
								$case_text = 'favs';
							break;
							case 'newitem':
							case 'edititem':
							case 'newpet':
							case 'editpet':
								$case_text = 'submit';
							break;
							case 'reviews':
								$case_text = 'rev';
							break;
							case 'mypets':
							break;
							case 'mybookings':
							case 'myservices':
							case 'myitems':
								$case_text = 'my';
							break;
							case 'invoices':
								$case_text = 'inv';
							break;
							default:
								$case_text = 'my';
							break;

						}

						if (!in_array($case_text, array('purchaseplan','renewplan','upgradeplan'))) {
						
							$setup29_dashboard_contents_my_page = PFSAIssetControl('setup29_dashboard_contents_'.$case_text.'_page','','');
							$setup29_dashboard_contents_my_page_pos = PFSAIssetControl('setup29_dashboard_contents_'.$case_text.'_page_pos','','1');
							$setup29_dashboard_contents_my_page_layout = PFSAIssetControl('setup29_dashboard_contents_profile_page_layout','','3');
							if ($ua_action == 'edititem') {
								$setup29_dashboard_contents_my_page_title = PFSAIssetControl('setup29_dashboard_contents_'.$case_text.'_page_titlee','','');
							}else{
								$setup29_dashboard_contents_my_page_title = PFSAIssetControl('setup29_dashboard_contents_'.$case_text.'_page_menuname','','');
							}
						}else{
							$setup29_dashboard_contents_my_page = PFSAIssetControl('setup29_dashboard_contents_submit_page','','');
							$setup29_dashboard_contents_my_page_layout = PFSAIssetControl('setup29_dashboard_contents_profile_page_layout','','3');
							$setup29_dashboard_contents_my_page_pos = PFSAIssetControl('setup29_dashboard_contents_submit_page_pos','','1');
							$membership_user_package_id = get_user_meta( $user_id, 'membership_user_package_id', true );

							switch ($case_text) {
								case 'purchaseplan':
									$setup29_dashboard_contents_my_page_title = esc_html__("Purchase New Plan","pointfindert2d" );
									break;
								
								case 'renewplan':
									if (!empty($membership_user_package_id)) {
										$setup29_dashboard_contents_my_page_title = esc_html__("Renew Current Plan","pointfindert2d" );
									}else{
										$setup29_dashboard_contents_my_page_title = esc_html__("Purchase New Plan","pointfindert2d" );
									}
									
									break;

								case 'upgradeplan':
									if (!empty($membership_user_package_id)) {
										$setup29_dashboard_contents_my_page_title = esc_html__("Upgrade Plan","pointfindert2d" );
									}else{
										$setup29_dashboard_contents_my_page_title = esc_html__("Purchase New Plan","pointfindert2d" );
									}
									
									break;
							}
						}
					
						$pf_ua_col_codes = '<div class="col-lg-9 col-md-9">';
						$pf_ua_col_close = '</div>';
						$pf_ua_prefix_codes = '<section role="main"><div class="pf-container clearfix"><div class="pf-row clearfix"><div class="pf-uadashboard-container clearfix">';
						$pf_ua_suffix_codes = '</div></div></div></section>';
						$pf_ua_sidebar_codes = '<div class="col-lg-3 col-md-3">';
						$pf_ua_sidebar_close = '</div>';
						

						PFGetHeaderBar('',$setup29_dashboard_contents_my_page_title);

						$content_of_section = '';
						if ($setup29_dashboard_contents_my_page != '') {	
							$content_of_section = do_shortcode(get_post_field( 'post_content', $setup29_dashboard_contents_my_page, 'raw' ));
						}
						if ($setup29_dashboard_contents_my_page_pos == 1 && $setup29_dashboard_contents_my_page != '') {
							echo $content_of_section;
						}


						switch($setup29_dashboard_contents_my_page_layout) {
							case '3':
							echo $pf_ua_prefix_codes.$pf_ua_col_codes;	
							break;
							case '2':
							echo $pf_ua_prefix_codes.$pf_ua_sidebar_codes.$sidebar_output;
							echo $pf_ua_sidebar_close.$pf_ua_col_codes;	
							break;
						}
					/**
					*End: Page Start Actions / Divs etc...
					**/

					
					get_template_part('admin/estatemanagement/includes/pages/dashboard/dashboard','frontend');
				
					$errorval = '';
					$sccval = '';

					switch ($ua_action) {

						case 'purchaseplan':
						case 'renewplan':
						case 'upgradeplan':
							/**
							*Start: Purchase/Renew/Upgrade Plan Page Content
							**/
								$output = new PF_Frontend_Fields(
										array(
											'formtype' => $ua_action,
											'current_user' => $user_id
										)
									);
								echo $output->FieldOutput;
								
								echo '<script type="text/javascript">
								(function($) {
									"use strict";
									$(function(){
									'.$output->ScriptOutput;
									echo '
									var pfsearchformerrors = $(".pfsearchformerrors");
										$("#pfuaprofileform").validate({
											  debug:false,
											  onfocus: false,
											  onfocusout: false,
											  onkeyup: false,
											  rules:{'.$output->VSORules.'},messages:{'.$output->VSOMessages.'},
											  ignore: ".select2-input, .select2-focusser, .pfignorevalidation",
											  validClass: "pfvalid",
											  errorClass: "pfnotvalid pfadmicon-glyph-858",
											  errorElement: "li",
											  errorContainer: pfsearchformerrors,
											  errorLabelContainer: $("ul", pfsearchformerrors),
											  invalidHandler: function(event, validator) {
												var errors = validator.numberOfInvalids();
												if (errors) {
													pfsearchformerrors.show("slide",{direction : "up"},100)
													$(".pfsearch-err-button").click(function(){
														pfsearchformerrors.hide("slide",{direction : "up"},100)
														return false;
													});
												}else{
													pfsearchformerrors.hide("fade",300)
												}
											  }
										});
									});'.$output->ScriptOutputDocReady;
								
								echo '	
								})(jQuery);
								</script>';
								unset($output);
							/**
							*End: Purchase/Renew/Upgrade Plan Page Content
							**/
							break;
                            
                        case 'myshop':

							if(isset($_POST) && $_POST!='' && count($_POST)>0){
								if (esc_attr($_POST['action']) == 'pfupdate_my_shop') {

									$nonce = esc_attr($_POST['security']);
									if ( ! wp_verify_nonce( $nonce, 'pfupdate_my_shop' ) ) {
										die( 'Security check' ); 
									}

									$vars = PFCleanArrayAttr('PFCleanFilters', $_POST);
								    
									if($user_id == 0){
										    $errorval .= esc_html__('Por favor inicie sesión (Usuario Inválido).', 'pointfindert2d');
                                        break;
								  	}else{
                                        
                                        extract($_POST);

								  		$mascotas_cuidador = array(
				                            'pequenos' => $tengo_pequenos, 
				                            'medianos' => $tengo_medianos, 
				                            'grandes'  => $tengo_grandes, 
				                            'gigantes' => $tengo_gigantes
				                        );
				                        $mascotas_cuidador = serialize($mascotas_cuidador);

								  		$tamanos_aceptados = array(
				                            'pequenos' => $acepta_pequenos, 
				                            'medianos' => $acepta_medianos, 
				                            'grandes'  => $acepta_grandes, 
				                            'gigantes' => $acepta_gigantes
				                        );
				                        $tamanos_aceptados = serialize($tamanos_aceptados);

								  		$edades_aceptadas = array(
				                            'cachorros' => $acepta_cachorros, 
				                            'adultos'	=> $acepta_adultos
				                        );
				                        $edades_aceptadas = serialize($edades_aceptadas);

				                        $comportamientos_aceptados = array(
				                            'sociables' 		  => $sociables, 
				                            'no_sociables'		  => $no_sociables,
				                            'agresivos_personas'	  => $agresivos_personas,
				                            'agresivos_perros'  => $agresivos_perros
				                        );
				                        $comportamientos_aceptados = serialize($comportamientos_aceptados);

				                        $ini_url = substr($video_youtube, 0, 5);
				                        if( $ini_url == 'https' ){
				                        	preg_match_all("#v=(.*?)&#", $video_youtube."&", $matches);
									  		if( count( $matches[0] ) > 0 ){
									  			$video_youtube = $matches[1][0];
									  		}else{
									  			preg_match_all("#be/(.*?)\?#", $video_youtube."?", $matches);
										  		$video_youtube = $matches[1][0];
									  		}
				                        }

				                        $atributos = array(
				                            'yard'	  		=> $yard,
				                            'green'		  	=> $green,
				                            'propiedad' 	=> $propiedad, 
				                            'esterilizado'  => $solo_esterilizadas,
				                            'emergencia' 	=> $emergencia,
				                            'video_youtube' => $video_youtube
				                        );
				                        $atributos = serialize($atributos);

								  		$sql = "
								  			UPDATE 
								  				cuidadores 
								  			SET 
								  				dni 				= '{$dni}',
								  				experiencia 		= '{$cuidando_desde}',
								  				direccion 			= '{$direccion}',
								  				check_in 			= '{$entrada}',
								  				check_out 			= '{$salida}',
								  				num_mascotas 		= '{$num_mascotas_casa}',
								  				mascotas_permitidas = '{$acepto_hasta}',
								  				latitud		 		= '{$latitude_petsitter}',
								  				longitud		 	= '{$longitude_petsitter}',
								  				mascotas_cuidador	= '{$mascotas_cuidador}',
								  				tamanos_aceptados	= '{$tamanos_aceptados}',
								  				edades_aceptadas	= '{$edades_aceptadas}',
								  				atributos			= '{$atributos}',
								  				comportamientos_aceptados	= '{$comportamientos_aceptados}'
								  			WHERE 
								  				id = {$id}";

						  				global $wpdb;

						  				$wpdb->query($sql);

								  		$sql = "
								  			UPDATE 
								  				ubicaciones 
								  			SET 
								  				estado 	   = '={$estado}=',
								  				municipios = '={$delegacion}='
								  			WHERE 
								  				cuidador = {$id}";

						  				$wpdb->query($sql);

                                        //UPDATE WC_META
                                        $query="SELECT * FROM wp_posts WHERE post_author='{$user_id}' AND post_type='product'";
                                        $result=$wpdb->get_results($query);
                                        //var_dump($result);

                                        foreach($result as $product){
                                            $product_id=$product->ID;
                                            update_post_meta($product_id, '_wc_booking_qty', $acepto_hasta);
                                            update_post_meta($product_id, '_wc_booking_max_persons_group', $acepto_hasta);
                                        }
                                    }
                                }
                            }
                        
                            $output = new PF_Frontend_Fields(
								array(
									'formtype' => 'myshop',
									'current_user' => $user_id
								)
							);

							echo $output->FieldOutput;
                            echo '<script type="text/javascript">
								'.$output->ScriptOutput.'
							</script>';
							unset($output);

							if( count($_POST) > 0){ header("location: ?ua=myshop"); }
								
                        break;							
                            
                        case 'myissues':
                            /**
                            *Start: My Pending Issues Page Content
                            **/
                            echo '<h1>Mis Asuntos Pendientes</h1><hr><br>';
								$output = new PF_Frontend_Fields(
									array(
										'formtype' => 'myissues',
										'current_user' => $user_id,
                                        'detail_url' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myissue&id=',
										'count' => $issues['count'],
                                        'list' => $issues['list']
									)
								);
								echo $output->FieldOutput;
								echo '<script type="text/javascript">
								(function($) {
									"use strict";
									'.$output->ScriptOutput.'
								})(jQuery);</script>';
								unset($output);

                            /**
                            *End: My Pending Issues Page Content
                            **/
                            break;							
							
                        case 'myservices':
                            /**
                            *Start: My Services Page Content
                            **/
                            echo '<h1>Mis Servicios</h1><hr>';
                            $output = new PF_Frontend_Fields(
                                array(
                                    'formtype' => 'myservices',
                                    'current_user' => $user_id,
                                    'detail_url' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myservice&id=',
                                    'count' => $services['count'],
                                    'list' => $services['list']
                                )
                            );
                            echo $output->FieldOutput;
                            echo '<script type="text/javascript">
                            (function($) {
                                "use strict";
                                '.$output->ScriptOutput.'
                            })(jQuery);</script>';
                            unset($output);
                            /**
                            *End: My Services Page Content
                            **/
                            break;
							
                        case 'newservice':
                           /**
                            *Start: My New Pet Detail Page Content
                            **/
                            echo '<h1>Agregar Nuevo Servicio </h1><hr><br>';
                            if(isset($_POST) && $_POST!='' && count($_POST)>0){
                                if (esc_attr($_POST['action']) == 'pfadd_new_service') {

                                    $nonce = esc_attr($_POST['security']);
                                    if ( ! wp_verify_nonce( $nonce, 'pfadd_new_service' ) ) {
                                        die( 'Security check' ); 
                                    }

                                    $vars = $_POST;

                                    $vars = PFCleanArrayAttr('PFCleanFilters',$vars);

                                    if($user_id == 0){
                                        $errorval .= esc_html__('Por favor inicie sesión (Usuario Inválido).', 'pointfindert2d');
                                        break;
                                    }
                                }
                            }

                        break;

						case 'updateservices':
							include("./wp-content/themes/pointfinder/vlz/admin/procesar_mis_servicios.php");
						break;
                            
                        case 'mybookings':
                            /**
                            *Start: My Bookings Page Content
                            **/
        //                     echo '<h1 style="margin-top: 0px;">Mis Reservas Pendientes</h1><hr>';
								$output = new PF_Frontend_Fields(
									array(
										'formtype' => 'mybookings',
										'current_user' => $user_id,
                                        'detail_url' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mybooking&id=',
										'count' => $bookings['count'],
                                        'list' => $bookings['list']
									)
								);
								echo $output->FieldOutput;
								echo '<script type="text/javascript">
								(function($) {
									"use strict";
									'.$output->ScriptOutput.'
								})(jQuery);</script>';
								unset($output);

                            /**
                            *End: My Bookings Page Content
                            **/
                            break;
							
                        case 'mybooking':
                            /**
                            *Start: My Booking Detail Page Content
                            **/
                            echo '<h1>Mi Reserva #'.$_GET['id'].'</h1><hr><br>';

                            /**
                            *End: My Booking Detail Page Content
                            **/
                            break;
							
                         case 'mypicturesdel':
                         	try{
                         	if(isset($_GET['p'])){
                         		$fullpath = "wp-content/uploads/cuidadores/galerias/".$user_id."/".$_GET['p'];
                         		if(file_exists($fullpath)){
                         			unlink($fullpath);
                         			echo '<h2>Imagen Eliminada Satisfactoriamente</h2>';
                         		}
                         	}
                         	}catch(Exception $e){}
                 			echo '<br><a href="'.get_option( 'siteurl' ).'/perfil-usuario/?ua=mypictures">Mostrar Mis Fotos</a>"
                 			<br><small>Redireccionando a Mis Fotos</small>';
                            echo '<script>location.href="'.get_option('siteurl').'/perfil-usuario/?ua=mypictures";</script>';
                 			//@header('location:'.get_option( 'siteurl' ).'/perfil-usuario/?ua=mypictures');

                            break;

                        case 'mypictures':
                            /**
                            *Start: My Pictures Page Content
                            **/
                            echo '<h1>Mis Fotos</h1><hr><br>';
								$output = new PF_Frontend_Fields(
									array(
										'formtype' => 'mypictures',
										'current_user' => $user_id,
                                        'detail_url' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mypicture&id=',
										'count' => $pictures['count'],
                                        'list' => $pictures['list']
									)
									);
								echo $output->FieldOutput;
								echo '<script type="text/javascript">
								(function($) {
									"use strict";
									'.$output->ScriptOutput.'
								})(jQuery);</script>';
								unset($output);

                            /**
                            *End: My Pictures Page Content
                            **/
                            break;
							
							
                        case 'newpicture':
                           /**
                            *Start: My New Picture Detail Page Content
                            **/
                            echo '<h1>Agregar Nueva Foto </h1><hr><br>';
                            if(isset($_POST) && $_POST!='' && count($_POST)>0){
                                if (esc_attr($_POST['action']) == 'pfadd_new_picture') {
                                    $nonce = esc_attr($_POST['security']);
                                    if ( ! wp_verify_nonce( $nonce, 'pfadd_new_picture' ) ) {
                                        die( 'Security check' ); 
                                    }

                                    $vars = $_POST;

                                    $vars = PFCleanArrayAttr('PFCleanFilters',$vars);

                                    if($user_id == 0){
                                        $errorval .= esc_html__('Por favor inicie sesión (Usuario Inválido).', 'pointfindert2d');
                                        break;
                                    }
                                }
                            }
                         case 'mypicture':
                            /**
                            *Start: My Picture Detail Page Content
                            Italo Guardar picture
                            **/
                            if($ua_action=='mypicture') echo '<h1>Mi Foto #'.$_GET['num'].'</h1><hr><br>';
                            if(isset($_POST) && $_POST!='' && count($_POST)>0){
                                if (esc_attr($_POST['action']) == 'pfupdate_my_picture') {

                                    $nonce = esc_attr($_POST['security']);
                                    if ( ! wp_verify_nonce( $nonce, 'pfupdate_my_picture' ) ) {
                                        die( 'Security check' ); 
                                    }

                                    $vars = $_POST;

                                    $vars = PFCleanArrayAttr('PFCleanFilters',$vars);

                                    if($user_id == 0){
                                        $errorval .= esc_html__('Por favor inicie sesión (Usuario Inválido).', 'pointfindert2d');
                                        break;
                                    }
                                    else {
                                        $picture_id = ($_GET['id']!='')? $_POST['id']:0;
                                        /*
                                        *   Obtiene los datos del cuidador
                                        */
                                        $args = array(
                                            'posts_per_page' => 1,
                                            'post_type' =>'petsitters',
                                            'meta_key' => 'user_petsitter',
                                            'meta_value' => $user_id,
                                            'post_status' => 'publish'
                                        );
                                        $petsitter = get_posts( $args );
                                        $petsitter = $petsitter[0];
                                        $petsitter_id = $petsitter->ID;
                                        $petsitter_code = get_post_meta($petsitter_id,'code_petsitter',true);
                                        /*
                                        *   Obtiene la galería de fotos del cuidador
                                        */
                                        $carousel_petsitter = get_post_meta($petsitter_id,'carousel_petsitter',true);
                                        $gallery = explode(',',$carousel_petsitter);
                                        $photo_name = str_pad($petsitter_code,4,"0", STR_PAD_LEFT) ."_";
                                        if($picture_id=='0') {
                                            if($carousel_petsitter=='') {
                                                $gallery = array();
                                                $photo_name.= '001';
                                            }
                                            else $photo_name.= str_pad(count($gallery)+1,3,"0", STR_PAD_LEFT);
                                        }
                                        else $photo_name .= str_pad($picture_id,3,"0", STR_PAD_LEFT);
/*
print_r($gallery);
print_r($_FILES);
die("<br>Foto:".$photo_name.", Cuidador:".$petsitter_code.'('.$petsitter_id.')'.', Usuario:'.$user_id);
*/
                                        if ( isset($_FILES['petsitter_photo'])) {   
                                            if ( $_FILES['petsitter_photo']['size'] >0) { 
                                                $file = array(
                                                  'name'     => $_FILES['petsitter_photo']['name'],
                                                  'type'     => $_FILES['petsitter_photo']['type'],
                                                  'tmp_name' => $_FILES['petsitter_photo']['tmp_name'],
                                                  'error'    => $_FILES['petsitter_photo']['error'],
                                                  'size'     => $_FILES['petsitter_photo']['size']
                                                );
                                                $allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');

                                                if(!in_array($_FILES['petsitter_photo']['type'], $allowed_file_types)) { // wrong file type
                                                  $errorval .= esc_html__("Please upload a JPG, GIF, or PNG file.<br>",'pointfindert2d');
                                                }else{
                                                	$tmp_user_id = $user_id;
                                                    $_FILES = array("petsitter_photo" => $file);
                                                    foreach ($_FILES as $file => $array) {
                                                        //
                                                        //  Sube y procesa la imagen para que tenga 800px de ancho x 
                                                        //  600px de alto
                                                        $url_gallery = 'wp-content/uploads/cuidadores/galerias/'.$tmp_user_id.'/';
														if(!file_exists($url_gallery)){	
                                                        	mkdir($url_gallery, 0777, true);
                                                        	chown ( $url_gallery , 'www-data www-data' );
                                                        }

                                                    	$name_photo_gallery = "gallery_".md5($_FILES['petsitter_photo']['name']);
                            							$result = kmimos_upload_photo($name_photo_gallery, $url_gallery."/", "petsitter_photo", $_FILES );                                                       	

                                                        // upload(
                                                        // 	$name_photo_gallery, 
                                                        // 	'wp-content/uploads/cuidadores/galerias/'.$tmp_user_id."/", 
                                                        // 	'petsitter_photo',
                                                        // 	$user_id
                                                        // );

                                                        // Italo guardar imagen
                                                        $my_post = array(
                                                            'ID'           => $newuploadphoto,
                                                            'post_title'   => $photo_name,
                                                            'post_content' => '',
                                                        );
                                                        wp_update_post( $my_post );
                                                        //
                                                        //  Asocia la imagen subida al cuidador actual
                                                        //
                                                        if ( is_wp_error( $newuploadphoto ) ) {
                                                            echo esc_html__("Error al intentar subir foto.<br>",'pointfindert2d');
                                                        }
                                                        else {
                                                            $gallery[]=$newuploadphoto;
                                                            $gallery = implode(',',$gallery);
                                                            update_post_meta($petsitter_id, 'carousel_petsitter', $gallery);
                                                        }
                                                    }
                                                }
                                            }

                                            echo '<h3>Foto cargada satisfactoriamente</h3><br><small>Redireccionando a Mis Fotos</small>';
                                            echo '<script>location.href="'.get_option('siteurl').'/perfil-usuario/?ua=mypictures";</script>';
                                            header("Location: ?ua=mypictures");
                                            die();
                                        }
/*                                        if(isset($vars['deletephoto'])){
                                            if($vars['deletephoto'] == 1){
                                              if(wp_delete_attachment(get_post_meta( $petsitter_id, '_thumbnail_id',true ),true)){
                                                 update_post_meta($petsitter_id, '_thumbnail_id', '');
                                                 $newuploadphoto = get_template_directory_uri().'/images/noimg.png';
                                              }
                                            }
                                        }
*/
                                        /*
                                        if($newupload != '' && !isset($newuploadphoto)){
                                            $newuploadphoto = wp_get_attachment_image_src( $newupload );
                                            $newuploadphoto = $newuploadphoto[0];
                                        }else{
                                            if(!isset($newuploadphoto)){
                                              $newuploadphoto = '';
                                            }

                                        }
*/
                                        $sccval .= '<strong>'.esc_html__('Your update was successful.','pointfindert2d').'</strong>';

                                        
/*print_r ($precio);
echo '<br>Precio base:'.$precio_base.'<br>';
print_r ($variante);
die('FIN');
/*

\||/
/~~\
\oo/
<_\>
 \/ 
/  \
----
POST
Array
*/
                                    }
                                }
                            }
								$output = new PF_Frontend_Fields(
									array(
										'formtype' => 'mypicture',
										'current_user' => $user_id,
                                        'picture_id' => $_GET['id']
									)
									);
								echo $output->FieldOutput;
								echo '<script type="text/javascript">
								(function($) {
									"use strict";
									'.$output->ScriptOutput.'
								})(jQuery);</script>';
								unset($output);

                            /**
                            *End: My Picture Detail Page Content
                            **/
                            break;
							
                       case 'mysales':
                            /**
                            *Start: My Sales Page Content
                            **/
                            echo '<h1>Mis Ventas</h1><hr><br>';

                            /**
                            *End: My Sales Page Content
                            **/
                            break;
							
                        case 'mypets':
                            /**
                            *Start: My Pets Page Content
                            **/
                            echo '<h1>Mis Mascotas</h1><hr><br>';
                            echo '<p align="justify">En esta sección podrás identificar a las mascotas de tu propiedad.</p>';
                            echo '<p align="justify">Si piensas contratar un servicio a través de Kmimos, es importante que las identifiques ya que solo las identificadas en tu perfil estarán amparadas por la cobertura de servicios veterinarios Kmimos.</p>';
                            echo '<p align="justify">Si además te interesa formar parte de la familia de Cuidadores asociados a Kmimos, es importante también que tus mascotas estén identificadas. Muchas personas prefieren contratar a cuidadores que tengan perritos similares a los suyos, mientras que hay otros que buscan cuidadores que tengan mascotas de determinadas razas y tamaños.</p><br><hr>';
                            
                            $output = new PF_Frontend_Fields(
									array(
										'formtype' => 'mypets',
										'current_user' => $user_id,
                                        'detail_url' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mypet&id=',
										'count' => $pets['count'],
										'list' => $pets['list']
									)
									);
								echo $output->FieldOutput;
								echo '<script type="text/javascript">
								(function($) {
									"use strict";
									'.$output->ScriptOutput.'
								})(jQuery);</script>';
								unset($output);
                            
                            /**
                            *End: My Pets Page Content
                            **/
                            break;
                            
                        case 'delpet':
                            /**
                            *Start: My New Pet Detail Page Content
                            **/
                            if($_POST['confirm_delete']==1){
								if(isset($_POST) && $_POST!='' && count($_POST)>0){
									if (esc_attr($_POST['action']) == 'pfpet_delete_confirm') {

										$nonce = esc_attr($_POST['security']);
										if ( ! wp_verify_nonce( $nonce, 'pfpet_delete_confirm' ) ) {
											die( 'Security check' ); 
										}

										$vars = $_POST;
										
										$vars = PFCleanArrayAttr('PFCleanFilters',$vars);
									    
										if($user_id == 0){
 										    $errorval .= esc_html__('Por favor inicie sesión (Usuario Inválido).', 'pointfindert2d');
                                            break;
									  	}
									}
								}
                                /*
                                *   Borra la mascota de la lista del usuario
                                */
                                $pet_id = $_POST['pet_id'];
                                update_post_meta($pet_id, 'owner_pet', '', $user_id);
                                wp_trash_post( $pet_id  );
//die('Borrando mascota #'.$_POST['pet_id']);
                                echo '<h1>Mascota Borrada Satisfactoriamente</h1><hr><br>';
                                $output = new PF_Frontend_Fields(
                                    array(
                                        'formtype' => 'dirpets',
                                        'fields' => $vars,
                                        'current_user' => $user_id,
                                        'pet_id' => $pet_id
                                    )
                                );
                                echo $output->FieldOutput;
                                echo '<script type="text/javascript">
                                (function($) {
                                    "use strict";
                                    '.$output->ScriptOutput.'
                                })(jQuery);</script>';
                                unset($output);
                                break;
                            }
                            /**
                            *End: My Pets Page Content
                            **/
                            break;
                            
                        case 'newpet':
                            /**
                            *Start: My New Pet Detail Page Content
                            **/
                            echo '<h1>Agregar Nueva Mascota </h1><hr><br>';
								if(isset($_POST) && $_POST!='' && count($_POST)>0){
									if (esc_attr($_POST['action']) == 'pfadd_new_pet') {

										$nonce = esc_attr($_POST['security']);
										if ( ! wp_verify_nonce( $nonce, 'pfadd_new_pet' ) ) {
											die( 'Security check' ); 
										}

										$vars = $_POST;
										
										$vars = PFCleanArrayAttr('PFCleanFilters',$vars);
									    
										if($user_id == 0){
 										    $errorval .= esc_html__('Por favor inicie sesión (Usuario Inválido).', 'pointfindert2d');
                                            break;
									  	}
									}
								}
                        case 'mypet':
                            /**
                            *Start: My Pet Detail Page Content
                            **/
                            $pet_id = $_GET['id'];
                            if($ua_action=='mypet') echo '<h1 style="text-transform: capitalize;">Mi Mascota '.get_the_title($pet_id).'</h1><hr><br>';
								if(isset($_POST) && $_POST!='' && count($_POST)>0){
									if (esc_attr($_POST['action']) == 'pfupdate_my_pet') {

										$nonce = esc_attr($_POST['security']);
										if ( ! wp_verify_nonce( $nonce, 'pfupdate_my_pet' ) ) {
											die( 'Security check' ); 
										}

										$vars = $_POST;
										
										$vars = PFCleanArrayAttr('PFCleanFilters',$vars);
									    
										if($user_id == 0){
 										    $errorval .= esc_html__('Por favor inicie sesión (Usuario Inválido).', 'pointfindert2d');
                                            break;
									  	}
                                        else {
                                            if($_POST['delete_pet']==1){
                                                // Solicita la confirmación del borrado de la mascota
                                                $output = new PF_Frontend_Fields(
                                                    array(
                                                        'formtype' => 'delpet',
                                                        'fields' => $vars,
                                                        'current_user' => $user_id,
                                                        'pet_id' => $pet_id
                                                    )
                                                );
                                                echo $output->FieldOutput;
                                                echo '<script type="text/javascript">
                                                (function($) {
                                                    "use strict";
                                                    '.$output->ScriptOutput.'
                                                })(jQuery);</script>';
                                                unset($output);

                                                break;
                                                
                                            }
                                        /*
                                        *   Guarda los datos de la mascota
                                        */
//print_r ($_POST);
                                        // BEGIN Pets - Photo
                                        $photo_pet = "";
                                        if ( isset($_FILES['portada_pet'])) {   
                                            if ( $_FILES['portada_pet']['size'] >0) { 
                                                $file = array(
                                                  'name'     => $_FILES['portada_pet']['name'],
                                                  'type'     => $_FILES['portada_pet']['type'],
                                                  'tmp_name' => $_FILES['portada_pet']['tmp_name'],
                                                  'error'    => $_FILES['portada_pet']['error'],
                                                  'size'     => $_FILES['portada_pet']['size']
                                                );
                                                $allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');
                                                if(!in_array($_FILES['portada_pet']['type'], $allowed_file_types)) { // wrong file type
                                                  $errorval .= esc_html__("Please upload a JPG, GIF, or PNG file.<br>",'pointfindert2d');
                                                }else{
                                                	$tmp_user_id = $user_id;

                                                    $_FILES = array("portada_pet" => $file);
                                                    foreach ($_FILES as $file => $array) {
                                                        //  Sube y procesa la imagen para que tenga 800x600px
                                                        $ext = pathinfo($_FILES['portada_pet']['name'], PATHINFO_EXTENSION);
                                                        //$photo_name_pet = 'photo_'.md5( $_POST['pet_name'] ).'.'.$ext;

                                                        $url_pets = 'wp-content/uploads/mypet/'.$tmp_user_id.'/';
														if(!file_exists($url_pets)){	
                                                        	mkdir($url_pets, 0777, true);
                                                        }
                                                    	$photo_name_pet = "pet_".md5($_FILES['portada_pet']['name']);
                            							$photo = kmimos_upload_photo(
                            								$photo_name_pet, 
                            								$url_pets, 
                            								"portada_pet", 
                            								$_FILES 
                            							);
                                                    	// italo hoy
                                                        // upload(
                                                        // 	$photo_name_pet, 
                                                        // 	$url_pets, 
                                                        // 	'portada_pet',
                                                        // 	$tmp_user_id
                                                        // );
                                                    }
                                                }
                                            }
                                        }
                                        // END Pets - Photo

                                            $metas = array(
                                                'owner_pet' => $user_id,
                                                // foto pet
                                                //'foto_pet_file' => $photo_pet,
                                                'photo_pet' => $photo['path'], // "{$url_pets}{$photo_name_pet}",
                                                'name_pet' => wp_strip_all_tags( $_POST['pet_name'] ),
                                                'breed_pet' => wp_strip_all_tags( $_POST['pet_breed'] ),
                                                'colors_pet' => wp_strip_all_tags( $_POST['pet_colors'] ),
                                                'birthdate_pet' => wp_strip_all_tags( $_POST['pet_birthdate'] ),
                                                'size_pet' => $_POST['pet_size'],
                                                'gender_pet' => $_POST['pet_gender'],
                                                'pet_sterilized'=> $_POST['pet_sterilized'],
                                                'pet_sociable'=> $_POST['pet_sociable'],
                                                'aggressive_with_humans' => $_POST['aggresive_humans'],
                                                'aggressive_with_pets' => $_POST['aggresive_pets'],
                                                'about_pet' => wp_strip_all_tags( $_POST['pet_observations'] ),
                                            );
                                            $pet_id =($_POST['pet_id']=='')? 0: $_POST['pet_id'];
                                            $args = array(
                                                'ID'            => $pet_id,
                                                'post_title'    => wp_strip_all_tags( $_POST['pet_name'] ),
                                                'post_status'   => 'publish',
                                                'post_author'   => $user_id,
                                                'post_type'     => 'pets',
                                                'meta_input'    => $metas
                                            );
                                            $pet_id = wp_insert_post( $args );
                                            wp_set_post_terms($pet_id, $_POST['pet_type'],'pets-types',false);
                                            echo '<h1>Mascota Guardada Satisfactoriamente</h1><hr><br>';
                                            $output = new PF_Frontend_Fields(
                                                array(
                                                    'formtype' => 'dirpets',
                                                    'fields' => $vars,
                                                    'current_user' => $user_id,
                                                    'pet_id' => $pet_id
                                                )
                                            );
                                            echo $output->FieldOutput;
                                            echo '<script type="text/javascript">
                                            (function($) {
                                                "use strict";
                                                '.$output->ScriptOutput.'
                                            })(jQuery);</script>';
                                            unset($output);
                                            //Redireccion a mis mascotas Italo
		                                    wp_redirect( get_option( 'siteurl' ).'/perfil-usuario/?ua=mypets' );
                                            break;
                                        }
									}
								}

                                            $output = new PF_Frontend_Fields(
                                                array(
                                                    'formtype' => 'mypet',
                                                    'fields' => $vars,
                                                    'current_user' => $user_id,
                                                    'pet_id' => $pet_id
                                                )
                                            );
                                            echo $output->FieldOutput;
                                            echo '<script type="text/javascript">
                                            (function($) {
                                                "use strict";
                                                '.$output->ScriptOutput.'
                                            })(jQuery);</script>';
                                            unset($output);
/*
                                            break;
											
										}else{
										    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
									  	}
									}
								}*/

                            /**
                            *End: My Booking Detail Page Content
                            **/
                            break;

						case 'cancelreq':
							/**
							*Start: New Petsitter Page Content
							**/
								if(isset($_POST) && $_POST!='' && count($_POST)>0){
									if (esc_attr($_POST['action']) == 'pfbepetsitter_cancel_request') {

										$nonce = esc_attr($_POST['security']);
										if ( ! wp_verify_nonce( $nonce, 'pfbepetsitter_cancel_request' ) ) {
											die( 'Security check' ); 
										}

										$vars = $_POST;
										
										$vars = PFCleanArrayAttr('PFCleanFilters',$vars);
									    
										if($user_id == 0){
 										    $errorval .= esc_html__('Por favor inicie sesión (Usuario Inválido).', 'pointfindert2d');
                                            break;
									  	} else {
	                                        /*
	                                        *   solicita confirmación de la cancelación
	                                        */
										}
                                    } else if (esc_attr($_POST['action']) == 'pfbepetsitter_cancel_confirm') {

										$nonce = esc_attr($_POST['security']);
										if ( ! wp_verify_nonce( $nonce, 'pfbepetsitter_cancel_confirm' ) ) {
											die( 'Security check' ); 
										}

										$vars = $_POST;
										
										$vars = PFCleanArrayAttr('PFCleanFilters',$vars);
									    
										if($user_id == 0){
 										    $errorval .= esc_html__('Por favor inicie sesión (Usuario Inválido).', 'pointfindert2d');
                                            break;
									  	} else {
	                                        /*
	                                        *   Cancela la postulación y envía correo a los administradores
	                                        */

                                            if($_POST['confirm_cancel'] == 1){

                                            	$user = wp_get_current_user();
												$user_id = $user->ID;

												$query_postulaciones = new WP_Query( 
													array( 
														'author' => $user_id,
														'post_type' => 'postulation' 
													) 
												);

												$ID = $query_postulaciones->posts[0]->ID;
												
		                                        global $wpdb;

		                                        $wpdb->query("DELETE FROM $wpdb->posts WHERE ID='{$ID}'");
		                                        $wpdb->query("DELETE FROM $wpdb->postmeta WHERE post_id='{$ID}'");
		                                        $wpdb->query("DELETE FROM $wpdb->usermeta WHERE user_id='{$user_id}' AND meta_key='petsitter_postulation'");

		                                        echo "
													<script>
														alert('CANCELACIÓN REALIZADA');
														location.href = '".get_home_url()."/perfil-usuario/?ua=profile';
													</script>
												";

												die('CANCELACIÓN REALIZADA');
                                            }
										}
                                    }
                                }
								$output = new PF_Frontend_Fields(
									array(
										'formtype' => 'cancelreq',
										'current_user' => $user_id,
										'sccval' => $sccval,
										'errorval' => $errorval
									)
									);
								echo $output->FieldOutput;
								echo '<script type="text/javascript">
								(function($) {
									"use strict";
									'.$output->ScriptOutput.'
								})(jQuery);</script>';
								unset($output);
                            /**
                            *End: My Booking Detail Page Content
                            **/
                            break;

						case 'newitem':
							/**
							*Start: New Petsitter Page Content
							**/	
								$EC = is_cuidador();
								if( isset($_POST) && $_POST!='' && count($_POST)>0 && $EC == 3 ){
									if (esc_attr($_POST['action']) == 'pfbepetsitter_submit_request') {

										$nonce = esc_attr($_POST['security']);
										if ( ! wp_verify_nonce( $nonce, 'pfbepetsitter_submit_request' ) ) {
											die( 'Security check' ); 
										}

										$vars = $_POST;
										
										$vars = PFCleanArrayAttr('PFCleanFilters',$vars);
									    
										if($user_id == 0){
 										    $errorval .= esc_html__('Por favor inicie sesión (Usuario Inválido).', 'pointfindert2d');
                                            break;
									  	} else {
                                        /*
                                        *   Guarda la postulación y envía correo a los administradores
                                        */
                                            $first_name = wp_strip_all_tags( $_POST['first_name'] );
                                            $last_name = wp_strip_all_tags( $_POST['last_name'] );
                                            $mobile = wp_strip_all_tags( $_POST['mobile'] );
                                            $phone = wp_strip_all_tags( $_POST['phone'] );
                                            $email = wp_strip_all_tags( $_POST['email'] );
                                            $birthdate = wp_strip_all_tags( $_POST['birthdate'] );
                                            $gender = $_POST['gender'];
                                            $dni = wp_strip_all_tags( $_POST['dni'] );
                                            $startdate = wp_strip_all_tags( $_POST['startdate'] );
                                            $description = wp_strip_all_tags( $_POST['description'] );
                                            $metas = array(
                                                'first_name' => $first_name,
                                                'last_name' => $last_name,
                                                'mobile' => $mobile,
                                                'phone' => $phone,
                                                'email' => $email,
                                                'birthdate' => $birthdate,
                                                'gender' => $gender,
                                                'dni' => $dni,
                                                'startdate' => $startdate,
                                                'description' => $description,
                                                'status' => 0 
                                            );

                                            $title = 'Postulación de '.$first_name.' '.$last_name;
                                            $args = array(
                                                'ID'            => 0,
                                                'post_title'    => $title,
                                                'post_status'   => 'publish',
                                                'post_author'   => $user_id,
                                                'post_type'     => 'postulation',
                                                'meta_input'    => $metas
                                            );
                                            $postulation = wp_insert_post( $args );

                                            // Guarda en los datos del usuario el número de la postulación

                                            update_user_meta( $user_id, 'petsitter_postulation', $postulation );

                                            $post = get_post($postulation);
                                            $generos = array(1=>'Masculino', 2=>'Femenino');
                                            $admin_email = get_option( 'admin_email', 'false' );
                                            $subject = 'Nueva postulación como cuidador';
                                            $message = '<p><strong>Hola,</strong></p>';
                                            $message .= '<br>';
                                            $message .= '<p>Se ha registrado un nuevo cuidador, pendiente por tu aprobación</p>';
                                            $message .= '<br>';
                                            $message .= '<p>Número de Postulación: '.$postulation.'</p>';
                                            $message .= '<p>Nombres del Postulante: '.$first_name.'</p>';
                                            $message .= '<p>Apellidos del Postulante: '.$last_name.'</p>';
                                            $message .= '<p>Email del Postulante: '.$email.'</p>';
                                            $message .= '<p>Número de Teléfono: '.$phone.'</p>';
                                            $message .= '<p>Número de Celular: '.$mobile.'</p>';
                                            $message .= '<p>Número del DNI: '.$dni.'</p>';
                                            $message .= '<p>Fecha de nacimiento: '.$birthdate.'</p>';
                                            $message .= '<p>Género del postulante: '.$generos[$gender].'</p>';
                                            $message .= '<p>Cuida mascotas desde: '.$startdate.'</p>';
                                            $message .= '<p>Descripción: '.$description.'</p>';
                                            $message .= '<br>';
                                            $message .= '<p>Para ver más detalle de su perfil DAR CLICK <a href="'.get_home_url().'/wp-admin/post.php?post='.$postulation.'&action=edit">AQUÍ</a></p>';
                                            
                                            $message = kmimos_get_email_html($subject, $message, 'Saludos,', false);

                                            $to = array($admin_email,'soporte.kmimos@gmail.com');
                                            wp_mail($to,$subject,$message);

                                            /*
                                            *   Envía un correo al postulante
                                            */
                                            $message = '<p><strong>Hola '.$first_name.' '.$last_name.',</strong></p>';
                                            $message .= '<br>';
                                            $message .= '<p>Hemos recibido tu solicitud para convertirte en parte de la familia Kmimos.</p>';
                                            $message .= '<br>';
                                            $message .= '<p>Tu perfil como cuidador se mantendrá inactivo hasta que completes nuestro proceso de certificación y entrenamiento.   Por favor sigue los siguientes pasos para completar el proceso de certificación:</p>';
                                            $message .= '<br>';
                                            $message .= '<ol>';
                                            $message .= '<li>Recibirás dentro de las próximas 24 horas tus Pruebas Psicométricas y de Conocimientos Veterinarios Básicos a través de la siguiente dirección de  correo electrónico: certificación.kmimos@desdigitec.com.  (Recuerda revisar tu Inbox y bandeja de Spam o Correo No Deseado de manera diaria)</li>';
                                            $message .= '<li>El correo incluirá las credenciales y ligas a las que debes ir para realizar ambas pruebas.</li>';
                                            $message .= '<li>Una vez completadas ambas pruebas y validado tu perfil y documentos te notificaremos los resultados y siguientes pasos dentro de las siguientes 24 horas, a través de la dirección de correo electrónico certificación.kmimos@desdigitec.com</li>';
                                            $message .= '</ol>';
                                            $message .= '<br>';
                                            $subject='¡Gracias por unirte a nuestra familia Kmimos!';
                                            $message = kmimos_get_email_html($subject, $message, 'Bienvenidos a la familia,', true);
                                            $to = array($email,'soporte.kmimos@gmail.com');
                                            wp_mail($to,$subject,$message);
                                        }
									}

									header("location: ".get_home_url()."/perfil-usuario/?ua=newitem");
								}
								$output = new PF_Frontend_Fields(
									array(
										'formtype' => 'bepetsitter',
										'current_user' => $user_id,
										'sccval' => $sccval,
										'errorval' => $errorval
									)
									);
								echo $output->FieldOutput;
								echo '<script type="text/javascript">
								(function($) {
									"use strict";
									'.$output->ScriptOutput.'
								})(jQuery);</script>';
								unset($output);

							/**
							*End: New Petsitter Pet Page Content
							**/
							break;
							
						case 'edititem':

							/**
							*Start: New/Edit Item Page Content
							**/
								$confirmed_postid = '';
								$formtype = 'upload';
								$dontshowpage = 0;
								if ($ua_action == 'edititem') {
									if (!empty($_GET['i'])) {
										$edit_postid = (is_numeric($_GET['i']))? esc_attr($_GET['i']):'';
										if(!empty($edit_postid)){
											$result = $wpdb->get_results( $wpdb->prepare( 
												"
													SELECT ID, post_author
													FROM $wpdb->posts 
													WHERE ID = %s and post_author = %s and post_type = %s
												", 
												$edit_postid,
												$user_id,
												$setup3_pointposttype_pt1
											) );


											if (is_array($result) && count($result)>0) {

												if ($result[0]->ID == $edit_postid) {
													$confirmed_postid = $edit_postid;
													$formtype = 'edititem';
												}else{
													$dontshowpage = 1;
													$errorval .= esc_html__('This is not your item.','pointfindert2d');
												}
											}else{
												$dontshowpage = 1;
												$errorval .= esc_html__('This is not your item.','pointfindert2d');
											}
										}else{
											$dontshowpage = 1;
											$errorval .= esc_html__('Please select an item for edit.','pointfindert2d');
										}
									} else{
										$dontshowpage = 1;
										$errorval .= esc_html__('Please select an item for edit.','pointfindert2d');
									}
									
									
								}

								/**
								*Start : Item Image & Featured Image Delete (OLD Image Upload)
								**/
									if($formtype == 'edititem'){
										if(isset($_GET) && isset($_GET['action'])){
											if (esc_attr($_GET['action']) == 'delfimg' && $setup4_submitpage_status_old == 1) {
												wp_delete_attachment(get_post_thumbnail_id( $confirmed_postid ),true);
												delete_post_thumbnail( $confirmed_postid );
												$sccval .= esc_html__('Featured image removed. Redirecting to item details...','pointfindert2d');

										  		$output = new PF_Frontend_Fields(
													array(
														'formtype' => 'errorview',
														'sccval' => $sccval
														)
													);

												echo $output->FieldOutput;											
											  	
												echo '<script type="text/javascript">
													<!--
													window.location = "'.$setup4_membersettings_dashboard_link.'/?ua=edititem&i='.$confirmed_postid.'"
													//-->
													</script>';
												break;
											}elseif (esc_attr($_GET['action']) == 'delimg' && $setup4_submitpage_status_old == 1) {
												$delimg_id = '';
												$delimg_id = esc_attr($_GET['ii']);

												if($delimg_id != ''){
													delete_post_meta( $confirmed_postid, 'webbupointfinder_item_images', $delimg_id );
													if(isset($confirmed_postid)){
														wp_delete_attachment( $delimg_id, true );
													}

													$sccval .= esc_html__('Image removed. Redirecting item details...','pointfindert2d');

											  		$output = new PF_Frontend_Fields(
														array(
															'formtype' => 'errorview',
															'sccval' => $sccval
															)
														);

													echo $output->FieldOutput;											
												  	
													echo '<script type="text/javascript">
														<!--
														window.location = "'.$setup4_membersettings_dashboard_link.'/?ua=edititem&i='.$confirmed_postid.'"
														//-->
														</script>';
													break;
												}
											}
										}
									}
								/**
								*End : Item Image & Featured Image Delete (OLD Image Upload)
								**/								
							
								$output = new PF_Frontend_Fields(
									array(
										'fields'=>'', 
										'formtype' => $formtype,
										'sccval' => $sccval,
										'post_id' => $confirmed_postid,
										'errorval' => $errorval,
										'current_user' => $user_id,
										'dontshowpage' => $dontshowpage
										)
									);

								echo $output->FieldOutput;
								echo '<script type="text/javascript">
								(function($) {
									"use strict";
									$(function(){
									'.$output->ScriptOutput;
									echo '
									
									var pfsearchformerrors = $(".pfsearchformerrors");
										$("#pfuaprofileform").validate({
											  debug:false,
											  onfocus: false,
											  onfocusout: false,
											  onkeyup: false,
											  rules:{'.$output->VSORules.'},messages:{'.$output->VSOMessages.'},
											  ignore: ".select2-input, .select2-focusser, .pfignorevalidation",
											  validClass: "pfvalid",
											  errorClass: "pfnotvalid pfadmicon-glyph-858",
											  errorElement: "li",
											  errorContainer: pfsearchformerrors,
											  errorLabelContainer: $("ul", pfsearchformerrors),
											  invalidHandler: function(event, validator) {
												var errors = validator.numberOfInvalids();
												if (errors) {
													pfsearchformerrors.show("slide",{direction : "up"},100)
													$(".pfsearch-err-button").click(function(){
														pfsearchformerrors.hide("slide",{direction : "up"},100)
														return false;
													});
												}else{
													pfsearchformerrors.hide("fade",300)
												}
											  }
										});
									});'.$output->ScriptOutputDocReady;
								
								echo '	
								})(jQuery);
								</script>';
								unset($output);
							/**
							*End: New/Edit Item Page Content
							**/
							break;

						case 'myitems':
							

							/**
							*Start: My Items Form Request
							**/
								$redirectval = false;
								if(isset($_GET)){
									if (isset($_GET['action'])) {
										$action_ofpage = esc_attr($_GET['action']);
										

										/**
										* Process for Membership System
										**/

											/**
											*Start:Response Membership Package
											**/
												
												if ($action_ofpage == 'pf_recm') {

													
													if($user_id != 0){

														if (isset($_GET['token'])) {
															global $wpdb;

															/*Check token*/
															$order_post_id = $wpdb->get_var( $wpdb->prepare( 
																"SELECT post_id FROM $wpdb->postmeta WHERE meta_value = %s and meta_key = %s", 
																esc_attr($_GET['token']),
																'pointfinder_order_token'
															) );

															
															$package_post_id = $item_post_id = $wpdb->get_var( $wpdb->prepare(
																"SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s and post_id = %s", 
																'pointfinder_order_packageid',
																$order_post_id
															) );
										
															$result = $wpdb->get_results( $wpdb->prepare( 
																"SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s", 
																$package_post_id,
																$user_id,
																$setup3_pointposttype_pt1
															) );


															
															if (!empty($package_post_id) && !empty($order_post_id)) {	

																	$paypal_price_unit = PFSAIssetControl('setup20_paypalsettings_paypal_price_unit','','USD');
																	$paypal_sandbox = PFSAIssetControl('setup20_paypalsettings_paypal_sandbox','','0');
																	$paypal_api_user = PFSAIssetControl('setup20_paypalsettings_paypal_api_user','','');
																	$paypal_api_pwd = PFSAIssetControl('setup20_paypalsettings_paypal_api_pwd','','');
																	$paypal_api_signature = PFSAIssetControl('setup20_paypalsettings_paypal_api_signature','','2');

																	$setup20_paypalsettings_decimals = PFSAIssetControl('setup20_paypalsettings_decimals','','2');
																	$setup20_paypalsettings_decimalpoint = PFSAIssetControl('setup20_paypalsettings_decimalpoint','','.');
																	$setup20_paypalsettings_thousands = PFSAIssetControl('setup20_paypalsettings_thousands','',',');

																	$packageinfo = pointfinder_membership_package_details_get($package_post_id);
																	$apipackage_name = $packageinfo['webbupointfinder_mp_title'];
																	$infos = array();
																	$infos['USER'] = $paypal_api_user;
																	$infos['PWD'] = $paypal_api_pwd;
																	$infos['SIGNATURE'] = $paypal_api_signature;

																	if($paypal_sandbox == 1){$sandstatus = true;}else{$sandstatus = false;}
																	
																	$paypal = new Paypal($infos,$sandstatus);

																	$tokenparams = array(
																	   'TOKEN' => esc_attr($_GET['token']), 
																	);

																	$response = $paypal -> request('GetExpressCheckoutDetails',$tokenparams);
																	
																	

																	if (is_array($response)) {

																		if(isset($response['CHECKOUTSTATUS'])){

																			if($response['CHECKOUTSTATUS'] != 'PaymentActionCompleted'){
																				
																				/*Create a payment record for this process */
																				PF_CreatePaymentRecord(
																					array(
																						'user_id'	=>	$user_id,
																						'order_post_id'	=> $order_post_id,
																						'response'	=>	$response,
																						'token'	=>	$response['TOKEN'],
																						'payerid'	=>	$response['PAYERID'],
																						'processname'	=>	'GetExpressCheckoutDetails',
																						'status'	=>	$response['ACK'],
																						'membership' => 1
																						)
																				);

																				/*Check Payer id check for hack*/
																				if($response['ACK'] == 'Success' &&  esc_attr($_GET['PayerID'] == $response['PAYERID'])){

																					$setup20_paypalsettings_paypal_verified = PFSAIssetControl('setup20_paypalsettings_paypal_verified','','0');

																					if ($setup20_paypalsettings_paypal_verified == 1) {
																						if($response['PAYERSTATUS'] == 'verified'){
																							$work_status = 'accepted';
																						}else{
																							$work_status = 'declined';
																						}
																					}else{
																						$work_status = 'accepted';
																					}

																					if ($work_status == 'accepted') {

																						$process_type = (isset($response['DESC']))? $response['DESC']:'n';
																						$newpackage_id = (isset($response['PAYMENTREQUEST_0_NOTETEXT']))?$response['PAYMENTREQUEST_0_NOTETEXT']:0;
																						if (!empty($newpackage_id)) {
																							$packageinfo_n = pointfinder_membership_package_details_get($newpackage_id);
																						}else{$packageinfo_n = $packageinfo;}
																						$pointfinder_order_pricesign = esc_attr(get_post_meta( $order_post_id, 'pointfinder_order_pricesign', true ));
																						$pointfinder_order_listingtime = esc_attr(get_post_meta( $order_post_id, 'pointfinder_order_listingtime', true ));
																						$pointfinder_order_price = esc_attr(get_post_meta( $order_post_id, 'pointfinder_order_price', true ));
																						$pointfinder_order_recurring = esc_attr(get_post_meta( $order_post_id, 'pointfinder_order_recurring', true ));
																						$pointfinder_order_listingtime = ($pointfinder_order_listingtime == '') ? 0 : $pointfinder_order_listingtime ;
																						$pointfinder_order_listingpid = esc_attr(get_post_meta($order_post_id, 'pointfinder_order_listingpid', true ));	

																						if ($process_type == 'u') {
																							$total_package_price =  number_format($packageinfo_n['webbupointfinder_mp_price'], $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands);
																						} else {
																							$total_package_price =  number_format($packageinfo['webbupointfinder_mp_price'], $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands);
																						}
																						
																						$user_info = get_userdata( $user_id );
																						

																						$admin_email = get_option( 'admin_email' );
										 												$setup33_emailsettings_mainemail = PFMSIssetControl('setup33_emailsettings_mainemail','',$admin_email);

										 												

																						if ($pointfinder_order_recurring == 1) {
																							/**
																							*Start : Recurring Payment Process
																							**/
																								/** Express Checkout **/
																								$expresspay_paramsr = array(
																									'TOKEN' => $response['TOKEN'],
																									'PAYERID' => $response['PAYERID'],
																									'PAYMENTREQUEST_0_AMT' => $total_package_price,
																									'PAYMENTREQUEST_0_CURRENCYCODE' => $paypal_price_unit,
																									'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
																								);
																								
																								$response_expressr = $paypal -> request('DoExpressCheckoutPayment',$expresspay_paramsr);
																								
																								if (isset($response_expressr['TOKEN'])) {
																									$tokenr = $response_expressr['TOKEN'];
																								}else{
																									$tokenr = '';
																								}
																								/*Create a payment record for this process */
																								PF_CreatePaymentRecord(
																										array(
																										'user_id'	=>	$user_id,
																										'order_post_id'	=> $order_post_id,
																										'response'	=>	$response_expressr,
																										'token'	=>	$tokenr,
																										'processname'	=>	'DoExpressCheckoutPayment',
																										'status'	=>	$response_expressr['ACK'],
																										'membership' => 1
																										)
																									);

																								if($response_expressr['ACK'] == 'Success'){
																									
																									if(isset($response_expressr['PAYMENTINFO_0_PAYMENTSTATUS'])){
																										if ($response_expressr['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed') {	

																											switch ($process_type) {
																												case 'n':
																													$exp_date = strtotime("+".$packageinfo['webbupointfinder_mp_billing_period']." ".pointfinder_billing_timeunit_text_ex($packageinfo['webbupointfinder_mp_billing_time_unit'])."");
																													$app_date = strtotime("now");

																													update_post_meta( $order_post_id, 'pointfinder_order_expiredate', $exp_date);
																													update_post_meta( $order_post_id, 'pointfinder_order_datetime_approval', $app_date);
																													update_post_meta( $order_post_id, 'pointfinder_order_bankcheck', 0);
																													

																									                $wpdb->update($wpdb->posts,array('post_status'=>'completed'),array('ID'=>$order_post_id));

																									                /*Create User Limits*/
																									                update_user_meta( $user_id, 'membership_user_package_id', $packageinfo['webbupointfinder_mp_packageid']);
																									                update_user_meta( $user_id, 'membership_user_package', $packageinfo['webbupointfinder_mp_title']);
																									                update_user_meta( $user_id, 'membership_user_item_limit', $packageinfo['webbupointfinder_mp_itemnumber']);
																									                update_user_meta( $user_id, 'membership_user_featureditem_limit', $packageinfo['webbupointfinder_mp_fitemnumber']);
																									                update_user_meta( $user_id, 'membership_user_image_limit', $packageinfo['webbupointfinder_mp_images']);
																									                update_user_meta( $user_id, 'membership_user_trialperiod', 0);
																									                update_user_meta( $user_id, 'membership_user_recurring', 0);
																									                update_user_meta( $user_id, 'membership_user_activeorder', $order_post_id);

																									                /* Create an invoice for this */
																										              PF_CreateInvoice(
																										                array( 
																										                  'user_id' => $user_id,
																										                  'item_id' => 0,
																										                  'order_id' => $order_post_id,
																										                  'description' => $packageinfo['webbupointfinder_mp_title'],
																										                  'processname' => esc_html__('Paypal Payment','pointfindert2d'),
																										                  'amount' => $packageinfo['packageinfo_priceoutput_text'],
																										                  'datetime' => strtotime("now"),
																										                  'packageid' => $packageinfo['webbupointfinder_mp_packageid'],
																										                  'status' => 'publish'
																										                )
																										              );
																													break;

																												case 'u':
																													if (!empty($newpackage_id)) {
																														
																														$exp_date = pointfinder_reenable_expired_items(array('user_id'=>$user_id,'packageinfo'=>$packageinfo_n,'order_id'=>$order_post_id,'process'=>'u'));
																														$app_date = strtotime("now");

																														update_post_meta( $order_post_id, 'pointfinder_order_expiredate', $exp_date);
																														update_post_meta( $order_post_id, 'pointfinder_order_datetime_approval', $app_date);
																														update_post_meta( $order_post_id, 'pointfinder_order_bankcheck', 0);
																														update_post_meta( $order_post_id, 'pointfinder_order_packageid', $newpackage_id);

																										                $wpdb->update($wpdb->posts,array('post_status'=>'completed'),array('ID'=>$order_post_id));

																										                /* Start: Calculate item/featured item count and remove from new package. */
																									                        $total_icounts = pointfinder_membership_count_ui($user_id);

																									                        /*Count User's Items*/
																									                        $user_post_count = 0;
																									                        $user_post_count = $total_icounts['item_count'];

																									                        /*Count User's Featured Items*/
																									                        $users_post_featured = 0;
																									                        $users_post_featured = $total_icounts['fitem_count'];

																									                        if ($packageinfo_n['webbupointfinder_mp_itemnumber'] != -1) {
																									                          $new_item_limit = $packageinfo_n['webbupointfinder_mp_itemnumber'] - $user_post_count;
																									                        }else{
																									                          $new_item_limit = $packageinfo_n['webbupointfinder_mp_itemnumber'];
																									                        }
																									                        
																									                        $new_fitem_limit = $packageinfo_n['webbupointfinder_mp_fitemnumber'] - $users_post_featured;


																									                        /*Create User Limits*/
																									                        update_user_meta( $user_id, 'membership_user_package_id', $packageinfo_n['webbupointfinder_mp_packageid']);
																									                        update_user_meta( $user_id, 'membership_user_package', $packageinfo_n['webbupointfinder_mp_title']);
																									                        update_user_meta( $user_id, 'membership_user_item_limit', $new_item_limit);
																									                        update_user_meta( $user_id, 'membership_user_featureditem_limit', $new_fitem_limit);
																									                        update_user_meta( $user_id, 'membership_user_image_limit', $packageinfo_n['webbupointfinder_mp_images']);
																									                        update_user_meta( $user_id, 'membership_user_trialperiod', 0);
																									                        update_user_meta( $user_id, 'membership_user_activeorder', $order_post_id);
																									                        update_user_meta( $user_id, 'membership_user_recurring', 0);
																									                     /* End: Calculate new limits */

																									                     /* Create an invoice for this */
																											              PF_CreateInvoice(
																											                array( 
																											                  'user_id' => $user_id,
																											                  'item_id' => 0,
																											                  'order_id' => $order_post_id,
																											                  'description' => $packageinfo_n['webbupointfinder_mp_title'].'-'.esc_html__('Upgrade','pointfindert2d'),
																											                  'processname' => esc_html__('Paypal Payment','pointfindert2d'),
																											                  'amount' => $packageinfo_n['packageinfo_priceoutput_text'],
																											                  'datetime' => strtotime("now"),
																											                  'packageid' => $packageinfo_n['webbupointfinder_mp_packageid'],
																											                  'status' => 'publish'
																											                )
																											              );

																													}
																													
																													break;

																												case 'r':
																													$exp_date = pointfinder_reenable_expired_items(array('user_id'=>$user_id,'packageinfo'=>$packageinfo,'order_id'=>$order_post_id,'process'=>'r'));
																													$app_date = strtotime("now");

																													update_post_meta( $order_post_id, 'pointfinder_order_expiredate', $exp_date);
																													update_post_meta( $order_post_id, 'pointfinder_order_datetime_approval', $app_date);

																									                $wpdb->update($wpdb->posts,array('post_status'=>'completed'),array('ID'=>$order_post_id));

																									                /* Create an invoice for this */
																										              PF_CreateInvoice(
																										                array( 
																										                  'user_id' => $user_id,
																										                  'item_id' => 0,
																										                  'order_id' => $order_post_id,
																										                  'description' => $packageinfo['webbupointfinder_mp_title'].'-'.esc_html__('Renew','pointfindert2d'),
																										                  'processname' => esc_html__('Paypal Payment','pointfindert2d'),
																										                  'amount' => $packageinfo['packageinfo_priceoutput_text'],
																										                  'datetime' => strtotime("now"),
																										                  'packageid' => $packageinfo['webbupointfinder_mp_packageid'],
																										                  'status' => 'publish'
																										                )
																										              );

																													break;
																											}
																										}
																									}
																									
																									if ($process_type == 'u') {
																										pointfinder_mailsystem_mailsender(
																											array(
																												'toemail' => $user_info->user_email,
																										        'predefined' => 'paymentcompletedmember',
																										        'data' => array('paymenttotal' => $packageinfo_n['packageinfo_priceoutput_text'],'packagename' => $packageinfo_n['webbupointfinder_mp_title']),
																												)
																											);

																										pointfinder_mailsystem_mailsender(
																											array(
																												'toemail' => $setup33_emailsettings_mainemail,
																										        'predefined' => 'newpaymentreceivedmember',
																										        'data' => array('ID' => $order_post_id,'paymenttotal' => $packageinfo_n['packageinfo_priceoutput_text'],'packagename' => $packageinfo_n['webbupointfinder_mp_title']),
																												)
																											);
																									}else{
																										pointfinder_mailsystem_mailsender(
																											array(
																												'toemail' => $user_info->user_email,
																										        'predefined' => 'paymentcompletedmember',
																										        'data' => array('paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],'packagename' => $apipackage_name),
																												)
																											);

																										pointfinder_mailsystem_mailsender(
																											array(
																												'toemail' => $setup33_emailsettings_mainemail,
																										        'predefined' => 'newpaymentreceivedmember',
																										        'data' => array('ID' => $order_post_id,'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],'packagename' => $apipackage_name),
																												)
																											);
																									}
																									
																									
																									$sccval .= esc_html__('Thanks for your payment. Please wait while redirecting...','pointfindert2d');
																									$redirectval = true;
																									/*Start : Creating Recurring Payment*/
																									$timestamp_forprofile = strtotime("+".$packageinfo['webbupointfinder_mp_billing_period']." ".pointfinder_billing_timeunit_text_ex($packageinfo['webbupointfinder_mp_billing_time_unit'])."");
																									
																									$billing_description = sprintf(
																					                  esc_html__('%s / %s / Recurring: %s per %s','pointfindert2d'),
																					                  $packageinfo['webbupointfinder_mp_title'],
																					                  $packageinfo['packageinfo_itemnumber_output_text'].' '.esc_html__('Item','pointfindert2d'),
																					                  $packageinfo['packageinfo_priceoutput_text'],
																					                  $packageinfo['webbupointfinder_mp_billing_period'].' '.$packageinfo['webbupointfinder_mp_billing_time_unit_text']                 
																					                 );

																									$recurringpay_params = array(
																										'TOKEN' => $response_expressr['TOKEN'],
																										'PAYERID' => $response['PAYERID'],
																										'PROFILESTARTDATE' => date("Y-m-d\TH:i:s\Z",$timestamp_forprofile),
																										'DESC' => $billing_description,
																										'BILLINGPERIOD' => pointfinder_billing_timeunit_text_paypal($packageinfo['webbupointfinder_mp_billing_time_unit']),
																										'BILLINGFREQUENCY' => $packageinfo['webbupointfinder_mp_billing_period'],
																										'AMT' => $total_package_price,
																										'CURRENCYCODE' => $paypal_price_unit,
																										'MAXFAILEDPAYMENTS' => 1
																									);
																									
																									$item_arr_rec = array(
																									   'L_PAYMENTREQUEST_0_NAME0' => $packageinfo['webbupointfinder_mp_title'],
																									   'L_PAYMENTREQUEST_0_AMT0' => $total_package_price,
																									   'L_PAYMENTREQUEST_0_QTY0' => '1',
																									   //'L_PAYMENTREQUEST_0_ITEMCATEGORY0'	=> 'Digital',
																									);
																									
																									$response_recurring = $paypal -> request('CreateRecurringPaymentsProfile',$recurringpay_params,$item_arr_rec);
																									unset($paypal);
																									/*Create a payment record for this process */
																									PF_CreatePaymentRecord(
																											array(
																											'user_id'	=>	$user_id,
																											'order_post_id'	=> $order_post_id,
																											'response'	=>	$response_recurring,
																											'token'	=>	$response_expressr['TOKEN'],
																											'processname'	=>	'CreateRecurringPaymentsProfile',
																											'status'	=>	$response_recurring['ACK'],
																											'membership' => 1
																											)

																										);


																										if($response_recurring['ACK'] == 'Success'){
																											
																											update_post_meta($order_post_id, 'pointfinder_order_recurringid', $response_recurring['PROFILEID'] );
																											update_post_meta($order_post_id, 'pointfinder_order_recurring', 1 );
																											update_user_meta($user_id, 'membership_user_recurring', 1);

																											
																											pointfinder_mailsystem_mailsender(
																												array(
																													'toemail' => $user_info->user_email,
																											        'predefined' => 'recprofilecreatedmember',
																											        'data' => array('title'=>get_the_title($order_post_id),'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],'packagename' => $apipackage_name,'nextpayment' => date("Y-m-d", strtotime("+".$pointfinder_order_listingtime." days")),'profileid' => $response_recurring['PROFILEID']),
																													)
																												);

																											pointfinder_mailsystem_mailsender(
																												array(
																													'toemail' => $setup33_emailsettings_mainemail,
																											        'predefined' => 'recurringprofilecreatedmember',
																											        'data' => array(
																											        	'ID' => $user_id,
																											        	'title'=>get_the_title($order_post_id),
																											        	'orderid'=>$order_post_id,
																											        	'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],
																											        	'packagename' => $apipackage_name,
																											        	'nextpayment' => date("Y-m-d", strtotime("+".$pointfinder_order_listingtime." days")),
																											        	'profileid' => $response_recurring['PROFILEID']),
																													)
																												);
																											
																											$sccval .= esc_html__('Recurring payment profile created.','pointfindert2d');
																										}else{
																											
																											update_post_meta($order_post_id, 'pointfinder_order_recurring', 0 );	
																											$errorval .= esc_html__('Error: Recurring profile creation is failed. Recurring payment option cancelled.','pointfindert2d');
																										}
																										
																										/*End : Creating Recurring Payment*/
																										
																								}else{
																									
																									$errorval .= esc_html__('Sorry: The operation could not be completed. Recurring profile creation is failed and payment process could not completed.','pointfindert2d').'<br>';
																									if (isset($response_expressr['L_SHORTMESSAGE0'])) {
																										$errorval .= '<br>'.esc_html__('Paypal Message:','pointfindert2d').' '.$response_expressr['L_SHORTMESSAGE0'];
																									}
																									if (isset($response_expressr['L_LONGMESSAGE0'])) {
																										$errorval .= '<br>'.esc_html__('Paypal Message Details:','pointfindert2d').' '.$response_expressr['L_LONGMESSAGE0'];
																									}
																								}
																								
																								/** Express Checkout **/

																							/**
																							*End : Recurring Payment Process
																							**/
																						
																						}else{
																							/**
																							*Start : Express Payment Process
																							**/
																								
																								$expresspay_params = array(
																									'TOKEN' => $response['TOKEN'],
																									'PAYERID' => $response['PAYERID'],
																									'PAYMENTREQUEST_0_AMT' => $total_package_price,
																									'PAYMENTREQUEST_0_CURRENCYCODE' => $paypal_price_unit,
																									'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
																								);

																								$response_express = $paypal -> request('DoExpressCheckoutPayment',$expresspay_params);
																							
																								unset($paypal);

																								

																									
																								/*Create a payment record for this process */
																								if (isset($response_express['TOKEN'])) {
																									$token = $response_express['TOKEN'];
																								}else{
																									$token = '';
																								}
																								PF_CreatePaymentRecord(
																										array(
																										'user_id'	=>	$user_id,
																										'order_post_id'	=> $order_post_id,
																										'response'	=>	$response_express,
																										'token'	=>	$token,
																										'processname'	=>	'DoExpressCheckoutPayment',
																										'status'	=>	$response_express['ACK']
																										)
																									);
																							

																								if($response_express['ACK'] == 'Success'){
																									
																									if(isset($response_express['PAYMENTINFO_0_PAYMENTSTATUS'])){
																										if ($response_express['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed') {	
																											switch ($process_type) {
																												case 'n':
																													$exp_date = strtotime("+".$packageinfo['webbupointfinder_mp_billing_period']." ".pointfinder_billing_timeunit_text_ex($packageinfo['webbupointfinder_mp_billing_time_unit'])."");
																													$app_date = strtotime("now");

																													update_post_meta( $order_post_id, 'pointfinder_order_expiredate', $exp_date);
																													update_post_meta( $order_post_id, 'pointfinder_order_datetime_approval', $app_date);
																													update_post_meta( $order_post_id, 'pointfinder_order_bankcheck', 0);
																													

																									                $wpdb->update($wpdb->posts,array('post_status'=>'completed'),array('ID'=>$order_post_id));

																									                /*Create User Limits*/
																									                update_user_meta( $user_id, 'membership_user_package_id', $packageinfo['webbupointfinder_mp_packageid']);
																									                update_user_meta( $user_id, 'membership_user_package', $packageinfo['webbupointfinder_mp_title']);
																									                update_user_meta( $user_id, 'membership_user_item_limit', $packageinfo['webbupointfinder_mp_itemnumber']);
																									                update_user_meta( $user_id, 'membership_user_featureditem_limit', $packageinfo['webbupointfinder_mp_fitemnumber']);
																									                update_user_meta( $user_id, 'membership_user_image_limit', $packageinfo['webbupointfinder_mp_images']);
																									                update_user_meta( $user_id, 'membership_user_trialperiod', 0);
																									                update_user_meta( $user_id, 'membership_user_recurring', 0);
																									                update_user_meta( $user_id, 'membership_user_activeorder', $order_post_id);

																									                /* Create an invoice for this */
																										              PF_CreateInvoice(
																										                array( 
																										                  'user_id' => $user_id,
																										                  'item_id' => 0,
																										                  'order_id' => $order_post_id,
																										                  'description' => $packageinfo['webbupointfinder_mp_title'],
																										                  'processname' => esc_html__('Paypal Payment','pointfindert2d'),
																										                  'amount' => $packageinfo['packageinfo_priceoutput_text'],
																										                  'datetime' => strtotime("now"),
																										                  'packageid' => $packageinfo['webbupointfinder_mp_packageid'],
																										                  'status' => 'publish'
																										                )
																										              );
																													break;

																												case 'u':

																													if (!empty($newpackage_id)) {

																														$exp_date = pointfinder_reenable_expired_items(array('user_id'=>$user_id,'packageinfo'=>$packageinfo_n,'order_id'=>$order_post_id,'process'=>'u'));

																														$app_date = strtotime("now");

																														update_post_meta( $order_post_id, 'pointfinder_order_expiredate', $exp_date);
																														update_post_meta( $order_post_id, 'pointfinder_order_datetime_approval', $app_date);
																														update_post_meta( $order_post_id, 'pointfinder_order_bankcheck', 0);
																														update_post_meta( $order_post_id, 'pointfinder_order_packageid', $newpackage_id);

																										                $wpdb->update($wpdb->posts,array('post_status'=>'completed'),array('ID'=>$order_post_id));

																										                /* Start: Calculate item/featured item count and remove from new package. */
																									                        $total_icounts = pointfinder_membership_count_ui($user_id);

																									                        /*Count User's Items*/
																									                        $user_post_count = 0;
																									                        $user_post_count = $total_icounts['item_count'];

																									                        /*Count User's Featured Items*/
																									                        $users_post_featured = 0;
																									                        $users_post_featured = $total_icounts['fitem_count'];

																									                        if ($packageinfo_n['webbupointfinder_mp_itemnumber'] != -1) {
																									                          $new_item_limit = $packageinfo_n['webbupointfinder_mp_itemnumber'] - $user_post_count;
																									                        }else{
																									                          $new_item_limit = $packageinfo_n['webbupointfinder_mp_itemnumber'];
																									                        }
																									                        
																									                        $new_fitem_limit = $packageinfo_n['webbupointfinder_mp_fitemnumber'] - $users_post_featured;


																									                        /*Create User Limits*/
																									                        update_user_meta( $user_id, 'membership_user_package_id', $packageinfo_n['webbupointfinder_mp_packageid']);
																									                        update_user_meta( $user_id, 'membership_user_package', $packageinfo_n['webbupointfinder_mp_title']);
																									                        update_user_meta( $user_id, 'membership_user_item_limit', $new_item_limit);
																									                        update_user_meta( $user_id, 'membership_user_featureditem_limit', $new_fitem_limit);
																									                        update_user_meta( $user_id, 'membership_user_image_limit', $packageinfo_n['webbupointfinder_mp_images']);
																									                        update_user_meta( $user_id, 'membership_user_trialperiod', 0);
																									                        update_user_meta( $user_id, 'membership_user_activeorder', $order_post_id);
																									                        update_user_meta( $user_id, 'membership_user_recurring', 0);
																									                    /* End: Calculate new limits */

																									                    /* Create an invoice for this */
																											              PF_CreateInvoice(
																											                array( 
																											                  'user_id' => $user_id,
																											                  'item_id' => 0,
																											                  'order_id' => $order_post_id,
																											                  'description' => $packageinfo_n['webbupointfinder_mp_title'].'-'.esc_html__('Upgrade','pointfindert2d'),
																											                  'processname' => esc_html__('Paypal Payment','pointfindert2d'),
																											                  'amount' => $packageinfo_n['packageinfo_priceoutput_text'],
																											                  'datetime' => strtotime("now"),
																											                  'packageid' => $packageinfo_n['webbupointfinder_mp_packageid'],
																											                  'status' => 'publish'
																											                )
																											              );
																										              
																													}
																													break;

																												case 'r':
																													$exp_date = pointfinder_reenable_expired_items(array('user_id'=>$user_id,'packageinfo'=>$packageinfo,'order_id'=>$order_post_id,'process'=>'r'));
																													$app_date = strtotime("now");

																													update_post_meta( $order_post_id, 'pointfinder_order_expiredate', $exp_date);
																													update_post_meta( $order_post_id, 'pointfinder_order_datetime_approval', $app_date);

																									                $wpdb->update($wpdb->posts,array('post_status'=>'completed'),array('ID'=>$order_post_id));
																									                /* Create an invoice for this */
																										              PF_CreateInvoice(
																										                array( 
																										                  'user_id' => $user_id,
																										                  'item_id' => 0,
																										                  'order_id' => $order_post_id,
																										                  'description' => $packageinfo['webbupointfinder_mp_title'].'-'.esc_html__('Renew','pointfindert2d'),
																										                  'processname' => esc_html__('Paypal Payment','pointfindert2d'),
																										                  'amount' => $packageinfo['packageinfo_priceoutput_text'],
																										                  'datetime' => strtotime("now"),
																										                  'packageid' => $packageinfo['webbupointfinder_mp_packageid'],
																										                  'status' => 'publish'
																										                )
																										              );
																													break;
																											}
																										}
																									}
																									

																									if ($process_type == 'u') {
																										pointfinder_mailsystem_mailsender(
																											array(
																												'toemail' => $user_info->user_email,
																										        'predefined' => 'paymentcompletedmember',
																										        'data' => array('paymenttotal' => $packageinfo_n['packageinfo_priceoutput_text'],'packagename' => $packageinfo_n['webbupointfinder_mp_title']),
																												)
																											);

																										pointfinder_mailsystem_mailsender(
																											array(
																												'toemail' => $setup33_emailsettings_mainemail,
																										        'predefined' => 'newpaymentreceivedmember',
																										        'data' => array('ID' => $order_post_id,'paymenttotal' => $packageinfo_n['packageinfo_priceoutput_text'],'packagename' => $packageinfo_n['webbupointfinder_mp_title']),
																												)
																											);
																									}else{
																										pointfinder_mailsystem_mailsender(
																											array(
																												'toemail' => $user_info->user_email,
																										        'predefined' => 'paymentcompletedmember',
																										        'data' => array('paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],'packagename' => $apipackage_name),
																												)
																											);

																										pointfinder_mailsystem_mailsender(
																											array(
																												'toemail' => $setup33_emailsettings_mainemail,
																										        'predefined' => 'newpaymentreceivedmember',
																										        'data' => array('ID' => $order_post_id,'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],'packagename' => $apipackage_name),
																												)
																											);
																									}
																										

																									$sccval .= esc_html__('Thanks for your payment. Please wait while redirecting...','pointfindert2d');
																									$redirectval = true;
																								}else{
																									$errorval .= esc_html__('Sorry: The operation could not be completed. Payment is failed.','pointfindert2d').'<br>';
																									if (isset($response_express['L_SHORTMESSAGE0'])) {
																										$errorval .= '<br>'.esc_html__('Paypal Message:','pointfindert2d').' '.$response_express['L_SHORTMESSAGE0'];
																									}
																									if (isset($response_express['L_LONGMESSAGE0'])) {
																										$errorval .= '<br>'.esc_html__('Paypal Message Details:','pointfindert2d').' '.$response_express['L_LONGMESSAGE0'];
																									}
																								}
																								
																							/**
																							*End : Express Payment Process
																							**/
																						}
																					
																						

																					
																					}else{
																						$errorval .= esc_html__('Sorry: Our payment system only accepts verified Paypal Users. Payment is failed.','pointfindert2d');
																					}
																					
																				}else{
																					$errorval .= esc_html__('Can not get express checkout informations. Payment is failed.','pointfindert2d');
																				}
																			}elseif($response['CHECKOUTSTATUS'] == 'PaymentActionCompleted'){
																				$sccval .= esc_html__('Payment Completed.','pointfindert2d').'';
																			}else{
																				$errorval .= esc_html__('Response could not be received. Payment is failed.','pointfindert2d').'(1)';
																			}
																		}else{
																			$errorval .= esc_html__('Response could not be received. Payment is failed.','pointfindert2d').'(2)';
																		}

																	}else{
																		$errorval .= esc_html__('Response could not be received. Payment is failed.','pointfindert2d');
																	}

															}

														}else{
															$errorval .= esc_html__('Need token value.','pointfindert2d');
														}

													}else{
													    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
												  	}
												}
												
											/**
											*End:Response Membership Package
											**/

											/**
											*Start:Bank Transfer Membership
											**/
												
												if ($action_ofpage == 'pf_pay2m') {

													if($user_id != 0){
												        $order_post_id = get_user_meta( $user_id, 'membership_user_activeorder_ex', true );
														$sccval .= esc_html__('Bank Transfer Process; Waiting payment...','pointfindert2d');
													}else{
													    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
												  	}

												  	/**
													*Start: Bank Transfer Page Content
													**/
														$output = new PF_Frontend_Fields(
																array(
																	'formtype' => 'banktransfer',
																	'sccval' => $sccval,
																	'errorval' => $errorval,
																	'post_id' => get_the_title($order_post_id),

																)
															);
														echo $output->FieldOutput;
														break;
													/**
													*End: Bank Transfer Page Content
													**/
												}
											/**
											*End:Bank Transfer Membership
											**/

											/**
											*Start:Cancel Bank Transfer Membership
											**/
												
												if ($action_ofpage == 'cancelbankm') {

													if($user_id != 0){
												        $order_post_id = get_user_meta( $user_id, 'membership_user_activeorder_ex', true );

												        update_post_meta( $order_post_id, 'pointfinder_order_bankcheck', 0);

												        delete_user_meta($user_id, 'membership_user_package_id_ex');
										                delete_user_meta($user_id, 'membership_user_activeorder_ex');
										                delete_user_meta($user_id, 'membership_user_subaction_ex');
										                delete_user_meta($user_id, 'membership_user_invnum_ex');

										                PFCreateProcessRecord(
										                  array( 
										                    'user_id' => $user_id,
										                    'item_post_id' => $order_post_id,
										                    'processname' => esc_html__('Bank Transfer Cancelled by User','pointfindert2d'),
										                    'membership' => 1
										                    )
										                );

										                /*Create email record for this*/
														$user_info = get_userdata( $user_id );
														pointfinder_mailsystem_mailsender(
															array(
																'toemail' => $user_info->user_email,
														        'predefined' => 'bankpaymentcancelmember',
														        'data' => array('ID' => $order_post_id),
																)
															);

														$sccval .= esc_html__('Bank Transfer Cancelled. Redirecting...','pointfindert2d');
														$redirectval = true;
													}else{
													    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
												  	}

												}
											/**
											*End:Cancel Bank Transfer Membership
											**/

										/**
										* Process for Membership System
										**/






										/**
										* Process for Basic Listing
										**/

											/**
											*Start:Extend free listing
											**/
												if ($action_ofpage == 'pf_extend') {
													$stp31_userfree = PFSAIssetControl("stp31_userfree","","0");
													
													if ($stp31_userfree == 1) {
														if($user_id != 0){

															$item_post_id = (is_numeric($_GET['i']))? esc_attr($_GET['i']):'';

															if ($item_post_id != '') {

																/*Check if item user s item*/
																global $wpdb;
											
																$result = $wpdb->get_results( $wpdb->prepare( 
																	"SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s", 
																	$item_post_id,
																	$user_id,
																	$setup3_pointposttype_pt1
																) );


																
																if (is_array($result) && count($result)>0) {	
																	
																	if ($result[0]->ID == $item_post_id) {

																		/*Meta for order*/
																		global $wpdb;
																		$result_id = $wpdb->get_var( $wpdb->prepare(
																			"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s", 
																			'pointfinder_order_itemid',
																			$item_post_id
																		) );

																		$status_of_post = get_post_status($item_post_id);

																		$pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
																		if ($status_of_post == 'pendingpayment' && $pointfinder_order_price == 0) {
																			/*Extend listing*/
																			$pointfinder_order_listingtime = esc_attr(get_post_meta( $result_id, 'pointfinder_order_listingtime', true ));
																			

														        			$old_expire_date = get_post_meta( $result_id, 'pointfinder_order_expiredate', true);

														        			$exp_date = date("Y-m-d H:i:s",strtotime($old_expire_date .'+'.$pointfinder_order_listingtime.' day'));
																			$app_date = date("Y-m-d H:i:s");
																		
																			update_post_meta( $result_id, 'pointfinder_order_expiredate', $exp_date);
																			update_post_meta( $result_id, 'pointfinder_order_datetime_approval', $app_date);

																			$wpdb->update($wpdb->posts,array('post_status'=>'publish'),array('ID'=>$item_post_id));
																			$wpdb->update($wpdb->posts,array('post_status'=>'completed'),array('ID'=>$result_id));

																			PFCreateProcessRecord(
																				array( 
																		        'user_id' => $user_id,
																		        'item_post_id' => $item_post_id,
																				'processname' => sprintf(esc_html__('Expire date extended by User (Free Listing): (Order Date: %s / Expire Date: %s)','pointfindert2d'),
																					$app_date,
																					$exp_date
																					)
																			    )
																			);
																			$sccval .= esc_html__('Item expire date extended.','pointfindert2d');
																		}else{
																			$errorval .= esc_html__('Item could not extend.','pointfindert2d');
																		}

																		
																	}else{
																		$errorval .= esc_html__('Wrong item ID (It is not your item!). Payment process is stopped.','pointfindert2d');
																	}
																}
															}else{
																$errorval .= esc_html__('Wrong item ID.','pointfindert2d');
															}
														}else{
														    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
													  	}
													}
												}
											/**
											*End:Extend Free Listing
											**/


											/**
											*Start:Bank Transfer
											**/
												
												if ($action_ofpage == 'pf_pay2') {

													if($user_id != 0){

														$item_post_id = (is_numeric($_GET['i']))? esc_attr($_GET['i']):'';

														if ($item_post_id != '') {

															/*Check if item user s item*/
															global $wpdb;
										
															$result = $wpdb->get_results( $wpdb->prepare( 
																"SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s", 
																$item_post_id,
																$user_id,
																$setup3_pointposttype_pt1
															) );


															
															if (is_array($result) && count($result)>0) {	
																
																if ($result[0]->ID == $item_post_id) {

																	/*Meta for order*/
																	global $wpdb;
																	$result_id = $wpdb->get_var( $wpdb->prepare(
																		"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s", 
																		'pointfinder_order_itemid',
																		$item_post_id
																	) );

																	$pointfinder_order_recurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_recurring', true ));

																	$pointfinder_order_frecurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_frecurring', true ));

																	if($pointfinder_order_recurring != 1 && $pointfinder_order_frecurring != 1){
												
																		update_post_meta($result_id, 'pointfinder_order_bankcheck', '1');

																		

																		/*Create a payment record for this process */
																		PF_CreatePaymentRecord(
																			array(
																			'user_id'	=>	$user_id,
																			'item_post_id'	=>	$item_post_id,
																			'order_post_id'	=>	$result_id,
																			'processname'	=>	'BankTransfer',
																			)
																		);

																		/*Create email record for this*/
																		$user_info = get_userdata( $user_id );
																		$mail_item_title = get_the_title($item_post_id);

																		$setup20_paypalsettings_decimals = PFSAIssetControl('setup20_paypalsettings_decimals','','2');
																		$setup20_paypalsettings_decimalpoint = PFSAIssetControl('setup20_paypalsettings_decimalpoint','','.');
																		$setup20_paypalsettings_thousands = PFSAIssetControl('setup20_paypalsettings_thousands','',',');
																		$pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));

																		$total_package_price =  number_format($pointfinder_order_price, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands);

																		$pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpid', true ));	
																		$pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpname', true ));	

																		$paymentName = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindert2d'));
																	
																		$apipackage_name = $pointfinder_order_listingpname;


																		/* Create an invoice for this */
																		$invoice_id = PF_CreateInvoice(
																			array( 
																			  'user_id' => $user_id,
																			  'item_id' => $item_post_id,
																			  'order_id' => $result_id,
																			  'description' => $apipackage_name,
																			  'processname' => esc_html__('Bank Payment','pointfindert2d'),
																			  'amount' => $total_package_price,
																			  'datetime' => strtotime("now"),
																			  'packageid' => 0,
																			  'status' => 'pendingpayment'
																			)
																		);
																		update_post_meta($result_id, 'pointfinder_order_invoice', $invoice_id);

																		pointfinder_mailsystem_mailsender(
																			array(
																			'toemail' => $user_info->user_email,
																	        'predefined' => 'bankpaymentwaiting',
																	        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name),
																			)
																		);

																		$admin_email = get_option( 'admin_email' );
											 							$setup33_emailsettings_mainemail = PFMSIssetControl('setup33_emailsettings_mainemail','',$admin_email);
																		pointfinder_mailsystem_mailsender(
																			array(
																				'toemail' => $setup33_emailsettings_mainemail,
																		        'predefined' => 'newbankpreceived',
																		        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name),
																				)
																			);

																		$sccval .= esc_html__('Bank Transfer Process; Completed','pointfindert2d');
																	}else{
																		$errorval .= esc_html__('Recurring Payment Orders not accepted for bank transfer.','pointfindert2d');
																	}
																}else{
																	$errorval .= esc_html__('Wrong item ID (It is not your item!). Payment process is stopped.','pointfindert2d');
																}
															}
														}else{
															$errorval .= esc_html__('Wrong item ID.','pointfindert2d');
														}
													}else{
													    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
												  	}

												  	/**
													*Start: Bank Transfer Page Content
													**/

														$output = new PF_Frontend_Fields(
																array(
																	'formtype' => 'banktransfer',
																	'sccval' => $sccval,
																	'errorval' => $errorval,
																	'post_id' => $item_post_id
																)
															);
														echo $output->FieldOutput;
														break;
													/**
													*End: Bank Transfer Page Content
													**/
												}
											/**
											*End:Bank Transfer
											**/


											/**
											*Start:Cancel Bank Transfer
											**/
												
												if ($action_ofpage == 'pf_pay2c') {

													if($user_id != 0){

														$item_post_id = (is_numeric($_GET['i']))? esc_attr($_GET['i']):'';

														if ($item_post_id != '') {

															/*Check if item user s item*/
															global $wpdb;
										
															$result = $wpdb->get_results( $wpdb->prepare( 
																"SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s", 
																$item_post_id,
																$user_id,
																$setup3_pointposttype_pt1
															) );

															
															if (is_array($result) && count($result)>0) {	
																
																if ($result[0]->ID == $item_post_id) {

																	/*Meta for order*/
																	global $wpdb;
																	$result_id = $wpdb->get_var( $wpdb->prepare(
																		"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s", 
																		'pointfinder_order_itemid',
																		$item_post_id
																	) );

																	update_post_meta($result_id, 'pointfinder_order_bankcheck', '0');
																	delete_post_meta( $result_id, 'pointfinder_order_invoice');

																	/*Create a payment record for this process */
																	PF_CreatePaymentRecord(
																			array(
																			'user_id'	=>	$user_id,
																			'item_post_id'	=>	$item_post_id,
																			'order_post_id'	=>	$result_id,
																			'processname'	=>	'BankTransferCancel',
																			)
																		);

																	/*Create email record for this*/
																	$user_info = get_userdata( $user_id );
																	$mail_item_title = get_the_title($item_post_id);
																	pointfinder_mailsystem_mailsender(
																		array(
																			'toemail' => $user_info->user_email,
																	        'predefined' => 'bankpaymentcancel',
																	        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title),
																			)
																		);


																	$sccval .= esc_html__('Bank Transfer Process; Cancelled','pointfindert2d');

																}else{
																	$errorval .= esc_html__('Wrong item ID (It is not your item!). Payment process is stopped.','pointfindert2d');
																}
															}
														}else{
															$errorval .= esc_html__('Wrong item ID.','pointfindert2d');
														}
													}else{
													    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
												  	}

												  	
												}

											/**
											*End:Cancel Bank Transfer
											**/


											/**
											*Start:Response Basic Listing
											**/
												
												if ($action_ofpage == 'pf_rec') {

													
													if($user_id != 0){

														if (isset($_GET['token'])) {
															global $wpdb;
															$otype = 0;

															/*Check token*/
															$order_post_id = $wpdb->get_var( $wpdb->prepare( 
																"SELECT post_id FROM $wpdb->postmeta WHERE meta_value = %s and meta_key = %s", 
																esc_attr($_GET['token']),
																'pointfinder_order_token'
															) );
																/* Check if sub order */
																if (empty($order_post_id)) {
																	$order_post_id = $wpdb->get_var( $wpdb->prepare( 
																		"SELECT post_id FROM $wpdb->postmeta WHERE meta_value = %s and meta_key = %s", 
																		esc_attr($_GET['token']),
																		'pointfinder_sub_order_token'
																	) );
																	if (!empty($order_post_id)) {
																		$otype = 1;
																	}
																}

															
															$item_post_id = $wpdb->get_var( $wpdb->prepare(
																"SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s and post_id = %s", 
																'pointfinder_order_itemid',
																$order_post_id
															) );
										
															$result = $wpdb->get_results( $wpdb->prepare( 
																"SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s", 
																$item_post_id,
																$user_id,
																$setup3_pointposttype_pt1
															) );


															
															if (is_array($result) && count($result)>0) {	
																
																if ($result[0]->ID == $item_post_id) {
																

																	$paypal_price_unit = PFSAIssetControl('setup20_paypalsettings_paypal_price_unit','','USD');
																	$paypal_sandbox = PFSAIssetControl('setup20_paypalsettings_paypal_sandbox','','0');
																	$paypal_api_user = PFSAIssetControl('setup20_paypalsettings_paypal_api_user','','');
																	$paypal_api_pwd = PFSAIssetControl('setup20_paypalsettings_paypal_api_pwd','','');
																	$paypal_api_signature = PFSAIssetControl('setup20_paypalsettings_paypal_api_signature','','2');

																	$setup20_paypalsettings_decimals = PFSAIssetControl('setup20_paypalsettings_decimals','','2');
																	$setup20_paypalsettings_decimalpoint = PFSAIssetControl('setup20_paypalsettings_decimalpoint','','.');
																	$setup20_paypalsettings_thousands = PFSAIssetControl('setup20_paypalsettings_thousands','',',');

																	$infos = array();
																	$infos['USER'] = $paypal_api_user;
																	$infos['PWD'] = $paypal_api_pwd;
																	$infos['SIGNATURE'] = $paypal_api_signature;

																	if($paypal_sandbox == 1){$sandstatus = true;}else{$sandstatus = false;}
																	
																	$paypal = new Paypal($infos,$sandstatus);

																	$tokenparams = array(
																	   'TOKEN' => esc_attr($_GET['token']), 
																	);

																	$response = $paypal -> request('GetExpressCheckoutDetails',$tokenparams);
																	
																	
																	if (is_array($response)) {

																			if(isset($response['CHECKOUTSTATUS'])){

																				if($response['CHECKOUTSTATUS'] != 'PaymentActionCompleted'){
																					/*Create a payment record for this process */
																					PF_CreatePaymentRecord(
																						array(
																							'user_id'	=>	$user_id,
																							'item_post_id'	=>	$item_post_id,
																							'order_post_id'	=> $order_post_id,
																							'response'	=>	$response,
																							'token'	=>	$response['TOKEN'],
																							'payerid'	=>	$response['PAYERID'],
																							'processname'	=>	'GetExpressCheckoutDetails',
																							'status'	=>	$response['ACK']
																							)
																					);

																		
																					/*Check Payer id*/
																					if($response['ACK'] == 'Success' &&  esc_attr($_GET['PayerID'] == $response['PAYERID'])){

																						$setup20_paypalsettings_paypal_verified = PFSAIssetControl('setup20_paypalsettings_paypal_verified','','0');

																						if ($setup20_paypalsettings_paypal_verified == 1) {
																							if($response['PAYERSTATUS'] == 'verified'){
																								$work_status = 'accepted';
																							}else{
																								$work_status = 'declined';
																							}
																						}else{
																							$work_status = 'accepted';
																						}

																						if ($work_status == 'accepted') {
																							
																							$result_id = $order_post_id;

																							$pointfinder_sub_order_change = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_change', true ));
              
              																				if ($pointfinder_sub_order_change == 1 && $otype == 1 ) {

																								$pointfinder_order_pricesign = esc_attr(get_post_meta( $result_id, 'pointfinder_order_pricesign', true ));
																								$pointfinder_order_listingtime = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_listingtime', true ));
																								$pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_price', true ));
																								$pointfinder_order_listingtime = ($pointfinder_order_listingtime == '') ? 0 : $pointfinder_order_listingtime ;

																								$pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_sub_order_listingpid', true ));
																								$pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_sub_order_listingpname', true ));		


																								$total_package_price = number_format($pointfinder_order_price, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands);
																								
																								$paymentName = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindert2d'));

																								$apipackage_name = $pointfinder_order_listingpname. esc_html__('(Plan/Featured/Category Change)','pointfindert2d');

												 												/* Create an invoice for this */
																								PF_CreateInvoice(
																									array( 
																									  'user_id' => $user_id,
																									  'item_id' => $item_post_id,
																									  'order_id' => $result_id,
																									  'description' => $apipackage_name,
																									  'processname' => esc_html__('Paypal Payment','pointfindert2d'),
																									  'amount' => $total_package_price,
																									  'datetime' => strtotime("now"),
																									  'packageid' => 0,
																									  'status' => 'publish'
																									)
																								);

																								/**
																								*Start : Express Payment Process
																								**/
																									
																									$expresspay_params = array(
																										'TOKEN' => $response['TOKEN'],
																										'PAYERID' => $response['PAYERID'],
																										'PAYMENTREQUEST_0_AMT' => $total_package_price,
																										'PAYMENTREQUEST_0_CURRENCYCODE' => $paypal_price_unit,
																										'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
																									);
																									
																									$response_express = $paypal -> request('DoExpressCheckoutPayment',$expresspay_params);
																									/*print_r($response_express);*/
																									unset($paypal);

																										
																										/*Create a payment record for this process */
																										if (isset($response_express['TOKEN'])) {
																											$token = $response_express['TOKEN'];
																										}else{
																											$token = '';
																										}

																										
																										PF_CreatePaymentRecord(
																												array(
																												'user_id'	=>	$user_id,
																												'item_post_id'	=>	$item_post_id,
																												'order_post_id'	=> $order_post_id,
																												'response'	=>	$response_express,
																												'token'	=>	$token,
																												'processname'	=>	'DoExpressCheckoutPayment',
																												'status'	=>	$response_express['ACK']
																												)
																											);
																									

																										if($response_express['ACK'] == 'Success'){
																											
																											if(isset($response_express['PAYMENTINFO_0_PAYMENTSTATUS'])){
																												if ($response_express['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed') {						
																													$pointfinder_sub_order_changedvals = get_post_meta( $order_post_id, 'pointfinder_sub_order_changedvals', true );
																													
																													pointfinder_additional_orders(
																														array(
																															'changedvals' => $pointfinder_sub_order_changedvals,
																															'order_id' => $order_post_id,
																															'post_id' => $item_post_id
																														)
																													);
																												}
																											}
																											$sccval .= esc_html__('Thanks for your payment. All changes completed.','pointfindert2d');
																											
																										}else{
																											$errorval .= esc_html__('Sorry: The operation could not be completed. Payment is failed.','pointfindert2d').'<br>';
																											if (isset($response_express['L_SHORTMESSAGE0'])) {
																												$errorval .= '<br>'.esc_html__('Paypal Message:','pointfindert2d').' '.$response_express['L_SHORTMESSAGE0'];
																											}
																											if (isset($response_express['L_LONGMESSAGE0'])) {
																												$errorval .= '<br>'.esc_html__('Paypal Message Details:','pointfindert2d').' '.$response_express['L_LONGMESSAGE0'];
																											}
																										}
																									
																								/**
																								*End : Express Payment Process
																								**/

																							}else{
																								$pointfinder_order_pricesign = esc_attr(get_post_meta( $result_id, 'pointfinder_order_pricesign', true ));
																								$pointfinder_order_listingtime = esc_attr(get_post_meta( $result_id, 'pointfinder_order_listingtime', true ));
																								$pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
																								$pointfinder_order_recurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_recurring', true ));
																								$pointfinder_order_listingtime = ($pointfinder_order_listingtime == '') ? 0 : $pointfinder_order_listingtime ;

																								$pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpid', true ));
																								$pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpname', true ));		


																								$total_package_price = number_format($pointfinder_order_price, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands);
																								
																								$paymentName = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindert2d'));

																								$apipackage_name = $pointfinder_order_listingpname;

																								$setup31_userlimits_userpublish = PFSAIssetControl('setup31_userlimits_userpublish','','0');
																								$publishstatus = ($setup31_userlimits_userpublish == 1) ? 'publish' : 'pendingapproval' ;
																								
																								$user_info = get_userdata( $user_id );
																								$mail_item_title = get_the_title($item_post_id);

																								$admin_email = get_option( 'admin_email' );
												 												$setup33_emailsettings_mainemail = PFMSIssetControl('setup33_emailsettings_mainemail','',$admin_email);

												 												/* Create an invoice for this */
																								PF_CreateInvoice(
																									array( 
																									  'user_id' => $user_id,
																									  'item_id' => $item_post_id,
																									  'order_id' => $result_id,
																									  'description' => $apipackage_name,
																									  'processname' => esc_html__('Paypal Payment','pointfindert2d'),
																									  'amount' => $total_package_price,
																									  'datetime' => strtotime("now"),
																									  'packageid' => 0,
																									  'status' => 'publish'
																									)
																								);

																								if ($pointfinder_order_recurring == 1) {
																									/**
																									*Start : Recurring Payment Process
																									**/

																										

																										/** Express Checkout **/
																										$expresspay_paramsr = array(
																											'TOKEN' => $response['TOKEN'],
																											'PAYERID' => $response['PAYERID'],
																											'PAYMENTREQUEST_0_AMT' => $total_package_price,
																											'PAYMENTREQUEST_0_CURRENCYCODE' => $paypal_price_unit,
																											'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
																										);
																										
																										$response_expressr = $paypal -> request('DoExpressCheckoutPayment',$expresspay_paramsr);
																										
																										if (isset($response_expressr['TOKEN'])) {
																											$tokenr = $response_expressr['TOKEN'];
																										}else{
																											$tokenr = '';
																										}
																										/*Create a payment record for this process */
																										PF_CreatePaymentRecord(
																												array(
																												'user_id'	=>	$user_id,
																												'item_post_id'	=>	$item_post_id,
																												'order_post_id'	=> $order_post_id,
																												'response'	=>	$response_expressr,
																												'token'	=>	$tokenr,
																												'processname'	=>	'DoExpressCheckoutPayment',
																												'status'	=>	$response_expressr['ACK']
																												)
																											);
																									

																										if($response_expressr['ACK'] == 'Success'){
																											
																											if(isset($response_expressr['PAYMENTINFO_0_PAYMENTSTATUS'])){
																												if ($response_expressr['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed') {						
																														wp_update_post(array('ID' => $item_post_id,'post_status' => $publishstatus) );
																														wp_reset_postdata();
																														wp_update_post(array('ID' => $order_post_id,'post_status' => 'completed') );
																														wp_reset_postdata();

																														pointfinder_order_fallback_operations($order_post_id,$pointfinder_order_price);
																												}
																											}

																											pointfinder_mailsystem_mailsender(
																												array(
																													'toemail' => $user_info->user_email,
																											        'predefined' => 'paymentcompleted',
																											        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name),
																													)
																												);

																											pointfinder_mailsystem_mailsender(
																												array(
																													'toemail' => $setup33_emailsettings_mainemail,
																											        'predefined' => 'newpaymentreceived',
																											        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name),
																													)
																												);

																											$sccval .= sprintf(esc_html__('Thanks for your payment. %s Now please wait until our admin approve your payment and activate your item.','pointfindert2d'),'<br>');
																												
																												/*Start : Creating Recurring Payment*/

																												/* Added with v1.6.4 */
																								                $pointfinder_order_featured = esc_attr(get_post_meta($result_id, 'pointfinder_order_featured', true)); 
																								                if ($pointfinder_order_featured == 1) {
																								                  $setup31_userpayments_pricefeatured = PFSAIssetControl('setup31_userpayments_pricefeatured','','5');
																								                  $total_package_price_recurring = $total_package_price -  $setup31_userpayments_pricefeatured;
																								                }else{
																								                  $total_package_price_recurring = $total_package_price;
																								                }

																								                $total_package_price_recurring = number_format($total_package_price_recurring, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands);


																												$timestamp_forprofile = strtotime('+ '.$pointfinder_order_listingtime.' days');
																										
																												$recurringpay_params = array(
																													'TOKEN' => $response_expressr['TOKEN'],
																													'PAYERID' => $response['PAYERID'],
																													'PROFILESTARTDATE' => date("Y-m-d\TH:i:s\Z",$timestamp_forprofile),
																													'DESC' => sprintf(
																														esc_html__('%s / %s / Recurring: %s%s per %s days / For: (%s)','pointfindert2d'),
																														$paymentName,
																														$apipackage_name,
																														$total_package_price_recurring,
																														$pointfinder_order_pricesign,
																														$pointfinder_order_listingtime,
																														$item_post_id
																													),
																													'BILLINGPERIOD' => 'Day',
																													'BILLINGFREQUENCY' => $pointfinder_order_listingtime,
																													'AMT' => $total_package_price_recurring,
																													'CURRENCYCODE' => $paypal_price_unit,
																													'MAXFAILEDPAYMENTS' => 1
																												);
																												
																												$item_arr_rec = array(
																												   'L_PAYMENTREQUEST_0_NAME0' => $paymentName.' : '.$apipackage_name,
																												   'L_PAYMENTREQUEST_0_AMT0' => $total_package_price_recurring,
																												   'L_PAYMENTREQUEST_0_QTY0' => '1',
																												);


																												/*If featured package enabled create a profile for this package*/
																												if ($pointfinder_order_featured == 1) {

																														$stp31_daysfeatured = PFSAIssetControl('stp31_daysfeatured','','3');
																														$timestamp_forprofile_featured = strtotime('+ '.$stp31_daysfeatured.' days');
																														
																														$setup31_userpayments_pricefeatured = number_format($setup31_userpayments_pricefeatured, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands);

																														$recurringpay_params_featured = array(
																															'TOKEN' => $response_expressr['TOKEN'],
																															'PAYERID' => $response['PAYERID'],
																															'PROFILESTARTDATE' => date("Y-m-d\TH:i:s\Z",$timestamp_forprofile_featured),
																															'DESC' => sprintf(
																																esc_html__('%s / %s / Recurring: %s%s per %s days / For: (%s)','pointfindert2d'),
																																$paymentName,
																																esc_html__('Featured Point','pointfindert2d'),
																																$setup31_userpayments_pricefeatured,
																																$pointfinder_order_pricesign,
																																$stp31_daysfeatured,
																																$item_post_id
																															),
																															'BILLINGPERIOD' => 'Day',
																															'BILLINGFREQUENCY' => $stp31_daysfeatured,
																															'AMT' => $setup31_userpayments_pricefeatured,
																															'CURRENCYCODE' => $paypal_price_unit,
																															'MAXFAILEDPAYMENTS' => 1
																														);
																														if ($total_package_price_recurring > 0) {
																															$item_arr_rec_featured = array(
																															   'L_PAYMENTREQUEST_0_NAME1' => $paymentName.' : '.$apipackage_name,
																															   'L_PAYMENTREQUEST_0_AMT1' => $setup31_userpayments_pricefeatured,
																															   'L_PAYMENTREQUEST_0_QTY1' => '1',
																															);
																														}else{
																															$item_arr_rec_featured = array(
																															   'L_PAYMENTREQUEST_0_NAME0' => $paymentName.' : '.$apipackage_name,
																															   'L_PAYMENTREQUEST_0_AMT0' => $setup31_userpayments_pricefeatured,
																															   'L_PAYMENTREQUEST_0_QTY0' => '1',
																															);
																														}
																														
																														
																														$response_recurring_featured = $paypal -> request('CreateRecurringPaymentsProfile',$recurringpay_params_featured,$item_arr_rec_featured);
																														

																														/*Create a payment record for this process */
																														PF_CreatePaymentRecord(
																																array(
																																'user_id'	=>	$user_id,
																																'item_post_id'	=>	$item_post_id,
																																'order_post_id'	=> $order_post_id,
																																'response'	=>	$response_recurring_featured,
																																'token'	=>	$response_expressr['TOKEN'],
																																'processname'	=>	'CreateRecurringPaymentsProfile',
																																'status'	=>	$response_recurring_featured['ACK']
																																)

																															);

																														if($response_recurring_featured['ACK'] == 'Success'){
																															update_post_meta($order_post_id, 'pointfinder_order_frecurringid', $response_recurring_featured['PROFILEID'] );	

																															pointfinder_mailsystem_mailsender(
																																array(
																																	'toemail' => $user_info->user_email,
																															        'predefined' => 'recprofilecreated',
																															        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $setup31_userpayments_pricefeatured,'packagename' => esc_html__('Featured Point','pointfindert2d'),'nextpayment' => date("Y-m-d", strtotime("+".$stp31_daysfeatured." days")),'profileid' => $response_recurring_featured['PROFILEID']),
																																	)
																																);

																															pointfinder_mailsystem_mailsender(
																																array(
																																	'toemail' => $setup33_emailsettings_mainemail,
																															        'predefined' => 'recurringprofilecreated',
																															        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $setup31_userpayments_pricefeatured,'packagename' => esc_html__('Featured Point','pointfindert2d'),'nextpayment' => date("Y-m-d", strtotime("+".$stp31_daysfeatured." days")),'profileid' => $response_recurring_featured['PROFILEID']),
																																	)
																																);
																															$sccval .= '<br>'.esc_html__('Recurring payment profile created for Featured Point.','pointfindert2d');
																														}else{
																															update_post_meta($order_post_id, 'pointfinder_order_frecurring', 0 );
																															$errorval .= '<br>'.esc_html__('Error: Recurring profile creation is failed for Featured Point. Recurring payment option cancelled for featured point.','pointfindert2d');
																														}
																												}

																												if ($total_package_price_recurring > 0) {
																													$response_recurring = $paypal -> request('CreateRecurringPaymentsProfile',$recurringpay_params,$item_arr_rec);
																												
																													/*Create a payment record for this process */
																													PF_CreatePaymentRecord(
																															array(
																															'user_id'	=>	$user_id,
																															'item_post_id'	=>	$item_post_id,
																															'order_post_id'	=> $order_post_id,
																															'response'	=>	$response_recurring,
																															'token'	=>	$response_expressr['TOKEN'],
																															'processname'	=>	'CreateRecurringPaymentsProfile',
																															'status'	=>	$response_recurring['ACK']
																															)

																														);


																													if($response_recurring['ACK'] == 'Success'){
																														
																														update_post_meta($order_post_id, 'pointfinder_order_recurringid', $response_recurring['PROFILEID'] );	

																														pointfinder_mailsystem_mailsender(
																															array(
																																'toemail' => $user_info->user_email,
																														        'predefined' => 'recprofilecreated',
																														        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name,'nextpayment' => date("Y-m-d", strtotime("+".$pointfinder_order_listingtime." days")),'profileid' => $response_recurring['PROFILEID']),
																																)
																															);

																														pointfinder_mailsystem_mailsender(
																															array(
																																'toemail' => $setup33_emailsettings_mainemail,
																														        'predefined' => 'recurringprofilecreated',
																														        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name,'nextpayment' => date("Y-m-d", strtotime("+".$pointfinder_order_listingtime." days")),'profileid' => $response_recurring['PROFILEID']),
																																)
																															);

																														$sccval .= '<br>'.esc_html__('Recurring payment profile created for Listing.','pointfindert2d');
																													}else{
																														
																														update_post_meta($order_post_id, 'pointfinder_order_recurring', 0 );	
																														$errorval .= '<br>'.esc_html__('Error: Recurring profile creation is failed. Recurring payment option cancelled.','pointfindert2d');
																													}
																												}else{
																													update_post_meta($order_post_id, 'pointfinder_order_recurring', 0 );
																												}
																												unset($paypal);
																												
																												/*End : Creating Recurring Payment*/
																												
																										}else{
																											
																											$errorval .= '<br>'.esc_html__('Sorry: The operation could not be completed. Recurring profile creation is failed and payment process could not completed.','pointfindert2d').'<br>';
																											if (isset($response_expressr['L_SHORTMESSAGE0'])) {
																												$errorval .= '<br>'.esc_html__('Paypal Message:','pointfindert2d').' '.$response_expressr['L_SHORTMESSAGE0'];
																											}
																											if (isset($response_expressr['L_LONGMESSAGE0'])) {
																												$errorval .= '<br>'.esc_html__('Paypal Message Details:','pointfindert2d').' '.$response_expressr['L_LONGMESSAGE0'];
																											}
																										}
																										
																										/** Express Checkout **/

																									/**
																									*End : Recurring Payment Process
																									**/
																								
																								}else{
																									/**
																									*Start : Express Payment Process
																									**/
																										
																										$expresspay_params = array(
																											'TOKEN' => $response['TOKEN'],
																											'PAYERID' => $response['PAYERID'],
																											'PAYMENTREQUEST_0_AMT' => $total_package_price,
																											'PAYMENTREQUEST_0_CURRENCYCODE' => $paypal_price_unit,
																											'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
																										);
																										
																										$response_express = $paypal -> request('DoExpressCheckoutPayment',$expresspay_params);
																										/*print_r($response_express);*/
																										unset($paypal);

																											
																											/*Create a payment record for this process */
																											if (isset($response_express['TOKEN'])) {
																												$token = $response_express['TOKEN'];
																											}else{
																												$token = '';
																											}

																											
																											PF_CreatePaymentRecord(
																													array(
																													'user_id'	=>	$user_id,
																													'item_post_id'	=>	$item_post_id,
																													'order_post_id'	=> $order_post_id,
																													'response'	=>	$response_express,
																													'token'	=>	$token,
																													'processname'	=>	'DoExpressCheckoutPayment',
																													'status'	=>	$response_express['ACK']
																													)
																												);
																										

																											if($response_express['ACK'] == 'Success'){
																												
																												if(isset($response_express['PAYMENTINFO_0_PAYMENTSTATUS'])){
																													if ($response_express['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed') {						
																														wp_update_post(array('ID' => $item_post_id,'post_status' => $publishstatus) );
																														wp_reset_postdata();
																														wp_update_post(array('ID' => $order_post_id,'post_status' => 'completed') );
																														wp_reset_postdata();
																														
																														pointfinder_order_fallback_operations($order_post_id,$pointfinder_order_price);

																													}
																												}

																												pointfinder_mailsystem_mailsender(
																													array(
																														'toemail' => $user_info->user_email,
																												        'predefined' => 'paymentcompleted',
																												        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name),
																														)
																													);

																												pointfinder_mailsystem_mailsender(
																													array(
																														'toemail' => $setup33_emailsettings_mainemail,
																												        'predefined' => 'newpaymentreceived',
																												        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name),
																														)
																													);

																												$sccval .= esc_html__('Thanks for your payment. Now please wait until our system approve your payment and activate your item listing.','pointfindert2d');
																											}else{
																												$errorval .= esc_html__('Sorry: The operation could not be completed. Payment is failed.','pointfindert2d').'<br>';
																												if (isset($response_express['L_SHORTMESSAGE0'])) {
																													$errorval .= '<br>'.esc_html__('Paypal Message:','pointfindert2d').' '.$response_express['L_SHORTMESSAGE0'];
																												}
																												if (isset($response_express['L_LONGMESSAGE0'])) {
																													$errorval .= '<br>'.esc_html__('Paypal Message Details:','pointfindert2d').' '.$response_express['L_LONGMESSAGE0'];
																												}
																											}
																										
																									/**
																									*End : Express Payment Process
																									**/
																								}
										
																							}
																							
																						}else{
																							$errorval .= esc_html__('Sorry: Our payment system only accepts verified Paypal Users. Payment is failed.','pointfindert2d');
																						}
																						
																					}else{
																						$errorval .= esc_html__('Can not get express checkout informations. Payment is failed.','pointfindert2d');
																					}
																				}elseif($response['CHECKOUTSTATUS'] == 'PaymentActionCompleted'){
																					$sccval .= esc_html__('Payment Completed.','pointfindert2d').'';
																				}else{
																					$errorval .= esc_html__('Response could not be received. Payment is failed.','pointfindert2d').'(1)';
																				}
																			}else{
																				$errorval .= esc_html__('Response could not be received. Payment is failed.','pointfindert2d').'(2)';
																			}

																	}else{
																		$errorval .= esc_html__('Response could not be received. Payment is failed.','pointfindert2d');
																	}
																	

																}else{
																	$errorval .= esc_html__('Wrong item ID (It is not your item!). Payment process is stopped.','pointfindert2d');
																}
															}

														}else{
															$errorval .= esc_html__('Need token value.','pointfindert2d');
														}
														
														

													}else{
													    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
												  	}
												}
												
											/**
											*End:Response Basic Listing
											**/


											/**
											*Start:Cancel Basic Listing
											**/
											
												if ($action_ofpage == 'pf_cancel') {
													$returned_token = esc_attr($_GET['token']);
													if(!empty($returned_token)){
														/*Create a payment record for this process */
														PF_CreatePaymentRecord(
																array(
																'user_id'	=>	$user_id,
																'token'	=>	$returned_token,
																'processname'	=>	'CancelPayment'
																)
															);
													}

													$errorval .= esc_html__('Sale process cancelled.','pointfindert2d');
												}
												
											/**
											*End:Cancel Basic Listing
											**/

										/**
										* Process Basic Listing
										**/
									}
								}

								
								


								/**
								*Start: Refine Listing
								**/
									if(isset($_POST) && $_POST!='' && count($_POST)>0){

										if (esc_attr($_POST['action']) == 'pf_refineitemlist') {

											$nonce = esc_attr($_POST['security']);
											if ( ! wp_verify_nonce( $nonce, 'pf_refineitemlist' ) ) {
												die( 'Security check' ); 
											}

											$vars = $_POST;
											
											$vars = PFCleanArrayAttr('PFCleanFilters',$vars);
										    
											if($user_id != 0){

												$output = new PF_Frontend_Fields(
														array(
															'formtype' => 'myitems',
															'fields' => $vars,
														)
													);
												echo $output->FieldOutput;
												echo '<script type="text/javascript">
												(function($) {
													"use strict";
													'.$output->ScriptOutput.'
												})(jQuery);</script>';
												unset($output);
												break;
												
											}else{
											    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
										  	}
										}
									}
								/**
								*End: Refine Listing
								**/
							/**
							*End: My Items Form Request
							**/



							/**
							*Start: My Items Page Content
							**/

								$output = new PF_Frontend_Fields(
										array(
											'formtype' => 'myitems',
											'sccval' => $sccval,
											'errorval' => $errorval,
											'redirect' => $redirectval
										)
									);
								echo $output->FieldOutput;
								echo '<script type="text/javascript">
								(function($) {
									"use strict";
									'.$output->ScriptOutput.'
								})(jQuery);</script>';
								unset($output);

							/**
							*End: My Items Page Content
							**/
							break;

						case 'reviews':
							/**
							*Review Page Content
							**/
								$output = new PF_Frontend_Fields(
										array(
											'formtype' => 'reviews',
											'current_user' => $user_id
										)
									);
								echo $output->FieldOutput;
							/**
							*Review Page Content
							**/
							break;

						case 'profile':
							/**
							*Start: Profile Page Content
							**/
                            echo '<h1>Mi Perfil</h1><hr>';
								$output = new PF_Frontend_Fields(
									array(
										'formtype' => 'profile',
										'current_user' => $user_id,
										'sccval' => $sccval,
										'errorval' => $errorval
									)
									);
								echo $output->FieldOutput;
								echo '<script type="text/javascript">
								(function($) {
									"use strict";
									'.$output->ScriptOutput.'
								})(jQuery);</script>';
								unset($output);
							/**
							*End: Profile Page Content
							**/
							break;

						case 'favorites':

							/**
							*Favs Page Content
							**/
                            echo '<h1>Cuidadores Favoritos</h1><hr><br>';
                            echo "<p>A continuación te mostramos la lista de los cuidadores que haz marcado como tus favoritos</p><br>";
								if(isset($_POST) && $_POST!='' && count($_POST)>0){

									if (esc_attr($_POST['action']) == 'pf_refinefavlist') {

										$nonce = esc_attr($_POST['security']);
										if ( ! wp_verify_nonce( $nonce, 'pf_refinefavlist' ) ) {
											die( 'Security check' ); 
										}

										$vars = $_POST;
										
										$vars = PFCleanArrayAttr('PFCleanFilters',$vars);
									    
										if($user_id != 0){

											$output = new PF_Frontend_Fields(
													array(
														'formtype' => 'favorites',
														'fields' => $vars,
														'current_user' => $user_id
													)
												);
											echo $output->FieldOutput;
											echo '<script type="text/javascript">
											(function($) {
												"use strict";
												'.$output->ScriptOutput.'
											})(jQuery);</script>';
											unset($output);
											break;
											
										}else{
										    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
									  	}
									}
								}
							/**
							*Favs Page Content
							**/

							$output = new PF_Frontend_Fields(
										array(
											'formtype' => 'favorites',
											'current_user' => $user_id
										)
									);
								echo $output->FieldOutput;
							break;

						case 'invoices':
								/**
								*Invoices Page Content
								**/
	/*								$output = new PF_Frontend_Fields(
											array(
												'formtype' => 'invoices',
												'current_user' => $user_id
											)
										);
									echo $output->FieldOutput;*/
								// echo do_shortcode( '[woocommerce_my_account]' );
	                            
	       //                      echo '<script type="text/javascript">
	       //                      (function($) {
	       //                          "use strict";
	       //                          jQuery(".status-complete.tips").each(function(){
	       //                              var reserva = jQuery(this).parent().siblings(":first").html();
	       //                              jQuery(this).closest("td").next("td").prop("id","boton_"+reserva);
	       //                              var ajaxurl = "'.get_home_url().'/wp-content/plugins/kmimos/app-server.php";
	       //                              jQuery.post(ajaxurl,{action: "get-rating-status", booking: reserva },function(response){
	       //                                  console.log("Respuesta: "+response);
	       //                                  jQuery("#boton_"+reserva).html(response);
	       //                              });
	       //                          });
	       //                      })(jQuery);</script>';
	                            
								/**
								*Invoices Page Content
								**/

							//include("./wp-content/themes/pointfinder/vlz/admin/page_invoices.php");
							include("./wp-content/themes/pointfinder/vlz/admin/dashboard/frontend/invoices.php");
						break;

						case 'caregiver':
							include("./wp-content/themes/pointfinder/vlz/admin/dashboard/frontend/caregiver.php");
						break;

					}
					
					/**
					*Start: Page End Actions / Divs etc...
					**/
						switch($setup29_dashboard_contents_my_page_layout) {
							case '3':
							echo $pf_ua_col_close.$pf_ua_sidebar_codes.$sidebar_output;
							echo $pf_ua_sidebar_close.$pf_ua_suffix_codes;	
							break;
							case '2':
							echo $pf_ua_col_close.$pf_ua_suffix_codes;
							break;						
						}


						if ($setup29_dashboard_contents_my_page_pos == 0 && $setup29_dashboard_contents_my_page != '') {
							echo $content_of_section;
						}
					/**
					*End: Page End Actions / Divs etc...
					**/

				}
				/**
				*End: Member Page Actions
				**/
		}


	}else{
		
	   PFLoginWidget();
	}
}else{
	$content = get_the_content();
	if (!empty($setup4_membersettings_dashboard)) {
		if (is_page($setup4_membersettings_dashboard)) {
			$setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
			$pfmenu_perout = PFPermalinkCheck();
			pf_redirect(''.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=profile');
		}else{
			if(function_exists('PFGetHeaderBar')){
			  PFGetHeaderBar();
			}
			
			if (!has_shortcode( $content , 'vc_row' )) {
				echo '<div class="pf-blogpage-spacing pfb-top"></div>';
	            echo '<section role="main">';
	                echo '<div class="pf-container">';
	                    echo '<div class="pf-row">';
	                        echo '<div class="col-lg-12">';
	                            the_content();
	                        echo '</div>';
	                    echo '</div>';
	                echo '</div>';
	            echo '</section>';
	            echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';
			}else{
				the_content();
			}
		    
		}
	}else{
		if(function_exists('PFGetHeaderBar')){
		  PFGetHeaderBar();
		}
		if (!has_shortcode( $content , 'vc_row' )) {
			echo '<div class="pf-blogpage-spacing pfb-top"></div>';
	        echo '<section role="main">';
	            echo '<div class="pf-container">';
	                echo '<div class="800row">';
	                    echo '<div cla600col-lg-12">';
	                        the_content();
	                    echo '</div>';
	                echo '</div>';
	            echo '</div>';
	        echo '</section>';
	        echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';
		}else{
			the_content();
		}
	}
	
	
}


function resizeImage ($tmpname, $size, $save_dir, $save_name){
	$save_dir  .= ( substr( $save_dir, -1 ) != "/" ) ? "/" : "";
	$gis        = getimagesize( $tmpname ); 	    
	$type       = $gis[2]; 	    	    
	switch($type){
        case "1": $imorig = @imagecreatefromgif($tmpname); break;
        case "2": $imorig = @imagecreatefromjpeg($tmpname);break;
        case "3": $imorig = @imagecreatefrompng($tmpname); break; 	
        default:  $imorig = @imagecreatefromjpeg($tmpname);
    }
    $x = imagesx($imorig);
    $y = imagesy($imorig);

    $aw = 800;
    $ah = 600;
    $im = imagecreatetruecolor($aw,$ah);
    if (imagecopyresampled($im,$imorig , 0,0,0,0,$aw,$ah,$x,$y)){
    	if (imagejpeg($im, "{$save_dir}{$save_name}")){
    		return true;
    	}else{
    		return false;
    	}
    }
}



function upload($name, $path, $fieldName, $user_id){
	$fichero_subido = $path.$name;
	if (@move_uploaded_file($_FILES[$fieldName]['tmp_name'], $fichero_subido)) { 

        $aImage = @imageCreateFromJpeg( $fichero_subido );

        $nWidth  = 800;
        $nHeight = 600;

        $aSize = getImageSize( $fichero_subido );

        if( $aSize[0] > $aSize[1] ){
            $nHeight = round( ( $aSize[1] * $nWidth ) / $aSize[0] );
        }else{
            $nWidth = round( ( $aSize[0] * $nHeight ) / $aSize[1] );
        }

        $aThumb = imageCreateTrueColor( $nWidth, $nHeight );

        imageCopyResampled( $aThumb, $aImage, 0, 0, 0, 0, $nWidth, $nHeight, $aSize[0], $aSize[1] );

        imagejpeg( $aThumb, $fichero_subido );

        imageDestroy( $aImage );
        imageDestroy( $aThumb );

        //unlink($fichero_subido);

	}else{
		// echo "Fallido";
	}
}

// function upload($name, $path, $fieldName)
// {
// 	$result = false;
// 	// Temp 
// 	$fileTemp= $_FILES[$fieldName]['tmp_name'];
// 	$size = $_FILES[$fieldName]['size'];
// 	// $ext = pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION);
// 	// New 
// //		$save_name = "$name.{$ext}";
// 	$save_name = $name;
// 	$pathFile = $path.$save_name;
// 	if( $size <= 50000000 ){ // Size Max
// 		// Upload 
// 		if(move_uploaded_file($fileTemp, $pathFile)){ 
// 			if($resize > 0){
// 				resizeImage($pathFile, $size, $path, $save_name);
// 			}
// 			$result = true;	
// 		}
// 	}
// 	return $result;
// }

?>