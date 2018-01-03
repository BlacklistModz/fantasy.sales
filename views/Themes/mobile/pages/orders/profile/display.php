<div class="web-profile">

	<div class="web-profile-header">
		<h1 class="fwb"><i class="icon-cube"></i> <?=$this->item['code']?></h1>
	</div>

	<div class="web-profile-content post">

		<table class="table-meta">
			<?php
			$a = array();
			$a[] = array('key'=>'user_code', 'icon'=>'address-book-o', 'label'=>'รหัส');
			$a[] = array('key'=>'user_name', 'icon'=>'home', 'label'=>'ชื่อร้านค้า');
			$a[] = array('key'=>'net_price', 'icon'=>'money', 'label'=>'ราคารวม');

			foreach ($a as $key => $value) {
				if( $value['key']=='net_price' ) {
					$this->item[$value['key']] = '<span style="color:red;">'.number_format($this->item[$value['key']]).' ฿</span>';
				}
				echo '<tr>
						<td class="label">
							<i class="icon-'.$value['icon'].'"></i> '.$value['label'].'
						</td>
						<td class="fwb">'.$this->item[$value['key']].'</td>
					  </tr>';
			}
			?>
		</table>

		<div class="web-profile-header">
			<h1>รายการสินค้า</h1>
			<table class="table table-bordered mtl" width="100%">
				<thead>
					<tr>
						<th class="name" width="40%">สินค้า</th>
						<th class="price" style="color:green" width="15%">จำนวน</th>
						<th class="price" style="color:blue" width="15%">ราคา</th>
						<th class="price" width="15%">ส่วนลด</th>
						<th class="price" style="color:red" width="15%">เงิน</th>
					</tr>
				</thead>
				<tbody>
					<?php $num=0; foreach ($this->item['items'] as $key => $value) { $num++ ?>
						<tr>
							<td><?=$num?>. <?=$value['name']?></td>
							<td class="tac"><?=number_format($value['qty'])?></td>
							<td class="tac"><?=number_format($value['price'])?></td>
							<td class="tac"><?=number_format($value['discount'])?></td>
							<td class="tac"><?=number_format($value['balance'])?></td>
						</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td class="tac fwb">ยอดรวมเงิน <?=$num?> รายการ</td>
						<td colspan="4" class="tar fwb" style="color:red; font-size:20px;"><?=$this->item['net_price']?>&nbsp;</td>
					</tr>
				</tfoot>
			</table>
		</div>

		<div class="web-profile-header">
			<div class="pam">
				<h3>หมายเหตุ *</h3>
				<?=nl2br($this->item['order_note'])?>
			</div>
		</div>

		<?php if( $this->item['process']['id'] == 9 ) { ?>
		<div class="web-profile-header">
			<div class="mtl" style="font-size: 18px;">
				<?php
				$radio = '<div>
					<label class="radio"><input type="radio" name="term_of_payment" value="1"> เงินสด</label>
				</div>
				<div>
					<label class="radio"><input type="radio" name="term_of_payment" value="2"> เครดิต 30 วัน</label>
				</div>';
				$form = new Form();
				$form = $form->create()
							 ->elem('div')
							 ->addClass('form-insert');
				$form 	->field('term_of_payment')
						->label("ประเภทการจ่ายเงิน")
						->text( $radio );

				$form 	->field("order_note")
						->label("หมายเหตุ")
						->addClass('inputtext')
						->attr('data-plugins', 'autosize')
						->type('textarea')
						->value( !empty($this->item['note']) ? $this->item['note'] : '' );
				?>
				<form class="js-submit-form" action="<?=URL?>orders/updateOrder">
					<?=$form->html()?>
					<input type="hidden" name="id" value="<?=$this->item['id']?>">
					<div class="mtl">
						<a href="<?=URL?>/orders" class="btn btn-red" style="width: 48%">กลับ</a>
						<button type="submit" class="btn btn-green" style="width: 48%">Confirm Order</button>
					</div>
				</form>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
