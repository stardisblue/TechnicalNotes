<form method="post">
    <input type="hidden" name="csrf" value="<?= $csrf ?>">
    <div class="form-group">
        <label for="word">Mot clé</label>
        <input class="form-control" type="text" name="word" id="word" placeholder="Mot clé" required>
    </div>

    <div class="form-group">
        <label>
            <input type="radio" name="type" id="type" value="tag"> Validé
        </label>
        <label>
            <input type="radio" name="type" id="type" value="proposed"> Proposé
        </label>
        <label>
            <input type="radio" name="type" id="type" value="refused"> Refusé
        </label>
    </div>

    <div class="form-group">
        <label for="positive">Nb votes positifs</label>
        <input type="number" name="positive" id="positive" min="0">
    </div>
    <div class="form-group">
        <label for="total">Nb votes Totals</label>
        <input type="number" name="total" id="total" min="0">
    </div>

    <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> Créer</button>

</form>
