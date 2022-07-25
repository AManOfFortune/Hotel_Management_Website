<a class="news-card" href="pages/links/news.php?id=<?= $news_id ?>">
    <img class="thumbnail" src="<?php if($news_thumbnail_path == '') {echo"/Semesterprojekt/pictures/thumbnails/default.png";} else {echo $news_thumbnail_path;}?>">

    <div class="news-information">
        <div class="news-title">
            <h2 class="news-headline"><?= $news_title ?></h2>
            <h4 class="news-datetime"><?= $news_datetime ?></h4>
        </div>

        <img src="/Semesterprojekt/pictures/icons/chevron-right.png">
    </div>
</a>