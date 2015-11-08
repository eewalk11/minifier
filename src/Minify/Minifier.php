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
	 */
	public function addFile($file)
	{
		if($file && !in_array($file, $this->files))
		{
			$this->files[] = $file;
		}
	}



	/**
	 * Add an array of files to minify.
	 * <p>If any files have already been added, they will be ignored.</p>
	 * @param mixed $files An array of files to minify. A single file may be passed as well, which
	 * is the same as calling addFile(). A falsey value will be treated as an empty array. The path
	 * of each file should be relative to the public directory, or to the Minify base directory if
	 * set.</p>
	 */
	public function addFiles($files)
	{
		if(!is_array($files))
		{
			$files = $files ? [$files] : [];
		}

		foreach($files as $file)
		{
			$this->addFile($file);
		}
	}



	/**
	 * Add a Minify group.
	 * <p>The group must be a group defined in the Minify groupsConfig.php file. If the group has
	 * already been added, this method will do nothing.</p>
	 * @param string $group The Minify group.
	 */
	public function addGroup($group)
	{
		if($group && !in_array($group, $this->groups))
		{
			$this->groups[] = $group;
		}
	}



	/**
	 * Add an array of Minify groups.
	 * <p>Each group must be a group defined in the Minify groupsConfig.php file. If any groups have
	 * already been added, this method will ignore them.</p>
	 * @param mixed $groups An aray of Minify groups. A single group can be passed as well, which is
	 * the same as calling addGroup(). A falsey value will be treated as an empty array.
	 */
	public function addGroups($groups)
	{
		if(!is_array($groups))
		{
			$groups = $groups ? [$groups] : [];
		}

		foreach($groups as $group)
		{
			$this->addGroup($group);
		}
	}



	/**
	 * Create a URI to minify the added files and write it to the document.
	 * <p>A MinifyException will be thrown if debugging is enabled and this Minifier contains
	 * invalid settings.</p>
	 * @return string|boolean The minify URI, false if nothing was added to minify.
	 * @throws MinifyException
	 */
	public function createUri()
	{
		$builder = new UriBuilder($this);
		return $builder->buildUri();
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
		$rem = false;
		if(($index = array_search($file, $this->files)) !== false)
		{
			unset($this->files[$index]);
			$rem = true;
		}
		return $rem;
	}



	/**
	 * Remove an array of files from this Minifier.
	 * <p>A file path must be exactly as it was added to this Minifier for it to be removed.</p>
	 * @param mixed $files An array of files to remove. A single file may be passed as well, which
	 * is the same as calling removeFile(). A falsey value will be treated as an empty array.</p>
	 * @return array An array containing all file names that were removed.
	 */
	public function removeFiles($files)
	{
		if(!is_array($files))
		{
			$files = $files ? [$files] : [];
		}

		$rem = [];
		foreach($files as $file)
		{
			if($this->removeFile($file))
			{
				$rem[] = $file;
			}
		}
		return $rem;
	}



	/**
	 * Remove a Minify group from this Minifier.
	 * @param string $group The group to remove.
	 * @return boolean True if the group was removed, false if the group was not found.
	 */
	public function removeGroup($group)
	{
		$rem = false;
		if(($index = array_search($group, $this->groups)) !== false)
		{
			unset($this->groups[$index]);
			$rem = true;
		}
		return $rem;
	}



	/**
	 * Remove an array of Minify groupsfrom this Minifier.
	 * @param mixed $groups An aray of Minify groups. A single group can be passed as well, which is
	 * the same as calling removeGroup(). A falsey value will be treated as an empty array.
	 * @return array An array containing all group names that were removed.
	 */
	public function removeGroups($groups)
	{
		if(!is_array($groups))
		{
			$groups = $groups ? [$groups] : [];
		}

		$rem = [];
		foreach($groups as $group)
		{
			if($this->removeGroup($group))
			{
				$rem[] = $group;
			}
		}
		return $rem;
	}



	/**
	 * Set the base directory for files to minify.
	 * @param string|boolean $dir The directory path relative to the document root, false to remove
	 * a base directory.
	 */
	public function setBase($dir)
	{
		$this->base = $dir || is_string($dir) ? $dir : false;
	}



}


