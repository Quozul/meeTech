<form id="submit-component" method="get" action="/hardware/index/" autocomplete="off">
    <div class="form-group">
        <label for="name">Nom/modèle*</label>
        <input type="text" class="form-control" id="name" placeholder="Nom/modèle" name="name">

        <label for="brand">Fabricant*</label>
        <input type="text" class="form-control" id="brand" placeholder="Fabricant" name="brand">
    </div>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="component-type">Composant</label>
        </div>

        <select class="custom-select" id="component-type" name="type">
            <option selected>Selectionnez...</option>
            <option value="cpu">Processeur</option>
            <option value="gpu">Carte graphique</option>
            <option value="ram">Mémoire vive</option>
            <option value="hdd">Disque dur</option>
            <option value="ssd">SSD</option>
            <option value="mb">Carte mère</option>
        </select>
    </div>

    <span id="submit-components-group">
        <hr class="d-none" id="submit-component-delimiter">

        <div class="form-group d-none" id="submit-component-cpu">
            <div class="input-group mb-3">
                <input type="number" class="form-control" placeholder="Fréquence" aria-label="Fréquence" aria-describedby="cpu-frequency" name="cpu-frequency">
                <div class="input-group-append">
                    <span class="input-group-text">GHz</span>
                </div>
            </div>

            <div class="input-group mb-3">
                <input type="number" class="form-control" placeholder="Coeurs physiques" aria-label="Coeurs physiques" aria-describedby="cpu-cores" name="cpu-cores">
            </div>

            <div class="input-group mb-3">
                <input type="number" class="form-control" placeholder="Coeurs logiques (threads)" aria-label="Coeurs logiques (threads)" aria-describedby="cpu-threads" name="cpu-threads">
            </div>
        </div>

        <div class="form-group d-none" id="submit-component-gpu">
            <div class="input-group mb-3">
                <input type="number" class="form-control" placeholder="Fréquence" aria-label="Fréquence" aria-describedby="gpu-frequency" name="gpu-frequency">
                <div class="input-group-append">
                    <span class="input-group-text">GHz</span>
                </div>
            </div>

            <div class="input-group mb-3">
                <input type="number" class="form-control" placeholder="Coeurs physiques" aria-label="Coeurs physiques" aria-describedby="gpu-cores" name="gpu-cores">
            </div>

            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Type de mémoire" aria-label="Type de mémoire" aria-describedby="gpu-memory-type" name="gpu-memory-type">
            </div>

            <div class="input-group mb-3">
                <input type="number" class="form-control" placeholder="Taille de la mémoire" aria-label="Taille de la mémoire" aria-describedby="gpu-memory-capacity" name="gpu-memory-capacity">
                <div class="input-group-append">
                    <span class="input-group-text">Go</span>
                </div>
            </div>

            <div class="input-group mb-3">
                <input type="number" class="form-control" placeholder="Fréquence de la mémoire" aria-label="Fréquence de la mémoire" aria-describedby="gpu-memory-frequency" name="gpu-memory-frequency">
                <div class="input-group-append">
                    <span class="input-group-text">MHz</span>
                </div>
            </div>
        </div>

        <div class="form-group d-none" id="submit-component-ram">
            <div class="input-group mb-3">
                <input type="number" class="form-control" placeholder="Fréquence" aria-label="Fréquence" aria-describedby="ram-frequency" name="ram-frequency">
                <div class="input-group-append">
                    <span class="input-group-text">MHz</span>
                </div>
            </div>

            <div class="input-group mb-3">
                <input type="number" class="form-control" placeholder="Barrettes mémoire" aria-label="Barrettes mémoire" aria-describedby="ram-modules" name="ram-modules">
                <div class="input-group-append" style="margin-right: -1px;">
                    <span class="input-group-text">×</span>
                </div>
                <input type="number" class="form-control" placeholder="Capacité mémoire" aria-label="Capacité mémoire" aria-describedby="ram-capacity" name="ram-capacity">
                <div class="input-group-append">
                    <span class="input-group-text">Go</span>
                </div>
            </div>
        </div>

        <div class="form-group d-none" id="submit-component-hdd">
            <div class="input-group mb-3">
                <input type="text" class="form-control col-md-10" placeholder="Capacité" aria-label="Capacité" name="hdd-capacity">
                <select class="custom-select col-md-2" name="hdd-capacity-unit">
                    <option value="TB" selected>To</option>
                    <option value="GB">Go</option>
                </select>
            </div>

            <div class="input-group mb-3">
                <input type="number" class="form-control" placeholder="Vitesse" aria-label="Vitesse" aria-describedby="hdd-speed" name="hdd-speed">
                <div class="input-group-append">
                    <span class="input-group-text">tr/min</span>
                </div>
            </div>
        </div>

        <div class="form-group d-none" id="submit-component-ssd">
            <div class="input-group mb-3">
                <select class="custom-select" name="ssd-type">
                    <option selected>Type de SSD</option>
                    <option value="nvme" selected>M.2 NVMe</option>
                    <option value="msata">M.2 SATA (6 Go/s)</option>
                    <option value="sata">2"5 SATA (6 Go/s)</option>
                </select>
            </div>

            <div class="input-group mb-3">
                <input type="text" class="form-control col-md-10" placeholder="Capacité" aria-label="Capacité" name="ssd-capacity">
                <select class="custom-select col-md-2" name="ssd-capacity-unit">
                    <option value="GB" selected>Go</option>
                    <option value="TB">To</option>
                </select>
            </div>

            <div class="input-group mb-3">
                <input type="number" class="form-control" placeholder="Vitesse d'écriture" aria-label="Vitesse d'écriture" aria-describedby="ssd-write-speed" name="ssd-write-speed">
                <div class="input-group-append">
                    <span class="input-group-text">Go/s</span>
                </div>
            </div>

            <div class="input-group mb-3">
                <input type="number" class="form-control" placeholder="Vitesse de lecture" aria-label="Vitesse de lecture" aria-describedby="ssd-read-speed" name="ssd-read-speed">
                <div class="input-group-append">
                    <span class="input-group-text">Go/s</span>
                </div>
            </div>
        </div>

        <div class="form-group d-none" id="submit-component-mb">
            <div class="input-group mb-3">
                <input type="number" class="form-control" placeholder="Chipset" aria-label="Chipset" aria-describedby="mb-chipset" name="mb-chipset">
            </div>

            <div class="input-group mb-3">
                <input type="number" class="form-control" placeholder="Socket" aria-label="Socket" aria-describedby="mb-socket" name="mb-socket">
            </div>
        </div>
    </span>
</form>

<script>
    document.getElementById('component-type').onchange = function() {
        let group = document.getElementById('submit-components-group');

        let children = group.children;

        for (const key in children)
            if (children.hasOwnProperty(key))
                children[key].classList.add('d-none');

        document.getElementById('submit-component-delimiter').classList.remove('d-none');

        document.getElementById(`submit-component-${this.value}`).classList.remove('d-none');
    };
</script>