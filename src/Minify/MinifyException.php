<?php



namespace Minify;

use Exception;



/**
 * An Exception to be thrown by Minifier.
 */
class MinifyException extends Exception
{



	/**
	 * Construct a new exception.
	 * @param string $message The error message.<br>
	 * <i>(default = null)</i>
	 */
	public function __construct($message = null)
	{
		$fullMessage = "Minifier encountered a problem";
		if($message)
		{
			$fullMessage .= ": $message";
		}
		parent::__construct($message);
	}



}


