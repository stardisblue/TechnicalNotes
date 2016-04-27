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
        $("#user_id").select2({
            ajax: {
                url: "<?= WEB_ROOT ?>/ajax/admin/users/",
                type: 'POST',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        csrf: Cookies.get('csrf'),
                        search: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 0;

                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 20) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            }, // let our custom formatter work
            templateResult: function (user) {
                return user.email;
            },
            templateSelection: function (user) {
                return user.title || user.text;
            },
            minimumInputLength: 3
        });

    })
</script>