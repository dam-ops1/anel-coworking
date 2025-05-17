<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container-fluid">
        <div class="hero-content">
            <h1 class="mb-0">El espacio para la <span class="text-highlight">innovación social</span> y<br>
                el crecimiento sostenible</h1>
            <p class="hero-subtitle mb-4">en Pamplona</p>
            <div class="hero-buttons">
                <a href="#" class="btn btn-primary btn-lg me-2">Conócenos</a>
                <a href="#" class="btn btn-outline-light btn-lg">Reserva</a>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services-section">
    <div class="container-fluid">
        <div class="row g-4">
            <!-- Gestión de recursos -->
            <div class="col-sm-12 col-md-4">
                <div class="card h-100 service-card">
                    <div class="card-body d-flex flex-column">
                        <div class="service-icon mb-3">
                            <img src="<?= base_url('images/resource-icon.svg') ?>" alt="Gestión de recursos">
                        </div>
                        <h3 class="card-title">Gestión de recursos</h3>
                        <p class="card-text flex-grow-1">Reserva sala de reuniones, espacios de trabajo y recursos compartidos.</p>
                        <a href="#" class="btn btn-primary mt-3">Reservar ahora</a>
                    </div>
                </div>
            </div>

            <!-- Comunidad -->
            <div class="col-sm-12 col-md-4">
                <div class="card h-100 service-card">
                    <div class="card-body d-flex flex-column">
                        <div class="service-icon mb-3">
                            <img src="<?= base_url('images/community-icon.svg') ?>" alt="Comunidad">
                        </div>
                        <h3 class="card-title">Comunidad</h3>
                        <p class="card-text flex-grow-1">Conecta con otros emprendedores y empresas del ecosistema.</p>
                        <a href="#" class="btn btn-primary mt-3">Explorar más</a>
                    </div>
                </div>
            </div>

            <!-- Servicios -->
            <div class="col-sm-12 col-md-4">
                <div class="card h-100 service-card">
                    <div class="card-body d-flex flex-column">
                        <div class="service-icon mb-3">
                            <img src="<?= base_url('images/services-icon.svg') ?>" alt="Servicios">
                        </div>
                        <h3 class="card-title">Servicios Anel</h3>
                        <p class="card-text flex-grow-1">Accede a servicios de consultoría, formación y otros elementos.</p>
                        <a href="#" class="btn btn-primary mt-3">Ver Servicios</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Events Section -->
<section class="events-section">
    <div class="container-fluid">
        <h2 class="section-title mb-4">Próximos eventos</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <p class="text-medium-emphasis">Descubre todas las actividades y eventos programados en nuestro espacio.</p>
                <a href="#" class="btn btn-outline-primary">
                    Ver próximos eventos
                </a>
            </div>
            <div class="col-md-8">
                <!-- Event Card -->
                <div class="card event-card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="card-title mb-2">Introducción a la Inteligencia Artificial</h3>
                                <div class="event-details mb-2">
                                    <span class="event-date me-3">
                                        <i class="cis-calendar me-1"></i>
                                        Marzo 15
                                    </span>
                                    <span class="event-time">
                                        <i class="cis-clock me-1"></i>
                                        16:00 a 18:00
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <a href="#" class="btn btn-primary">Inscribirse</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Event Card -->
                <div class="card event-card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="card-title mb-2">Contabilidad Básica</h3>
                                <div class="event-details mb-2">
                                    <span class="event-date me-3">
                                        <i class="cis-calendar me-1"></i>
                                        Marzo 20
                                    </span>
                                    <span class="event-time">
                                        <i class="cis-clock me-1"></i>
                                        10:00 a 12:00
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <a href="#" class="btn btn-primary">Inscribirse</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Event Card -->
                <div class="card event-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="card-title mb-2">Dinamización Pedagógica</h3>
                                <div class="event-details mb-2">
                                    <span class="event-date me-3">
                                        <i class="cis-calendar me-1"></i>
                                        Marzo 25
                                    </span>
                                    <span class="event-time">
                                        <i class="cis-clock me-1"></i>
                                        15:00 a 17:00
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <a href="#" class="btn btn-primary">Inscribirse</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Logotipos Colaboradores -->
<section class="partners-section mt-5">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-3">
                <img src="<?= base_url('images/icn_logo_anel.png') ?>" alt="ANEL Logo" class="img-fluid mb-3 mb-md-0">
            </div>
            <div class="col-md-9">
                <div class="row justify-content-end align-items-center">
                    <div class="col-4 col-md-2">
                        <img src="<?= base_url('images/logo-gob-navarra.png') ?>" alt="Gobierno de Navarra" class="img-fluid">
                    </div>
                    <div class="col-4 col-md-2">
                        <img src="<?= base_url('images/logo-eu.png') ?>" alt="European Union" class="img-fluid">
                    </div>
                    <div class="col-4 col-md-2">
                        <img src="<?= base_url('images/logo-interreg.png') ?>" alt="Interreg" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?> 