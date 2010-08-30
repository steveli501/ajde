$(document).ready(function() {
	SyntaxHighlighter.autoloader(
	  'js jscript javascript			/public/js/lib/syntaxhighlighter/shBrushJScript.js',
	  'php  							/public/js/lib/syntaxhighlighter/shBrushPhp.js',
	  'xml xhtml xslt html xhtml  		/public/js/lib/syntaxhighlighter/shBrushXml.js',
	  'css  							/public/js/lib/syntaxhighlighter/shBrushCss.js'
	);	 
	SyntaxHighlighter.all();
});