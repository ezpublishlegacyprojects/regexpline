Datatype extension for eZ publish 3.6+ (License: GPL)
-----------------------------------------------------

Provides a regular expression datatype for eZ publish.


What is it?
-----------
For end users it will look like the standard "Text line" datatype that ships with eZ publish.

The biggest difference is that the input will be validated against a regular expression. This enables you to make sure the input meets certain criteria.

It can act like a regular "Text line" datatype by allowing all input (regexp: /.*/).


Features
--------
- Check user input against a regular expression (Perl-compatible)
- Use regular expression subpatterns to be able to use parts of the input in the object name pattern
- Ability to supply a helptext for users
- Required checks compatible with newer eZ publish versions (drafts ignore required fields)
- Information collection support
- Attribute filtering support
- Ability to define preset regular expressions in an ini file
- Contentclass import & export
- i18n support


Planned Features
----------------
These features are NOT available yet, but they're coming.

(- Support for multiple regular expressions)*

*: If needed


Requirements
------------
- A suitable eZ publish version: 3.6.x; 3.7.x has also been used during development.
- Knowledge of Perl Compatible Regular Expression syntax (http://www.php.net/manual/en/ref.pcre.php)


Installation
------------
You can always find the latest version of this extension @
http://pubsvn.ez.no/community/trunk/extension/regexpline

If you can't use SVN, you can also download the most recently released version from the Contributions section @
http://ez.no/community/contribs

- Extract or checkout the regexpline extension into the eZ publish 'extension' folder
- Activate the extension by means of the admin interface or by adding
  
  ActiveExtensions[]=regexpline

  to [ExtensionSettings] in site.ini.append(.php)
- Clear the template override cache to make sure eZ publish picks up the templates in the extension.


Bugs? Comments? Wishes?
-----------------------
- Bugs: If you report a bug, please make sure you have a minimal testcase so I can reproduce the problem.
  If I can't reproduce it, I can't fix it.

- Comments: Nothing special here :)

- Wishes: Please provide a clear description.

Direct all (fan|bug|wish)mail at: hans <dotty> melis <atty> gmail <dotty> com

Please note that this datatype has been developed in my spare time, and is supplied as is. I'm not responsible for any data loss or failures. YMMV!


Copyright
---------

Regular Expression datatype for eZ publish 3.6+
Copyright (C) 2005  Hans Melis

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA