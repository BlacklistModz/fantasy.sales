<section class="setting-section settings-accordion">
	<div class="uiBoxWhite pas pam">
		<form action="<?=URL?>reports/orders">
			<label for="month">เลือกเดือน</label>
			<select name="month" class="inputtext" style="display:inline;">
				<?php
				for($i=1;$i<=12;$i++){
					$sel = '';
					if( $this->month == $i ) $sel = ' selected="1"';
					echo '<option'.$sel.' value="'.$i.'">'.$this->fn->q('time')->month((int)$i).'</option>';
				}
				?>
			</select>
			<select name="year" class="inputtext" style="display:inline;">
				<?php
				for($i=date("Y");$i>date("Y", strtotime("-5 years"));$i--){
					$sel = '';
					if( $this->year == $i ) $sel = ' selected="1"';
					echo '<option'.$sel.' value="'.$i.'">'.$i.'</option>';
				}
				?>
			</select>
			<div class="clearfix" style="display:inline;">
				<button type="submit" class="btn btn-green" style=" margin-top: -1mm">GO</button>
			</div>
		</form>
	</div>
	<div class="uiBoxWhite pas pam mts">
		<h3 class="fwb">รายงาน เดือน <?=$this->fn->q('time')->month((int)$this->month, true)?> <?=$this->year?></h3>
		<table class="table table-bordered mtl" width="100%">
			<thead>
				<tr>
					<th width="10%">วันที่</th>
					<th width="10%">Order</th>
					<th width="30%">ยอดรวม</th>
					<th width="30%">ยอดรับเงิน</th>
					<th width="20%">COMS</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$end = "{$this->year}-{$this->month}-01";
				$end_date = date("t", strtotime($end));

				$tr = '';
				$total_amount = 0;
				$total_payment = 0;
				$total_comission = 0;

				for($i=1; $i<=$end_date; $i++){
					$day = sprintf("%02d", $i);
					$date = date("Y-m-d", strtotime("{$this->year}-{$this->month}-{$day}"));
					// $monthStr = $this->fn->q('time')->month( date("n",strtotime($date)), true, 'en' );
					// $dateStr = "{$i}/{$monthStr}/{$this->year}";

					$item = !empty($this->results[$date]) ? $this->results[$date] : array();
					$total = !empty($item['total']) ? '<span class="fwb" style="color:blue;">'.$item['total'].'</span>'
													: '<span class="fwb" style="color:red;">0</span>';

					$amount = !empty($item['amount']) ? '<span class="fwb mrs" style="color:blue;">'.number_format($item['amount']).'</span>'
													: '<span class="fwb mrs" style="color:red;">0</span>';

					$payment = !empty($item['payment']) ? '<span class="fwb mrs" style="color:blue;">'.number_format($item['payment']).'</span>'
													: '<span class="fwb mrs" style="color:red;">0</span>';

					$comission = !empty($item['comission']) ? '<span class="fwb mrs" style="color:blue;">'.number_format($item['comission']).'</span>'
													: '<span class="fwb mrs" style="color:red;">0</span>';

					$total_amount += !empty($item['amount']) ? $item['amount'] : 0;
					$total_payment += !empty($item['payment']) ? $item['payment'] : 0;
					$total_comission += !empty($item['comission']) ? $item['comission'] : 0;

					$tr .= '<tr>'.
								'<td class="tac">'.$i.'</td>'.
								'<td class="tac">'.$total.'</td>'.
								'<td class="tar">'.$amount.'</td>'.
								'<td class="tar">'.$payment.'</td>'.
								'<td class="tar">'.$comission.'</td>'.
						   '</tr>';
				}

				echo $tr;
				?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="2">รวม</th>
					<th class="tar"><span class="mrs"><?=number_format($total_amount)?></span></th>
					<th class="tar"><span class="mrs"><?=number_format($total_payment)?></span></th>
					<th class="tar"><span class="mrs"><?=number_format($total_comission)?></span></th>
				</tr>
			</tfoot>
		</table>
	</div>
</section>
