<?php
var_dump(get_defined_vars());
?>

<form method="post">
    <input type="hidden" name="csrf" value="<?= $csrf ?>">
    <label for="title">Titre</label>
    <input type="text" name="title" id="title" minlength="3" placeholder="Les meilleurs titres sont les plus courts">

    <label for="content">Contenu</label>
    <textarea name="content" id="content" cols="30" rows="10" placeholder="Contenu"></textarea>
    <select name="user_id" id="user_id">
        <?php foreach ($users as $user): ?>
            <option value="<?= $user->id ?>"><?= $user->email ?></option>
        <?php endforeach; ?>
    </select>

    <input type="submit" value="Creer">
</form>
