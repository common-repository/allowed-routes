=== Allowed Routes ===
Contributors: nerdismFTW
Tags: Routing, Whitelist, Permalink, Routes, Route, SEO
Requires at least: 4.0
Tested up to: 5.2.3
Requires PHP: 5.3
Stable tag: 1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Define only allowed routes for your website. Permalinks will be overruled. Wildcard Support.

== Description ==

Define allowed routes for your website, all other routes will return a 404 error. Permalinks will be overruled.
    
Login, Backend routes and local files (like wp-login.php) are always available to prevent a lockout.

Also delete all your page caches after enabling to prevent unwanted routing behavior.    

Use cases:

* Absolute control over all permalinks
* Prevent unwanted search engine indexing
* Be sure no custom post type adds unwanted permalinks
* No frontend needed

Important:

* The routing overrules permalinks and works like a whitelist. Only correct entered routes will go through. You should test the routing before using on production environments
* Be careful using several routing- or redirect plugins at the same time
* Do not forget to delete all your page caches after enabling/disabling the routing to prevent unwanting routing behavior

Behaviour:

* The used protocol (HTTP or HTTPS) will be ignored
* GET params will be ignored
* Routes are case sensitive

Syntax / Wildcards:

* The route / allows the index page (Check the checkbox \"Allow index page\")
* Wildcard * allows a single term with an arbitrary value (e.g. category/*/page/*)
* Wildcard ** permits all possible combinations of terms (e.g. category/**) This wildcard is only allowed at the end of a route
* Wildcards are only allowed as complete terms. Correct: /foo/*/bar/** Wrong: /foo/ba*/test**

== Installation ==

1. Unzip and upload the plugin package into the plugins directory
1. Login to the dashboard and activate the plugin
1. Click Settings to configure the plugin

== Screenshots ==

1. Example

== Changelog ==

* Compatibility check to WP 5; minor wording changes

* Initial release