<nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top w-100" style="z-index: 1000; background-color: #1A6A6D;">
    <div class="container">
        
        <a href="<?= url('/') ?>" class="navbar-brand d-flex align-items-center">
            <div class="bg-white rounded-pill shadow-sm px-3 py-1 d-flex align-items-center" style="border: 1px solid rgba(255,255,255,0.3); transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                 <img src="<?= asset('imgs/logo_tekopora.png') ?>" alt="Logo TekoPorã Bolivia" style="width: 175px; height: 48px; object-fit: contain;"> 
            </div>
        </a>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ml-auto align-items-center">
                
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle text-white px-3" data-toggle="dropdown">Proyectos</a>
                    <div class="dropdown-menu border-0 rounded-0 shadow m-0 mt-2" style="background: #217F82;">
                        <a href="<?= url('/proyectos') ?>" class="dropdown-item text-white">Todos los Proyectos</a>
                        <a href="<?= url('/empresas') ?>" class="dropdown-item text-white">
                            <i class="fa fa-industry mr-2"></i> Empresas Constructoras
                        </a>
                        <?php if (isset($_SESSION['usuario']) && in_array($_SESSION['usuario']['rol'], ['Administrador', 'Personal Alcaldia'])): ?>
                            <div class="dropdown-divider"></div>
                            <a href="<?= url('/proyectosadd') ?>" class="dropdown-item text-warning font-weight-bold">
                                <i class="fa fa-plus-circle"></i> Añadir Proyecto
                            </a>
                            </a>
                            <a href="<?= url('/empresas/add') ?>" class="dropdown-item text-warning font-weight-bold">
                                <i class="fa fa-building mr-2"></i> Registrar Empresa
                            </a>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['usuario']) && in_array($_SESSION['usuario']['rol'], ['Administrador', 'Moderador Obra'])): ?>
                          <div class="dropdown-divider"></div>
                            <a href="<?= url('/mis-obras') ?>" class="dropdown-item text-warning font-weight-bold">
                                <i class="fa fa-hard-hat"></i> Mis Obras Asignadas
                            </a>
                        <?php endif; ?>

                        
                    </div>
                </div>
                
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle text-white px-3" data-toggle="dropdown">Turismo</a>
                    <div class="dropdown-menu border-0 rounded-0 shadow m-0 mt-2" style="background: #217F82;">
                        <a href="<?= url('/turismo') ?>" class="dropdown-item text-white">Mapa Turístico</a>
                        <a href="<?= url('/destinos') ?>" class="dropdown-item text-white">Destinos</a>
                        
                        <?php if (isset($_SESSION['usuario']) && in_array($_SESSION['usuario']['rol'], ['Administrador', 'Moderador Turismo'])): ?>
                            <div class="dropdown-divider"></div>
                            <a href="<?= url('/validar-sitios') ?>" class="dropdown-item text-warning font-weight-bold">
                                <i class="fa fa-check-circle"></i> Validar Sitios
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="nav-item d-flex align-items-center">
                    <a href="<?= url('/foro') ?>" class="nav-link text-white px-3 font-weight-bold" style="transition: 0.3s;" onmouseover="this.style.color='#ffc107'" onmouseout="this.style.color='white'">
                        <i class="fa fa-comments"></i> Foro Ciudadano
                    </a>
                </div>

                <?php if (isset($_SESSION['usuario'])): ?>
                    
                    <?php
                    // Lógica de Gamificación e Insignias
                    $rolUser = $_SESSION['usuario']['rol'] ?? 'Usuario';
                    $nombreUser = $_SESSION['usuario']['nombre'] ?? 'Ciudadano';
                    $karmaUser = $_SESSION['usuario']['karma'] ?? 0;
                    $insignia = 'cobre.png'; 

                    if ($rolUser === 'Administrador') {
                        $insignia = 'superadmin.png';
                    } elseif (in_array($rolUser, ['Moderador Turismo', 'Moderador Obra', 'Personal Alcaldia'])) {
                        $insignia = 'constructora.png';
                    } else {
                        if ($karmaUser >= 500) { $insignia = 'morado.png'; }
                        elseif ($karmaUser >= 300) { $insignia = 'oro.png'; }
                        elseif ($karmaUser >= 100) { $insignia = 'azul.png'; }
                        elseif ($karmaUser >= 50) { $insignia = 'plata.png'; }
                        else { $insignia = 'cobre.png'; }
                    }
                    ?>

                    <div class="nav-item dropdown ml-2">
                        <a href="#" class="nav-link text-white px-3" data-toggle="dropdown">
                            <i class="fa fa-bell"></i>
                            <span class="badge badge-warning rounded-circle position-absolute" style="top: 5px; right: 15px; font-size: 0.6rem;">2</span>
                        </a>
                        <div class="dropdown-menu border-0 rounded-0 dropdown-menu-right shadow-lg mt-2" style="background: #217F82; min-width: 250px;">
                            <span class="dropdown-item text-white-50 small font-weight-bold">Notificaciones Recientes</span>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item text-white small">
                                <i class="fa fa-hard-hat text-warning mr-2"></i> Nuevo reporte en tu obra asignada.
                            </a>
                            <a href="#" class="dropdown-item text-white small">
                                <i class="fa fa-star text-info mr-2"></i> ¡Has ganado +10 de Karma!
                            </a>
                        </div>
                    </div>

                    <div class="nav-item dropdown ml-1">
                        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center text-white px-3 py-1 rounded-pill" data-toggle="dropdown" style="background-color: rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.2);">
                            <img src="<?= asset('imgs/' . $insignia) ?>" alt="Nivel" class="mr-2" style="width: 34px; height: 34px; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.4)); transform: scale(3.3); transform-origin: center;">
                            <span class="font-weight-bold"><?= htmlspecialchars($nombreUser) ?></span>
                        </a>
                        
                        <div class="dropdown-menu border-0 rounded-0 dropdown-menu-right shadow-lg mt-3" style="background: #217F82; min-width: 220px;">
                            
                            <?php if(!in_array($rolUser, ['Administrador', 'Moderador Turismo', 'Moderador Obra', 'Personal Alcaldia'])): ?>
                                <div class="text-center py-3 mb-2" style="background: rgba(0,0,0,0.15);">
                                    <img src="<?= asset('imgs/' . $insignia) ?>" alt="Nivel" style="width: 65px; height: 65px; object-fit: contain; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.5));">
                                    <div class="mt-2">
                                        <span class="badge badge-light text-dark font-weight-bold shadow-sm px-2 py-1">
                                            <i class="fa fa-star text-warning"></i> <?= $karmaUser ?> pts
                                        </span>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-3 mb-2" style="background: rgba(0,0,0,0.15);">
                                    <img src="<?= asset('imgs/' . $insignia) ?>" alt="Nivel" style="width: 65px; height: 65px; object-fit: contain; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.5));">
                                </div>
                            <?php endif; ?>

                            <span class="dropdown-item text-white font-weight-bold">
                                <i class="fa fa-user mr-2"></i> <?= $_SESSION['usuario']['nombre'] ?>
                            </span>
                            <span class="dropdown-item text-white-50 small">
                                <i class="fa fa-id-badge mr-2"></i> <?= $_SESSION['usuario']['rol'] ?>
                            </span>
                            <div class="dropdown-divider"></div>

                            <a href="<?= url('/perfil-ciudadano') ?>" class="dropdown-item text-white">
                                <i class="fa fa-id-card mr-2"></i> Mi Perfil
                            </a>

                            <?php if ($_SESSION['usuario']['rol'] === 'Administrador'): ?>
                                <div class="dropdown-divider"></div>
                                <a href="<?= url('/adminpanel') ?>" class="dropdown-item text-warning font-weight-bold">
                                    <i class="fa fa-cog mr-2"></i> Panel Admin
                                </a>
                                <a href="<?= url('/usuarios') ?>" class="dropdown-item text-warning font-weight-bold">
                                    <i class="fa fa-users mr-2"></i> Gestión Usuarios
                                </a>
                            <?php endif; ?>

                            <div class="dropdown-divider"></div>
                            <a href="<?= url('/logout') ?>" class="dropdown-item text-danger font-weight-bold">
                                <i class="fa fa-sign-out-alt mr-2"></i> Cerrar Sesión
                            </a>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="nav-item dropdown ml-3">
                        <a href="#" class="nav-link dropdown-toggle text-white px-3" data-toggle="dropdown">Cuenta</a>
                        <div class="dropdown-menu border-0 rounded-0 dropdown-menu-right" style="background: #217F82;">
                            <a href="<?= url('/login') ?>" class="dropdown-item text-white">
                                <i class="fa fa-sign-in-alt mr-2"></i> Iniciar Sesión
                            </a>
                            <a href="<?= url('/register') ?>" class="dropdown-item text-white">
                                <i class="fa fa-user-plus mr-2"></i> Registrarse
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>