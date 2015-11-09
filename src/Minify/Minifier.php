<?php



namespace Minify;

use JsonSerializable;



/**
 * This class builds a URI to pass JavaScript and CSS files to Minify.
 */
class Minifier implements JsonSerializable
{



	/*
	 * PROTECTED VARIABLES
	 */



	/**
	 * @var string|boolean the base directory for files to be minified, false if unset.
	 */
	protected $base = false;

	/**
	 * @var array An array of relative file paths to minify.
	 */
	protected $files = [];

	/**
	 * @var array An array of Minify groups.
	 */
	protected $groups = [];



	/**
	 * @var string|boolean Once a URI is generated for this Minifier it will be stored here. If any
	 * settings are changed it will be reset to null.
	 */
	protected $uri = null;



	/*
	 * CONSTRUCTOR / DESTRUCTOR
	 */



	/**
	 * Construct a new minifier.
	 */
	public function __construct()
	{
		//Empty constructor
	}



	/**
	 * Destroy this minifier.
	 */
	public function __destruct()
	{
		//Empty destructor
	}



	/*
	 * PUBLIC IMPLEMENTED METHODS
	 */



	/**
	 * Get a JSON encodable array.
	 * @return array An array in the following format:
	 * <ul>
	 *   <li>"base" => The base directory, false if unset.</li>
	 *   <li>"groups" => An array of Minify group names.</li>
	 *   <li>"files" => An array of relative file paths.</li>
	 * <ul>
	 */
	public function jsonSerialize()
	{
		return [
			"base" => $this->base,
			"groups" => $this->groups,
			"files" => $this->files
		];
	}



	/*
	 * PUBLIC METHODS
	 */



	/**
	 * Add a file to minify.
	 * <p>If the file has already been added, this method will do nothing.</p>
	 * @param string $file The path of the file to add relative to the public directory, or to the
	 * Minify base directory if set.</p>.
	 * @return boolean True if the file was added, false otherwise.
	 */
	public function addFile($file)
	{
		return $this->addItem($file, $this->files);
	}



	/**
	 * Add an array of files to minify.
	 * <p>If any files have already been added, they will be ignored.</p>
	 * @param array|string $files An array of files to minify. A single file may be passed as well,
	 * which is the same as calling addFile(). A falsey value will be treated as an empty array. The
	 * path of each file should be relative to the public directory, or to the Minify base directory
	 * if set.
	 * @return array An array containing each new file added.
	 */
	public function addFiles($files)
	{
		return $this->addArray($files, $this->files);
	}



	/**
	 * Add a Minify group.
	 * <p>The group must be a group defined in the Minify groupsConfig.php file. If the group has
	 * already been added, this method will do nothing.</p>
	 * @param string $group The Minify group.
	 * @return boolean True if the group was added, false otherwise.
	 */
	public function addGroup($group)
	{
		return $this->addItem($group, $this->groups);
	}



	/**
	 * Add an array of Minify groups.
	 * <p>Each group must be a group defined in the Minify groupsConfig.php file. If any groups have
	 * already been added, this method will ignore them.</p>
	 * @param array|string $groups An aray of Minify groups. A single group can be passed as well,
	 * which is the same as calling addGroup(). A falsey value will be treated as an empty array.
	 * @return array An array containing each new group added.
	 */
	public function addGroups($groups)
	{
		return $this->addArray($groups, $this->groups);
	}



	/**
	 * Create a URI to minify the added files.
	 * <p>Once the URI has been generated it will be stored in this Minifier until any files,
	 * groups, or the base is changed. This allows the URI to be retrieved multiple times with only
	 * being generated once.</p>
	 * <p>A MinifyException will be thrown if debugging is enabled and this Minifier contains
	 * invalid settings.</p>
	 * @return string|boolean The minify URI, false if nothing was added to minify.
	 * @throws MinifyException
	 */
	public function createUri()
	{
		if($this->uri === null)
		{
			//Generate the URI
			$builder = new UriBuilder($this);
			$this->uri = $builder->buildUri();
		}

		return $this->uri;
	}



	/**
	 * Get the Minify base set for this Minifier.
	 * @return string|boolean The relative path to the Minify base directory, false if unset.
	 */
	public function getBase()
	{
		return $this->base;
	}



	/**
	 * Get an array of relative file paths added to this Minifier.
	 * @return array An array of files.
	 */
	public function getFiles()
	{
		return $this->files;
	}



	/**
	 * Get an array of Minify groups added to this Minifier.
	 * @return array An array of groups.
	 */
	public function getGroups()
	{
		return $this->groups;
	}



