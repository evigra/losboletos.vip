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
					evento e join 
					invitado i on i.id_evento=e.id_evento
				WHERE
					md5(e.id_evento)='{$_REQUEST["id"]}'
			";				
			$this->datas				= $this->__EXECUTE($comando_sql);

			#$this->__PRINT_R($this->datas);

			$datas="";

			$url_evento="http://losboletos.vip/invitado/show/&id=1". $_REQUEST["id"];
			#$url_evento="http://losboletos.vip/invitado/show/&id=1";
			$wa1_evento="https://wa.me/+52{$this->datas[0]["tel1_evento"]}?text=". urlencode($url_evento);
			$wa2_evento="https://wa.me/+52{$this->datas[0]["tel2_evento"]}?text=". urlencode($url_evento);

			$this->words["qr_evento"]	= $this->__QR($url_evento, 800);
			$this->words["url1_evento"]	= $wa1_evento;
			$this->words["url2_evento"]	= $wa2_evento;

			$this->words["tel1_evento"]	= $this->datas[0]["tel1_evento"];
			$this->words["tel2_evento"]	= $this->datas[0]["tel2_evento"];
			
			foreach($this->datas as $data)
			{	
				#nucleo/qrlib/imagen_qr.php?data=
				$wa="https://wa.me/+52{$data["telefono_invitado"]}?text=http://losboletos.vip/invitacion/show/&id=" . md5($data["id_invitado"]);

				$url_text	=urlencode("http://losboletos.vip/invitacion/show/&id=" . md5($data["id_invitado"]));
				$url_qr	=urlencode("http://losboletos.vip/nucleo/qrlib/imagen_qr.php?data=$url_text");


				$text_wa=urlencode("HOLA {$data["nombre_invitado"]}\n\n En Este dia tan especial, queremos invitarte a nuestra boda \n\nFavor de confirmar su asistencia  \n\n $url_text \n\n $url_qr");

				$wa="https://wa.me/+52{$data["telefono_invitado"]}?text=$url_qr";
				$wa="https://wa.me/+52{$data["telefono_invitado"]}?text=$text_wa";
				
				$datas.="
					<tr>
						<td>{$data["nombre_invitado"]}</td>
						<td>
							<a href=\"$wa\">{$data["telefono_invitado"]}</a><br>
							{$data["email_invitado"]}
						</td>
						<td>{$data["status_gral_invitado"]}</td>
					</tr>
				";
			}
			
			$this->words["datos"]=$datas;

			return parent::__CONSTRUCT($option);
		}
	}
?>
