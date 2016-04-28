<?php
var_dump(get_defined_vars());
?>

<form method="post">
    <input type="hidden" name="csrf" value="<?= $csrf ?>">
    <label for="title">Titre</label>
    <input class="form-control" type="text" name="title" id="title" minlength="3"
           placeholder="Les meilleurs titres sont les plus courts">

    <label for="content">Contenu</label>
    <textarea class="form-control" name="content" id="content" cols="30" rows="10" placeholder="Contenu"></textarea>
    <label for="user_id">Utilisateur</label>
    <select name="user_id" id="user_id" class="form-control">
        <option value="<?= $logged->id ?>" selected="selected"><?= $logged->email ?></option>
    </select>

    <input type="submit" value="Creer">
</form>

<script>
    $(document).ready(function () {
        $("#user_id").select2(
            ajaxBuilder(
                '<?=WEB_ROOT?>/ajax/admin/users',
                function (params) {
                    return {
                        csrf_ajax: Cookies.get('csrf_ajax'),
                        search: params.term, // search term
                        page: params.page
                    };
                },
                function (user) {
                    return user.email;
                },
                function (user) {
                    return user.title || user.text;
                })
        );
    })
</script>