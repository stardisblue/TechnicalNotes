<?php

var_dump(get_defined_vars());

?>

<form action="" method="post">
    <input type="hidden" name="csrf" value="<?= $csrf ?>"/>
    <label for="name">name</label>
    <input type="text" name="name" id="name" required>
    <label for="firstname">firstname</label>
    <input type="text" name="firstname" id="firstname" required>
    <label for="email">Email</label>
    <input type="email" name="email" id="email" required>

    <label for="username">Pseudo</label><input type="text" name="username" id="username" required>
    <label for="password">password</label>
    <input type="password" name="password" id="password" required>
    <label for="verifypassword">retype password</label>
    <input type="password" name="verifypassword" id="verifypassword" required>

    <input type="submit" value="Submit">
</form>