Wordpress Api
=============

This is simple plugin that hooks into the Wordpress Rewrite rules to add an endpoint at /api/* on your Wordpress Blog.

From here you can then choose to handle the requests as you see fit.

The class ApiEndpoint is well documented internally, so you should be able to easily add Api Endpoints as you need!

Just remember that when you specify your Regex, Wordpress interprets them on a first match basis, so it is important to ensure that your most complicated Regex Routes go at the top of the list, working down to the simplest.
