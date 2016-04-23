<?php

var_dump(get_defined_vars());
?>
<ol class="breadcrumb">
    <li><a href="<?= WEB_ROOT . '/admin' ?>">Home</a></li>
    <li><a href="<?= WEB_ROOT . '/admin/users' ?>">Users</a></li>
    <li class="active"><?= $user->name . ' ' . $user->firstname ?></li>
</ol>

<form method="post">
    <fieldset>
        <legend>Infos basique</legend>
        <input type="hidden" name="csrf" value="<?= $csrf ?>">
        <div class="form-group">
            <label for="nom">Nom :</label>
            <input class="form-control" type="text" name="name" id="name" value="<?= $user->name ?>"
                   placeholder="Nom">
        </div>
        <div class="form-group">
            <label for="firstname">Prenom :</label>
            <input class="form-control" type="text" name="firstname" id="firstname" value="<?= $user->firstname ?>"
                   placeholder="Prenom">
        </div>
        <div class="form-group">
            <label for="email">Email :</label>
            <input class="form-control" type="email" name="email" id="email" value="<?= $user->email ?>"
                   placeholder="E-mail">
        </div>
        <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-ok"></i> Mettre a jour</button>
    </fieldset>
</form>

<form method="post">
    <fieldset>
        <legend>Changer le mot de passe</legend>
        <div class="form-group">
            <label for="password">Nouveau mot de passe :</label>
            <input type="password" name="password" id="password" placeholder="Mot de passe">
        </div>
        <div class="form-group">
            <label for="verifypassword">Retapez le mot de passe :</label>
            <input type="password" name="verifypassword" id="verifypassword" placeholder="Retaper le mot de passe">
        </div>
        <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-ok"></i> Mettre a jour</button>
    </fieldset>
</form>
