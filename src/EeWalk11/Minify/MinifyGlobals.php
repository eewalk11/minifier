<?php



namespace EeWalk11\Minify;



/**
 * This file contains static functions to access global Minifier objects.
 */
final class MinifyGlobals
{



	/*
	 * PUBLIC STATIC FUNCTIONS
	 */



	/**
	 * Add a file to a URI.
	 * <p>If the file has already been added, this method will do nothing.</p>
	 * @param string $id An ID to indicate which URI this file will be added to. A MinifyException
	 * will be thrown if this is not a string.
	 * @param string $file The path of the file to add relative to the public directory, or to the
	 * Minify base directory if set.</p>.
	 * @return boolean True if the file was added, false otherwise.
	 * @throws MinifyException
	 */
	public static function addFile($id, $file)
	{
		return self::getMinifier($id)->addFile($file);
	}



	/**
	 * Add an array of files to a URI.
	 * <p>If any files have already been added, they will be ignored.</p>
	 * @param string $id An ID to indicate which URI these files will be added to. A MinifyException
	 * will be thrown if this is not a string.
	 * @param array|string $files An array of files to minify. A single file may be passed as well,
	 * which is the same as calling addFile(). A falsey value will be treated as an empty array. The
	 * path of each file should be relative to the public directory, or to the Minify base directory
	 * if set.
	 * @return array An array containing each new file added.
	 * @throws MinifyException
	 */
	public static function addFiles($id, $files)
	{
		return self::getMinifier($id)->addFiles($files);
	}



	/**
	 * Add a Minify group to a URI.
	 * <p>The group must be a group defined in the Minify groupsConfig.php file. If the group has
	 * already been added, this method will do nothing.</p>
	 * @param string $id An ID to indicate which URI this group will be added to. A MinifyException
	 * will be thrown if this is not a string.
	 * @param string $group The Minify group.
	 * @return boolean True if the groups was added, false otherwise.
	 * @throws MinifyException
	 */
	public static function addGroup($id, $group)
	{
		return self::getMinifier($id)->addGroup($group);
	}



	/**
	 * Add an array of Minify groups to a URI.
	 * <p>Each group must be a group defined in the Minify groupsConfig.php file. If any groups have
	 * already been added, this method will ignore them.</p>
	 * @param string $id An ID to indicate which URI these groups will be added to. A
	 * MinifyException will be thrown if this is not a string.
	 * @param array|string $groups An aray of Minify groups. A single group can be passed as well,
	 * which is the same as calling addGroup(). A falsey value will be treated as an empty array.
	 * @return array An array containing each new group added.
	 * @throws MinifyException
	 */
	public static function addGroups($id, $groups)
	{
		return self::getMinifier($id)->addGroups($groups);
	}



	/**
	 * Create a URI to minify the added files.
	 * <p>Once the URI has been generated it will be stored for this ID until any files,
	 * groups, or the base is changed. This allows the URI to be retrieved multiple times with only
	 * being generated once.</p>
	 * <p>A MinifyException will be thrown if debugging is enabled and this Minifier contains
	 * invalid settings.</p>
	 * @param string $id An ID to indicate which URI to construct. A MinifyException will be thrown
	 * if this is not a string.
	 * @return string|boolean The minify URI, false if nothing was added to minify.
	 * @throws MinifyException
	 */
	public static function createUri($id)
	{
		return self::getMinifier($id)->createUri();
	}



	/**
	 * Get the Minify base set for a URI.
	 * @param string $id An ID to indicate which URI the base will be retrieved from. A
	 * MinifyException will be thrown if this is not a string.
	 * @return string|boolean The relative path to the Minify base directory, false if unset.
	 * @throws MinifyException
	 */
	public static function getBase($id)
	{
		return self::getMinifier($id)->getBase();
	}



	/**
	 * Get an array of relative file paths added to a URI.
	 * @param string $id An ID to indicate which URI the files will be retrieved from. A
	 * MinifyException will be thrown if this is not a string.
	 * @return array An array of files.
	 * @throws MinifyException
	 */
	public static function getFiles($id)
	{
		return self::getMinifier($id)->getFiles();
	}



