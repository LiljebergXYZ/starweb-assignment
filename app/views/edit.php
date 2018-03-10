<form method="POST" class="list-view">
    <?php if(isset($error)) { ?>
        <div class="error">
            <?php echo $error; ?>
        </div>
    <?php } ?>
    <?php if(isset($saved)) { ?>
        Inlägg sparat
    <?php } ?>
    <div class="header">
        <h1>Ändra</h1>
        <a class="pure-button" href="/">Tillbaka</a>
    </div>
    <input type="text" name="title" value="<?php echo $post->title; ?>" id="title" />
    <textarea name="description" id="description"><?php echo $post->description ?></textarea>
    <input type="submit" class="pure-button" value="Spara" />
</form>