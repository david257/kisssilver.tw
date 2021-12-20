<?php
$topNews = get_top_news();
if(!empty($topNews)) {
?>
<div class="top-tips">
    <div class="owl-carousel">
        <?php 
		foreach ($topNews as $news) { 
			if(!empty($news['url'])) {
				$url = $news['url'];
			} else {
				$url = front_link('News/detail', ['newsid' => $news['newsid']]);
			}
		?>
        <div class="item">
        <a href="{$url}" class="top-tips-a">{$news['title']}
            </a>
            </div>
        <?php } ?>
    </div>
</div>
<?php } ?>