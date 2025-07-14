<?php require_once __DIR__ .'/../components/header.php'; ?>

<div class="hero-section bg-light py-5">
    <div class="container text-center">
        <h1 class="display-4">Bienvenue sur notre plateforme médicale</h1>
        <p class="lead">Prenez rendez-vous avec les meilleurs médecins en quelques clics</p>
        
        <?php if(!isset($_SESSION['user_id'])): ?>
            <div class="mt-4">
                <a href="/auth/register" class="btn btn-primary btn-lg mx-2">S'inscrire</a>
                <a href="/auth/login" class="btn btn-outline-primary btn-lg mx-2">Se connecter</a>
            </div>
        <?php else: ?>
            <div class="mt-4">
                <?php if($_SESSION['user_role'] == 'patient'): ?>
                    <a href="/patient/doctors" class="btn btn-primary btn-lg mx-2">Voir les médecins</a>
                <?php else: ?>
                    <a href="/doctor/patients" class="btn btn-primary btn-lg mx-2">Voir les patients</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Pour les patients</h5>
                    <p class="card-text">Trouvez facilement un médecin et prenez rendez-vous en ligne.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Pour les médecins</h5>
                    <p class="card-text">Gérez vos rendez-vous et vos patients en toute simplicité.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Sécurisé et simple</h5>
                    <p class="card-text">Une plateforme sécurisée pour une expérience utilisateur optimale.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ .'/../components/footer.php'; ?>