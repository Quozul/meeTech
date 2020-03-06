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
                        <div class="input-group">
                            <input type="text" class="form-control" id="username_input" name="username" placeholder="Votre pseudo" required>
                            <div class="invalid-feedback d-block" id="username-invalid-feedback"></div>
                        </div>
                        <label for="email_input">Adresse e-mail</label>
                        <div class="input-group">
                            <input type="email" class="form-control" id="email_input" name="email" placeholder="Votre adresse e-mail" required>
                            <div class="invalid-feedback d-block" id="email-invalid-feedback"></div>
                        </div>
                        <label for="password_input">Mot de passe</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_input" name="password" placeholder="Votre mot de passe" required>
                            <div class="invalid-feedback d-block" id="password-invalid-feedback"></div>
                        </div>
                        <label for="password_input">Confirmez le mot de passe</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirm_password_input" name="confirm-password" placeholder="Votre mot de passe" required>
                            <div class="invalid-feedback d-block" id="confirm-password-invalid-feedback"></div>
                        </div>
                        <label for="puzzle-canvas">Complétez le captcha</label>
                        <input class="d-none" name="puzzle-completed" id="puzzle-completed" type="checkbox" required>
                        <div class="input-group">
                            <canvas id="puzzle-canvas" style="width: 100%;"></canvas>
                            <div class="invalid-feedback d-block" id="captcha-invalid-feedback"></div>
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
    document.getElementById('sign_up_modal').addEventListener('keypress', function(e) {
        if (e.key == 'Enter')
            create_acount();
    });

    const canvas = document.getElementById('puzzle-canvas')
    drawCaptcha(canvas, '/image.jpg', function() {
        const ctx = canvas.getContext('2d');

        ctx.fillStyle = 'rgba(0, 0, 0, .5)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        ctx.fillStyle = 'rgba(0, 255, 0, 1)';
        ctx.font = canvas.height + 'px serif';
        ctx.textAlign = "center";
        ctx.textBaseline = "middle";
        ctx.fillText('✅', canvas.width / 2, canvas.height / 2);

        document.getElementById('puzzle-completed').checked = true;
    });

    function create_acount() {
        request('/actions/profile/sign_up_process.php', formToQuery('sign_up_form')).then(function(req) {
            console.log(req.response);
            const response = req.response.split('\\n');

            // remove "is-invalid" classes
            let invalid_fields = document.getElementsByClassName('is-invalid');
            for (const key in invalid_fields)
                if (invalid_fields.hasOwnProperty(key)) {
                    const element = invalid_fields[key];

                    element.classList.remove('is-invalid');
                    element.innerHTML = '';
                }

            // display errors
            let uif = document.getElementById('username-invalid-feedback');
            let eif = document.getElementById('email-invalid-feedback');
            let pif = document.getElementById('password-invalid-feedback');
            let cpif = document.getElementById('confirm-password-invalid-feedback');
            let cif = document.getElementById('captcha-invalid-feedback');

            if (response[0] == 'ERROR')
                response[1].split(';').forEach((code) => {
                    switch (code) {
                        case 'username_not_set':
                            uif.innerHTML = 'Veuillez entrer un nom d\'utilisateur.';
                            uif.classList.add('is-invalid');
                            break;
                        case 'username_too_long':
                            uif.innerHTML = 'Le nom d\'utilisateur entré est trop long.';
                            uif.classList.add('is-invalid');
                            break;
                        case 'username_already_taken':
                            uif.innerHTML = 'Ce nom d\'utilisateur est déjà pris.';
                            uif.classList.add('is-invalid');
                            break;
                        case 'email_not_set':
                            eif.innerHTML = 'Veuillez entrer une adresse e-mail.';
                            eif.classList.add('is-invalid');
                            break;
                        case 'invalid_email_address':
                            eif.innerHTML = 'Adresse e-mail invalide.';
                            eif.classList.add('is-invalid');
                            break;
                        case 'email_already_taken':
                            eif.innerHTML = 'Cette adresse e-mail est déjà prise.';
                            eif.classList.add('is-invalid');
                            break;
                        case 'password_not_set':
                            pif.innerHTML = 'Veuillez entrer un mot de passe';
                            pif.classList.add('is-invalid');
                            break;
                        case 'password_too_short':
                            pif.innerHTML = 'Mot de passe trop court, taille minimum : 8.';
                            pif.classList.add('is-invalid');
                            break;
                        case 'confirm_password':
                            cpif.innerHTML = 'Veuillez taper votre mot de passe 2 fois.';
                            cpif.classList.add('is-invalid');
                            break;
                        case 'incorrect_password':
                            cpif.innerHTML = 'Le mot de passe renseigné ne correspond pas.';
                            cpif.classList.add('is-invalid');
                            break;
                        case 'no_captcha':
                            cif.innerHTML = 'Le captcha n\'a pas été validé.';
                            cif.classList.add('is-invalid');
                            break;
                    }
                });
            else
                window.location.reload();

        }).catch(function(req) {
            alert('Une erreur est survenue, contacter un administrateur avec le code d\'erreur suivant :\n' + req.status);
        });
    }
</script>