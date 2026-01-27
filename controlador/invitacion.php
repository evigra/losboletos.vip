<?php


	$objeto			=new invitacion();

	//$objeto->words["html_head_css"]			="default"; 
	$objeto->words["html_head_title"]		.="Invitacion";
	
	$objeto->words["html_head_description"]		="Eres tan especial, que te damos LosBoletos.VIP";
	$objeto->words["html_head_keywords"]		="boletos, losboletos, losboletos.vip, vip, evento, fiesta, invitacion";

	#$objeto->__PRINT_R($_REQUEST);
	




	$objeto->words["html_body"]				=$objeto->__VIEW_BASE("body", $objeto->words);

	$objeto->words["html_left"]				="";

	#$objeto->__PRINT_R($_REQUEST);


	if(isset($_REQUEST["estado"]) and $_REQUEST["estado"]=="ingreso")
		$objeto->words["html_center"]			=$objeto->__VIEW_BASE("invitacion_ingreso", $objeto->words);
	elseif(isset($objeto->fields["status_gral_invitado"]) and $objeto->fields["status_gral_invitado"]=="CANCELAR")
		$objeto->words["html_center"]			=$objeto->__VIEW_BASE("invitacion_cancelada", $objeto->words);
	elseif($_REQUEST["method"]=="sobre")
		$objeto->words["html_center"]			=$objeto->__VIEW_BASE("invitacion_sobre", $objeto->words);
	else
		$objeto->words["html_center"]			=$objeto->__VIEW_BASE("invitacion", $objeto->words);


	
	$objeto->words["html_right"]			="";

	$objeto->words["html_menu"]				=$objeto->__VIEW_BASE("menu", $objeto->words);
	$objeto->words["html_pie"]				=$objeto->__VIEW_BASE("pie", $objeto->words);
	
	echo $objeto->__VIEW_BASE("index", $objeto->words);	
?>

