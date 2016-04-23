<?php
var_dump(get_defined_vars());
?>

<form method="post">
    <fieldset>
        <legend>Technote</legend>
        <input type="hidden" name="csrf" value="<?= $csrf ?>">
        <input type="hidden" name="user_id" value="<?= $technote->user_id ?>">

        <div class="form-group">
            <label for="title">Titre :</label>
            <input type="text" name="title" id="title" class="form-control" value="<?= $technote->title ?>"
                   placeholder="Les meilleurs titres sont les plus courts">
        </div>
        <div class="form-group">
            <label for="content">Note :</label>
            <textarea name="content" id="content" cols="30" rows="10"
                      class="form-control"><?= $technote->content ?></textarea>
        </div>
        <button class="btn btn-success"><i class="glyphicon glyphicon-edit"></i> Mettre a jour</button>
    </fieldset>

</form>
