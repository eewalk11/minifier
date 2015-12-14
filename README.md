# Minifier

A simple library for object-oriented use of the php Minify library. All functions and classes included are found in the *Minify* namespace.

The Minify php library must be correctly installed in the project to use this library:<br> https://github.com/mrclay/minify

Classes
-------

These are the important classes to use the library.

### Minifier

The *Minifier* class is an object to collect files and groups identified in the /min/groupsConfig.php file. Once settings are complete, the Minifier can be run by calling *Minifier::createUri()* to generate a URI. The URI should be set as the value of a css *link* tag's *href* attribute or a *script* tag's *src* attribute. If the Minify library is properly installed in the project, it will handle the rest.

### UriBuilder

This takes the data stored in a *Minifier* object, a *JsonSerializable* object, or an associated array and generates a minify URI. If using a *Minifier* object, calling *Minifier::createUri()* will automatically construct and run a *UriBuilder*. Otherwise, just construct a new *UriBuilder* and run *UriBuilder::buildUri()*.

If using *JsonSerializable* or an associative array to construct a *UriBuilder*, the array should have the following keys/values:

* **base** *[string|boolean]* The base directory for the files in the URI, false if unset.
* **groups** *[string|array]* A single minify group or an array of minify groups. Each group must be registered in /min/groupsConfig.php.
* **files** [string|array] A single file to minify or an array of files to minify. If the base directory is set, file paths should be relative to the base. If the base directory is not set, file paths should be relative to the document root.
 
### MinifyGlobals

This class contains static functions to access a *Minifier* object from anywhere. Each function in the *MinifyGlobals* class is exactly the same as a public method in the *Minifier* class that takes an extra ID string as the first argument. The ID is used to reference a particular *Minifier* so that the object's reference doesn't need to be passed around.

### MinifyConfig

Change configuration settings for the library. At the moment only 1 option exists, but it's there if I need/want to expand on it later.

Options:

* **debug** *[boolean]* If true, an exception will be thrown if a URI contains nonexistent/unreadable files or unconfigured Minify groups. If false, no exceptions will be thrown when the URI is generated.

### MinifyException

Any time an exception is thrown in this library, it will be this type.

Helper Functions
----------------

There are a few helper functions in the *Minify* namespace. Most are just wrappers around the *MinifyGlobals* static functions. There is also a wrapper function *minify()* to construct, run, and return the result of a *UriBuilder* from an argument.
