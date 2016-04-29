<form method="post">
    <fieldset>
        <legend>Technote</legend>
        <input type="hidden" name="csrf" value="<?= $csrf ?>">

        <div class="form-group">
            <label for="title">Titre :</label>
            <input type="text" name="title" id="title" class="form-control" value="<?= $technote->title ?>"
                   placeholder="Les meilleurs titres sont les plus courts">
        </div>
        <div class="form-group">
            <label for="content">Note :</label>
            <textarea name="content" id="content" cols="30" rows="10"
                      class="form-control"><?= $technote->content ?></textarea>
        </div>

        <label for="tags">Mots-clés</label>
        <select class="form-control" name="tags[]" id="tags" multiple>
            <?php foreach ($tags as $tag): ?>
                <option value="<?= $tag->id ?>" selected="selected"><?= $tag->word ?></option>
            <?php endforeach; ?>
        </select>

        <label for="user_id">Utilisateur</label>
        <select class="form-control" name="user_id" id="user_id">
            <option value="<?= $user->id ?>" selected><?= $user->username ?></option>
        </select>

        <button class="btn btn-success"><i class="glyphicon glyphicon-edit"></i> Mettre a jour</button>
    </fieldset>
</form>

<script>
    $(document).ready(function () {
        $('#tags').select2(
            ajaxBuilder(
                '<?=WEB_ROOT?>/ajax/tags',
                function (params) {
                    return {
                        csrf_ajax: Cookies.get('csrf_ajax'),
                        search: params.term, // search term
                        page: params.page
                    };
                },
                function (tag) {
                    return tag.word;
                },
                function (tag) {
                    return tag.word || tag.text;
                })
        );

        $('#user_id').select2(
            ajaxBuilder('<?=WEB_ROOT?>/ajax/admin/users', function (params) {
                    return {
                        csrf_ajax: Cookies.get('csrf_ajax'),
                        search: params.term, // search term
                        page: params.page
                    };
                },
                function (user) {
                    return user.username;
                },
                function (user) {
                    return user.username || user.text;
                })
        );
    })
</script>
