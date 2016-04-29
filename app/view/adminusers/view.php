<table class="table table-responsive table-striped">
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
    <tr>
        <td>Est activé</td>
        <td><?php if ($user->token === ''): ?>
                <i class="glyphicon glyphicon-ok-sign"></i>
            <?php else: ?>
                <i class="glyphicon glyphicon-remove-sign"></i>
                <form action="/admin/user/<?= $user->id ?>/validate" method="post">
                    <input type="hidden" name="csrf" value="<?= $csrf ?>"/>
                    <input type="hidden" name="haha" value="haha"/>
                    <input type="submit" value="Submit">
                </form>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>Est administrateur</td>
        <td><?php if ($user->isadmin == 1): ?>
                <i class="glyphicon glyphicon-ok-sign"></i>

                <form action="/admin/user/<?= $user->id ?>/downgrade" method="post">
                    <input type="hidden" name="csrf" value="<?= $csrf ?>"/>
                    <button type="submit" class="btn btn-danger"><i class="glyphicon glyphicon-chevron-down"></i>
                        Destituer
                    </button>
                </form>
            <?php else: ?>
                <i class="glyphicon glyphicon-remove-sign"></i>
                <form action="/admin/user/<?= $user->id ?>/upgrade" method="post">
                    <input type="hidden" name="csrf" value="<?= $csrf ?>"/>
                    <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-chevron-up"></i>
                        Promouvoir
                    </button>
                </form>
            <?php endif; ?></td>
    </tr>
</table>