	/**
	 * Get an array of Minify groups added to a URI.
	 * @param string $id An ID to indicate which URI the groups will be retrieved from. A
	 * MinifyException will be thrown if this is not a string.
	 * @return array An array of groups.
	 * @throws MinifyException
	 */
	public static function getGroups($id)
	{
		return self::getMinifier($id)->getGroups();
	}



	/**
	 * Remove a file from a URI.
	 * <p>The file path must be exactly as it was added to the URI for it to be removed.</p>
	 * @param string $id An ID to indicate which URI this file will be removed from. A
	 * MinifyException will be thrown if this is not a string.
	 * @param string $file The file to remove.
	 * @return boolean True if the file was removed, false if the file was not found.
	 * @throws MinifyException
	 */
	public static function removeFile($id, $file)
	{
		return self::getMinifier($id)->removeFile($file);
	}



	/**
	 * Remove an array of files from a URI.
	 * <p>A file path must be exactly as it was added to the URI for it to be removed.</p>
	 * @param string $id An ID to indicate which URI these files will be removed from. A
	 * MinifyException will be thrown if this is not a string.
	 * @param mixed $files An array of files to remove. A single file may be passed as well, which
	 * is the same as calling removeFile(). A falsey value will be treated as an empty array.</p>
	 * @return array An array containing all file names that were removed.
	 * @throws MinifyException
	 */
	public static function removeFiles($id, $files)
	{
		return self::getMinifier($id)->removeFiles($files);
	}



	/**
	 * Remove a Minify group from a URI.
	 * @param string $id An ID to indicate which URI this group will be removed from. A
	 * MinifyException will be thrown if this is not a string.
	 * @param string $group The group to remove.
	 * @return boolean True if the group was removed, false if the group was not found.
	 * @throws MinifyException
	 */
	public static function removeGroup($id, $group)
	{
		return self::getMinifier($id)->removeGroup($group);
	}



	/**
	 * Remove an array of Minify groups from a URI.
	 * @param string $id An ID to indicate which URI these groups will be removed from. A
	 * MinifyException will be thrown if this is not a string.
	 * @param mixed $groups An aray of Minify groups. A single group can be passed as well, which is
	 * the same as calling removeGroup(). A falsey value will be treated as an empty array.
	 * @return array An array containing all group names that were removed.
	 * @throws MinifyException
	 */
	public static function removeGroups($id, $groups)
	{
		return self::getMinifier($id)->removeGroups($groups);
	}



	/**
	 * Set the base directory for files to minify.
	 * @param string $id An ID to indicate which URI this base will be set for. A MinifyException
	 * will be thrown if this is not a string.
	 * @param string|boolean $dir The directory path relative to the document root, false to remove
	 * a base directory.
	 * @return boolean True if the base directory was modified, false otherwise.
	 * @throws MinifyException
	 */
	public static function setBase($id, $dir)
	{
		return self::getMinifier($id)->setBase($dir);
	}



	/*
	 * PRIVATE STATIC FUNCTIONS
	 */



	/**
	 * Get a global Minifier by its ID.
	 * <p>If the Minifier has not been constructed yet, it will be constructed and then fetched.</p>
	 * @param string $id The ID of the Minifier to fetch. A MinifyException will be thrown if this
	 * is not a string.
	 * @return Minfier The requested Minifier.
	 * @throws MinifyException
	 */
	private static function getMinifier($id)
	{
		if(!is_string($id))
		{
			throw new MinifyException("Invalid Minifier ID: $id");
		}

		//Create a static array in which to store global Minifiers

		static $array = null;
		if($array === null)
		{
			$array = [];
		}

		//Fetch the Minifier if it has been constructed
		//Create and store a new Minifier if not

		if(array_key_exists($id, $array))
		{
			$min = $array[$id];
		}
		else
		{
			$min = new Minifier;
			$array[$id] = $min;
		}

		return $min;
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


