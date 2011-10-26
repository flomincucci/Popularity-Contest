<?php
add_action('wp_ajax_votar', 'quierovotar');
add_action('wp_ajax_nopriv_votar', 'quierovotar');

function quierovotar() {
 $votado = $_POST['votado'];
 $votante =  $_POST['votante'];
 vote($votante, $votado);
 echo getvotes($votado);
 die();
}

?>
