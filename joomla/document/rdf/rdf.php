<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Document
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * DocumentRDF class, provides an easy interface to parse and display any RDF document
 *
 * @package     Joomla.Platform
 * @subpackage  Document
 * @since       11.1
 */

jimport('joomla.document.document');

class JDocumentRDF extends JDocument
{
	/**
	 * The JRDFNode items collection
	 *
	 * @var    array
	 * @since  11.1
	 */
	public $items = array();
	
	/**
	 * Class constructor
	 *
	 * @param   array  $options Associative array of options
	 *
	 * @since  11.1
	 */
	public function __construct($options = array())
	{
		parent::__construct($options);

		//set document type
		$this->_type = 'rdf';
	}
	
	/**
	 * Render the document
	 *
	 * @param   boolean  $cache   If true, cache the output
	 * @param   array    $params  Associative array of attributes
	 *
	 * @return  The rendered data
	 *
	 * @since  11.1
	 */
	public function render($cache = false, $params = array())
	{
		global $option;

		// Get the feed type
		$type = JRequest::getCmd('type', 'dublincore');

		/*
		 * Cache TODO In later release
		 */
		$cache		= 0;
		$cache_time = 3600;
		$cache_path = JPATH_CACHE;

		// set filename for rss feeds
		$file = strtolower(str_replace('.', '', $type));
		$file = $cache_path . '/' . $file.'_'.$option.'.rdf';
		
		// Instantiate feed renderer and set the mime encoding
		$renderer = $this->loadRenderer(($type) ? $type : 'dublincore');
		if (!is_a($renderer, 'JDocumentRenderer')) {
			JError::raiseError(404, JText::_('JGLOBAL_RESOURCE_NOT_FOUND'));
		}

		// Output
		// Generate prolog
		$data	= "<?xml version=\"1.0\" encoding=\"".$this->_charset."\"?>\n";
		$data	.= "<!-- generator=\"".$this->getGenerator()."\" -->\n";

		// Render the feed
		$data .= $renderer->render();

		parent::render();
		return $data;
	}
	
	/**
	 * Adds an JRDFNode to the RDF.
	 *
	 * @param   object JRDFNode $item The feeditem to add to the RDF.
	 *
	 * @since  11.1
	 */
	public function addNode(JRDFNode &$item)
	{
		$this->items[] = $item;
	}
}

/**
 * JRDFNode is an internal class that stores RDF item information
 *
 * @package     Joomla.Platform
 * @subpackage  Document
 * @since       11.1
 */
class JRDFNode extends JObject
{
	private $elementName;
	private $value;
	private $_attributes = array();
	private $_childs = array();
	
	public function __construct($name,$value=null,$prefix='rdf:')
	{
		$this->_prefix = $prefix;
		
		$this->elementName = $prefix.$name;
		$this->value = $value;
	}
	
	public function __call($method_name,$arguments)
	{
		if (method_exists($this->dom, $method_name))
		{
			
		}
		else {
			parent::__call($method_name,$arguments);
		}
	}
	
	public function addChild(JRDFNode $node)
	{
		array_push($this->_childs, $node);
	}
	
	public function setAttribute($name,$value,$prefix=null)
	{
		if (is_null($prefix)) {
			$prefix = $this->_prefix;
		}
		
		$attributeName = $prefix.$name;
		
		$this->_attributes[$attributeName] = $value;
	}
	
	public function __toString()
	{
		$node = chr(13).'<'.$this->elementName.$this->attributes();
		if ($this->hasChilds()) {
			$node .= '>';
			foreach ($this->_childs as $childNode) {
				$node .= (string)$childNode;
			}
			$node .= chr(13).'</'.$this->elementName.'>';
		}
		else if (!empty($this->value)) {
			$node .= '>';
			$node .= $this->value;
			$node .= '</'.$this->elementName.'>';
		}
		else {
			$node .= ' />';
		}
		
		return $node;
	}
	
	final public function attributes()
	{
		$attributes = '';
		
		if ($this->hasAttributes()) {
			foreach ($this->_attributes as $attribute => $value) {
				$attributes .= ' '.$attribute.'="'.$value.'"';
			}
		}
		
		return $attributes;
	}
	
	final public function hasChilds()
	{
		return count($this->_childs);
	}
	
	final public function hasAttributes()
	{
		return count($this->_attributes);
	}
}