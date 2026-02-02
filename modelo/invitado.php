<?php
	class invitado extends general
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
			$aux_comando=" AND i.id_invitado=''";
			if(isset($_REQUEST["id_invitado"]))
			{
				$aux_comando=" AND i.id_invitado='{$_REQUEST["id_invitado"]}'";
			}
			

			if(isset($_REQUEST["action"]))
			{
				$this->__SAVE();
			}
			$this->__INI();
			

			$comando_sql				="
				SELECT * 
				FROM 
					evento e left join 
					invitado i on i.id_evento=md5(e.id_evento) $aux_comando
				WHERE
					md5(e.id_evento)='{$_REQUEST["id"]}'					
				LIMIT 1	
			";		

			#echo $comando_sql;
			#i.id_invitado='{$_REQUEST["id"]}'		
			$this->fields		= @$this->__EXECUTE($comando_sql)[0];

			


			if(is_array($this->fields))
			{
				if($this->fields["nombre_invitado"]=="")
				{
					$this->fields["nombre_invitado"]="";
					$this->fields["telefono_invitado"]="";
					$this->fields["numero_invitado"]=$_REQUEST["b"];
				}	

				$this->words["texto_numero_invitado"]="";
				if($this->fields["numero_invitado"]!=-1)
				{				
					$this->words["texto_numero_invitado"]="
						<br><br><br>
						<div class=\"container titulo cursiva\">Esta invitacion es para</div>
						<div class=\"container titulo\">{numero_invitado}</div>
					";

				}	


				$this->words 		= @array_merge(@$this->words, @$this->fields);
			}	
			



			if(isset($_REQUEST["id_invitado"]))
			{
				$id_invitado	=@md5(    $this->fields["id_invitado"]       );



				$url_text	="http://losboletos.vip/invitacion/show/&id=" . $id_invitado;
				$this->words["link_invitacion"]="<a href=\"$url_text\">VER INVITACION</a>";
			}
	

			return parent::__CONSTRUCT($option);
		}


		public function __SAVE()
		{	
			if(isset($_REQUEST["nombre_invitado"]) and $_REQUEST["nombre_invitado"]!="")
			{

				if(isset($_REQUEST["id_invitado"]) and $_REQUEST["id_invitado"]!="")	
					$comando_sql="
						UPDATE invitado SET 
							nombre_invitado=\"{$_REQUEST["nombre_invitado"]}\", 
							pais_telefono_invitado=\"" . trim($_REQUEST["pais_telefono_invitado"]) . "\",
							telefono_invitado=\"" . trim($_REQUEST["telefono_invitado"]) . "\"
						WHERE 
							id_invitado=\"{$_REQUEST["id_invitado"]}\"	
					";							
				else
					$comando_sql ="INSERT INTO invitado (id_evento,nombre_invitado,pais_telefono_invitado,telefono_invitado,numero_invitado) 
						VALUES(\"{$_REQUEST["id"]}\",\"{$_REQUEST["nombre_invitado"]}\",\"" . trim($_REQUEST["pais_telefono_invitado"]) . "\",\"" . trim($_REQUEST["telefono_invitado"]) . "\",\"" . trim($_REQUEST["numero_invitado"]) . "\") 
					";
					
				#$this->__PRINT_R($this->__EXECUTE($comando_sql));
				$return=$this->__EXECUTE($comando_sql);

				$id_invitado	=@md5($return);
				#nucleo/qrlib/imagen_qr.php?data=

				$url_text	=urlencode("http://losboletos.vip/invitacion/show/&id=" . $id_invitado);
				$url_qr		=urlencode("http://losboletos.vip/nucleo/qrlib/imagen_qr.php?data=$url_text");

				$texto_wa_numero_invitado="";
				if($_REQUEST["numero_invitado"]!=-1)	
					$texto_wa_numero_invitado=" para {$_REQUEST["numero_invitado"]} personas";

$text_wa=urlencode("{$_REQUEST["nombre_invitado"]} \n
Nos complace enviarte la invitación$texto_wa_numero_invitado, a un evento muy especial para nosotros. \n
Deseamos disfrutar éste día con personas con quienes hemos compartido valiosos momentos de nuestra vida.  \n
Personas positivas que nos acompañen con alegría y buena vibra en ese momento tan especial para nosotros.	\n
Esperamos contar con tu puntual asistencia.\n 
Confirmanos por medio del siguiente link:\n
") . $url_text;

				$wa="https://wa.me/". trim($_REQUEST["pais_telefono_invitado"]) . trim($_REQUEST["telefono_invitado"]). "?text=$text_wa";

				header("Location: $wa");
				exit;				
			}	
			else
			{

				
			}
		}		
		public function __INI()
		{	
			$this->words["qr"]	="";
			$this->words["map_salon"]	="";
			$this->words["map_misa"]	="";
			$this->words["link_invitacion"]="";
		}		
	}
?>
