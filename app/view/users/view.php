<table class="table table-bordered table-responsive table-striped">
    <tr>
        <td>Nom</td>
        <td><?= $user->name ?></td>
    </tr>
    <tr>
        <td>Prenom</td>
        <td><?= $user->firstname ?></td>
    </tr>
    <tr>
        <td>Pseudo</td>
        <td><?= $user->username ?></td>
    </tr>
    <tr>
        <td>Email</td>
        <td><?= $user->email ?></td>
    </tr>
    <?php if ($user->id === $userLogged->id) : ?>
        <tr>
            <td>Supprimer mon compte</td>
            <td>
                <form method="post" action="<?= WEB_ROOT ?>/user/delete"
                      onsubmit="return confirm('Etes-vous sur ? Votre compte et toutes les données associés vont etre supprimées');">
                    <input type="hidden" name="csrf" value="<?= $csrf ?>">
                    <button type="submit" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></button>
                </form>
            </td>
        </tr>
        <tr>
            <td>Editer</td>
            <td>
                <a class="btn btn-default" href="<?= WEB_ROOT ?>/user/edit" title="Modifier"><span
                        class="glyphicon glyphicon-pencil"></span> Modifier</a>
            </td>
        </tr>
    <?php endif; ?>
</table>
<?php if ($questions): ?>
    <h1>Questions</h1>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Titre</th>
            <th>Date</th>
        </tr>
        </thead>
        <?php foreach ($questions as $question): ?>
            <tr>
                <td><?= $question->id ?></td>
                <td><a href="<?= WEB_ROOT ?>/question/<?= $question->id ?>-<?= $question->slug ?>">
                        <?= $question->title ?>
                        <?php if ($question->status) : ?>
                            <i class="glyphicon glyphicon-ok-sign"></i>
                        <?php else : ?>
                            <i class="glyphicon glyphicon-question-sign"></i>
                        <?php endif; ?> </a>
                </td>
                <td><?= date('d/m/Y H:i', strtotime($question->creation_date)); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
<?php if ($technotes): ?>
    <h1>Technotes</h1>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Titre</th>
            <th>Date</th>
        </tr>
        </thead>
        <?php foreach ($technotes as $technote): ?>
            <tr>
                <td><?= $technote->id ?></td>
                <td><a href="<?= WEB_ROOT ?>/technote/<?= $technote->id ?>-<?= $technote->slug ?>">
                        <?= $technote->title ?></a></td>
                <td><?= date('d/m/Y H:i', strtotime($technote->creation_date)); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>