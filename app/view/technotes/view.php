<h1><?= $technote->title ?>
</h1>
<?php if (isset($userLogged) && $userLogged->id === $technote->user_id) : ?>
    <a class="btn btn-default btn-xs" href="<?= WEB_ROOT ?>/technote/<?= $technote->id ?>/edit" title=""><span
            class="glyphicon glyphicon-pencil"></span> Modifier</a>
    <form action="<?= WEB_ROOT ?>/technote/<?= $technote->id ?>/delete" method="post"
          onsubmit="return confirm('Etes-vous sur ? Votre compte et toutes les données associés vont etre supprimées');">
        <input type="hidden" name="csrf" value="<?= $csrf ?>">
        <button type="submit" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Supprimer
        </button>
    </form>
<?php endif; ?>
<p class="text-muted"><span class="glyphicon glyphicon-calendar"></span> <?= date('d/m/Y H:i',
        strtotime($technote->creation_date)); ?></p>
<article>
    <?= $technote->content ?>
</article>

<p></p>
<p class="text-muted"> Par : <a href="<?= WEB_ROOT ?>/user/<?= $user->id ?>"><?= $user->username ?></a>
</p>
<p class="text-muted"> Tags : <?php foreach ($tags as $tag):
        ?><a href="<?= WEB_ROOT ?>/tag/<?= $tag->id ?>"><?= $tag->word ?></a>, <?php
    endforeach; ?>
</p>


<h2>Comments</h2>
<?php
if ($comments) {
    \techweb\app\view\HtmlHelper::displayComments($comments, $csrf, isset($userLogged) ? $userLogged->id : null);
} else {
    echo 'pas de commentaires';
}
?>

<form id="comment" action="<?= WEB_ROOT ?>/technote/<?= $technote->id ?>/comment" method="post">
    <input type="hidden" name="csrf" value="<?= $csrf ?>">
    <input type="hidden" name="parent_id" id='parent_id'>
    <div class="form-group">
        <label class="control-label" for="content">Commenter</label>
        <textarea class="form-control" name="content" id="content" cols="30" rows="10" required></textarea>
    </div>
    <button class="btn btn-success" type="submit">Commenter</button>
</form>

<script>
    $(function () {
        $('.comment').click(function () {
            $('#parent_id').val(this.id);
        })
    })
</script>