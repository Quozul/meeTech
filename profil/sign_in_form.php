
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
                    <form id="sign_in_form" method="post" action="/profil/sign_in_process.php" autocomplete="off" novalidate>
                        <input class="form-control" type="text" name="username" placeholder="Votre pseudo"><br>
                        <input class="form-control" type="password" name="password" placeholder="Votre mot de passe"><br>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" form="sign_in_form">Se connecter</button>
                </div>
            </div>
        </div>
    </div>