<ol class="breadcrumb">
    <li><a href="<?= WEB_ROOT . '/users' ?>">Users</a></li>
    <li class="active"><?= $user->name . ' ' . $user->firstname ?></li>
</ol>

<form method="post">
    <fieldset>
        <legend>Infos basique</legend>
        <input type="hidden" name="csrf" value="<?= $csrf ?>">
        <div class="form-group">
            <label class="control-label" for="nom">Nom :</label>
            <input class="form-control" type="text" name="name" id="name" value="<?= $user->name ?>"
                   placeholder="Nom">
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="firstname">Prenom :</label>
            <input class="form-control" type="text" name="firstname" id="firstname" value="<?= $user->firstname ?>"
                   placeholder="Prenom">
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="email">Email :</label>
            <input class="form-control" type="email" name="email" id="email" value="<?= $user->email ?>"
                   placeholder="E-mail" disabled>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="username">Pseudo :</label>
            <input class="form-control" type="text" name="username" id="username" value="<?= $user->username ?>"
                   placeholder="Pseudo">
            <div class="help-block with-errors"></div>
        </div>
        <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-ok"></i> Mettre a jour</button>
    </fieldset>
</form>

<form method="post">
    <fieldset>
        <legend>Changer le mot de passe</legend>
        <div class="form-group">
            <label class="control-label" for="password">Nouveau mot de passe :</label>
            <input class="form-control" type="password" minlength="6" name="password" id="password" placeholder=":)"
                   required>
            <div class="help-block">Minimum of 6 characters</div>
        </div>
        <div class="form-group">
            <label class="control-label" for="verifypassword">Retapez le mot de passe :</label>
            <input class="form-control" type="password" minlength="6" name="verifypassword" id="verifypassword"
                   placeholder="confirmer"
                   data-match="#password" data-match-error="Oups, les mots de passes ne sont pas identiques" required>
            <div class="help-block with-errors"></div>
        </div>
        <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-ok"></i> Mettre a jour</button>
    </fieldset>
</form>
