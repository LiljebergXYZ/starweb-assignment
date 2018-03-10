<form method="POST" class="list-view">
    <?php if(isset($error)) { ?>
        <div class="error">
            <?php echo $error; ?>
        </div>
    <?php } ?>
    <?php if(isset($post)) { ?>
        <div class="post-added">
            Inlägg tillagt med id <a href="/edit/<?php echo $post->id; ?>"><?php echo $post->id; ?></a>
        </div>
    <?php } ?>
    <div class="header">
        <h1>Lägg till</h1>
        <a class="pure-button" href="/">Tillbaka</a>
    </div>
    <input type="text" placeholder="Rubrik" name="title" id="title" />
    <textarea name="description" id="description"></textarea>
    <input type="submit" class="pure-button" value="Spara" />
</form>