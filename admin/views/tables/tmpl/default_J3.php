<?php
/**
 * @package    EasyTable_Pro
 * @author     Craig Phillips <craig@craigphillips.biz>
 * @copyright  Copyright (C) 2012 Craig Phillips Pty Ltd.
 * @license    GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @url        http://www.seepeoplesoftware.com
 */

// No Direct Access
defined('_JEXEC') or die('Restricted Access');
require_once '' . JPATH_COMPONENT_ADMINISTRATOR . '/helpers/general.php';

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$user		= JFactory::getUser();
$userId		= $user->get('id');
?>
<?php if (!empty( $this->sidebar)) : ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
	<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
	<div id="filter-bar" class="btn-toolbar">
		<div class="filter-search btn-group pull-left">
			<label class="element-invisible" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search"  placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip"  title="<?php echo JHtml::tooltipText('COM_EASYTABLE_FILTER_SEARCH_DESC'); ?>" />
		</div>
		<div class="btn-group pull-left">
			<button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
			<button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
		</div>
	</div>
	<div class="clr"> </div>
	<table class="table table-striped">
	<thead>
		<tr>
			<th width="1%" class="hidden-phone"><input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" /></th>
			<th width="25%" class="center"><?php echo JHtml::_('grid.sort', 'COM_EASYTABLEPRO_MGR_TABLE', 't.easytablename', $listDirn, $listOrder); ?></th>
			<th width="5%" class="center"><?php echo JText::_('COM_EASYTABLEPRO_MGR_EDIT_DATA'); ?></th>
			<th width="5%" class="center hidden-phone"><?php echo JText::_('COM_EASYTABLEPRO_MGR_UPLOAD_DATA'); ?></th>
			<th width="5%" class="nowrap center"><?php echo JHtml::_('grid.sort', 'JPUBLISHED', 't.published', $listDirn, $listOrder); ?></th>
			<th><?php echo JText::_('COM_EASYTABLEPRO_MGR_DESCRIPTION'); ?></th>
			<th width="1%"><?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 't.id', $listDirn, $listOrder); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="13"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;

	for ($i = 0, $n = count($this->rows); $i < $n; $i++)
	{
		$row = $this->rows[$i];

		$canCreate        = $this->canDo->get('core.create',              'com_easytablepro');
		$canEdit          = $this->canDo->get('core.edit',                'com_easytablepro.table.' . $row->id);
		$canCheckin       = $user->authorise('core.manage',               'com_checkin') || $row->checked_out == $userId || $row->checked_out == 0;
		$canEditOwn       = $this->canDo->get('core.edit.own',            'com_easytablepro.table.' . $row->id) && $row->created_by == $userId;
		$canChange        = $this->canDo->get('core.edit.state',          'com_easytablepro.table.' . $row->id) && $canCheckin;
		$canEditRecords   = $this->canDo->get('easytablepro.editrecords', 'com_easytablepro.table.' . $row->id);
		$canImportRecords = $this->canDo->get('easytablepro.import',      'com_easytablepro.table.' . $row->id);

		$rowParamsObj = new JRegistry;

		$rowParamsObj->loadString($row->params);

		$row->params = $rowParamsObj->toArray();
		$locked = ($row->checked_out && ($row->checked_out != $user->id));

		if ($locked)
		{
			$lockedBy = JFactory::getUser($row->checked_out);
			$lockedByName = $lockedBy->name;
		}
		else
		{
			$lockedByName = '';
		}

		$published = ET_ManagerHelper::publishedIcon($locked, $row, $i, $canCheckin, $lockedByName);
		$etet = $row->datatablename?true:false;

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo JHtml::_('grid.id', $i, $row->id); ?>
			</td>
			<td><?php
				if ($row->checked_out)
				{
					echo JHTML::_('jgrid.checkedout', $i, $row->editor, $row->checked_out_time, 'tables.', $canCheckin);
				}

				echo ET_ManagerHelper::getEditorLink($locked, $i, $row->easytablename, $canEdit, $lockedByName);?>
			<div class="clr"></div>
				<span class="ept_tablelist_table_details"><?php
					echo JText::sprintf('COM_EASYTABLEPRO_TABLESX_BY_Y', $row->easytablealias, $row->author_name);
					?></span><div class="clr"></div>
				<span class="ept_tablelist_table_details hidden-phone"><?php
					echo JText::sprintf('COM_EASYTABLEPRO_TABLES_VIEWABLE_BY', ET_General_Helper::accessLabel($row->access));
					?></span>
				<span class="et_mgr_hits_counter hidden-phone"><?php
					echo JText::sprintf('COM_EASYTABLEPRO_MGR_HITS_COUNT', $row->hits);
					?></span>
			</td>
			<td class="center">
				<?php
					echo ET_ManagerHelper::getDataEditorIcon($locked, $i, $row->easytablename, $etet, $canEditRecords, $lockedByName);
				?>
			</td>
			<td class="center hidden-phone">
				<?php
					echo ET_ManagerHelper::getDataUploadIcon($locked, $row->id, $row->easytablename, $etet, $canImportRecords, $lockedByName);
				?>
			</td>
			<td class="center">
				<?php echo JHtml::_('jgrid.published', $row->published, $i, 'tables.', $canChange, 'cb'); ?>
			</td>
			<td>
				<span class="et_mgr_desc"><?php echo $row->description; ?></span>
			</td>
			<td>
				<?php echo $row->id; ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?></tbody>
	</table>
</div>