<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <!-- Información de Contacto -->
            <div class="col-md-4 mb-4 mb-md-0">
                <h5 class="footer-title mb-4">Contacto</h5>
                <ul class="list-unstyled contact-list">
                    <li class="d-flex align-items-start mb-3">
                        <i class="fas fa-map-marker-alt me-3 mt-1 contact-icon"></i>
                        <span>Calle Rio Alzania 29<br>31004 Pamplona - Iruña</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="fas fa-phone me-3 contact-icon"></i>
                        <span>Tel: <a href="tel:948244200">948 24 42 00</a></span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="fas fa-envelope me-3 contact-icon"></i>
                        <span><a href="mailto:info@anel.es">info@anel.es</a></span>
                    </li>
                </ul>
                <div class="social-links mt-4">
                    <a href="#" class="social-link" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>

            <!-- Enlaces Rápidos -->
            <div class="col-md-4 mb-4 mb-md-0">
                <h5 class="footer-title mb-4">Enlaces Rápidos</h5>
                <div class="row">
                    <div class="col-6">
                        <ul class="list-unstyled footer-links">
                            <li class="mb-2"><a href="<?= base_url('nosotros') ?>" class="footer-link">Sobre Nosotros</a></li>
                            <li class="mb-2"><a href="<?= base_url('servicios') ?>" class="footer-link">Servicios</a></li>
                            <li class="mb-2"><a href="<?= base_url('comunidad') ?>" class="footer-link">Comunidad</a></li>
                            <li class="mb-2"><a href="<?= base_url('eventos') ?>" class="footer-link">Eventos</a></li>
                        </ul>
                    </div>
                    <div class="col-6">
                        <ul class="list-unstyled footer-links">
                            <li class="mb-2"><a href="<?= base_url('login') ?>" class="footer-link">Iniciar Sesión</a></li>
                            <li class="mb-2"><a href="<?= base_url('register') ?>" class="footer-link">Registro</a></li>
                            <li class="mb-2"><a href="<?= base_url('politica-privacidad') ?>" class="footer-link">Privacidad</a></li>
                            <li class="mb-2"><a href="<?= base_url('contacto') ?>" class="footer-link">Contacto</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="col-md-4">
                <h5 class="footer-title mb-4">Newsletter</h5>
                <p>Suscríbete para recibir las últimas novedades y eventos de nuestro espacio.</p>
                <form class="newsletter-form mt-4">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Tu correo electrónico" aria-label="Tu correo electrónico" required>
                        <button class="btn btn-primary" type="submit">
                            Suscribirse
                        </button>
                    </div>
                    <div class="form-text small text-light opacity-75">
                        No compartimos tu correo con terceros. Consulta nuestra <a href="<?= base_url('politica-privacidad') ?>" class="text-decoration-underline">política de privacidad</a>.
                    </div>
                </form>
            </div>
        </div>

        <hr class="footer-divider my-4">

        <!-- Pie de Footer -->
        <div class="row align-items-center footer-bottom">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0">&copy; <?= date('Y') ?> Anel Coworking. Todos los derechos reservados.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <div class="footer-logos">
                    <img src="<?= base_url('images/logo-gob-navarra.png') ?>" alt="Gobierno de Navarra" class="me-3" height="32">
                    <img src="<?= base_url('images/logo-eu.png') ?>" alt="European Union" class="me-3" height="32">
                    <img src="<?= base_url('images/logo-interreg.png') ?>" alt="Interreg" height="32">
                </div>
            </div>
        </div>
    </div>
</footer> 