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
			$aux_comando="";
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
					invitado i on i.id_evento=md5(e.id_evento)
				WHERE
					md5(e.id_evento)='{$_REQUEST["id"]}'
					$aux_comando
				LIMIT 1	
			";		

			#echo $comando_sql;
			#i.id_invitado='{$_REQUEST["id"]}'		
			$this->fields		= @$this->__EXECUTE($comando_sql)[0];

			if(is_array($this->fields))
				$this->words 		= @array_merge(@$this->words, @$this->fields);

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
					$comando_sql				="
						UPDATE invitado SET 
							nombre_invitado=\"{$_REQUEST["nombre_invitado"]}\", 
							pais_telefono_invitado=\"" . trim($_REQUEST["pais_telefono_invitado"]) . "\",
							telefono_invitado=\"" . trim($_REQUEST["telefono_invitado"]) . "\"
						WHERE 
							id_invitado=\"{$_REQUEST["id_invitado"]}\"	
					";							
				else
					$comando_sql				="
						INSERT INTO invitado (id_evento,nombre_invitado,pais_telefono_invitado,telefono_invitado,email_invitado) 
						VALUES(\"{$_REQUEST["id"]}\",\"{$_REQUEST["nombre_invitado"]}\",\"" . trim($_REQUEST["pais_telefono_invitado"]) . "\",\"" . trim($_REQUEST["telefono_invitado"]) . "\",\"{$_REQUEST["email_invitado"]}\") 
					";							
				$this->__EXECUTE($comando_sql);
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
