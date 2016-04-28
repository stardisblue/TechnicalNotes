<?php var_dump(get_defined_vars()); ?>
<ol class="breadcrumb">
    <li><a href="<?= WEB_ROOT . '/admin' ?>">Home</a></li>
    <li class="active">Tags</li>
</ol>

<a class="btn btn-success" href="<?= WEB_ROOT ?>/admin/tag/create"><i class="glyphicon glyphicon-plus"></i> Creer</a>

<table class="table table-responsive table-striped">
    <?php foreach ($tags as $tag): ?>
        <tr>
            <td><?= $tag->word ?></td>
            <td>
                <div class="form-inline">
                    <form action="<?= WEB_ROOT ?>/admin/tag/propose" class="form-group">
                        <input type="hidden" name="csrf" value="<?= $csrf ?>">
                        <input type="hidden" name="word" value="<?= $tag->word ?>">
                        <button class="btn btn-default" type="submit" data-toggle="tooltip" title=" re-Proposer">
                            <i class="glyphicon glyphicon-question-sign"></i>

                        </button>
                    </form>
                    <form action="<?= WEB_ROOT ?>/admin/tag/refuse" class="form-group">
                        <input type="hidden" name="csrf" value="<?= $csrf ?>">
                        <input type="hidden" name="word" value="<?= $tag->word ?>">
                        <button class="btn btn-danger" type="submit" data-toggle="tooltip" title="Refuser">
                            <i class="glyphicon glyphicon-ban-circle"></i></button>
                    </form>
                </div>
            </td>
            <td>
                <form action="<?= WEB_ROOT ?>/admin/tag/<?= $tag->id ?>/delete" method="post">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="<?= WEB_ROOT ?>/admin/tag/<?= $tag->id ?>" class="btn btn-default"
                           data-toggle="tooltip" title="Voir">
                            <i class="glyphicon glyphicon-eye-open"></i>
                        </a>
                        <a href="<?= WEB_ROOT ?>/admin/tag/<?= $tag->id ?>/update" class="btn btn-default"
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
</table>