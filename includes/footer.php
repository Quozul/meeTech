<footer class="d-block footer p-2 mt-3 <?php if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false) echo 'mt-backoffice-color'; ?>">
    <div class="container d-flex">

        <div class="col-6">
            <h5>meeTech</h5>
            <p class="text-wrap text-white">Site de configuration d'ordinateur, d'informations sur les nouveautés en relation avec l'informatique et d'échange autour de ce domaine.</p>
        </div>

        <div class="col-3">
            <h5>Liens</h5>
            <ul class="text-muted list-group list-unstyled">
                <li><a href="https://discord.gg/CfxuZ3J">Discord</a></li>
                <li><a href="/about/">A propos</a></li>
                <li><a href="/brand/">L'image</a></li>
                <li><a href="/statistics/">Statistiques</a></li>
            </ul>
        </div>

        <div class="col-3">
            <h5>Contact</h5>
            <p class="text-muted text-break"><a href="mailto:meeteam@meetech.ovh">meeteam@meetech.ovh</a></p>
        </div>

    </div>
</footer>

<script>
    // Form validation
    // https://getbootstrap.com/docs/4.1/components/forms/#validation
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            let forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            let validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>