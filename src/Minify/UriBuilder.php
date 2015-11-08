<?php



namespace Minify;



/**
 * A class to construct Minify URIs from the settings in a Minifier.
 */
class UriBuilder
{



	/*
	 * PROTECTED VARIABLES
	 */



	/**
	 * @var Minifier The minifer to use to construct the URI.
	 */
	protected $min = null;



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
	 * @param Minifier $min The minifier to use to construct the URI.
	 */
	public function __construct(Minifier $min)
	{
		$this->min = $min;
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
		$base = $this->min->getBase();

		if($this->debug)
		{
			Check::base($base);
		}

		$uri .= ($base ? "b=$base" : null);
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
		$base = $this->min->getBase();
		$files = $this->min->getFiles();

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
		$groups = $this->min->getGroups();

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







}


