<form class="form-signin" method="post">
    <h2 class="form-signin-heading">Se connecter</h2>
    <input type="hidden" name="csrf" value="<?= $csrf ?>"/>

    <label for="inputEmail" class="sr-only">Email</label>

    <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required
           autofocus>

    <label for="inputPassword" class="sr-only">Mot de passe</label>
    <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>

    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
</form>
