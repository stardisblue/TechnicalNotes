<?php
var_dump(get_defined_vars());
?>
<h1><?= $tag->word ?></h1>

<h2>Questions</h2>

<table class="table table-responsive table-striped">
    <?php foreach ($questions as $question): ?>
        <tr>
            <td><a href="<?= WEB_ROOT ?>/admin/question/<?= $question->id ?>"><?= $question->title ?></a></td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Technotes</h2>
<table class="table table-responsive table-striped">
    <?php foreach ($technotes as $technote): ?>
        <tr>
            <td><a href="<?= WEB_ROOT ?>/admin/technote/<?= $technote->id ?>"><?= $technote->title ?></a></td>
        </tr>
    <?php endforeach; ?>
</table>