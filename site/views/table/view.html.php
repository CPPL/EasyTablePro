<?php
/**
 * @package     EasyTable Pro
 * @Copyright   Copyright (C) 2012 Craig Phillips Pty Ltd.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @author      Craig Phillips {@link http://www.seepeoplesoftware.com}
 */

defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.view');
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
$pvf = ''.JPATH_COMPONENT_SITE.'/views/viewfunctions.php';
require_once $pvf;

class EasyTableViewEasyTable extends JView
{
	function display ($tpl = null)
	{
		$jAp = JFactory::getApplication();
		$option = JRequest::getCmd('option');
		// Better breadcrumbs
		$pathway   = $jAp->getPathway();
		$id = (int) JRequest::getVar('id',0);
		// For a better backlink - lets try this:
		$start_page = JRequest::getVar('start',0,'','int');					// get the start var from JPagination
		$jAp->setUserState( "$option.start_page", $start_page );		// store the start page

		$params = $jAp->getParams(); // Component wide & menu based params

		// Get the table based on the id from the request - we do it here so we can merge the tables params in.
		$easytable = JTable::getInstance('EasyTable','Table');
		$easytable->load($id);
		if($easytable->published == 0) {
			JError::raiseError(404,JText::_( "COM_EASYTABLEPRO_SITE_TABLE_NOT_AVAILABLE" ).$id);
		}
		$tableParams = new JParameter( $easytable->params );
		$params->merge( $tableParams );// Merge them with specific table based params

		/* Check the user against table access */
		// Create a user $access object for the current $user
		$user = JFactory::getUser();
		$access = new stdClass();
		// Check to see if the user has access to view the table
		$aid	= $user->get('aid');

		if ($tableParams->get('access') > $aid)
		{
			if ( ! $aid )
			{
				// Redirect to login
				$uri		= JFactory::getURI();
				$return		= $uri->toString();

				$url  = 'index.php?option=com_user&amp;view=login';
				$url .= '&amp;return='.base64_encode($return);

				//$url	= JRoute::_($url, false);
				$jAp->redirect($url, JText::_('COM_EASYTABLEPRO_SITE_RESTRICTED_TABLE') );
			}
			else{
				JError::raiseWarning( 403, JText::_('ALERTNOTAUTH') );
				return;
			}
		}
		// So our column headings pop out :D (Handy for users that want to put a note in about the field or column sorting
		JHTML::_('behavior.tooltip');

		$show_description = $params->get('show_description',1);
		$show_search = $params->get('show_search',1);
		$show_pagination = $params->get('show_pagination',1);
		$show_pagination_header = $params->get('show_pagination_header',0);
		$show_pagination_footer = $params->get('show_pagination_footer',1);
		$show_created_date = $params->get('show_created_date',1);
		$show_modified_date = $params->get('show_modified_date',0);
		$modification_date_label = $params->get('modification_date_label','');
		$show_page_title = $params->get('show_page_title',1);
		$pageclass_sfx = $params->get('pageclass_sfx','');
		$etet = $easytable->datatablename?TRUE:FALSE;

		$pathway->addItem($easytable->easytablename, 'index.php?option='.$option.'&amp;id='.$id.'&amp;start='.$start_page);
		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		// Get the menu item object
		$menus =JSite::getMenu();
		$menu  = $menus->getActive();

		if (is_object( $menu ) && isset($menu->query['view']) && $menu->query['view'] == 'easytable' && isset($menu->query['id']) && $menu->query['id'] == $id) {
			$menu_params = new JParameter( $menu->params );
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',$easytable->easytablename);
			}
		} else {
			$params->set('page_title',$easytable->easytablename);
		}
		$page_title = $params->get( 'page_title' );

		// Get the default image directory from the table.
		$imageDir = $easytable->defaultimagedir;

		//If required get the document and load the js for table sorting
		$doc = JFactory::getDocument();
		$SortableTable = $params->get ( 'make_tables_sortable' );
		if( $SortableTable ) {
			$doc->addScript(JURI::base().'media/com_easytablepro/js/webtoolkit.sortabletable.js');
		}

		// Get a database object
		$db = JFactory::getDBO();
		if(!$db){
			JError::raiseError(500,JText::_( 'COM_EASYTABLEPRO_SITE_DB_NOT_AVAILABLE_FOR_TABLE_ID' ).$id);
		}
		// Get the meta data for this table
		$query = "SELECT label, fieldalias, type, detail_link, description, params FROM ".$db->nameQuote('#__easytables_table_meta')." WHERE easytable_id =".$id." AND list_view = '1' ORDER BY position;";
		$db->setQuery($query);
		
		$easytables_table_meta = $db->loadRowList();
		$etmCount = count($easytables_table_meta); //Make sure at least 1 field is set to display
		// If any of the fields are designated as eMail load the JS file to allow cloaking.
		if(ET_VHelper::hasEmailType($easytables_table_meta))
			$doc->addScript(JURI::base().'media/com_easytablepro/js/easytableprotable_fe.js');

		if($etmCount)  //Make sure at least 1 field is set to display
		{
			// Get paginated table data
			if($show_pagination)
			{
				$paginatedRecords = $this->get('data');
				$paginatedRecordsFNILV = $this->get('dataFieldsNotInListView');
			}
			else
			{
				$paginatedRecords = $this->get('alldata');
				$paginatedRecordsFNILV = $this->get('alldataFieldsNotInListView');
			}

			// Get pagination object
			$pagination = $this->get('pagination');

		}
		else
		{
			$show_pagination_footer = FALSE;
			$show_pagination_header = FALSE;
			$show_search = FALSE;
			$pagination = FALSE;
			$easytables_table_meta = array(array("Warning EasyTable List View Empty","","","","",""));
			$errObj = new stdClass;
			$errObj->id = 0;
			$errObj->Message = "No fields selceted to display in list view for this table";
			$paginatedRecords = array('Error'=>$errObj);
		}
		// Search
		$search = $db->getEscaped($this->get('search'));
		//Get form link
		$paginationLink = JRoute::_('index.php?option=com_easytablepro&amp;view=easytable&amp;id='.$id.':'.$easytable->easytablealias);

		// Assing these items for use in the tmpl
		$this->assign('show_description', $show_description);
		$this->assign('show_search', $show_search);
		$this->assign('show_pagination', $show_pagination);
		$this->assign('show_pagination_header', $show_pagination_header);
		$this->assign('show_pagination_footer', $show_pagination_footer);

		$this->assign('show_created_date', $show_created_date);
		$this->assign('show_modified_date', $show_modified_date);
		$this->assign('modification_date_label', $modification_date_label);

		$this->assign('show_page_title', $show_page_title);
		$this->assign('page_title', $page_title);
		$this->assign('pageclass_sfx',$pageclass_sfx);
		
		$this->assign('SortableTable', $SortableTable);

		$this->assign('tableId', $id);
		$this->assign('imageDir', $imageDir);
		$this->assignRef('easytable', $easytable);
		$this->assignRef('easytables_table_meta', $easytables_table_meta);
		$this->assignRef('pagination', $pagination);
		$this->assign('paginationLink', $paginationLink);
		$this->assignRef('paginatedRecords', $paginatedRecords);
		$this->assignRef('paginatedRecordsFNILV', $paginatedRecordsFNILV);
		$this->assign('search',$search);
		$this->assign('etmCount', $etmCount);
		parent::display($tpl);
	}
}
?>