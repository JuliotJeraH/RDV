<?php require_once('../../components/header.php'); ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Mes rendez-vous</h1>
    </div>
    
    <?php if(empty($appointments)): ?>
        <div class="alert alert-info">Vous n'avez aucun rendez-vous</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Médecin</th>
                        <th>Spécialité</th>
                        <th>Date demande</th>
                        <th>Date rendez-vous</th>
                        <th>Motif</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($appointments as $appointment): ?>
                        <tr>
                            <td>Dr. <?= htmlspecialchars($appointment['medecin_nom']) ?></td>
                            <td><?= htmlspecialchars($appointment['specialite']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($appointment['date_demande'])) ?></td>
                            <td>
                                <?= $appointment['date_rendez_vous'] ? date('d/m/Y H:i', strtotime($appointment['date_rendez_vous'])) : 'Non défini' ?>
                            </td>
                            <td><?= htmlspecialchars($appointment['motif']) ?></td>
                            <td>
                                <?php 
                                $statusClass = '';
                                switch($appointment['statut']) {
                                    case 'en_attente': $statusClass = 'text-warning'; break;
                                    case 'accepte': $statusClass = 'text-success'; break;
                                    case 'refuse': $statusClass = 'text-danger'; break;
                                    case 'annule': $statusClass = 'text-secondary'; break;
                                }
                                ?>
                                <span class="<?= $statusClass ?>">
                                    <?= ucfirst(str_replace('_', ' ', $appointment['statut'])) ?>
                                </span>
                            </td>
                            <td>
                                <?php if($appointment['statut'] == 'en_attente' || $appointment['statut'] == 'accepte'): ?>
                                    <form method="post" action="/patient/cancel-appointment/<?= $appointment['id_rendez_vous'] ?>" class="d-inline">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <?= $appointment['statut'] == 'accepte' ? 'Annuler' : 'Supprimer' ?>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once('../../components/footer.php'); ?>