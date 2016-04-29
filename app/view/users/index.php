<ol class="breadcrumb">
    <li><a href="<?= WEB_ROOT . '/admin' ?>">Home</a></li>
    <li class="active">Users</li>
</ol>

<table class="table table-striped">
    <thead>
    <tr>
        <th>nom</th>
        <th>prenom</th>
        <th>Pseudo</th>
        <th>email</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <?php if ($user->token === '') : ?>
            <tr>
                <td><?= $user->name ?></td>
                <td><?= $user->firstname ?></td>
                <td><a href="<?= WEB_ROOT ?>/user/<?= $user->id ?>"><?= $user->username ?></a></td>
                <td><?= $user->email ?></td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
    </tbody>
</table>

