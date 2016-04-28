<?php

var_dump(get_defined_vars());
?>

<ol class="breadcrumb">
    <li><a href="<?= WEB_ROOT . '/admin' ?>">Home</a></li>
    <li class="active">Technotes</li>
</ol>

<a href="<?= WEB_ROOT . '/admin/technote/create' ?>">Creer</a>
<a href="<?= WEB_ROOT . '/admin/' ?>">home</a>
<table class="table table-striped">

    <thead>
    <tr>
        <th>id</th>
        <th>Title</th>
        <th>Date</th>
        <th>edit</th>
        <th>supprimer</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($technotes as $technote): ?>
        <tr>
            <td><?= $technote->id ?></td>
            <td><?= $technote->title ?></td>
            <td><?= date('d/m/Y H:i', strtotime($technote->creation_date)); ?></td>

            <td><a class="btn btn-success" href="<?= WEB_ROOT ?>/admin/technote/<?= $technote->id ?>/update"><i
                        class="glyphicon glyphicon-edit"></i> Modifier</a>
            </td>
            <td>
                <form action="<?= WEB_ROOT ?>/admin/technote/<?= $technote->id ?>/delete" method="post">
                    <input type="hidden" name="csrf" value="<?= $csrf ?>">
                    <button type="submit" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

