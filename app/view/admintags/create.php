<?php
var_dump(get_defined_vars());
?>

<form method="post">
    <input type="hidden" name="csrf" value="<?= $csrf ?>">
    <div class="form-group">
        <label for="word">Mot clé</label>
        <input class="form-control" type="text" name="word" id="word" placeholder="Mot clé" required>
    </div>

    <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> Créer</button>

</form>
