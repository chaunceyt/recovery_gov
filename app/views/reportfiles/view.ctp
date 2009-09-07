<div class="reportfiles view">
<h2><?php  __('Report file: '.$reportfile['Reportfile']['file_name']);?></h2>
		<p><?php __('File Date'); ?>:
		<?php echo $reportfile['Reportfile']['file_date']; ?></p>
		
		<p><strong><?php __('Submitter Name'); ?>:</strong>
			<?php echo $reportfile['Reportfile']['submitter_name']; ?></p>

		<p><strong><?php __('Submitter Contact Info'); ?>:</strong>
			<?php echo $reportfile['Reportfile']['submitter_contact_info']; ?></p>
</div>
<div class="related">
	<h3><?php __('Related Major Activities');?></h3>
	<?php if (!empty($reportfile['Majoractivity'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Major Actions'); ?></th>
		<th><?php __('Planned Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($reportfile['Majoractivity'] as $majoractivity):
		?>
		<tr>
			<td><?php echo $majoractivity['major_actions'];?></td>
			<td><?php echo $majoractivity['planned_actions'];?></td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

</div>
<div class="related">
	<h3><?php __('Related Weekly Reports');?></h3>
	<?php if (!empty($reportfile['Weeklyreport'])):?>
	<table cellpadding = "0" cellspacing = "4">
	<tr>
		<th><?php __('Agency Code'); ?></th>
		<th><?php __('Account Code'); ?></th>
		<th><?php __('Subaccount Code'); ?></th>
		<th><?php __('Appropriation'); ?></th>
		<th><?php __('Obligations'); ?></th>
		<th><?php __('Disbursements'); ?></th>
	</tr>
	<?php
		$i = 0;
        $total_appropriation = 0;
        $total_obligations = 0;
        $total_disbursements = 0;
		foreach ($reportfile['Weeklyreport'] as $weeklyreport):
		?>
		<tr>
			<td align="center"><?php echo $weeklyreport['agency_code'];?></td>
			<td align="center"><?php echo $weeklyreport['account_code'];?></td>
			<td align="center"><?php echo $weeklyreport['subaccount_code_optional'];?></td>
			<td align="right"><?php echo number_format($weeklyreport['total_appropriation'], 2);?></td>
			<td align="right"><?php echo number_format($weeklyreport['total_obligations'], 2);?></td>
			<td align="right"><?php echo number_format($weeklyreport['total_disbursements'], 2);?></td>
		</tr>
        <?php
            $total_appropriation += $weeklyreport['total_appropriation'];
            $total_obligations += $weeklyreport['total_obligations'];
            $total_disbursements += $weeklyreport['total_disbursements'];
        ?>
	<?php endforeach; ?>
    <tr>
    <td colspan="3">Grand Totals</td>
    <td align="right">
    <?php echo number_format($total_appropriation, 2); ?>
    </td>
    <td align="right">
    <?php echo number_format($total_obligations, 2); ?>
    </td>
    <td align="right">
    <?php echo number_format($total_disbursements, 2); ?>
    </td>
    </tr>
	</table>
<?php endif; ?>
</div>
