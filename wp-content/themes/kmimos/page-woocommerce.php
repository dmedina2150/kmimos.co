<?php 
    /*
        Template Name: Woocommerce
    */

    wp_enqueue_style('producto', getTema()."/css/producto.css", array(), '1.0.0');
	wp_enqueue_style('producto_responsive', getTema()."/css/responsive/producto_responsive.css", array(), '1.0.0');

	wp_enqueue_script('producto', getTema()."/js/producto.js", array("jquery"), '1.0.0');
    wp_enqueue_script('check_in_out', getTema()."/js/fecha_check_in_out.js", array(), '1.0.0');

	wp_enqueue_script('openpay-v1', getTema()."/js/openpay.v1.min.js", array("jquery"), '1.0.0');
	wp_enqueue_script('openpay-data', getTema()."/js/openpay-data.v1.min.js", array("jquery", "openpay-v1"), '1.0.0');
    wp_enqueue_script('check_in_out', getTema()."/js/fecha_check_in_out.js", array(), '1.0.0');


	get_header();

		date_default_timezone_set('America/Bogota');

		if( !isset($_SESSION)){ session_start(); }
		
		global $wpdb;

		$post_id = vlz_get_page();

		$post = get_post( $post_id );

		$D = $wpdb;

		$id_user = get_current_user_id();

		$busqueda = getBusqueda();

		$servicio_id = $post_id;

		$hoy = date("Y-m-d");

		$cupos = $wpdb->get_results("SELECT * FROM cupos WHERE servicio = '{$servicio_id}' AND fecha >= '".date("Y-m-d", time())."'" );

		$sql = "
	        SELECT
	            tipo_servicio.slug AS slug
	        FROM 
	            wp_term_relationships AS relacion
	        LEFT JOIN wp_terms as tipo_servicio ON ( tipo_servicio.term_id = relacion.term_taxonomy_id )
	        WHERE 
	            relacion.object_id = '{$servicio_id}' AND
	            relacion.term_taxonomy_id != 28
	    ";
		$tipo = $wpdb->get_var($sql);

		$cuidador = $wpdb->get_row( "SELECT * FROM cuidadores WHERE user_id = ".$post->post_author );

		$cuidador_name = $wpdb->get_var( "SELECT post_title FROM wp_posts WHERE ID = ".$cuidador->id_post );
		$servicio_name = $wpdb->get_var( "SELECT post_title FROM wp_posts WHERE ID = ".$servicio_id );

		$servicio_name_corto = explode(" - ", $servicio_name);
		$servicio_name_corto = $servicio_name_corto[0];

	    $precios = "";
	    
		$adicionales = unserialize($cuidador->adicionales);

		$precargas = array();
		$id_seccion = 'MR_'.get_the_ID()."_".md5($id_user);
        if( isset($_SESSION[$id_seccion] ) ){

        	$cupos_menos = $_SESSION[$id_seccion]["variaciones"]["cupos"];

        	$ini = strtotime( $_SESSION[$id_seccion]["fechas"]["inicio"] );
        	$fin = strtotime( $_SESSION[$id_seccion]["fechas"]["fin"] );

        	foreach ($cupos as $value) {
        		$xfecha = strtotime( $value->fecha );
        		if( $ini >= $xfecha && $xfecha <= $fin ){
        			$value->cupos -= $cupos_menos;
        			$value->full = 0;
        			$value->no_disponible = 0;
        		}
        	}

            $HTML .= "
                <a href='".getTema()."/procesos/perfil/update_reserva.php?b=".get_the_ID()."_".md5($id_user)."' class='theme_button btn_modificar'>
                    Salir de modificar reserva
                </a>
            ";

            $busqueda["checkin"] = date("d/m/Y", strtotime($_SESSION[$id_seccion]["fechas"]["inicio"]) );
            $busqueda["checkout"] = date("d/m/Y", strtotime($_SESSION[$id_seccion]["fechas"]["fin"]) );

            $precargas["tamanos"] = $_SESSION[$id_seccion]["variaciones"];
            if( isset($_SESSION[$id_seccion]["transporte"][0])){
            	$precargas["transp"] = $_SESSION[$id_seccion]["transporte"][0];
            }
            $precargas["adicionales"] = $_SESSION[$id_seccion]["adicionales"];
        }

	    if( $tipo == "hospedaje" ){
	    	$precios = getPrecios( unserialize($cuidador->hospedaje), $precargas["tamanos"], unserialize($cuidador->tamanos_aceptados) );
	    }else{
	    	$precios = getPrecios( $adicionales[$tipo], $precargas["tamanos"], unserialize($cuidador->tamanos_aceptados) );
	    } 

		$transporte = getTransporte($adicionales, $precargas["transp"]);
		if( $transporte != "" ){
			$transporte = '
				<div class="km-service-title"> TRANSPORTACI&Oacute;N </div>
				<div class="km-services">
					<select id="transporte" name="transporte" class="km-input-custom"><option value="">SELECCIONE UNA OPCI&Oacute;N</option>'.$transporte.'</select>
				</div>
			';
		}

		$adicionales = getAdicionales($adicionales, $precargas["adicionales"]);
		if( $adicionales != "" ){
			$adicionales = '
				<div class="km-service-title"> SERVICIOS ADICIONALES </div>
				<div id="adicionales" class="km-services">
					'.$adicionales.'
				</div>
			';
		}

		$productos .= '</div>';

		$email = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = {$id_user}");

		$saldo = getSaldo();

		$saldoTXT = "";
		$saldoTXT = $saldo["cupon"];

		$error = "";
		if( $id_user  == ""){
			$error = "
				<h1 align='justify'>Debes iniciar sesión para poder realizar reservas.</h1>
				<h2 align='justify'>Pícale <span id='cerrarModal' onclick=\"document.getElementById('login').click(); jQuery('.vlz_modal').css('display', 'none')\" style='color: #00b69d; font-weight: 600; cursor: pointer;'>Aquí</span> para acceder a kmimos.<h2>";
		}else{
			$propietario = $wpdb->get_var("SELECT post_author FROM wp_posts WHERE ID = ".get_the_ID() );
			if( $propietario == $id_user ){
				$error = "
					<h1 align='justify'>No puedes realizarte reservas a tí mismo.</h1>
					<h2 align='justify'>Pícale <a href='".get_home_url()."/busqueda/' style='color: #00b69d; font-weight: 600;'>Aquí</a> para buscar entre cientos de cuidadores certificados kmimos.<h2>
				";
			}else{
				$meta = get_user_meta($id_user);
				if( $meta['first_name'][0] == '' ||  $meta['last_name'][0] == '' || ( $meta['user_mobile'][0] == '' ) && ( $meta['user_phone'][0] == '' )){
					$error = "
						<h1 align='justify'>Kmiusuario, para continuar con tu reserva debes ir a tu perfil para completar algunos datos de contacto.</h1>
						<h2 align='justify'>Pícale <a href='".get_home_url()."/perfil-usuario/?ua=profile' target='_blank' style='color: #00b69d; font-weight: 600;'>Aquí</a> para cargar tu información.<h2>
					";
				}else{
					$mascotas = $wpdb->get_var("SELECT count(*) FROM wp_posts WHERE post_type = 'pets' AND post_author = ".$id_user );
					if( $mascotas == 0 ){
						$error = "
							<h1 align='justify'>Debes cargar por lo menos una mascota para poder realizar una reserva.</h1>
							<h2 align='justify'>Pícale <a href='".get_home_url()."/perfil-usuario/mascotas/' style='color: #00b69d; font-weight: 600;'>Aquí</a> para agregarlas.<h2>
						";
					}
				}
			}
		}
		//$error = "";

		include( dirname(__FILE__)."/procesos/funciones/config.php" );

		$HTML .= "
		<script> 
			var SERVICIO_ID = '".get_the_ID()."';
			var cupos = eval('".json_encode($cupos)."');
			var tipo_servicio = '".$tipo."'; 
			var name_servicio = '".$servicio_name."'; 
			var cliente = '".$id_user."'; 
			var cuidador = '".$cuidador->id_post."'; 
			var email = '".$email."'; 
			var saldo = '".$saldoTXT."';
			var acepta = '".$cuidador->mascotas_permitidas."';
			var OPENPAY_TOKEN = '".$MERCHANT_ID."';
			var OPENPAY_PK = '".$OPENPAY_KEY_PUBLIC."';
			var OPENPAY_PRUEBAS = ".$OPENPAY_PRUEBAS.";
		</script>";

		if( $error != "" ){
			$actual = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			$referencia = $_SERVER['HTTP_REFERER'];

			if( $actual == $referencia ){
				$referencia = get_home_url();
			} 
			$HTML .= "
			<style>
				.vlz_modal{ position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; display: table; z-index: 10000; background: rgba(0, 0, 0, 0.8); vertical-align: middle !important; }
				h1{ font-size: 18px; }
				h2{ font-size: 16px; }
				.vlz_modal_interno{ display: table-cell; text-align: center; vertical-align: middle !important; }
				.vlz_modal_ventana{ position: relative; display: inline-block; width: 60%!important; text-align: left; box-shadow: 0px 0px 4px #FFF; border-radius: 5px; z-index: 1000; }
				.vlz_modal_titulo{ background: #FFF; padding: 15px 10px; font-size: 18px; color: #52c8b6; font-weight: 600; border-radius: 5px 5px 0px 0px; }
				.vlz_modal_contenido{ background: #FFF; height: 450px; box-sizing: border-box; padding: 5px 15px; border-top: solid 1px #d6d6d6; border-bottom: solid 1px #d6d6d6; overflow: auto; text-align: justify; }
				.vlz_modal_pie{ background: #FFF; padding: 15px 10px; border-radius: 0px 0px 5px 5px; }
				.vlz_modal_fondo{ position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; z-index: 500; }
				.vlz_boton_siguiente{ padding: 10px 50px; background-color: #a8d8c9; display: inline-block; font-size: 16px; border: solid 1px #2ca683; border-radius: 3px; float: right; cursor: pointer; } 
				@media screen and (max-width: 750px){ .vlz_modal_ventana{ width: 90% !important; } }
			</style>

			<div id='jj_modal_ir_al_inicio' class='vlz_modal'>
				<div class='vlz_modal_interno'>
					<div class='vlz_modal_fondo' onclick='jQuery('#jj_modal_ir_al_inicio').css('display', 'none');'></div>
					<div class='vlz_modal_ventana jj_modal_ventana'S>
						<div class='vlz_modal_titulo'>¡Oops!</div>
						<div class='vlz_modal_contenido' style='height: auto;'>
							{$error}
						</div>
						<div class='vlz_modal_pie' style='border-radius: 0px 0px 5px 5px!important; height: 70px;'>
							<a href='".$referencia."' ><input type='button' style='text-align: center;' class='vlz_boton_siguiente' value='Volver'/></a>
						</div>
					</div>
				</div>
			</div>";

			echo comprimir_styles($HTML);
		}else{

			$descripcion = $wpdb->get_var("SELECT post_excerpt FROM wp_posts WHERE ID = {$post_id}");

			$HTML .= '

		 		<form id="reservar" class="km-content km-content-reservation">
					<div id="step_1" class="km-col-steps">
						<div class="km-col-content">
							<ul class="steps-numbers">
								<li><span class="number active">1</span></li>
								<li class="line"></li><li><span class="number">2</span></li>
								<li class="line"></li><li><span class="number">3</span></li>
							</ul>

							<div class="km-title-step">
								RESERVACIÓN<br>
								'.$servicio_name_corto.'<br>
								<div class="km-info-box">
									<i class="fa fa-info-circle km-info"></i>
									<div>'.$descripcion.'</div>
								</div>
							</div>

							<div class="km-sub-title-step">
								Reserva las fechas y los servicios con tu cuidador(a) '.$cuidador_name.'
							</div>

							<div class="km-dates-step">
								<div class="km-ficha-fechas">
									<input type="text" id="checkin" name="checkin" placeholder="DESDE" value="'.$busqueda["checkin"].'" class="date_from" readonly>
									<input type="text" id="checkout" name="checkout" placeholder="HASTA" value="'.$busqueda["checkout"].'" readonly>
								</div>
							</div>

							<div class="km-content-step">
								<div class="km-content-new-pet">
									'.$precios.'
									<div class="km-services-content">
										<div class="contenedor-adicionales">'.$transporte.'</div>
										<div class="contenedor-adicionales">'.$adicionales.'</div>
									</div>

									<div class="km-services-total km-total-calculo">
										<div class="valido">
											<span class="km-text-total">TOTAL</span>
											<span class="km-price-total">$0.00</span>
										</div>
										<div class="invalido">
											
										</div>
									</div>

								</div>
							</div>

							<a href="#" id="reserva_btn_next_1" class="km-end-btn-form km-end-btn-form-disabled disabled vlz_btn_reservar">
								<span>SIGUIENTE</span>
							</a>

						</div>
					</div>


					<div id="step_2" class="km-col-steps">

						<div class="km-col-content">

							<div id="atras_1" class="atras"> < </div>

							<ul class="steps-numbers">
								<li>
									<span class="number checked">1</span>
								</li>
								<li class="line"></li>
								<li>
									<span class="number active">2</span>
								</li>
								<li class="line"></li>
								<li>
									<span class="number">3</span>
								</li>
							</ul>

							<div class="km-title-step">
								RESUMEN DE TU RESERVA
							</div>

							<div class="km-sub-title-step">
								Queremos confirmar tu reservación y tu método de pago
							</div>

							<div class="km-content-step km-content-step-2">
								<div class="km-option-resume">
									<span class="label-resume">CUIDADOR SELECCIONADO</span>
									<span class="value-resume">'.$cuidador_name.'</span>
								</div>

								<div class="km-option-resume">
									<span class="label-resume">FECHA</span>
									<span class="value-resume">
										<span class="fecha_ini"></span>
										&nbsp; &gt; &nbsp;
										<span class="fecha_fin"></span>
									</span>
								</div>

								<div class="km-option-resume">

									<div class="km-option-resume-service">
										<span class="label-resume-service">'.$servicio_name.'</span>
									</div>

									<div class="items_reservados"></div>

								</div>

								<div class="cupones_desglose km-option-resume">
									<span class="label-resume">Descuentos</span>
									<div></div>
								</div>

								<div class="km-services-total">
									<span class="km-text-total">TOTAL</span>
									<span class="km-price-total2">$420.00</span>
								</div>
							</div>

							<div class="km-select-method-paid">
								<div class="km-method-paid-title">
									SELECCIONA PAGO PARCIAL ó TOTAL
								</div>

								<div class="km-method-paid-options">
									<div class="km-method-paid-option km-option-deposit active">
										<div class="km-text-one">
											RESERVA CON PAGO PARCIAL
										</div>
										<div class="km-text-two">
											Pague ahora el 17% y el restante
										</div>
										<div class="km-text-three">
											AL CUIDADOR EN EFECTIVO
										</div>
									</div>

									<div class="km-method-paid-option km-option-total">
										<div class="km-text-one">
											PAGO TOTAL DE LA RESERVA
										</div>
									</div>
								</div>
							</div>

							<div class="km-detail-paid-deposit" style="display:block;">
								<div class="km-detail-paid-line-one">
									<span class="km-detail-label">SUBTOTAL</span>
									<span id="" class="sub_total km-detail-value"></span>
								</div>

								<div class="km-detail-paid-line-one">
									<span class="km-detail-label">DESCUENTO</span>
									<span id="" class="descuento km-detail-value">$0.00</span>
								</div>

								<div class="km-detail-paid-line-one">
									<span class="km-detail-label">TOTAL</span>
									<span id="" class="monto_total km-detail-value"></span>
								</div>

								<div class="km-detail-paid-line-two">
									<span class="km-detail-label">MONTO A PAGAR <b>EN EFECTIVO AL CUIDADOR</b></span>
									<span id="" class="pago_cuidador km-detail-value">$809.25</span>
								</div>

								<div class="km-detail-paid-line-three">
									<span class="km-detail-label">PAGUE AHORA</span>
									<span id="" class="pago_17 km-detail-value">$165.75</span>
								</div>
							</div>

							<div class="km-cupones">
								<div>
									<input type="text" id="cupon" placeholder="Ingresa tu cupón">
								</div>
								<div class="">
									<span id="cupon_btn">Cup&oacute;n</span>
								</div>
							</div>

							<span id="reserva_btn_next_2" class="km-end-btn-form vlz_btn_reservar">
								<span>SIGUIENTE</span>
							</span>

						</div>

					</div>

					<div id="step_3" class="km-col-steps">

						<div class="km-col-content">

							<div id="atras_2" class="atras"> < </div>

							<ul class="steps-numbers">
								<li>
									<span class="number checked">1</span>
								</li>
								<li class="line"></li>
								<li>
									<span class="number checked">2</span>
								</li>
								<li class="line"></li>
								<li>
									<span class="number active">3</span>
								</li>
							</ul>

							<div class="km-title-step">
								RESUMEN DE TU RESERVA
							</div>

							<div class="km-tab-content" style="display: block;">
								<div class="km-content-step km-content-step-2">
									<div class="km-option-resume">
										<span class="label-resume">CUIDADOR SELECCIONADO</span>
										<span class="value-resume">'.$cuidador_name.'</span>
									</div>

									<div class="km-option-resume">
										<span class="label-resume">FECHA</span>
										<span class="value-resume">
											<span class="fecha_ini"></span>
											&nbsp; &gt; &nbsp;
											<span class="fecha_fin"></span>
										</span>
									</div>

									<div class="km-option-resume">
										<div class="km-option-resume-service">
											<span class="label-resume-service">'.$servicio_name.'</span>
										</div>
										<div class="items_reservados"></div>
									</div>

									<div class="cupones_desglose km-option-resume">
										<span class="label-resume">Descuentos</span>
										<div></div>
									</div>

									<div class="km-services-total">
										<span class="km-text-total">TOTAL</span>
										<span class="km-price-total2"></span>
									</div>

									<div class="km-detail-paid-deposit">
										<div class="km-detail-paid-line-one">
											<span class="km-detail-label">SUBTOTAL</span>
											<span id="" class="sub_total km-detail-value"></span>
										</div>

										<div class="km-detail-paid-line-one">
											<span class="km-detail-label">DESCUENTO</span>
											<span id="" class="descuento km-detail-value">$0.00</span>
										</div>

										<div class="km-detail-paid-line-one">
											<span class="km-detail-label">TOTAL</span>
											<span id="" class="monto_total km-detail-value"></span>
										</div>

										<div class="km-detail-paid-line-two">
											<span class="km-detail-label">MONTO A PAGAR <b>EN EFECTIVO AL CUIDADOR</b></span>
											<span id="" class="pago_cuidador km-detail-value">$809.25</span>
										</div>

										<div class="km-detail-paid-line-three">
											<span class="km-detail-label">PAGUE AHORA</span>
											<span id="" class="pago_17 km-detail-value">$165.75</span>
										</div>
									</div>

								</div>
							</div>

							<div id="metodos_pagos">
								<div class="km-tab-content" style="display: block;">
									<div class="km-content-method-paid-inputs">

										<div class="km-select-method-paid">
											<div class="km-method-paid-title">
												MEDIO DE PAGO
											</div>

											<div class="km-method-paid-options km-medio-paid-options">
												<div class="km-method-paid-option km-tienda km-option-3-lineas active">
													<div class="km-text-one">
													PAGO EN TIENDA
													</div>
													<div class="km-text-three">
													DE CONVENIENCIA
														
													</div>

												</div>

												<div class="km-method-paid-option km-tarjeta km-option-3-lineas ">
													<div class="km-text-one">
														<div class="km-text-one">								
														PAGO CON TARJETA
														</div>
														<div class="km-text-three">
															DE CRÉDITO O DÉBITO
														</div>
													</div>

												</div>

											</div>
										</div>

										<select id="tipo_pago" style="display: none;">
											<option value="tienda">PAGO EN TIENDA DE CONVENIENCIA</option>
											<option value="tarjeta">PAGO CON TARJETA DE CRÉDITO O DÉBITO</option>
										</select>

										<div class="errores_box">
											Datos de la tarjeta invalidos
										</div>

										<div id="tienda_box" class="metodos_container" style="display:block;">
											<img src="'.get_template_directory_uri().'/images/tiendas.png" />
											<img src="'.get_template_directory_uri().'/images/pasos.png" />
										</div>
										<div id="tarjeta_box" class="metodos_container" style="display:none;">

											<div class="label-placeholder">
												<label>Nombre del tarjetahabiente*</label>
												<input type="text" id="nombre" name="nombre" value="" class="input-label-placeholder solo_letras" data-openpay-card="holder_name">
											</div>

											<div class="label-placeholder">
												<label>Número de Tarjeta*</label>
												<input type="text" id="numero" name="numero" class="input-label-placeholder next solo_numeros maxlength" data-max="16" data-next="mes">
												<input type="hidden" id="numero_oculto" data-openpay-card="card_number">
											</div>

											<div class="content-placeholder">
												<div class="label-placeholder">
													<label>Expira (MM AA)</label>
													<input type="text" id="mes" name="mes" class="input-label-placeholder next expiration solo_numeros maxlength" data-max="2" data-next="anio" maxlength="2" data-openpay-card="expiration_month">
													<input type="text" id="anio" name="anio" class="input-label-placeholder next expiration solo_numeros maxlength" data-max="2" data-next="codigo" maxlength="2" data-openpay-card="expiration_year">
												</div>

												<div class="label-placeholder">
													<label>Código de seguridad(CVV)</label>
													<input type="text" id="codigo" name="codigo" class="input-label-placeholder next solo_numeros maxlength" data-max="3" maxlength="3" data-next="null" data-openpay-card="cvv2">
													<small>Número de tres dígitos en el reverso de la tarjeta</small>
												</div>
											</div>
											<!--
											<div class="km-msje-minimal">
												*Recuerda que tus datos deben ser los mismos que el de tu tarjeta
											</div>
											-->
										</div>

									</div>
								</div>
							</div>

							<div class="km-term-conditions">
								<label>
									<input type="checkbox" id="term-conditions" name="term-conditions" value="1">
									Acepto los <a href="'.get_home_url().'/terminos-y-condiciones/" target="_blank">términos y condiciones</a>
								</label>
							</div>

							<span id="reserva_btn_next_3" class="km-end-btn-form vlz_btn_reservar disabled">
								<div class="perfil_cargando" style="background-image: url('.getTema().'/images/cargando.gif);" ></div> <span>TERMINAR RESERVA</span>
							</span>

						</div>
					</div>

					<div class="km-col-empty">
						<img src="'.getTema().'/images/new/bg-cachorro.png" style="max-width: 100%;">
					</div>
				</form>

				<!-- SECCIÓN BENEFICIOS -->
				<div class="km-beneficios km-beneficios-footer" style="margin-top: 60px;">
					<div class="container">
						<div class="row">
							<div class="col-xs-4">
								<div class="km-beneficios-icon">
									<img src="'.getTema().'/images/new/km-pago.svg">
								</div>
								<div class="km-beneficios-text">
									<h5 class="h5-sub">PAGO EN EFECTIVO O CON TARJETA</h5>
								</div>
							</div>
							<div class="col-xs-4 brd-lr">
								<div class="km-beneficios-icon">
									<img src="'.getTema().'/images/new/km-certificado.svg">
								</div>
								<div class="km-beneficios-text">
									<h5 class="h5-sub">CUIDADORES CERTIFICADOS</h5>
								</div>
							</div>
							<div class="col-xs-4">
								<div class="km-beneficios-icon">
									<img src="'.getTema().'/images/new/km-veterinaria.svg">
								</div>
								<div class="km-beneficios-text">
									<h5 class="h5-sub">COBERTURA VETERINARIA</h5>
								</div>
							</div>
						</div>
					</div>
				</div>
		 	';

			echo comprimir_styles($HTML);

		}

    get_footer(); 
?>