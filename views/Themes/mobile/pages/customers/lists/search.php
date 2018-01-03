<div class="row">
	<div class="lfloat mlm pal rfloat" role="search">
		<form class="form-search" action="" id="global-nav-search" style="position: fixed; right: 0;">
			<label class="visuallyhidden" for="search-query">Search query</label>
			<input class="inputtext" type="text" placeholder="ค้นหารหัส / ชื่อลูกค้า" name="key" autocomplete="off" value="<?=(!empty($_GET['key']) ? rawurldecode($_GET['key']) : '')?>" style="height: 40px;">
			<button type="submit" class="search-icon js-search-action" tabindex="-1"><i class="icon-search"></i><span class="visuallyhidden"></span>
			</button>
		</form>
	</div>
</div>