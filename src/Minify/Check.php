<?php



namespace Minify;



/**
 * This class contains static functions to check Minify files and settings.
 */
final class Check
{



	/*
	 * PUBLIC STATIC FUNCTIONS
	 */



	/**
	 * Check if a base directory exists and is readable.
	 * <p>A MinifyException will be thrown if the directory is invalid.</p>
	 * @param string $dir The path to the directory from the document root.
	 * @throws MinifyException
	 */
	public static function base($dir)
	{
		$path = filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/$dir";

		if(!file_exists($path))
		{
			throw new MinifyException("The Minify base does not exist: $path");
		}
		if(!is_dir($path))
		{
			throw new MinifyException("The Minify base is not a directory: $path");
		}
		if(!is_readable($path))
		{
			throw new MinifyException("The Minify base is not readable");
		}
	}



	/**
	 * Check if a file exists and is readable.
	 * <p>A MinifyException will be thrown if the file is invalid.</p>
	 * @param string $file The file path from the document root (or Minify base directory if set).
	 * @param string|boolean $base The Minify base directory, false if unset.
	 * @throws MinifyException
	 */
	public static function file($file, $base)
	{
		$fullpath = filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/"
			. ($base ? "$base/" : null) . $file;

		if(!file_exists($fullpath))
		{
			throw new MinifyException("File not exist: $fullpath");
		}
		if(!is_readable($fullpath))
		{
			throw new MinifyException("File is not readable: $fullpath");
		}
	}



	/**
	 * Check if a Minify group is configured.
	 * <p>A MinifyException will be thrown if the group is not configured in groupsConfig.php.</p>
	 * @param string $group The Minify group name to check.
	 * @throws MinifyException
	 */
	public static function group($group)
	{
		//Load groupsConfig.php (only once)

		static $groups = null;
		if($groups === null)
		{
			$path = filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/min/groupsConfig.php";
			$groups = require($path);
		}

		//Check the group

		if(!array_key_exists($group, $groups))
		{
			throw new MinifyException("Cannot add group: does not exist: $group");
		}
	}



	/**
	 * Check if Minify has been added to the project and is readable.
	 * <p>A MinifyException will be thrown if Minify is not correctly installed.</p>
	 * @throws MinifyException
	 */
	public static function install()
	{
		$path = filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/min";

		if(!is_dir($path))
		{
			throw new MinifyException("Minify has not been installed in this project");
		}
		if(!is_readable($path))
		{
			throw new MinifyException("The Minify directory is not readable");
		}
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


