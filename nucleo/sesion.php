<?php		
	if(isset($_REQUEST["datos"]) AND $_REQUEST["datos"]=="")					$_REQUEST["datos"]="index/show/";	

	if(isset($_REQUEST["datos"]))	
	{		
		if($_REQUEST["datos"]!="")	
		{
			$vpath						=explode("/", $_REQUEST["datos"]);	
			$_REQUEST["path"]			=$vpath[0] . "/";
			$_REQUEST["method"]			=$vpath[1];
			$_REQUEST["class"]			=$vpath[0];
		}
		
		unset($_REQUEST["datos"]);	
	}

	if(isset($_COOKIE["losboletos"]))
	{
		setcookie("losboletos", $_COOKIE["losboletos"], time()+ (60 * 60 * 24 *31), "/", $_REQUEST["server"]);

		if(!isset($_SESSION))
		{
			$usuarios_sesion						="PHPSESSID";
			session_name($usuarios_sesion);
			session_start();
		}

		if(!isset($_SESSION["user"]))
		{
			$path_model="modelo/Sesion.php";		
			require_once($path_model);			
			$objeto			=new sesion();
			$objeto->__COOKIE();			
		}
	}

	if(@$_GET["sys_action"]=="cerrar_sesion")
	{
		if(isset($_SESSION))
		{			
			session_destroy();			
		}	
		if(isset($_COOKIE))
		{							
			setcookie("losboletos", $_COOKIE["losboletos"], time() - (3600), "/", $_REQUEST["server"]);
			unset($_COOKIE['losboletos']);
			//setcookie("losboletos", "", time() - 3600, "/");
		}	
		Header ("Location: http://"  . $_REQUEST["server"] . "/Mapa/Show/");			
	}
	
	$pre_path="";	
?>