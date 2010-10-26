<?php

class TestOfXhtmlParser extends UnitTestCase {

	function setUp() {
		$template = new Ajde_Template('test/xhtml/', 'empty');
		$this->parser = $template->getParser(); 	
	}
	
	function testCreateParser() {	
		$this->assertIsA($this->parser, 'Ajde_Template_Parser_Xhtml');
	}
	
	function testParseEmptyTemplate() {
		$result = trim($this->parser->parse());
		$this->assertTrue(empty($result));
	}
	
}