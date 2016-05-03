<h1><?= $question->title ?>

    <?php if (!$question->status) : ?>
        <i class="glyphicon glyphicon-question-sign"></i>
    <?php else : ?>
        <i class="glyphicon glyphicon-ok-sign" data-toggle="tooltip" title="Résolu"></i>
    <?php endif; ?>
</h1>
<?php if (isset($userLogged) && $userLogged->id === $question->user_id) : ?>
    <a class="btn btn-default btn-xs" href="<?= WEB_ROOT ?>/question/<?= $question->id ?>/edit" title=""><span
            class="glyphicon glyphicon-pencil"></span> Modifier</a>
    <form action="<?= WEB_ROOT ?>/question/<?= $question->id ?>/delete" method="post"
          onsubmit="return confirm('Etes-vous sur ? Votre compte et toutes les données associés vont etre supprimées');">
        <input type="hidden" name="csrf" value="<?= $csrf ?>">
        <button type="submit" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Supprimer
        </button>
    </form>
<?php endif; ?>
<p class="text-muted"><span class="glyphicon glyphicon-calendar"></span> <?= date('d/m/Y H:i',
        strtotime($question->creation_date)); ?></p>
<article>
    <?= $question->content ?>
</article>

<p></p>
<p class="text-muted"> Par : <a href="<?= WEB_ROOT ?>/user/<?= $user->id ?>"><?= $user->username ?></a>
</p>
<p class="text-muted"> Tags : <?php foreach ($tags as $tag):
        ?><a href="<?= WEB_ROOT ?>/tag/<?= $tag->id ?>"><?= $tag->word ?></a>, <?php
    endforeach; ?>
</p>

<div class="comments">


    <h3>Comments</h3>
    <?php
    if ($comments) {
        \techweb\app\view\HtmlHelper::displayComments($comments, $csrf, isset($userLogged) ? $userLogged->id : null,
            'question');
    } else {
        echo 'pas de commentaires';
    }
    ?>
    <form id="comment" action="<?= WEB_ROOT ?>/question/<?= $question->id ?>/comment" method="post">
        <input type="hidden" name="csrf" value="<?= $csrf ?>">
        <input type="hidden" name="parent_id" id="parent_id">
        <div class="form-group">
            <label class="control-label" for="content">Commenter</label>
            <textarea class="form-control" name="content" id="content" cols="30" rows="3" required></textarea>
        </div>
        <button class="btn btn-success" type="submit">Commenter</button>
    </form>
</div>

<h2>Réponses</h2>
<?php
if ($answers) {
    foreach ($answers as $answer):?>

        <p class="text-muted"><span class="glyphicon glyphicon-calendar"></span> <?= date('d/m/Y H:i',
                strtotime($answer->creation_date)); ?></p>
        <article>
            <?= $answer->content ?>
        </article>

        <p></p>
        <p class="text-muted"><a href="<?= WEB_ROOT ?>/user/<?= $user->id ?>"><?= $user->username ?></a>
        <?php if (isset($userLogged) && $userLogged->id === $answer->user_id) : ?>
            <form action="<?= WEB_ROOT ?>/answer/<?= $answer->id ?>/delete" method="post"
                  onsubmit="return confirm('Etes-vous sur ? toutes les données associés vont etre supprimées');">
                <input type="hidden" name="csrf" value="<?= $csrf ?>">
                <button type="submit" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span>
                    Supprimer
                </button>
            </form>
        <?php endif; ?>
        </p>
        <div class="answers">
            <h4>Commentaires de la réponse</h4>
            <?php \techweb\app\view\HtmlHelper::displayComments($answer->comments, $csrf,
                isset($userLogged) ? $userLogged->id : null, 'question/answer'); ?>
            <h5>Commenter</h5>
            <form id="answer"
                  action="<?= WEB_ROOT ?>/question/<?= $question->id ?>/<?= $answer->id ?>/comment" method="post">
                <input type="hidden" name="csrf" value="<?= $csrf ?>">
                <input type="hidden" name="parent_id" id="answer_parent_id">
                <div class="form-group">
                    <label class="control-label" for="content">Commenter</label>
                    <textarea class="form-control" name="content" id="content" cols="30" rows="3" required></textarea>
                </div>
                <button class="btn btn-success" type="submit">Commenter</button>
            </form>
        </div>

        <?php
    endforeach;
} else {
    echo 'pas de réponses';
}
?>
<h3>Répondre</h3>
<form id="answer" action="<?= WEB_ROOT ?>/question/<?= $question->id ?>/answer" method="post">
    <input type="hidden" name="csrf" value="<?= $csrf ?>">
    <div class="form-group">
        <label class="control-label" for="content">Commenter</label>
        <textarea class="form-control" name="content" id="content" cols="30" rows="10" required></textarea>
    </div>
    <button class="btn btn-success" type="submit">Commenter</button>
</form>

<script>
    $(function () {
        $('.comments .comment').click(function () {
            $('#parent_id').val(this.id);
        });


        $('.answers .comment').click(function () {
            $('#answer_parent_id').val(this.id);
        })
    })
</script>