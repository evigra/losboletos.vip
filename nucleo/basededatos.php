<?php	
	class basededatos 
	{    
		public function __SYS_DB()
		{  
			$host	="localhost";
			$db		="losboletos";
			
			
			if($_SERVER["HTTP_HOST"] == "losboletos.localhost")
			{
				$host	="localhost";
				$db		="losboletos";
				$type	="mysqli";
			}
			if($_SERVER["HTTP_HOST"] == "losboletos.vip")
			{
				$host	="losboletos.vip";	
				$db		="losboletos";
				$type	="mysqli";
			}			

			return array(
				"user"		=>"evigra",
				"pass"		=>"EvG30AvI03",
				"name"		=>$db,
				"host"		=>$host,
				"type"		=>$type,
			);
		}


		
		function abrir_conexion()
		{
			$OPHP_database=$this->__SYS_DB();
			if($OPHP_database["type"]=="mysql")	        	
			{			
				@$this->OPHP_conexion = @mysql_connect($OPHP_database["host"], $OPHP_database["user"], $OPHP_database["pass"], $OPHP_database["name"]) OR $this->reconexion();
			}
			if($OPHP_database["type"]=="mysqli")	        	
			{			
				@$this->OPHP_conexion = mysqli_connect($OPHP_database["host"], $OPHP_database["user"], $OPHP_database["pass"], $OPHP_database["name"]) OR $this->reconexion();
			}

		}

		function reconexion()
		{
			$OPHP_database=$this->__SYS_DB();
			if($OPHP_database["type"]=="mysql")	        	
			{
				$this->OPHP_conexion = @mysql_connect("localhost", $OPHP_database["user"], $OPHP_database["pass"], $OPHP_database["name"]);
			}
			if($OPHP_database["type"]=="mysqli")	        	
			{
				$this->OPHP_conexion = mysqli_connect("localhost", $OPHP_database["user"], $OPHP_database["pass"], $OPHP_database["name"]);
			}

		}
		
		function cerrar_conexion()
		{
			if(isset($this->OPHP_conexion) AND is_object($this->OPHP_conexion))
		    	@$this->OPHP_conexion->close();
		    else
		    {
		    	echo "SE PRESENTO UNA FALLA EN LA CONECCION";	
		    	exit();
		    }	
		}	

		public function __PRINT_R($variable, $title=NULL)
		{  
		    echo "<div class=\"developer\" title=\"Sistema $title\"><pre>";
		    @print_r(@$variable);
		    echo "</pre></div>";		    			
    	} 
	}
?>