	/**
	 * Remove a file from this Minifier.
	 * <p>The file path must be exactly as it was added to this Minifier for it to be removed.</p>
	 * @param string $file The file to remove.
	 * @return boolean True if the file was removed, false if the file was not found.
	 */
	public function removeFile($file)
	{
		return $this->removeItem($file, $this->files);
	}



	/**
	 * Remove an array of files from this Minifier.
	 * <p>A file path must be exactly as it was added to this Minifier for it to be removed.</p>
	 * @param array|string $files An array of files to remove. A single file may be passed as well,
	 * which is the same as calling removeFile(). A falsey value will be treated as an empty
	 * array.</p>
	 * @return array An array containing all file names that were removed.
	 */
	public function removeFiles($files)
	{
		return $this->removeArray($files, $this->files);
	}



	/**
	 * Remove a Minify group from this Minifier.
	 * @param string $group The group to remove.
	 * @return boolean True if the group was removed, false if the group was not found.
	 */
	public function removeGroup($group)
	{
		return $this->removeItem($group, $this->groups);
	}



	/**
	 * Remove an array of Minify groups from this Minifier.
	 * @param array|string $groups An aray of Minify groups. A single group can be passed as well,
	 * which is the same as calling removeGroup(). A falsey value will be treated as an empty array.
	 * @return array An array containing all group names that were removed.
	 */
	public function removeGroups($groups)
	{
		return $this->removeArray($groups, $this->groups);
	}



	/**
	 * Set the base directory for files to minify.
	 * @param string|boolean $dir The directory path relative to the document root, false to remove
	 * a base directory.
	 * @return boolean True if the base directory was modified, false otherwise.
	 */
	public function setBase($dir)
	{
		$base = $dir || is_string($dir) ? $dir : false;

		if(($mod = $base != $this->base))
		{
			$this->clearUri();
			$this->base = $base;
		}

		return $mod;
	}



	/*
	 * PROTECTED METHODS
	 */



	/**
	 * Clear this Minifier's stored URI.
	 */
	protected function clearUri()
	{
		$this->uri = null;
	}



	/*
	 * PRIVATE METHODS
	 */



	/**
	 * Add an array of items to one of this Minifier's data arrays.
	 * @param array|string $add The items to add.
	 * @param array $array Reference to the array to add to.
	 * @return array An array containing all new items that were added.
	 */
	private function addArray($add, &$array)
	{
		//Convert the argument to an array

		if(!is_array($add))
		{
			$add = $add ? [$add] : [];
		}

		//Add all items in the array

		$added = [];

		foreach($add as $item)
		{
			if($this->addItem($item, $array))
			{
				$added[] = $item;
			}
		}

		//NOTE: Do not clear the stored URI here, calling $this->addItem() will clear it

		return $added;
	}



	/**
	 * Add an item to one of this Minifier's data arrays.
	 * @param string $item The item to add.
	 * @param array $array Reference to the array to add to.
	 * @return boolean True if the item was added, false otherwise.
	 */
	private function addItem($item, &$array)
	{
		//Add the item

		$added = false;

		if($item && is_string($item) && !in_array($item, $array))
		{
			$array[] = $item;
			$added = true;
		}

		//Clear the stored URI if the item was added

		if($added)
		{
			$this->clearUri();
		}

		return $added;
	}



	/**
	 * Remove an array of items from one of this Minifier's data arrays.
	 * @param array|string $rem The items to remove.
	 * @param array $array Reference to the array to remove from.
	 * @return array An array containing all items that were removed.
	 */
	private function removeArray($rem, &$array)
	{
		//Convert the argument to an array

		if(!is_array($rem))
		{
			$rem = $rem ? [$rem] : [];
		}

		//Remove all items in the array

		$removed = [];

		foreach($rem as $item)
		{
			if($this->removeItem($item, $array))
			{
				$removed[] = $item;
			}
		}

		//NOTE: Do not clear the stored URI here, calling $this->removeItem() will clear it

		return $removed;
	}



	/**
	 * Remove an item from one of this Minifier's data arrays.
	 * @param sttring $item The item to remove.
	 * @param array $array Reference to the array to remove from.
	 * @return boolean True if the item was removed from the array, false if the item was not found.
	 */
	private function removeItem($item, &$array)
	{
		//Remove the item from the array

		$rem = false;

		if(($index = array_search($item, $array)) !== false)
		{
			unset($array[$index]);
			$rem = true;
		}

		//Clear the stored URI if an item was removed

		if($rem)
		{
			$this->clearUri();
		}

		return $rem;
	}



}


