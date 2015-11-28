<?php



namespace EeWalk11\Minify;



/**
 * This class contains configuration settings.
 */
final class Config
{



	/*
	 * PUBLIC STATIC FUNCTIONS
	 */



	/**
	 * Get the current configuration setting for an option.
	 * <p>Valid options are:</p>
	 * <ul>
	 *   <li>"debug"</li>
	 * </ul>
	 * <p>A MinifyException will be thrown if the key is invalid.</p>
	 * @param string $key The option name.
	 */
	public static function getOption($key)
	{
		$array = self::getOptionsArray();
		if(!array_key_exists($key, $array))
		{
			throw new MinifyException("Invalid option: $key");
		}
		return $array[$key];
	}



	/**
	 * Set a Minifier config option.
	 * <p>Valid values are:</p>
	 * <ul>
	 *   <li>"debug" bool default=true — True to run checks on Minifer settings while constructing
	 *   URIs and throw MinifyExceptions upon encountering errors, false to skip checks</li>
	 * </ul>
	 * <p>A MinifyException will be thrown if the key is invalid.</p>
	 * @param string $key The option name.
	 * @param mixed $value The option value.
	 * @throws MinifyException
	 */
	public static function setOption($key, $value)
	{
		$array = &self::getOptionsArray();
		switch($key)
		{
			case "debug": $array["debug"] = (bool)$value; break;
			default: throw new MinifyException("Invalid option: $key");
		}
	}



	/*
	 * PRIVATE STATIC FUNCTIONS
	 */



	/**
	 * Get a reference to the options array.
	 * @return array The options array.
	 */
	private static function &getOptionsArray()
	{
		static $array = null;

		if($array === null)
		{
			//Initialize the array

			$array = [
				"debug" => true
			];
		}
		
		return $array;
	}



	/*
	 * CONSTRUCTOR
	 */



	/**
	 * Private constructor so this class cannot be instantiated.
	 */
	private function __construct()
	{
		//Empty constructor
	}



}


