<?php

var_dump(get_defined_vars());
?>

<ol class="breadcrumb">
    <li><a href="<?= WEB_ROOT . '/admin' ?>">Home</a></li>
    <li class="active">Questions</li>
</ol>

<a class="btn btn-success" href="<?= WEB_ROOT . '/admin/question/create' ?>"><i class="glyphicon glyphicon-plus"></i>
    Creer</a>

<table class="table table-striped">

    <thead>
    <tr>
        <th>id</th>
        <th>Title</th>
        <th>Date</th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($questions as $question): ?>
        <tr>
            <td><?= $question->id ?></td>
            <td><?= $question->title ?></td>
            <td><?= date('d/m/Y H:i', strtotime($question->creation_date)); ?></td>
            <td>
                <?php if ($question->status) : ?>
                    <form action="<?= WEB_ROOT ?>/admin/question/<?= $question->id ?>/open" method="post">
                        <input type="hidden" name="csrf" value="<?= $csrf ?>">
                        <button class="btn btn-info" type="submit" data-toggle="tooltip" title="Ouvrir"><i
                                class="glyphicon glyphicon-question-sign"></i></button>
                    </form>
                <?php else : ?>
                    <form action="<?= WEB_ROOT ?>/admin/question/<?= $question->id ?>/close" method="post">
                        <input type="hidden" name="csrf" value="<?= $csrf ?>">
                        <button class="btn btn-success" type="submit" data-toggle="tooltip" title="Fermer"><i
                                class="glyphicon glyphicon-ok-sign"></i></button>
                    </form>
                <?php endif; ?>
            </td>
            <td>
                <form action="<?= WEB_ROOT ?>/admin/question/<?= $question->id ?>/delete" method="post">

                    <div class="btn-group">
                        <a class="btn btn-success" href="<?= WEB_ROOT ?>/admin/question/<?= $question->id ?>/update"><i
                                class="glyphicon glyphicon-edit"></i> Modifier</a>
                        <input type="hidden" name="csrf" value="<?= $csrf ?>">
                        <button type="submit" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></button>
                    </div>
                </form>

            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

