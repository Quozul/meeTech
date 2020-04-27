<div class="modal fade" id="createChanModal" tabindex="-1" role="dialog" aria-labelledby="createChanModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createChanModalTitle">Création du salon de discussion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="create_chan" method="post" autocomplete="off" novalidate>
                    <label for="username_input">Nom du channel</label>
                    <input class="form-control" id="chanName" type="text" name="name" placeholder="Nom de la salle de discussion">
                    <div class="invalid-feedback d-block" id="password-invalid-infeedback"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="create_chan()">Créer le salon</button>
            </div>
        </div>
    </div>
</div>