<div class="container">
    <h2>Tableau de Bord Médical</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Mes Informations</h5>
                    <p>Nom : Dr. <?= htmlspecialchars($doctor['nom']) ?></p>
                    <p>Spécialité : <?= htmlspecialchars($doctor['specialite']) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Rendez-vous Aujourd'hui</h5>
                    <?php if (!empty($appointments)): ?>
                        <ul>
                            <?php foreach ($appointments as $apt): ?>
                                <li><?= date('H:i', strtotime($apt['date_rendez_vous'])) ?> - <?= htmlspecialchars($apt['patient_nom']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Aucun rendez-vous aujourd'hui</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>