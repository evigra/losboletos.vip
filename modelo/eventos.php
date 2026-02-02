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


			$aux_comando="";
			if(isset($_REQUEST["id"]))
			{
				$aux_comando=" WHERE e.id_evento='{$_REQUEST["id"]}'";
			}


			$comando_sql				="
				SELECT * 
				FROM 
					evento e 	
				$aux_comando	
			";			
			$this->datas				= $this->__EXECUTE($comando_sql);


			

			$datas="";

			
			foreach($this->datas as $data)
			{	

				if(isset($_REQUEST["id"]))
				{
					$this->fields=$data;

				}
				$url_evento="http://losboletos.vip/invitaciones/show/&id=".md5($data["id_evento"]);
				$wa1_evento="https://wa.me/+52{$data["tel1_evento"]}?text=". urlencode($url_evento);
				$wa2_evento="https://wa.me/+52{$data["tel2_evento"]}?text=". urlencode($url_evento);
	
				
				$datas.="
					<tr>
					
						<td width=\"70\" style=\"height:70px; text-align:center; vertical-align: middle;\">
							<a href=\"../../eventos/show/&id={$data["id_evento"]}\"><font class=\"ui-icon ui-icon-pencil\"></font></a> 
						</td>

						<td>
							<a href=\"$wa1_evento\" target=\"_blank\">{$data["tel1_evento"]}</a>
							<a href=\"$wa1_evento\" target=\"_blank\">{$data["tel2_evento"]}</a>
						</td>
						<td>{$data["nombre_evento"]}</td>
						<td>{$data["fecha_evento"]}</td>
					</tr>
				";
			}

			if(isset($this->fields))
				$this->words 		= array_merge($this->words, $this->fields);

			$this->words["datos"]=$datas;

			return parent::__CONSTRUCT($option);
		}
	}
?>
