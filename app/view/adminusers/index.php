<?php
var_dump(get_defined_vars());
?>
<ol class="breadcrumb">
    <li><a href="<?= WEB_ROOT . '/admin' ?>">Home</a></li>
    <li class="active">Users</li>
</ol>

<a href="<?= WEB_ROOT . '/admin/user/create' ?>">Creer</a>
<a href="<?= WEB_ROOT . '/admin/' ?>">home</a>
<table class="table table-striped">

    <thead>
    <tr>
        <th>id</th>
        <th>nom</th>
        <th>prenom</th>
        <th>email</th>
        <th>actif</th>
        <th>admin</th>
        <th>edit</th>
        <th>supprimer</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user->id ?></td>
            <td><?= $user->name ?></td>
            <td><?= $user->firstname ?></td>
            <td><?= $user->email ?></td>

            <td>
                <?php if ($user->token === '') : ?>
                    <input type="checkbox" name="actif" id="actif" readonly disabled checked>
                <?php else : ?>
                    <form action="/admin/user/<?= $user->id ?>/validate" method="post">
                        <input type="hidden" name="csrf" value="<?= $csrf ?>"/>
                        <input type="hidden" name="haha" value="haha"/>
                        <input type="submit" value="Submit">
                    </form>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($user->isadmin === '1'): ?>
                    <form action="/admin/user/<?= $user->id ?>/downgrade" method="post">
                        <input type="hidden" name="csrf" value="<?= $csrf ?>"/>
                        <button type="submit" class="btn btn-danger"><i class="glyphicon glyphicon-chevron-down"></i>
                            Destituer
                        </button>
                    </form>
                <?php else : ?>
                    <form action="/admin/user/<?= $user->id ?>/upgrade" method="post">
                        <input type="hidden" name="csrf" value="<?= $csrf ?>"/>
                        <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-chevron-up"></i>
                            Promouvoir
                        </button>
                    </form>
                <?php endif; ?>
            </td>
            <td><a class="btn btn-success" href="<?= WEB_ROOT . '/admin/user/' . $user->id . '/update' ?>"><i
                        class="glyphicon glyphicon-edit"></i> Modifier</a>
            </td>
            <td>
                <form action="/admin/user/<?= $user->id ?>/delete" method="post">
                    <input type="hidden" name="csrf" value="<?= $csrf ?>">
                    <button type="submit" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

