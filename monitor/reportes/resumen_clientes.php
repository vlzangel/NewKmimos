<?php

require_once( 'class/clientes.php' );

$clientes = new clientes();

echo '<pre>';
print_r( $clientes->get_clientes( '9' ) );
echo '</pre>';

