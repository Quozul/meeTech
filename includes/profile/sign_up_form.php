<div class="modal fade" id="sign_up_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Création de mon compte</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="sign_up_form" autocomplete="off" novalidate>
                    <div class="form-row">
                        <label for="username_input">Pseudonyme</label>
                        <input type="text" class="form-control" id="username_input" name="username" placeholder="Votre pseudo" required>
                        <div class="valid-feedback">
                            C'est bon !
                        </div>
                        <label for="email_input">Adresse e-mail</label>
                        <input type="email" class="form-control" id="email_input" name="email" placeholder="Votre adresse e-mail" required>
                        <div class="valid-feedback">
                            C'est bon !
                        </div>
                        <label for="password_input">Mot de passe</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_input" name="password" placeholder="Votre mot de passe" required>
                            <div class="invalid-feedback">
                                Ajouter un mot de passe.
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="create_acount()">Créer mon compte</button>
            </div>
        </div>
    </div>
</div>
<script>
    function create_acount() {
        request('/actions/profile/sign_up_process.php', formToQuery('sign_up_form')).then(function(req) {
            document.location.reload();
        }).catch(function(req) {
            alert('Une erreur est survenue, contacter un administrateur avec le code d\'erreur suivant :\n' + req.status);
        });
    }
</script>