<?php
	class galeria extends general
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
			$comando_sql				="
				SELECT * FROM evento e 
				WHERE
				md5(e.id_evento)='{$_REQUEST["id"]}'
			";				
			$this->fields				= @$this->__EXECUTE($comando_sql)[0];

			if(isset($_REQUEST["action"]))
			{
				$this->__SAVE($_REQUEST["action"]);
			}


			$this->__INI();


			$comando_sql				="
				SELECT * 
				FROM 
					evento e left join 
					file f on f.id_evento=e.id_evento
				WHERE
					md5(e.id_evento)='{$_REQUEST["id"]}'
			";				
			$this->fields				= @$this->__EXECUTE($comando_sql);


			#$this->__PRINT_R($this->fields);

			$imagen_qr = $this->__QR("http://losboletos.vip/galeria/show/&id=" . $_REQUEST["id"], 400);
			#$this->words["md5_id_invitado"]=md5($this->fields["id_invitado"]);
			$this->words["qr"] = "$imagen_qr <br> ";	
			
			$this->words["files"]="	
<label class=\"file-btn\">
Selecciona 📷
  <input type=\"file\" name=\"files[]\" accept=\"image/*\" multiple>
  
</label>
<font value=\"CARGAR\" type=\"button\">Subirlas</font>    

<img id=\"preview\" alt=\"Previsualización\" />	
				";

			if(is_array($this->fields) and is_array($this->fields[0]))
				$this->words 		= array_merge($this->words, $this->fields[0]);

			#foreach($values as $row => $data)	
			foreach($this->fields as $foto)
			{
				$path="../..";
				$path="http://losboletos.vip";

				if(@$this->words["fotos"]=="")	
					$this->words["fotos"]="<img src=\"$path/files/file_". md5($foto["id_file"])  .".jpeg\" class=\"active\">";
				$this->words["fotos"].="<img src=\"$path/files/file_". md5($foto["id_file"])  .".jpeg\">";
			}


			return parent::__CONSTRUCT($option);
		}


		public function __SAVE($option=null)
		{	
				
			#$this->__EXECUTE($comando_sql);



			if(isset($_FILES["files"]))
			{	
				$files_available							=array("image/png","image/jpeg", "video/mp4");

				foreach($_FILES["files"] as $field => $values)
				{		        
					foreach($values as $row => $data) 
					{
						if(in_array($_FILES["files"]["type"][$row], $files_available))
						{
							$width 			= 0;
							$height 		= 0;

							if($field=="name")
							{
								$path="files/";
								/*
								if(!isset($events_id)) 
								{
									$events_id			=$this->__EXECUTE($comando_sql);
									if(isset($_REQUEST["event"]))
										$events_id=$events_id[0]["event_id"];
								}
								*/
								$newHeight 		= 0;
								$newWidth 		= 0;
								$orientation 	= "";

								$temporal		=$_FILES["files"]["tmp_name"][$row];
								$temporal_img	=$temporal;

								$vname			=explode(".", $_FILES["files"]["name"][$row]);
								$extencion		=$vname[count($vname)-1];
								$extencion_img	=$extencion;

								$vtype			=explode("/", $_FILES["files"]["type"][$row]);
								$type			=$vtype[0];

								
								$data_im			=$this->__PROCESS_IMG($temporal_img);							
								$im					=$data_im["im"];
								$width				=$data_im["width"];
								$height				=$data_im["height"];
								$orientation		=$data_im["orientation"];
					
								$comando_sql		="INSERT INTO file (invitado_id, id_evento, documento, tipo_file)
								VALUES(	
									'{$_REQUEST["id"]}', 
									'" . $this->fields["id_evento"] ."',
									'galeria',
									'" . $extencion_img . "'
								)";
								$file_id			=$this->__EXECUTE($comando_sql);													

								$archivo 			=$path . "file_" . md5($file_id);

								// redimencionada
								$im->writeImage($archivo.".".$extencion_img );	
								$th				=$im;

								// thumb
								$redimencion	=$this->__REDIMENSION(180, $width, $height);								
								$height 		= $redimencion[0];	
								$width 			= $redimencion[1];								
								$th->resizeImage($width,$height, imagick::FILTER_LANCZOS, 0.8, true);					

								$th->writeImage($archivo."_th.".$extencion_img);
								
							}						
						}	
					}
				}					
			}



		}		
		public function __INI()
		{	
			$this->words["html_confirmacion_evento"]="";

			$this->words["qr"]	="";
			$this->words["map_salon"]	="";
			$this->words["map_misa"]	="";
			$this->words["files"]		="";

		}		


		public function __PROCESS_IMG($temporal)
    	{    	
			$im 			= new imagick($temporal);
					
			$matrizExif = $im->getImageProperties("exif:*");

			$imageprops 	= $im->getImageGeometry();
			$width 			= $imageprops['width'];
			$height 		= $imageprops['height'];

			$redimencion	=$this->__REDIMENSION(700, $width, $height);

			$newWidth 			= $redimencion[1];
			$newHeight 		= $redimencion[0];	
			$im->resizeImage($newWidth,$newHeight, imagick::FILTER_LANCZOS, 0.8, true);					

			/*
			$logo = new Imagick();
			$logo->readImage("logo.png") or die("Couldn't load $logo");
			*/
			if(@$matrizExif["exif:Orientation"]==1)				$orientation 	= "horizontal";										
			if(@$matrizExif["exif:Orientation"]==6)
			{
				$width 			= $newHeight;
				$height 		= $newWidth;	

				$orientation 	= "vertical";
				//$logo->rotateimage(new ImagickPixel(), 270);
			}
			if(@$matrizExif["exif:Orientation"]==8)
			{
				$width 			= $newHeight;
				$height 		= $newWidth;	

				$orientation 	= "vertical";
				//$logo->rotateimage(new ImagickPixel(), 90);
			}

			if(@$orientation=="")
			{
				if($height>$width)	$orientation 	= "vertical";
				else				$orientation 	= "horizontal";
			}

			$return=array(
				"im"			=>$im,
				"width"			=>$width,
				"height"		=>$height,
				"orientation"	=>$orientation,
			);
			return $return;
		}		



	}
?>
