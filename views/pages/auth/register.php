<?php require_once __DIR__ . '/../../components/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Inscription</h2>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                
                <form action="/auth/register" method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Je suis :</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="rolePatient" value="patient" checked>
                            <label class="form-check-label" for="rolePatient">
                                Patient
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="roleDoctor" value="medecin">
                            <label class="form-check-label" for="roleDoctor">
                                Médecin
                            </label>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom complet</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date_naissance" class="form-label">Date de naissance</label>
                            <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                        </div>
                    </div>
                    
                    <div id="patientFields">
                        <div class="mb-3">
                            <label for="groupe_sanguin" class="form-label">Groupe sanguin (optionnel)</label>
                            <select class="form-select" id="groupe_sanguin" name="groupe_sanguin">
                                <option value="">Sélectionnez...</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                    </div>
                    
                    <div id="doctorFields" style="display: none;">
                        <div class="mb-3">
                            <label for="specialite" class="form-label">Spécialité</label>
                            <input type="text" class="form-control" id="specialite" name="specialite">
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                    </div>
                </form>
                
                <div class="mt-3 text-center">
                    <p>Déjà inscrit ? <a href="index.php?page=auth/login">Se connecter</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rolePatient = document.getElementById('rolePatient');
    const roleDoctor = document.getElementById('roleDoctor');
    const patientFields = document.getElementById('patientFields');
    const doctorFields = document.getElementById('doctorFields');
    
    function toggleFields() {
        if (rolePatient.checked) {
            patientFields.style.display = 'block';
            doctorFields.style.display = 'none';
        } else {
            patientFields.style.display = 'none';
            doctorFields.style.display = 'block';
        }
    }
    
    rolePatient.addEventListener('change', toggleFields);
    roleDoctor.addEventListener('change', toggleFields);
    
    // Initial state
    toggleFields();
});
</script>

<?php require_once __DIR__ . '/../../components/footer.php'; ?>