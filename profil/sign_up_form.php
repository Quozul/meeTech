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
                <form id="sign_up_form" method="post" action="/profil/sign_up_process.php" autocomplete="off" novalidate>
                    <!-- <input class="form-control" type="text" name="username" placeholder="Votre pseudo"><br>
                        <input class="form-control" type="email" name="email" placeholder="Votre email"><br>
                        <input class="form-control" type="password" name="password" placeholder="Votre mot de passe"><br>
                    </form> -->
                    <div class="form-row">
                        <label for="username_input">Username</label>
                        <input type="text" class="form-control" id="username_input" name="username" required>
                        <div class="valid-feedback">
                            C'est bon !
                        </div>
                        <label for="email_input">email</label>
                        <input type="email" class="form-control" id="email_input" name="email" required>
                        <div class="valid-feedback">
                            C'est bon !
                        </div>
                        <label for="password_input">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_input" name="password" required>
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
        const form = document.getElementById("sign_up_form");
        const form_data = new FormData(form);
        let query = '';

        for (let pair of form_data.entries())
            query += pair[0] + '=' + pair[1] + '&';

        request('/profil/sign_up_process.php', query).then(function() {
            document.location.reload();
        });
    }
</script>