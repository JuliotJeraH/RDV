<?php require_once __DIR__ . '/../../components/header.php'; ?>
<div class="container">
    <h2>Mon Tableau de Bord</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Mes Informations</h5>
                    <p>Nom : <?= htmlspecialchars($patient['nom']) ?></p>
                    <p>Date de naissance : <?= date('d/m/Y', strtotime($patient['date_naissance'])) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Mes Prochains Rendez-vous</h5>
                    <?php if (!empty($appointments)): ?>
                        <ul>
                            <?php foreach ($appointments as $apt): ?>
                                <li><?= date('d/m/Y H:i', strtotime($apt['date_rendez_vous'])) ?> - Dr. <?= htmlspecialchars($apt['medecin_nom']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Aucun rendez-vous prévu</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../../components/footer.php'; ?>