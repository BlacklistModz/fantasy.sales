<section class="setting-section settings-accordion">
	<div class="uiBoxWhite pas pam mts">
		<h3 class="fwb">ORDER ที่ค้างชำระ </h3>
		<table class="table table-bordered mtl" width="100%">
			<thead>
				<tr>
					<th width="10%">วันที่</th>
					<th width="20%">Order Number</th>
					<th width="50%">ชื่อร้าน</th>
					<th width="20%">ยอดค้าง</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$total_balance = 0;
				foreach ($this->results as $key => $value) { 
					$total_balance += $value['balance'];
					?>
					<tr>
						<td class="tac"><?=date("d/m/y", strtotime($value['ord_dateCreate']))?></td>
						<td class="tac"><?=$value['ord_code']?></td>
						<td><?=$value['user_name']?></td>
						<td class="tar" style="color:orange;"><?=number_format($value['balance'])?></td>
					</tr>
					<?php 
				} 
				?>
			</tbody>
			<tfoot>
				<th colspan="3" class="tar fwb">รวม</th>
				<th class="tar fwb" style="color:orange;"><?=number_format($total_balance)?></th>
			</tfoot>
		</table>
	</div>
</section>