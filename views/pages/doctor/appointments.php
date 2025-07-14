<?php require_once('../../components/header.php'); ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Mes rendez-vous acceptés</h1>
    </div>
    
    <?php if(empty($appointments)): ?>
        <div class="alert alert-info">Vous n'avez aucun rendez-vous à venir</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Date rendez-vous</th>
                        <th>Motif</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($appointments as $appointment): ?>
                        <tr>
                            <td><?= htmlspecialchars($appointment['patient_nom']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($appointment['date_rendez_vous'])) ?></td>
                            <td><?= htmlspecialchars($appointment['motif']) ?></td>
                            <td>
                                <form method="post" action="/doctor/respond-appointment" class="d-inline">
                                    <input type="hidden" name="id_rendez_vous" value="<?= $appointment['id_rendez_vous'] ?>">
                                    <input type="hidden" name="response" value="annule">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Annuler</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once('../../components/footer.php'); ?>