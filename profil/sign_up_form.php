
<div class="modal fade" id="sign_up_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    
<div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Création de mon compte</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" id="submit-component" method="post" action="/includes/hardware/add_component/" autocomplete="off" novalidate>
                    <input type="text" name="username" placeholder="Votre pseudo"><br>
                    <input type="email" name="email" placeholder="Votre email"><br>
                    <input type="password" name="password" placeholder="Votre mot de passe"><br>
                    <button type="submit" name="submit">S'inscrire</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary" form="submit-component">Créer mon compte</button>
            </div>
        </div>
    </div>
</div>