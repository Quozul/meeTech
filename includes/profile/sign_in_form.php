<div class="modal fade" id="sign_in_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Se connecter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="sign_in_form" method="post" action="/actions/profile/sign_in_process.php" autocomplete="off" novalidate>
                    <label for="username_input">Pseudonyme</label>
                    <input class="form-control" type="text" name="username" placeholder="Votre pseudo">
                    <div class="invalid-feedback d-block" id="username-invalid-infeedback"></div>
                    <label for="username_input">Mot de passe</label>
                    <input class="form-control" type="password" name="password" placeholder="Votre mot de passe">
                    <div class="invalid-feedback d-block" id="password-invalid-infeedback"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="sign_in();">Se connecter</button>
            </div>
        </div>
    </div>
</div>

<script>
    function sign_in() {
        request('/actions/profile/sign_in_process.php', formToQuery('sign_in_form')).then(function(req) {
            const response = req.response.split('\\n');

            console.log(response);

            // remove "is-invalid" classes
            let invalid_fields = document.getElementsByClassName('is-invalid');
            for (const key in invalid_fields)
                if (invalid_fields.hasOwnProperty(key)) {
                    const element = invalid_fields[key];

                    element.classList.remove('is-invalid');
                    element.innerHTML = '';
                }

            // display errors
            let uif = document.getElementById('username-invalid-infeedback');
            let pif = document.getElementById('password-invalid-infeedback');

            if (response[0] == 'ERROR')
                response[1].split(';').forEach((code) => {
                    switch (code) {
                        case 'username_not_found':
                            uif.innerHTML = 'Cet utilisateur n\'a pas été trouvé.';
                            uif.classList.add('is-invalid');
                            break;
                        case 'wrong_password':
                            pif.innerHTML = 'Mot de passe incorrect.';
                            pif.classList.add('is-invalid');
                            break;
                    }
                });
            else
                window.location.reload();
        });
    }
</script>