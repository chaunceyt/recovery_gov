<div class="reportfiles index">
<h2><?php __('Report files');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php echo $paginator->sort('file_name');?></th>
	<th><?php echo $paginator->sort('file_date');?></th>
	<th><?php echo $paginator->sort('submitter_name');?></th>
</tr>
<?php
$i = 0;
foreach ($reportfiles as $reportfile):
?>
	<tr>
		<td>
			<?php //echo $reportfile['Reportfile']['id']; ?>
			<?php echo $html->link(__('View', true), array('action' => 'view', $reportfile['Reportfile']['id'])); ?>
		</td>
		<td>
			<?php echo $reportfile['Reportfile']['file_name']; ?>
		</td>
		<td>
			<?php echo date("F j, Y", strtotime($reportfile['Reportfile']['file_date'])); ?>
		</td>
		<td>
			<?php echo $reportfile['Reportfile']['submitter_name']; ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
</div>
