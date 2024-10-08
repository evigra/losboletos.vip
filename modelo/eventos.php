<?php
	class eventos extends general
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
					evento e 
					
			";				
			$this->datas				= $this->__EXECUTE($comando_sql);

			#$this->__PRINT_R($this->datas);

			$datas="";

			
			foreach($this->datas as $data)
			{	
				$url_evento="http://losboletos.vip/invitaciones/show/&id=".md5($data["id_evento"]);
				$wa1_evento="https://wa.me/+52{$data["tel1_evento"]}?text=". urlencode($url_evento);
				$wa2_evento="https://wa.me/+52{$data["tel2_evento"]}?text=". urlencode($url_evento);
	
				
				$datas.="
					<tr>
						<td>
							<a href=\"$wa1_evento\" target=\"_blank\">{$data["tel1_evento"]}</a>
							<a href=\"$wa1_evento\" target=\"_blank\">{$data["tel2_evento"]}</a>
						</td>
						<td>{$data["nombre_evento"]}</td>
						<td>{$data["fecha_evento"]}</td>
					</tr>
				";
			}
			
			$this->words["datos"]=$datas;

			return parent::__CONSTRUCT($option);
		}
	}
?>
