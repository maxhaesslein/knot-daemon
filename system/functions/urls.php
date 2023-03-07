<?php

function normalize_url( $url, $fragment_allowed = true ) {

	$url = parse_url($url);

	if( array_key_exists('path', $url) && $url['path'] == '' ) {
		return false;
	}

	// parse_url returns just "path" for naked domains, so
	// move that into the "host" instead
	if( count($url) == 1 && array_key_exists('path', $url) ) {
		if( preg_match('/([^\/]+)(\/.+)/', $url['path'], $match) ) {
			$url['host'] = $match[1];
			$url['path'] = $match[2];
		} else {
			$url['host'] = $url['path'];
			unset($url['path']);
		}
	}

	if( ! array_key_exists('scheme', $url) ) {
		$url['scheme'] = 'http';
	}

	if( ! array_key_exists('path', $url) ) {
		$url['path'] = '/';
	}

	// Invalid scheme
	if( ! in_array($url['scheme'], array('http','https')) ) {
		return false;
	}

	if( ! $fragment_allowed ) {
		// fragment not allowed
		if( array_key_exists('fragment', $url) ) {
			return false;
		}
	}

	return build_url($url);
}


function build_url( $parsed_url ) {
	$scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
	$host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
	$port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
	$user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
	$pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
	$pass     = ($user || $pass) ? "$pass@" : '';
	$path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
	$query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
	$fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

	return "$scheme$user$pass$host$port$path$query$fragment";
}
