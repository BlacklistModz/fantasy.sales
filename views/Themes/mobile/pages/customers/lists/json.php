<?php

$li = '';
foreach ($this->results['lists'] as $key => $value) {

	$address = '';
	if( !empty($value['address'][0]) ){
		if( !empty($value['address'][0]['area']) ){
			$address .= $value['address'][0]['area'];
		}
		if( !empty($value['address'][0]['province']) ){
			$address .= ' จ.'.$value['address'][0]['province'];
		}
	}

	$li .= '<li class="ui-list-item border-bottom mhs pas anchor clearfix"><a class="anchor clearfix" href="'.URL.'customers/'.$value['id'].'">'.

			'<div class="avatar lfloat icon tac mrm"><i class="icon-user"></i></div>'.

			'<div class="rfloat content">'.
				'<div class="spacer"></div>'.
				$address.
			'</div>'.
			// 		if( !empty($value['phone']) ){
			// 			$li .= '<span class="gbtn mrs">'.
			// 					'<a href="tel:'.$value['phone'].'" class="btn btn-blue btn-jumbo" style="font-size: 30px;">
			// 					<i class="icon-phone-square"></i>
			// 					</a>'.
			// 				'</span>';
			// 		}
			// $li .= '<span class="gbtn">'.
			// 			'<a href="'.URL.'mobile/createOrder/'.$value['id'].'" class="btn btn-green btn-jumbo" style="font-size: 30px;">
			// 				<i class="icon-cart-plus"></i>
			// 			</a>'.
			// 		'</span>'.
			// '</div>'.

			'<div class="content">'.
				'<div class="spacer"></div>'.
					'<div class="massages">'.
					'<div class="ui-score"></div>'.
					'<div class="title fwb">'.$value['name_store'].'</div>'.
					'<div class="fwn">'.$value['sub_code'].'</div>'.
				'</div>'.
			'</div>'.
			'</a></li>';
}

echo json_encode( array_merge($this->results, array(
	'$lis'=>$li,
)) );