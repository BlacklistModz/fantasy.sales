<div class="clearfix">
	<div class="rfloat pam" style="display:flex;">
		<?php if( !empty($this->item['line_id']) ) { ?>
			<a href="http://line.me/ti/p/~<?=$this->item['line_id']?>" style="margin-right:5px;"><img src="<?=IMAGES?>/icon/line-me.png" style="height:50px; width:auto;" /></a>
		<?php } ?>

		<a href="tel:<?=$this->item['phone']?>" class="btn btn-blue btn-large" style="font-size: 30px; height:50px; width:50px; padding-top:9px; padding-left:9px;"><i class="icon-phone"></i></a>
		<a href="<?=URL?>orders/create/<?=$this->item['id']?>" class="btn btn-green btn-large" style="font-size:30px; height:50px; width:50px; padding-top:9px; padding-left:9px;"><i class="icon-cart-plus"></i></a>
	</div>
	<div class="uiBoxGray pal">
		<ui style="font-size: 16px;">
			<h3 class="mbm fwb"><i class="icon-home"></i> ข้อมูลลูกค้า</h3>
			<li class="mts">
				<span class="fwb"><i class="icon-info-circle"></i> รหัส : </span> <?=$this->item['sub_code']?>
			</li>
			<li class="mts">
				<span class="fwb"><i class="icon-address-card"></i> ชื่อร้าน : </span> <?=$this->item['name_store']?>
			</li>
			<li class="mts">
				<?php
				$num = 1;
				foreach ($this->item['address'] as $key => $value) {

					$address = '';
					if( !empty($value['address']) ){
						$address .= $value['address'];
					}
					if( !empty($value['road']) ){
						$address .= ' <span class="fwb">ถนน</span> '.$value['road'];
					}
					if( !empty($value['district']) ){
						$address .= ' <span class="fwb">แขวง/ตำบล</span> '.$value['district'];
					}
					if( !empty($value['area']) ){
						$address .= ' <span class="fwb">เขต/อำเภอ</span> '.$value['area'];
					}
					if( !empty($value['province']) ){
						$address .= ' <span class="fwb">จังหวัด</span> '.$value['province'];
					}
					if( !empty($value['post_code']) ){
						$address .= ' '.$value['post_code'];
					}
					if( !empty($value['country_name']) ){
						$address .= ' <span class="fwb">'.$value['country_name'].'</span>';
					}

					echo '<li>
					<label>
					<span class="fwb">(ที่อยู่ '.$num.')</span> '.$address.'
					</label>
					</li>';
					$num++;
				}
				?>
			</li>
		</ui>
	</div>
	<div class="clearfix mtm">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th width="15%">#</th>
					<th width="60%">ORDER</th>
					<th width="25%">ราคา</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if( !empty($this->item['orders']) ) {
					$num=1;
				foreach ($this->item['orders'] as $key => $value) { ?>
				<tr>
					<tr>
					<td class="tac"><?=$num?></td>
					<td class="mls"><?=$value['code']?></td>
					<td class="tar"><?=number_format($value['price'])?></td>
				</tr>
				<?php $num++; }
				}
				else{
					echo '<td colspan="3" class="tac fwb" style="color:red;">ไม่มีรายการสั่งซื้อสินค้า</td>';
				} ?>
			</tbody>
		</table>
	</div>
</div>
