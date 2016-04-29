<ol class="breadcrumb">
    <li><a href="<?= WEB_ROOT . '/admin' ?>">Home</a></li>
    <li class="active">Users</li>
</ol>

<a class="btn btn-success" href="<?= WEB_ROOT . '/admin/user/create' ?>"><i class="glyphicon glyphicon-plus"></i> Creer</a>

<table class="table table-striped">

    <thead>
    <tr>
        <th>nom</th>
        <th>prenom</th>
        <th>Pseudo</th>
        <th>email</th>
        <th>actif</th>
        <th>admin</th>
        <th>actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user->name ?></td>
            <td><?= $user->firstname ?></td>
            <td><?= $user->username ?></td>
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
            <td>
                <form action="<?= WEB_ROOT ?>/admin/user/<?= $user->id ?>/delete" method="post">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="<?= WEB_ROOT ?>/admin/user/<?= $user->id ?>" class="btn btn-default"
                           data-toggle="tooltip" title="Voir">
                            <i class="glyphicon glyphicon-eye-open"></i>
                        </a>
                        <a href="<?= WEB_ROOT ?>/admin/user/<?= $user->id ?>/update"
                           class="btn btn-default"
                           data-toggle="tooltip" title="Modifier">
                            <i class="glyphicon glyphicon-edit"></i>
                        </a>

                        <input type="hidden" name="csrf" value="<?= $csrf ?>">
                        <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Supprimer">
                            <i class="glyphicon glyphicon-remove"></i>
                        </button>
                    </div>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

