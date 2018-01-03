<style type="text/css" media="screen">
	.mediaWrapper>*, .mediaWrapper>a>* {
		position: relative;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
	}
</style>
<?php 
include("search.php");
?>

<div role="main" class="pal">
	<div class="wrapper web-lists-wrap active posts clearfix" data-plugins="datalistsbox" data-options="<?=$this->fn->stringify( array('url' => URL.'mobile/customers'.(!empty($_GET['key']) ? '?key='.rawurldecode($_GET['key']) : '')) )?>">
		<ul class="ui-list clearfix" role=listsbox></ul>
		<div class="empty">
			<div class="empty-loader loader-spin-wrap"><div class="loader-spin"></div></div>
			<?php 
			if( !empty($_GET["key"]) ){
				echo '<div class="empty-text"></div>';
			}
			?>
			<div class="empty-error js-refresh"></div>
		</div>
		<div class="web-lists-more"><a class="btn btn-jumbo js-more">โหลดเพิ่ม</a></div>
	</div>
</div>