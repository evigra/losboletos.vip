<?php
	class invitacion extends general
	{   
		##############################################################################	
		##  Propiedades	
		##############################################################################
		var $fields		=Array();
		##############################################################################	
		##  Metodos	
		##############################################################################
         
		public function __CONSTRUCT($option=null)
		{	
			if(isset($_REQUEST["action"]))
			{
				$this->__SAVE($_REQUEST["action"]);
			}

			$date1_fecha_evento = new DateTime('2024-12-27 17:00');
			$date1_fecha_evento = new DateTime('2024-12-23 17:00');
			$date2_fecha_servidor = new DateTime(Date('Y-m-d H:i'));


			$this->__INI();


			$comando_sql				="
				SELECT * 
				FROM 
					evento e join 
					invitado i on i.id_evento=md5(e.id_evento)
				WHERE
					md5(i.id_invitado)='{$_REQUEST["id"]}'
			";				
			$this->fields				= $this->__EXECUTE($comando_sql)[0];

			if($this->fields["status_gral_invitado"]=="ACEPTAR")			
			{
				$imagen_qr = $this->__QR("http://losboletos.vip/invitacion/show/&estado=ingreso&id=" . $_REQUEST["id"], 400);

				$this->words["qr"] = "$imagen_qr <br> INVITACION CONFIRMADA <br>" . md5($this->fields["id_invitado"]);	
				$this->words["qr"] = "$imagen_qr
				<div class=\"container subtitulo\">
				{$this->fields["numero_invitado"]} Personas
				</div>        
				";	
			}			
			
			if ($date1_fecha_evento > $date2_fecha_servidor) 
			{
				$this->words["html_confirmacion_evento"]="
					<div class=\"container subtitulo\"><br>
						<select name=\"numero_invitado\" class=\"subtitulo\">
							{option_invitado}
						</select>
					</div>        
					<table class=\"subtitulo\" border=\"0\" style=\"width: 100%;\">
						<tr><td style=\"text-align: center;\" align=\"center\">
							Favor de confirmar o cancelar <br>antes del {confirmacion_evento}
						</td></tr>
					</table>
					<br>
					<div class=\"container\">    
						<font value=\"ACEPTAR\" type=\"button\">CONFIRMADA</font>    
						<font value=\"CANCELAR\" type=\"button\">CANCELADA</font>
					</div>
				";
			}
			else
			{
				/*
				$this->words["files"]="
					<div class=\"container\">   <br> 
						Compartenos tu fotos !! <br>
						<input type=\"file\">

					</div>

				";
				*/
			} 

			if($this->fields["lsalon_evento"]!="")			
				$this->words["map_salon"]	= $this->__MAP($this->fields["lsalon_evento"]);

			if($this->fields["lmisa_evento"]!="")			
				$this->words["map_misa"]	= $this->__MAP($this->fields["lmisa_evento"]);

			$this->words["option_invitado"]="";
			$checked="";
			for($a=1;$a<=$this->fields["numero_invitado"]; $a++)
			{
				if($a==$this->fields["numero_invitado"])	$checked="checked";
				$this->words["option_invitado"] ="<option value=\"$a\" $checked > $a Personas</option>" . $this->words["option_invitado"];

			}	

			$this->words 		= array_merge($this->words, $this->fields);

			return parent::__CONSTRUCT($option);
		}


		public function __SAVE($option=null)
		{	
			$complemento_sql="";
			if(isset($_REQUEST["numero_invitado"]))
				$complemento_sql=", numero_invitado='{$_REQUEST["numero_invitado"]}'";

			if(isset($_REQUEST["action"]))
			{
				$complemento_sql=", fecha_gral_invitado='" . date('Y-m-d') . "'";
			}
	
				
			$comando_sql				="
				UPDATE invitado SET status_gral_invitado='$option' $complemento_sql
				WHERE
					md5(id_invitado)='{$_REQUEST["id"]}'
			";
				
			$this->__EXECUTE($comando_sql);
		}		
		public function __INI()
		{	
			$this->words["html_confirmacion_evento"]="";

			$this->words["qr"]	="";
			$this->words["map_salon"]	="";
			$this->words["map_misa"]	="";
			$this->words["files"]		="";

		}		

	}
?>
