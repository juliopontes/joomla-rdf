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
 * JDocumentRenderer_DublinCore is a RDF that implements DUBLIN CORE Specification
 *
 * @package     Joomla.Platform
 * @subpackage  Document
 * @see         http://dublincore.org/documents/2002/07/31/dcmes-xml/dcmes-xml-dtd.dtd
 * @since       11.1
 */
class JDocumentRendererDublinCore extends JDocumentRenderer
{
	/**
	 * Render the RDF
	 *
	 * @return  string
	 *
	 * @since   11.1
	 */
	public function render($name = null, $params = null, $content = null)
	{
		$app	= JFactory::getApplication();

		// Gets and sets timezone offset from site configuration
		$tz	= new DateTimeZone($app->getCfg('offset'));
		$now	= JFactory::getDate();
		$now->setTimeZone($tz);

		$data	= &$this->_doc;
		
		$uri = JFactory::getURI();
		$url = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
		$syndicationURL = JRoute::_('&format=rdf&type=dublincore');
		
		$rdf = '<!DOCTYPE rdf:RDF PUBLIC "-//DUBLIN CORE//DCMES DTD 2002/07/31//EN"
    "http://dublincore.org/documents/2002/07/31/dcmes-xml/dcmes-xml-dtd.dtd">';
		$rdf .= chr(13).'<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dc="http://purl.org/dc/elements/1.1/">';
		foreach($data->items as $item) {
			$rdf .= (string) $item;
		}
		$rdf .= chr(13).'</rdf:RDF>';
		
		return $rdf;
	}
}