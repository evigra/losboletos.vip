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

			#$this->__PRINT_R($this->fields);

			if($this->fields["status_gral_invitado"]=="ACEPTAR")			
				$this->words["qr"]	= $this->__QR("http://losboletos.vip/invitacion/show/&estado=ingreso&id=" . $_REQUEST["id"], 400);

			if($this->fields["lsalon_evento"]!="")			
				$this->words["map_salon"]	= $this->__MAP($this->fields["lsalon_evento"]);

			if($this->fields["lmisa_evento"]!="")			
				$this->words["map_misa"]	= $this->__MAP($this->fields["lmisa_evento"]);


			$this->words 		= array_merge($this->words, $this->fields);

			return parent::__CONSTRUCT($option);
		}


		public function __SAVE($option=null)
		{	

			$comando_sql				="
				UPDATE invitado SET status_gral_invitado='$option', numero_invitado='{$_REQUEST["numero_invitado"]}'
				WHERE
					md5(id_invitado)='{$_REQUEST["id"]}'
			";
				
			$this->__EXECUTE($comando_sql);
		}		
		public function __INI()
		{	

			$this->words["qr"]	="";
			$this->words["map_salon"]	="";
			$this->words["map_misa"]	="";

		}		

	}
?>
