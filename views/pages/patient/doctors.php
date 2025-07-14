<?php require_once __DIR__ . '/../../components/header.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Liste des médecins</h1>
    </div>
    
    <div class="card mb-4">
        <div class="card-body">
            <form method="get" action="/patient/doctors">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Rechercher un médecin ou une spécialité..." value="<?= htmlspecialchars($search ?? '') ?>">
                    <button class="btn btn-primary" type="submit">Rechercher</button>
                    <?php if(!empty($search)): ?>
                        <a href="/patient/doctors" class="btn btn-outline-secondary">Annuler</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    
    <?php if(empty($doctors)): ?>
        <div class="alert alert-info">Aucun médecin trouvé</div>
    <?php else: ?>
        <div class="row">
            <?php foreach($doctors as $doctor): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Dr. <?= htmlspecialchars($doctor['nom']) ?></h5>
                            <p class="card-text text-muted"><?= htmlspecialchars($doctor['specialite']) ?></p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#appointmentModal<?= $doctor['id_medecin'] ?>">
                                Demander un rendez-vous
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Modal pour la demande de rendez-vous -->
                <div class="modal fade" id="appointmentModal<?= $doctor['id_medecin'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Demande de rendez-vous avec Dr. <?= htmlspecialchars($doctor['nom']) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="post" action="/patient/request-appointment">
                                <div class="modal-body">
                                    <input type="hidden" name="id_medecin" value="<?= $doctor['id_medecin'] ?>">
                                    <div class="mb-3">
                                        <label for="motif" class="form-label">Motif de la consultation</label>
                                        <textarea class="form-control" id="motif" name="motif" rows="3" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-primary">Envoyer la demande</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../components/footer.php'; ?>