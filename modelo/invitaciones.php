<?php
	class invitaciones extends general
	{   
		##############################################################################	
		##  Propiedades	
		##############################################################################
		var $data		=Array();
		##############################################################################	
		##  Metodos	
		##############################################################################
         
		public function __CONSTRUCT($option=null)
		{	
			if(isset($_REQUEST["action"]))
			{
				$this->__SAVE($_REQUEST["action"]);
			}

			$comando_sql				="
				SELECT * 
				FROM 
					evento e left join 
					invitado i on i.id_evento=md5(e.id_evento)
				WHERE
					md5(e.id_evento)='{$_REQUEST["id"]}'
			";				
			$this->datas				= $this->__EXECUTE($comando_sql);

			#$this->__PRINT_R($this->datas);

			$datas="";

			$url_evento="http://losboletos.vip/invitado/show/&id=". $_REQUEST["id"];
			#$url_evento="http://losboletos.vip/invitado/show/&id=1";
			$wa1_evento="https://wa.me/+52{$this->datas[0]["tel1_evento"]}?text=". urlencode($url_evento);
			$wa2_evento="https://wa.me/+52{$this->datas[0]["tel2_evento"]}?text=". urlencode($url_evento);

			$this->words["qr_evento"]	= $this->__QR($url_evento, 800);
			$this->words["url1_evento"]	= $wa1_evento;
			$this->words["url2_evento"]	= $wa2_evento;

			$this->words["tel1_evento"]	= $this->datas[0]["tel1_evento"];
			$this->words["tel2_evento"]	= $this->datas[0]["tel2_evento"];
			$invitados_confirmados=0;
			$invitados_espera=0;
			$invitados_cancelados=0;
			foreach($this->datas as $data)
			{	
				$id_invitado	=@md5($data["id_invitado"]);
				#nucleo/qrlib/imagen_qr.php?data=
				$wa="https://wa.me/+52{$data["telefono_invitado"]}?text=http://losboletos.vip/invitacion/show/&id=" . $id_invitado;

				$url_text	=urlencode("http://losboletos.vip/invitacion/show/&id=" . $id_invitado);
				$url_qr		=urlencode("http://losboletos.vip/nucleo/qrlib/imagen_qr.php?data=$url_text");


				$text_wa=urlencode("{$data["nombre_invitado"]} \n
Nos complace enviarte la invitación para {$data["numero_invitado"]} personas, a un evento muy especial para nosotros: Nuestra boda. \n
Deseamos disfrutar éste día con personas con quienes hemos compartido valiosos momentos de nuestra vida.  \n
Personas positivas que nos acompañen con alegría y buena vibra en ese momento tan especial para nosotros, en el que formalizaremos nuestra unión.	\n
Para el mayor disfrute de todos los que estemos ahí,  este evento se programó sólo para adultos, por lo que los menores, deberán quedarse a descansar para dejar a sus papis disfrutar. \n
Esperamos contar con tu puntual asistencia.\n 
Confirmanos antes del {$this->datas[0]["confirmacion_evento"]} por medio del siguiente link:\n
") . $url_text;

				
				$wa="https://wa.me/+52{$data["telefono_invitado"]}?text=$url_qr";
				$wa="https://wa.me/{$data["pais_telefono_invitado"]}{$data["telefono_invitado"]}?text=$text_wa";
				
				$status_invitado="";
				$mesa_invitado="";
				if($data["status_gral_invitado"]=="ACEPTAR")	
				{
					$invitados_confirmados+=intval($data["numero_invitado"]);
					$status_invitado ="background-color: green;";
					$mesa_invitado="<input class=\"subtitulo\" style=\"width:50px;\" name=\"mesa_" . md5($data["id_invitado"]) . "\" value=\"" . $data["mesa_invitado"] . "\"> ";	

				}
				elseif($data["status_gral_invitado"]=="CANCELAR")
				{
					$invitados_cancelados+=intval($data["numero_invitado"]);	

				}
				else
				{
					$invitados_espera+=intval($data["numero_invitado"]);					
				}
					
				if($data["status_gral_invitado"]=="CANCELAR")	$status_invitado ="background-color: red;";

				$datas.="
					<tr>
						<td width=\"70\" style=\"height:70px; text-align:center; vertical-align: middle;\">
							<input class=\"subtitulo\" style=\"width:50px;\"  name=\"inv_" . md5($data["id_invitado"]) . "\" value=\"" . $data["numero_invitado"] . "\"> 
						</td>
						<td width=\"70\" style=\"height:70px; text-align:center; vertical-align: middle;  $status_invitado \">
							$mesa_invitado
						</td>

						<td style=\"height:70px; vertical-align: middle;\">
							<a href=\"$wa\">{$data["nombre_invitado"]}</a>
						</td>
						<td width=\"40\" style=\"height:70px; text-align:center; vertical-align: middle;\">{$data["fecha_gral_invitado"]}</td>
					</tr>
				";
			}
			
			$this->words["invitados_confirmados"]=$invitados_confirmados;
			$this->words["invitados_espera"]=$invitados_espera;
			$this->words["invitados_cancelados"]=$invitados_cancelados;
			
			$this->words["datos"]=$datas;

			return parent::__CONSTRUCT($option);
		}
		public function __SAVE()
    	{

			$comando_sql				="
				SELECT * 
				FROM 
					evento e left join 
					invitado i on i.id_evento=md5(e.id_evento)
				WHERE
					md5(e.id_evento)='{$_REQUEST["id"]}'
			";				
			$this->datas				= $this->__EXECUTE($comando_sql);

			foreach($this->datas as $data)
			{	
				
				$invitaciones	=@$_REQUEST["inv_" . md5($data["id_invitado"])];
				$mesa			=@$_REQUEST["mesa_" . md5($data["id_invitado"])];

				$comando_sql="
					UPDATE invitado SET numero_invitado=\"$invitaciones\", mesa_invitado=\"$mesa\" 
					WHERE
						id_invitado='{$data["id_invitado"]}'
				";
				@$this->__EXECUTE($comando_sql);				
				
			}
		}    	

	}
?>