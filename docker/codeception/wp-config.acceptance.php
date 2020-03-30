<?php

// allows URLs to work while accessing the WordPress service from the host using mapped ports
if ( 8443 === (int) $_SERVER['SERVER_PORT'] || 8080 === (int) $_SERVER['SERVER_PORT'] ) {
	$protocol = 8443 === (int) $_SERVER['SERVER_PORT'] ? 'https' : 'http';
	define( 'WP_HOME', "{$protocol}://{$_SERVER['HTTP_HOST']}" );
	define( 'WP_SITEURL', "{$protocol}://{$_SERVER['HTTP_HOST']}" );
}
