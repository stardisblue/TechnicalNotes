<ol class="breadcrumb">
    <li><a href="<?= WEB_ROOT . '/' ?>">Home</a></li>
    <li class="active">Technotes</li>
</ol>
<a class="btn btn-success" href="<?= WEB_ROOT . '/technote/create' ?>"><i class="glyphicon glyphicon-plus"></i>
    Creer</a>

<table class="table table-striped">

    <thead>
    <tr>
        <th>id</th>
        <th>Title</th>
        <th>Date</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($technotes as $technote): ?>
        <tr>
            <td><?= $technote->id ?></td>
            <td><?= $technote->title ?></td>
            <td><?= date('d/m/Y H:i', strtotime($technote->creation_date)); ?>
            </td>
            <td>
                <?php if (isset($userLogged) && $userLogged->id === $technote->user_id): ?>
                <form action="<?= WEB_ROOT ?>/technote/<?= $technote->id ?>/delete" method="post">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <?php endif; ?>
                        <a href="<?= WEB_ROOT ?>/technote/<?= $technote->id ?>-<?= $technote->slug ?>"
                           class="btn btn-default"
                           data-toggle="tooltip" title="Voir">
                            <i class="glyphicon glyphicon-eye-open"></i>
                        </a>
                        <?php if (isset($userLogged) && $userLogged->id === $technote->user_id): ?>
                        <a href="<?= WEB_ROOT ?>/technote/<?= $technote->id ?>/edit"
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
            <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

