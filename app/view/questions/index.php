<ol class="breadcrumb">
    <li><a href="<?= WEB_ROOT . '/' ?>">Home</a></li>
    <li class="active">Technotes</li>
</ol>
<a class="btn btn-success" href="<?= WEB_ROOT . '/question/create' ?>"><i class="glyphicon glyphicon-plus"></i>
    Creer</a>

<table class="table table-striped">

    <thead>
    <tr>
        <th>id</th>
        <th>Title</th>
        <th>Date</th>
        <th>Statut</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($questions as $question): ?>
        <tr>
            <td><?= $question->id ?></td>
            <td><?= $question->title ?></td>
            <td><?= date('d/m/Y H:i', strtotime($question->creation_date)); ?>
            </td>
            <td>
                <?php if (!$question->status) : ?>
                    <i class="glyphicon glyphicon-question-sign"></i>
                <?php else : ?>
                    <i class="glyphicon glyphicon-ok-sign" data-toggle="tooltip" title="Résolu"></i>
                <?php endif; ?>
            </td>
            <td>
                <?php if (isset($userLogged) && $userLogged->id === $question->user_id): ?>
                <form action="<?= WEB_ROOT ?>/question/<?= $question->id ?>/delete" method="post">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <?php endif; ?>
                        <a href="<?= WEB_ROOT ?>/question/<?= $question->id ?>-<?= $question->slug ?>"
                           class="btn btn-default"
                           data-toggle="tooltip" title="Voir">
                            <i class="glyphicon glyphicon-eye-open"></i>
                        </a>
                        <?php if (isset($userLogged) && $userLogged->id === $question->user_id): ?>
                        <a href="<?= WEB_ROOT ?>/question/<?= $question->id ?>/edit"
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

