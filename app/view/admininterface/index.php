<?php

var_dump(get_defined_vars());

?>
<a href="<?= WEB_ROOT ?>/admin/logout?csrf=<?= $csrf ?>"> logout </a>
<a href="<?= WEB_ROOT ?>/admin/users"> users </a>
<a href="<?= WEB_ROOT ?>/admin/technotes"> notes </a>
<a href="<?= WEB_ROOT ?>/admin/questions"> questions </a>
<a href="<?= WEB_ROOT ?>/admin/tags"> tags </a>


