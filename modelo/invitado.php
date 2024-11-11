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
			
			if(isset($_REQUEST["action"]))
			{
				$this->__SAVE();
			}
			$this->__INI();
			

			$comando_sql				="
				SELECT * 
				FROM 
					evento e left join 
					invitado i on i.id_evento=e.id_evento
				WHERE
					md5(e.id_evento)='{$_REQUEST["id"]}'
					
			";		
			#i.id_invitado='{$_REQUEST["id"]}'		
			$this->fields		= @$this->__EXECUTE($comando_sql)[0];
			if(is_array($this->fields))
				$this->words 		= @array_merge(@$this->words, @$this->fields);

			return parent::__CONSTRUCT($option);
		}


		public function __SAVE()
		{	
			if(isset($_REQUEST["nombre_invitado"]) and $_REQUEST["nombre_invitado"]!="")
			{
				$comando_sql				="
					INSERT INTO invitado (id_evento,nombre_invitado,telefono_invitado,email_invitado) 
					VALUES(\"{$_REQUEST["id"]}\",\"{$_REQUEST["nombre_invitado"]}\",\"{$_REQUEST["telefono_invitado"]}\",\"{$_REQUEST["email_invitado"]}\") 
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
		}		
	}
?>
