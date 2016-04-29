<?php
var_dump(get_defined_vars());
?>
    <h1><?= $tag->word ?></h1>

<?php if (isset($questions)): ?>
    <h2>Questions</h2>

    <table class="table table-responsive table-striped">
        <?php foreach ($questions as $question): ?>
            <tr>
                <td><a href="<?= WEB_ROOT ?>/admin/question/<?= $question->id ?>"><?= $question->title ?></a></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
<?php if (isset($votes)) : ?>


    <progress class="progresscustom" value="<?= $tag->positive_votes ?>" max="<?= $tag->total_votes ?>">
        <?= $tag->total_votes == 0 ? 0 : number_format(($tag->positive_votes * 100 / $tag->total_votes), 2) ?>%
    </progress>

    <table class="table table-responsive table-striped">
        <?php foreach ($votes as $vote): ?>
            <tr class="<?= $vote->ispositive ? '.positive' : '.negative'; ?>">
                <td><a href="<?= WEB_ROOT ?>/admin/user/<?= $vote->user_id ?>"><?= $vote->username ?></a></td>
            </tr>
        <?php endforeach; ?></table>
<?php endif; ?>
<?php if (isset($technotes)): ?>
    <h2>Technotes</h2>
    <table class="table table-responsive table-striped">
        <?php foreach ($technotes as $technote): ?>
            <tr>
                <td><a href="<?= WEB_ROOT ?>/admin/technote/<?= $technote->id ?>"><?= $technote->title ?></a></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>