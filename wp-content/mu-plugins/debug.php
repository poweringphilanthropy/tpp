<?php

add_action( 'template_redirect', 'debug' );
function debug(){
	if(!isset($_GET['debug']))
		return;

	$x = pp_get_connected_organization_options();
	echo "<pre>";
	print_r($x);
	echo "</pre>";
	exit();
}