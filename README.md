Craft-Compressor
================

A Craft CMS extension to automatically combine, minify, and cache-break your CSS and JS resources.

Example usage:

	{{ craft.compressor.css([
		'/style/style.css',
		'/style/media-queries.css',
		'/style/fontello.css',
		'http://fonts.googleapis.com/css?family=Lato:400,700,900',
		'/style/flexslider.css'
	]) }}

	{{ craft.compressor.js([
		'/scripts/jquery.js',
		'/scripts/ddsmoothmenu.js',
		'/scripts/selectnav.js',
		'/scripts/jquery.flexslider.js',
		'/scripts/scripts.js'
	]) }}

Will output:

	<link rel="stylesheet" href="http://example.com/cache/cached.5fe383065467625b28499c2b885d60fa.css">
	<script src="http://example.com/cache/cached.0c4a14a00d89f6a600a931c274ad16ba.js"></script>

When any CSS or JS file is updated or removed the file will be generated with a new hash and filename.