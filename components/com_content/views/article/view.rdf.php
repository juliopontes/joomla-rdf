<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * RDF Article View class for the Content component
 *
 * @package		Joomla.Site
 * @subpackage	com_content
 * @since		1.5
 */
class ContentViewArticle extends JView
{
	function display($tpl = null)
	{
		$document	= JFactory::getDocument();
		$item		= $this->get('Item');
		$config		= new JConfig();
		$modified_by = JFactory::getUser($item->modified_by);
		
		$language = JFactory::getLanguage()->getName();
		if ($item->language != '*'){
			$language = $item->language;
		}
		
		$description = new JRDFNode('description');
		$description->setAttribute('about',JFactory::getURI()->root());
		$description->addChild(new JRDFNode('creator', $item->created_by_alias,'dc:'));
		$description->addChild(new JRDFNode('contributor',$modified_by->name,'dc:'));
		$description->addChild(new JRDFNode('publisher',$config->sitename,'dc:'));
		$description->addChild(new JRDFNode('subject',$item->alias,'dc:'));
		$description->addChild(new JRDFNode('description',$item->introtext,'dc:'));
		$description->addChild(new JRDFNode('identifier',$item->id,'dc:'));
		$description->addChild(new JRDFNode('relation',$item->id,'dc:')); //itens relacionados
		$description->addChild(new JRDFNode('source',JFactory::getURI()->current(),'dc:'));
		$description->addChild(new JRDFNode('rights','','dc:'));
		$description->addChild(new JRDFNode('format','text/html','dc:'));
		$description->addChild(new JRDFNode('type','','dc:'));
		$description->addChild(new JRDFNode('title',$item->title,'dc:'));
		$description->addChild(new JRDFNode('date', $item->created));
		$description->addChild(new JRDFNode('coverage','','dc:'));
		$description->addChild(new JRDFNode('language',$language,'dc:'));
		
		$document->addNode($description);
	}
}