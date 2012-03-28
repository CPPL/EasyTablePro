<?php
//--No direct access
defined('_JEXEC') or die('Restricted Access');
?>

<div class="width-100 fltlft" id="et_tableFieldMeta" >
	<fieldset class="adminform">
		<legend class="hasTip" title="<?php echo JText::_( 'COM_EASYTABLEPRO_TABLE_META_DATA_TT' ).' '.$this->item->easytablename.' ('.$this->item->easytablealias.')'; ?>!"><?php echo $this->item->easytablename.' '.JText::_( 'COM_EASYTABLEPRO_TABLE_FIELDSET_TITLE_FIELD_CONFIGURATION' ); ?></legend>
		<table class="adminlist" id="et_fieldList">
		<thead>
			<tr valign="top">
				<th><?php echo JText::_( 'COM_EASYTABLEPRO_MGR_ID' ); ?></th>
				<th class="hasTip" title="<?php echo JText::_( 'COM_EASYTABLEPRO_TABLE_FIELDSET_COL_POSITION_TT' ); ?>" ><?php echo JText::_( 'COM_EASYTABLEPRO_TABLE_FIELDSET_COL_POSITION' ); ?></th>
				<th class="hasTip" title="<?php echo JText::_( 'COM_EASYTABLEPRO_TABLE_FIELDSET_COL_LABEL_TT' ); ?>" ><?php echo JText::_( 'COM_EASYTABLEPRO_TABLE_FIELDSET_COL_LABELALIAS' ); ?></th>
				<th class="hasTip" title="<?php echo JText::_( 'COM_EASYTABLEPRO_TABLE_DESCRIPTION_TT' ) ?>" ><?php echo JText::_( 'COM_EASYTABLEPRO_MGR_DESCRIPTION' ); ?></th>
				<th class="hasTip" title="<?php echo JText::_( 'COM_EASYTABLEPRO_TABLE_FIELDSET_COL_OPTIONS_TT' ); ?>" ><?php echo JText::_( 'COM_EASYTABLEPRO_LABEL_TYPE' ).' / '.JText::_( 'COM_EASYTABLEPRO_TABLE_LABEL_OPTIONS' ); ?></th>
				<th class="hasTip" title="<?php echo JText::_( 'COM_EASYTABLEPRO_TABLE_FIELDSET_COL_LIST_VIEW_TT' ); ?>" ><?php echo JText::_( 'COM_EASYTABLEPRO_LABEL_LIST_VIEW' ); ?><br />
				<a href="#" onclick="flipAll('list')"title="<?php echo JText::_('COM_EASYTABLEPRO_TABLE_TOGGLE_ALL_IN_LIST_TT'); ?>" class="hasTip"> F </a> | 
				<a href="#" onclick="turnAll('on','list')" title="<?php echo JText::_('COM_EASYTABLEPRO_TABLE_TURN_ON_ALL_IN_LIST_TT'); ?>" class="hasTip" > √ </a> | 
				<a href="#" onclick="turnAll('off','list')" title="<?php echo JText::_('COM_EASYTABLEPRO_TABLE_TURN_OFF_ALL_IN_LIST_TT'); ?>" class="hasTip" > X </a></th>
				<th class="hasTip" title="<?php echo JText::_( 'COM_EASYTABLEPRO_TABLE_FIELDSET_COL_DETAIL_LINK_TT' ); ?>" ><?php echo JText::_( 'COM_EASYTABLEPRO_LABEL_DETAIL_LINK' ); ?></th>
				<th class="hasTip" title="<?php echo JText::_( 'COM_EASYTABLEPRO_TABLE_FIELDSET_COL_DETAIL_VIEW_TT' ); ?>" ><?php echo JText::_( 'COM_EASYTABLEPRO_LABEL_DETAIL_VIEW' ); ?><br />
				<a href="#" onclick="flipAll('detail')"title="<?php echo JText::_('COM_EASYTABLEPRO_TABLE_TOGGLE_ALL_IN_DETAIL_VIEW_TT'); ?>" class="hasTip"> F </a> | 
				<a href="#" onclick="turnAll('on','detail')" title="<?php echo JText::_('COM_EASYTABLEPRO_TABLE_TURN_ON_ALL_IN_DETAIL_VIEW_TT'); ?>" class="hasTip" > √ </a> | 
				<a href="#" onclick="turnAll('off','detail')" title="<?php echo JText::_('COM_EASYTABLEPRO_TABLE_TURN_OFF_ALL_IN_DETAIL_VIEW_TT'); ?>" class="hasTip" > X </a></th>
				<th class="hasTip" title="<?php echo JText::_( 'COM_EASYTABLEPRO_TABLE_FIELDSET_COL_SEARCHABLE_TT' ); ?>" ><?php echo '<img src="/media/com_easytablepro/images/search-sm.png" style="clear:both;" alt="Toggle Search Visibility" />'; ?><br />
				<a href="#" onclick="flipAll('search')"title="<?php echo JText::_('COM_EASYTABLEPRO_TABLE_TOGGLE_ALL_FLDS_SEARCH_TT'); ?>" class="hasTip"> F </a> | 
				<a href="#" onclick="turnAll('on','search')" title="<?php echo JText::_('COM_EASYTABLEPRO_TABLE_TURN_ON_ALL_FLDS_SEARCH_TT'); ?>" class="hasTip" > √ </a> | 
				<a href="#" onclick="turnAll('off','search')" title="<?php echo JText::_('COM_EASYTABLEPRO_TABLE_TURN_OFF_ALL_FLDS_SEARCH_TT'); ?>" class="hasTip" > X </a></th>
			</tr>
		</thead>
		<?php echo $this->loadTemplate('metatable_body'); ?>
		</table>
		<input type="hidden" id="mRIds" name="mRIds" value="<?php echo implode(', ',$mRIds); ?>" />
	</fieldset>
</div>