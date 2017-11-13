<!-- POPUP CONOCE AL CUIDADOR -->
<?php
	wp_enqueue_script('conocer_al_cuidador_js', getTema()."/js/conocer_al_cuidador.js", array("jquery"), '1.0.0');

global $current_user;
date_default_timezone_set('America/Bogota');
$user_id = $current_user->ID;
$user_email = $current_user->user_email;
$mascotas = kmimos_get_my_pets($user_id);

$btn_perfil['icon'] = '<i class="fa fa-check" style="color: #3c763d;"></i>';
$btn_perfil['btn'] = 'tu perfil';

$btn_login['btn'] = 'iniciado sesión';
$btn_login['icon'] = '<i class="fa fa-check" style="color: #3c763d;"></i>';

$btn_mascota['btn'] = 'lista de mascotas';
$btn_mascota['icon'] = '<i class="fa fa-check" style="color: #3c763d;"></i>';

$validar_perfil_completo = false;
if ( !is_user_logged_in() ){ 
	// Login
	$btn_login['btn'] = '<a  style="color:#337ab7;" role="button" data-target="#popup-iniciar-sesion"><strong>iniciado sesión</strong></a>';
	$btn_login['icon'] = '<i class="fa fa-close" style="color: #c72929;"></i>';

	// Perfil	
	$btn_perfil['btn'] = '<a  style="color:#337ab7;" role="button" data-target="#popup-iniciar-sesion"><strong> tu perfil</strong></a>';
	$btn_perfil['icon'] = '<i class="fa fa-close" style="color: #c72929;"></i>';

	$btn_mascota['btn'] = '<a  style="color:#337ab7;" role="button" data-target="#popup-iniciar-sesion"><strong> lista de mascotas</strong></a>';
	$btn_mascota['icon'] = '<i class="fa fa-close" style="color: #c72929;"></i>';

}else{

	/* Validar mascotas */
	if ( count($mascotas) < 1 ){ 
		$btn_mascota['btn'] = '<a href="'.get_home_url().'/perfil-usuario/mascotas" style="color:#337ab7;" role="button" ><strong>lista de mascotas</strong></a>';
		$btn_mascota['icon'] = '<i class="fa fa-close" style="color: #c72929;"></i>';
	}				

	/* Validar perfil de usuario*/
	$validar_perfil_completo = validar_perfil_completo();
	if( !$validar_perfil_completo ){
		$btn_perfil['btn'] = '<a  style="color:#337ab7;" role="button" href="'.get_home_url().'/perfil-usuario" style="color:#337ab7;" role="button"><strong> tu perfil</strong></a>';
		$btn_perfil['icon'] = '<i class="fa fa-close" style="color: #c72929;"></i>';		
	}
}

