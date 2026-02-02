<?php


	$objeto			=new galeria();

	//$objeto->words["html_head_css"]			="default"; 
	$objeto->words["html_head_title"]		.="Invitacion";
	
	$objeto->words["html_head_description"]		="Eres tan especial, que te damos LosBoletos.VIP";
	$objeto->words["html_head_keywords"]		="boletos, losboletos, losboletos.vip, vip, evento, fiesta, invitacion";

	#$objeto->__PRINT_R($_REQUEST);
	




	$objeto->words["html_body"]				=$objeto->__VIEW_BASE("body", $objeto->words);

	$objeto->words["html_left"]				="";

	#$objeto->__PRINT_R($_REQUEST);


	$objeto->words["html_center"]			=$objeto->__VIEW_BASE("galeria", $objeto->words);


	
	$objeto->words["html_right"]			="";

	$objeto->words["html_menu"]				=$objeto->__VIEW_BASE("menu", $objeto->words);
	$objeto->words["html_pie"]				=$objeto->__VIEW_BASE("pie", $objeto->words);
	
	echo $objeto->__VIEW_BASE("index", $objeto->words);	
?>

