<?php
if (isset($warning)) {
    var_dump($warning);
}
if (isset($info)) {
    var_dump($info);
}
if (isset($success)) {
    var_dump($success);
}
?>

<form action="" method="post">
    <input type="hidden" name="csrf" value="<?= $csrf ?>"/>
    <label for="email">Email</label>
    <input type="email" name="email" id="email" required>

    <label for="password">Mot de passe</label>
    <input type="password" name="password" id="password" required>

    <input type="submit" value="Se connecter">
</form>