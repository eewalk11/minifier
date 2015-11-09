<?php



namespace Minify;

use JsonSerializable;



/**
 * A class to construct Minify URIs from the settings in a Minifier.
 */
class UriBuilder
{



	/*
	 * PROTECTED VARIABLES
	 */



	/**
	 * @var array An array of data used to construct the URI.
	 */
	protected $data = [];



	/*
	 * PRIVATE VARIABLES
	 */



	/**
	 * @var boolean Set to false to disable file checks when building the URI.
	 */
	private $debug = true;



	/*
	 * CONSTRUCTOR / DESTRUCTOR
	 */



	/**
	 * Construct a new Minify URI builder.
	 * @param Minifier|\JsonSerializable|array $data Data from which to construct the URI. This may
	 * be:
	 * <ul>
	 *   <li>An instance of Minify/Minifier</li>
	 *   <li>An instance of JsonSerializable, where jsonSerialize() produces an array in the format
	 *   of Minify/Minifier::jsonSerialize()</li>
	 *   <li>An array matching the format of the output from Minify/Minifier::jsonSerialize()</li>
	 * </ul>
	 * The URI will fail to generate if this is not an expected type.
	 */
	public function __construct($data)
	{
		if($data instanceof JsonSerializable)
		{
			$this->data = json_decode(json_encode($data), true);
		}
		elseif(is_array($data))
		{
			$this->data = $data;
		}

		//$this->data will be empty if data type is invalid

		$this->debug = Config::getOption("debug");
	}



	/**
	 * Destroy this Minify URI builder.
	 */
	public function __destruct()
	{
		//Empty destructor
	}



	/*
	 * PUBLIC METHODS
	 */



	/**
	 * Construct the URI for Minify.
	 * <p>If debugging is enabled and Minify is not installed correctly or any of the Minifier
	 * settings are invalid, a MinifyException will be thrown.</p>
	 * @return string A minify URI.
	 * @throws MinifyException
	 */
	public function buildUri()
	{
		//Make sure Minify is installed

		if($this->debug)
		{
			Check::install();
		}

		//Build the URI

		$uri = "";
		$this->addBase($uri);
		$fadded = $this->addGroups($uri);
		$gadded = $this->addFiles($uri);
		return $fadded || $gadded ? "/min/$uri" : false;
	}



	/*
	 * PROTECTED FUNCTIONS
	 */



	/**
	 * Add the Minify base directory to the URI.
	 * <p>If the base directory does not exist, a MinifyException will be thrown.</p>
	 * @param string &$uri Reference to the URI being built.
	 * @throws MinifyException
	 */
	protected function addBase(&$uri)
	{
		if(($base = $this->getBase()))
		{
			if($this->debug)
			{
				Check::base($base);
			}

			$uri .= "b=$base";
		}
	}



	/**
	 * Insert all files added to the Minifier into the Minify URI.
	 * <p>If debugging is enabled and any file paths are invalid, a MinifyException will be
	 * thrown.</p>
	 * @param string &$uri Reference to the URI being built.
	 * @return boolean True if at least 1 file was added, false otherwise.
	 * @throws MinifyException
	 */
	protected function addFiles(&$uri)
	{
		$base = $this->getBase();
		$files = $this->getFiles();

		if($files)
		{
			$uri .= $uri ? "&amp;f=" : "f=";

			foreach($files as $file)
			{
				if($this->debug)
				{
					Check::file($file, $base);
				}

				$uri .= "$file,";
			}

			$uri = substr($uri, 0, -1); //Remove trailing ","
		}

		return (bool)$files;
	}



	/**
	 * Insert all Minify groups added to the Minifier into the Minify URI.
	 * <p>If bebugging is enabled and any groups are invalid, a MinifyException will be thrown.</p>
	 * @param string &$uri Referene to the URI being built.
	 * @return boolean True if at least 1 group was added, false otherwise.
	 * @throws MinifyException
	 */
	protected function addGroups(&$uri)
	{
		$groups = $this->getGroups();

		if($groups)
		{
			$uri .= $uri ? "&amp;g=" : "g=";

			foreach($groups as $group)
			{
				if($this->debug)
				{
					Check::group($group);
				}

				$uri .= "$group,";
			}

			$uri = substr($uri, 0, -1); //Remove trailing ","
		}

		return (bool)$groups;
	}



	/**
	 * Get the base directory from the data array.
	 * @return string The base directory, false if unset or invalid.
	 */
	protected function getBase()
	{
		if(
			($base = array_key_exists("base", $this->data) ? $this->data["base"] : false)
			&& is_string($base)
		)
		{
			if(substr($base, -1) == "/")
			{
				//Remove trailing slash
				$base = substr($base, 0, -1);

				//Store the modified string
				$this->base = $base;
			}
		}
		else
		{
			$base = false;
		}

		return $base;
	}



	/**
	 * Get the array of files from the data array.
	 */
	protected function getFiles()
	{
		return $this->getDataArray("files");
	}



	/**
	 * Get the array of groups from the data array.
	 */
	protected function getGroups()
	{
		return $this->getDataArray("groups");
	}



	/*
	 * PRIVATE METHODS
	 */



	/**
	 * Get a value in the data array as an array.
	 * @param string $key The array key.
	 * @return array An array, false if the data is invalid.
	 */
	private function getDataArray($key)
	{
		$data = array_key_exists($key, $this->data) ? $this->data[$key] : false;
		if(!is_array($data))
		{
			$data = $data && is_string($data) ? [$data] : false;
		}
		return $data;
	}



}


