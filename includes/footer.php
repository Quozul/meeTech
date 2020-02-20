<footer class="d-block footer p-1">
    <div class="container d-flex">

        <div class="p-2 flex-fill h-50 w-50">
            <h5>meeTech</h5>
            <p class="text-wrap text-white">Site de configuration d'ordinateur, d'informations sur les nouveautés en relation avec l'informatique et d'échange autour de ce domaine.</p>
        </div>

        <div class="p-2 flex-fill">
            <h5>Liens</h5>
            <ul class="text-muted list-group list-unstyled">
                <li><a href="/about/">A propos</a></li>
                <li><a href="/brand/">L'image</a></li>
            </ul>
        </div>

        <div class="p-2 flex-fill">
            <h5>Contact</h5>
            <p class="text-muted text-break"><a href="mailto:meeteam@meetech.ovh">meeteam@meetech.ovh</a></p>
        </div>

    </div>
</footer>

<script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });

    // Form validation
    // https://getbootstrap.com/docs/4.1/components/forms/#validation
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
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