<form action="<?= $curPageName ?>?registration=true&newsid=<?= $newsid ?>" method="post" enctype="multipart/form-data">
    
    <div class="row form-group col-sm-12">
        <label for="title">Titel</label>
        <input type="text" class="form-control" id="title" placeholder="This is a cool Title!" name="title" value="<?= $news_title ?>" required>
    </div>

    <div class="row col-sm-12" id="news_content_wrapper">
        <div class="form-group col-sm-12">
            <label for="news_content">Inhalt</label>
            <textarea class="form-control" rows="20" id="news_content" name="content" placeholder="The main content of the article. Press the buttons below to insert headings, lists, etc." required><?= $text_content ?></textarea>
        </div>
        <div class="row col-sm-12 space-around">
            <button type="button" class="show-btn" onclick="insertAtCursor('h1')">h1</button>
            <button type="button" class="show-btn" onclick="insertAtCursor('h2')">h2</button>
            <button type="button" class="show-btn" onclick="insertAtCursor('h3')">h3</button>
            <button type="button" class="show-btn" onclick="insertAtCursor('ul')">ul</button>
            <button type="button" class="show-btn" onclick="insertAtCursor('ol')">ol</button>
            <button type="button" class="show-btn" onclick="insertAtCursor('a')">a</button>
        </div>
    </div>

    <div class="row form-group col-sm-12">
        <h2 for="pictures[]">Laden Sie Bilder hoch (optional):</h2>
        <input class="form-control" type="file" name="pictures[]" id="pictures[]" multiple>
    </div>