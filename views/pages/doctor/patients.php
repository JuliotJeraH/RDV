<?php require_once __DIR__ . '/../../components/header.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Demandes de rendez-vous</h1>
    </div>
    
    <div class="card mb-4">
        <div class="card-body">
            <form method="get" action="/doctor/patients">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Rechercher un patient..." value="<?= htmlspecialchars($search ?? '') ?>">
                    <button class="btn btn-primary" type="submit">Rechercher</button>
                    <?php if(!empty($search)): ?>
                        <a href="/doctor/patients" class="btn btn-outline-secondary">Annuler</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    
    <?php if(empty($patients)): ?>
        <div class="alert alert-info">Aucun patient trouvé</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Date de naissance</th>
                        <th>Groupe sanguin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($patients as $patient): ?>
                        <tr>
                            <td><?= htmlspecialchars($patient['nom']) ?></td>
                            <td><?= date('d/m/Y', strtotime($patient['date_naissance'])) ?></td>
                            <td><?= $patient['groupe_sanguin'] ?? 'Non renseigné' ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#patientAppointmentsModal<?= $patient['id_patient'] ?>">
                                    Voir les demandes
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Modal pour les demandes de rendez-vous du patient -->
                        <div class="modal fade" id="patientAppointmentsModal<?= $patient['id_patient'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Demandes de <?= htmlspecialchars($patient['nom']) ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <?php 
                                        $patientRequests = array_filter($pendingAppointments, function($req) use ($patient) {
                                            return $req['id_patient'] == $patient['id_patient'];
                                        });
                                        ?>
                                        
                                        <?php if(empty($patientRequests)): ?>
                                            <div class="alert alert-info">Aucune demande de rendez-vous</div>
                                        <?php else: ?>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Date demande</th>
                                                            <th>Motif</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach($patientRequests as $request): ?>
                                                            <tr>
                                                                <td><?= date('d/m/Y H:i', strtotime($request['date_demande'])) ?></td>
                                                                <td><?= htmlspecialchars($request['motif']) ?></td>
                                                                <td>
                                                                    <form method="post" action="index.php?page=doctor/respond-appointment" class="d-inline">
                                                                        <input type="hidden" name="id_rendez_vous" value="<?= $request['id_rendez_vous'] ?>">
                                                                        <input type="hidden" name="response" value="accepte">
                                                                        <div class="input-group">
                                                                            <input type="datetime-local" class="form-control form-control-sm" name="date_rendez_vous" required>
                                                                            <button type="submit" class="btn btn-sm btn-success">Accepter</button>
                                                                        </div>
                                                                    </form>
                                                                    <form method="post" action="index.php?page=doctor/respond-appointment" class="d-inline ms-2">
                                                                        <input type="hidden" name="id_rendez_vous" value="<?= $request['id_rendez_vous'] ?>">
                                                                        <input type="hidden" name="response" value="refuse">
                                                                        <button type="submit" class="btn btn-sm btn-danger">Refuser</button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../components/footer.php'; ?>