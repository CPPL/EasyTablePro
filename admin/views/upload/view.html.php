<?php
/**
 * @package    EasyTable_Pro
 * @author     Craig Phillips <craig@craigphillips.biz>
 * @copyright  Copyright (C) 2009 Craig Phillips Pty Ltd.
 * @license    GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @url        http://www.seepeoplesoftware.com
 */

//--No direct access
defined('_JEXEC') or die('Restricted Access');

jimport( 'joomla.application.component.view');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/managerfunctions.php';

/**
 * HTML View class for the EasyTables Component
 *
 * @package	   EasyTables
 * @subpackage Views
 */

class EasyTableProViewUpload extends JViewLegacy
{
	/**
	 * View display method
	 *
	 * @param   string  $tpl  Tmpl to use.
	 *
	 * @return void
	 */
	function display($tpl = null)
	{
		// Get our Joomla Tag
		$this->jvtag = ET_General_Helper::getJoomlaVersionTag();

		// Get the document and load the system css file
		$doc = JFactory::getDocument();

		$doc->addStyleSheet(JURI::root() . 'templates/system/css/system.css');

		JHTML::_('behavior.tooltip');

		if($this->jvtag != 'j2')
		{
			JHtml::_('bootstrap.framework');
			$minOrNot = !JDEBUG ? '.min' : '';
			$doc->addStyleSheet(JURI::root() . 'media/jui/css/bootstrap' . $minOrNot . '.css');
			$doc->addStyleSheet(JURI::root() . 'media/com_easytablepro/css/easytable_upload' . $minOrNot . '.css');
		}

		$form = $this->get('Form');
		$item = $this->get('Item');

		// Store it for later
		$this->form = $form;
		$this->item = $item;

		// Set up our layout details
		$jInput = JFactory::getApplication()->input;
		$this->step = $jInput->get('step', '');
		$this->prevStep = $jInput->get('prevStep', '');
		$this->prevAction = $jInput->get('prevAction', '');
		$this->dataFile = $jInput->get('datafile', JText::_('COM_EASYTABLEPRO_UPLOAD_NOFILENAME'));
		$this->initialRecords = $jInput->get('initialRecords', 0);
		$this->uploadedRecords = $jInput->get('uploadedRecords', 0);
		$this->finalRecordCount = $jInput->get('finalRecordCount', 0);
		$this->status = ($jInput->get('uploadedRecords', 0) > 0) ? 'SUCCESS' : 'FAIL';
		$this->setLayout('upload_' . $this->jvtag);

		switch ($this->step)
		{
			case 'new':
				$this->closeURL = 'window.parent.SqueezeBox.close();';
				$this->stepLabel = JText::_('COM_EASYTABLEPRO_UPLOAD_CREATE_A_NEW_TABLE');
				$this->stepLegend = JText::_('COM_EASYTABLEPRO_UPLOAD_TABLE_CREATION_WIZARD');
				break;

			case 'uploadCompleted':
				$this->closeURL = "window.parent.location.reload();window.parent.SqueezeBox.close";
				$this->stepLabel = JText::_('COM_EASYTABLEPRO_UPLOAD_DATA_UPLOAD_COMPLETED');
				$this->stepLegend = JText::sprintf('COM_EASYTABLEPRO_UPLOAD_UPLOADED_X_RECORDS_TO_Y', $this->uploadedRecords, $this->item->easytablename);
				break;

			default:
				$this->closeURL = 'window.parent.SqueezeBox.close();';
				$this->stepLabel = JText::_('COM_EASYTABLEPRO_UPLOAD_DATA');
				$this->stepLegend = JText::sprintf('COM_EASYTABLEPRO_UPLOAD_UPLOAD_RECORDS_TO_X', $this->item->easytablename);
				break;
		}

		parent::display($tpl);
	}
}