$HTML_CONOCER = '
<div id="popup-conoce-cuidador" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<div class="popup-iniciar-sesion-1">
				<p class="popup-tit">Solicitud para conocer a <span id="modal-name-cuidador"></span></p>
				<div class="pre_requisitos">
					<p>Para poder conocer al cuidador primero tienes que:</p>
					<ol class="list-unstyled">
						<li>'.$btn_login['icon'].' Haberte registrado en nuestro portal y haber '.$btn_login['btn'].'</li>
						<li>'.$btn_perfil['icon'].' Completar todos los datos requeridos en '.$btn_perfil['btn'].'</li>
						<li>'.$btn_mascota['icon'].' Completar tu '.$btn_mascota['btn'].' en tu perfil</li>
					</ol>
				</div>';
				if( count($mascotas)>0 && $validar_perfil_completo ){
					$HTML_CONOCER .= '
					<form id="conoce_cuidador" style="padding:0px;" method="post">

						<input name="post_id" type="hidden" value="">

						<div class="km-box-form">
							<div class="content-placeholder">

								<div class="km-calendario">
									<label>¿Cuándo deseas conocer al cuidador?</label>
									<input type="text" id="meeting_when" name="meeting_when" placeholder="dd/mm/aaaa" class="km-calendario date_from" value="'.$busqueda["checkin"].'" readonly>
									<small data-error="meeting_when" style="display: none;">Debes ingresar una fecha</small>
								</div>
								<div class="km-datos-mascota">
									<select class="km-datos-mascota-opcion" data-charset="all" id="meeting_time" name="meeting_time">
										<option value="" class="vacio">¿A qué hora te convendría la reunión?</option>
			                        	<option value="07:00:00" data-id="7">07:00  a.m.</option>
			                        	<option value="07:30:00" data-id="7.5">07:30  a.m.</option>
			                        	<option value="08:00:00" data-id="8">08:00  a.m.</option>
			                        	<option value="08:30:00" data-id="8.5">08:30  a.m.</option>
			                        	<option value="09:00:00" data-id="9">09:00  a.m.</option>
			                        	<option value="09:30:00" data-id="9.5">09:30  a.m.</option>
			                        	<option value="10:00:00" data-id="10">10:00  a.m.</option>
			                        	<option value="10:30:00" data-id="10.5">10:30  a.m.</option>
			                        	<option value="11:00:00" data-id="11">11:00  a.m.</option>
			                         	<option value="11:30:00" data-id="11.5">11:30  a.m.</option>
			                        	<option value="12:00:00" data-id="12">12:00  m</option>
			                        	<option value="12:30:00" data-id="12.5">12:30  m</option>
			                        	<option value="13:00:00" data-id="13">01:00  p.m.</option>
			                        	<option value="13:30:00" data-id="13.5">01:30  p.m.</option>
			                        	<option value="14:00:00" data-id="14">02:00  p.m.</option>
			                        	<option value="14:30:00" data-id="14.5">02:30  p.m.</option>
			                        	<option value="15:00:00" data-id="15">03:00  p.m.</option>
			                        	<option value="15:30:00" data-id="15.5">03:30  p.m.</option>
			                        	<option value="16:00:00" data-id="16">04:00  p.m.</option>
			                        	<option value="16:30:00" data-id="16.5">04:30  p.m.</option>
			                        	<option value="17:00:00" data-id="17">05:00  p.m.</option>
			                        	<option value="17:30:00" data-id="17.5">05:30  p.m.</option>
			                        	<option value="18:00:00" data-id="18">06:00  p.m.</option>
			                        	<option value="18:30:00" data-id="18.5">06:30  p.m.</option>
			                        	<option value="19:00:00" data-id="19">07:00  p.m.</option>
			                        </select>
									<small data-error="meeting_time" style="display: none;">Debes seleccionar una hora</small>
								</div>
								<div class="label-placeholder">
									<label>¿Dónde deseas conocer al cuidador?</label>
									<input type="text" id="meeting_where" name="meeting_where" data-charset="xlfalfnum" autocomplete="off" value="" class="input-label-placeholder date_form">
									<small data-error="meeting_where" style="display: none;">Debes ingresar un lugar</small>
								</div>
								<div class="km-group-checkbox">
									<label>¿Qué mascotas requieren el servicio?</label>
									<ul id="pet_conoce">';
										foreach ($mascotas as $mascota) {
				                            $HTML_CONOCER .= '
				                            <li>
			                                	<input type="checkbox" name="pet_ids[]" id="pet_'.$mascota->ID.'" value="'.$mascota->ID.'" >
				                                <label for="pet_'.$mascota->ID.'" style="padding-top: 0px !important;">
				                                	'.$mascota->post_title.'
				                               	</label>
				                            </li>';
				                        } $HTML_CONOCER .= '
				                    </ul>
									<small data-error="pet_conoce" style="display: none;">Debes seleccionar al menos una mascota</small>
								</div>
								<div class="km-calendario">
									<label>¿Desde cuándo requieres el servicio?</label>
									<input type="text" id="service_start" name="service_start" placeholder="dd/mm/aaaa" class="date_from" value="'.$busqueda["checkin"].'" readonly>
									<small data-error="service_start" style="display: none;">Debes ingresar una fecha</small>
								</div>
								<div class="km-calendario">
									<label>¿Hasta cuándo requieres el servicio?</label>
									<input type="text" id="service_end" name="service_end" placeholder="dd/mm/aaaa" class="date_from" value="'.$busqueda["checkout"].'" readonly>
									<small data-error="service_end" style="display: none;">Debes ingresar una fecha</small>
								</div>
								</br>							
								<a href="javascript:;" id="btn_enviar_conocer" data-id="enviar_datos" class="km-btn-basic">ENVIAR SOLICITUD</a>
							</div>
						</div>
					</form>';
				} $HTML_CONOCER .= '
			</div>
			<div class="popup-iniciar-sesion-2" style="display: none;">
				<img src="'.getTema().'/images/new/km-reserva/img-end-step.png" width="197">
				<br>
				<h2>
					¡Genial '.get_user_meta($user_id, "first_name", true).'!<br>
					Solicitud Enviada Exitosamente
				</h2>
				<br>

				
				<div style="text-align:justify;">
				<p style=" font-family: Arial;  font-weight: bold; color:#6b1c9b;" >Te acabamos de enviar un correo a tu dirección registrada con ésta información. Por favor revisa tu Buzón de Entrada o Buzón de No Deseados.</p>
				 <br>  
				<p>Recibimos la solicitud realizada para Conocer a un Cuidador Kmimos.</p>
				<p >Tu codigo de solicitud es: <B><span id="n_solicitud"></span></B></p>
				<div>
				    <div> 
				    	<br>   
				        <p>Datos del cuidador</p>					    
						<p>Nombre: <B><span id="nombre"></span></B></p>
						<p>Telefono: <span id="telefono"></span> / '.get_user_meta($user_id, "user_mobile", true).'</p>
					    <p>Correo: <span id="email"></span></p>
					</div>
				    <div> 
				     	<br>   
						<p >DATOS DE LA REUNION</p>
						<p >Fecha: <B><span id="fecha"></span></B></p>
						<p >Hora: <span id="hora_reu"></span> horas</p>
						<p >Lugar: <span id="lugar_reu"></span></p>
						<br>
						<p >POSIBLE FECHA DE ESTADIA</p>
						<p >Inicio: <span id="fecha_ini"></span></p>
						<p >Fin: <span id="fecha_fin"></span></p>
					</div>	
					<div  style="clear:both;"></div>
				</div>

					<div>
						<p style=" font-family: Arial; font-size:16px; font-weight: bold; color:#6b1c9b; text-align:center;">IMPORTANTE</p>
						<label> Dentro de las siguientes 2-4 horas recibirás una llamada o correo electrónico por parte del Cuidador y/o de un asesor Kmimos para confirmar tu cita o brindarte soporte con este proceso. También podrás contactar al cuidador a partir de este momento, a los teléfonos y/o correos mostrados a continuación para acelerar el proceso si así lo deseas.</label>
						<label> Para cualquier duda y/o comentario puedes contactar al Staff Kmimos a los teléfonos (01) 55 4742 3162 y WhatsApp +52 (55) 6892 2182, o al correo contactomex@kmimos.la</label>
					</div>
				</div>
				<br>
				<br>
				<div>
					'.$pdf.'
					<a class="btn_fin_reserva" href="'.get_home_url().'/perfil-usuario/solicitudes/">VER MIS SOLICITUDES</a>
				</div>
			</div>
		</div>
	</div>
</div>';

echo comprimir_styles( $HTML_CONOCER );

?>
