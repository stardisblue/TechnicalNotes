<form method="post" data-toggle="validator" role="form">
    <input type="hidden" name="csrf" value="<?= $csrf ?>"/>
    <div class="form-group">
        <label class="control-label" for="name">name</label>
        <input class="form-control" type="text" name="name" id="name" value="<?= $user->name ?>" required>
        <div class="help-block with-errors"></div>
    </div>
    <div class="form-group">
        <label class="control-label" for="firstname">Prenom</label>
        <input class="form-control" type="text" name="firstname" id="firstname" value="<?= $user->firstname ?>"
               required>
        <div class="help-block with-errors"></div>
    </div>
    <div class="form-group">
        <label class="control-label" for="email">Email</label>
        <input class="form-control" type="email" name="email" id="email" value="<?= $user->email ?>" required>
        <div class="help-block with-errors"></div>
    </div>
    <div class="form-group">
        <label class="control-label" for="username">Pseudo</label>
        <input class="form-control" type="text" name="username" id="username" value="<?= $user->username ?>" required>
        <div class="help-block with-errors"></div>
    </div>
    <div class="form-group">
        <label for="password" class="control-label">Mot de passe</label>
        <input class="form-control" type="password" minlength="6" name="password" id="password"
               value="<?= $user->password ?>" placeholder=":)"
               required>
        <div class="help-block">Minimum of 6 characters</div>
    </div>
    <div class="form-group">
        <label class="control-label" for="verifypassword">Confirmer le mot de passe</label>
        <input class="form-control" type="password" minlength="6" name="verifypassword" id="verifypassword"
               placeholder="confirmer"
               data-match="#password" data-match-error="Oups, les mots de passes ne sont pas identiques" required>
        <div class="help-block with-errors"></div>
    </div>
    <input type="submit" value="Submit">
</form>