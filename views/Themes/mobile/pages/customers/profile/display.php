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
			<li>
				<div class="clearfix">
					<br>
					<?php if( !empty($_GET["due"]) ){ ?>
						<a href="<?=URL?>customers/<?=$this->item['id']?>" class="rfloat btn btn-blue btn-large" >ดูบิลทั้งหมด</a>
					<?php } else { ?>
						<a href="<?=URL?>customers/<?=$this->item['id']?>?due=1" class="rfloat btn btn-red btn-large" >ดูยอดค้างจ่าย</a>
					<?php } ?>
				</div>
			</li>
		</ui>

	</div>

	<div class="clearfix mtm">
		<?php if( !empty($_GET["due"]) ){ ?>
		<h3 width="20" style="color:#ff0000; margin-bottom:10px;">&nbsp;ยอดค้างจ่าย</h3>
		<?php } ?>

		<table class="table table-bordered">
			<thead>
				<tr>
					<th width="15%">#</th>
					<th width="45%">ORDER</th>
					<th width="20%">ราคา</th>
					<?php if( !empty($_GET["due"]) ){ ?>
					<th width="20" style="color:#ff0000;">ยอดค้างจ่าย</th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php
				if( !empty($this->item['orders']) ) {
					$num=1;
					$sum_price=0;
					$sum_balance=0;
				foreach ($this->item['orders'] as $key => $value) {
					$sum_price+=$value['net_price'];
					$sum_balance+=$value['balance'];
					?>
				<tr>
					<tr>
					<td class="tac"><?=$num?></td>
					<td class="mls">&nbsp;<?=$value['code']?></td>
					<td class="tar"><?=number_format($value['net_price'])?>&nbsp;</td>
					<?php if( !empty($_GET["due"]) ){ ?>
						<td class="tar" style="color:#ff0000;"><?=number_format($value['balance'])?>&nbsp;</td>
					<?php }?>
				</tr>
				<?php $num++; }
				}
				else{
					if( !empty($_GET["due"]) ) echo '<td colspan="4" class="tac fwb" style="color:red;">ไม่มีรายการสั่งซื้อสินค้า</td>';
					else echo '<td colspan="3" class="tac fwb" style="color:red;">ไม่มีรายการสั่งซื้อสินค้า</td>';
				} ?>
				<?php
				if( !empty($_GET["due"]) )
					echo '<td colspan="2" class="tac fwb">รวม</td><td class="tar"><b>'.number_format($sum_price).' ฿</b>&nbsp;</td><td class="tar" style="color:#ff0000;"><b>'.number_format($sum_balance).' ฿</b>&nbsp;</td>';
				else
					echo '<td colspan="2" class="tac fwb">รวม</td><td class="tar"><b>'.number_format($sum_price).' ฿</b>&nbsp;</td>';
				?>

			</tbody>
		</table>
	</div>
</div>
