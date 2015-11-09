<?php



namespace Minify;



/**
 * Generate a minify URI.
 * <p>This function is simply a wrapper function to generating a Minify/UriBuilder and running it.
 * @param Minifier|\JsonSerializable|array $data Any value that can be used to construct a
 * Minify/UriBuilder.
 */
function minify($data)
{
	$builder = new UriBuilder($data);
	return $builder->buildUri();
}


