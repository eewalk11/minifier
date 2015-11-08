# Minifier

A simple library for object-oriented use of the php minify library. All class included are found in the *Minify* namespace.

The *Minify* php library must be correctly installed in the project to use this library: https://github.com/mrclay/minify

Classes
-------

These are the important classes to use the library.

### Minify/Minifier

The *Minifier* class is an object to collect files and groups identified in the /min/groupsConfig.php file. Once settings are complete, the Minifier can be run to generate a URI. The URI should be set as the value of a css *link* tag's *href* attribute or a *script* tag's *src* attribute. If the Minify library is properly installed in the project, it will handle the rest.

### Minify/Config

Change configuration settings for the library. At the moment only 1 option exists, but it's there if I need/want to expand on it later.

Options:

* "debug" => set to a boolean. If true, an exception will be thrown if a URI contains nonexistent/unreadable files or unconfigured Minify groups. If false, no exceptions will be thrown when the URI is generated.

### Minify/MinifyException

Any time an exception is thrown in this library, it will be this type.
