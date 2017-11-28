<?php
?>
<table class="mainTable padTable">
		<thead>
			<tr>
				<th<?php if($report_id == 0): ?> style="display:none" <?php endif; ?>><?php echo lang('number_of_views') ?></th>
				<th><?php echo lang('entry_id') ?></th>
				<th><?php echo lang('title') ?></th>
				<th><?php echo lang('site_id') ?></th>
				<th><?php echo lang('channel_id') ?></th>
				<th><?php echo lang('member_id') ?></th>
				<th><?php echo lang('session_id') ?></th>
				<th><?php echo lang('ip_address') ?></th>
				<th><?php echo lang('user_agent') ?></th>
				<th><?php echo lang('entry_date') ?></th>
			</tr>			
		</thead>
		<tbody>
		
		<?php foreach ($data as $key => $value) {
			
			echo "<tr>";

			foreach ($value as $key2 => $value2)
			{
				if($key2 === 'entry_date')
				{
					echo "<td>" . ee()->localize->human_time($value2) . "</td>";
				}
				else
				{
					echo "<td>" . $value2 . "</td>";	
				}
				
			}
			echo "</tr>";
		}

		?>

		</tbody>
	</table>

