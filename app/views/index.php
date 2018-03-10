<div class="list-view">
    <div class="header">
        <h1>Listan</h1>
        <a class="pure-button" href="/add">Lägg till</a>
    </div>
    <div class="list">
    <?php foreach($posts as $post) { ?>
        <div class="item" data-id="<?php echo $post->id; ?>">
            <h2><?php echo $post->title; ?></h2>
            <div class="flex">
                <div class="content"><?php echo nl2br($post->description); ?></div>
                <div class="btn-area">
                    <a href="/edit/<?php echo $post->id; ?>" class="pure-button pure-button-primary">Ändra</a>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
</div>