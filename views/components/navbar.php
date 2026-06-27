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

                    <!-- ==============================================
                         BOTÓN DE NOTIFICACIONES (Abre el Modal)
                    =============================================== -->
                    <div class="nav-item ml-2">
                        <a href="#" class="nav-link text-white px-3 position-relative" data-toggle="modal" data-target="#notificacionesModal" onclick="marcarNotificacionesLeidas()">
                            <i class="fa fa-bell" style="font-size: 1.2rem;"></i>
                            <span class="badge badge-warning rounded-circle position-absolute" id="notifCount" style="top: 0px; right: 5px; font-size: 0.65rem; display: none; box-shadow: 0 0 5px rgba(0,0,0,0.5);">0</span>
                        </a>
                    </div>

                    <!-- Menú de Usuario -->
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

<!-- ==============================================
     MODAL DE NOTIFICACIONES (Ventana Flotante)
=============================================== -->
<?php if (isset($_SESSION['usuario'])): ?>
<div class="modal fade" id="notificacionesModal" tabindex="-1" role="dialog" aria-labelledby="notificacionesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            
            <div class="modal-header text-white" style="background-color: #1A6A6D; border-bottom: 4px solid #217F82;">
                <h5 class="modal-title font-weight-bold" id="notificacionesModalLabel">
                    <i class="fa fa-bell mr-2 text-warning"></i> Centro de Notificaciones
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9; text-shadow: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body p-0" style="background-color: #f4f7f6; min-height: 300px;">
                <!-- Contenedor inyectado por JS -->
                <div id="modalNotifBox">
                    <div class="text-center py-5 text-muted">
                        <i class="fa fa-spinner fa-spin fa-3x mb-3"></i>
                        <h5>Cargando tus notificaciones...</h5>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer bg-white border-top-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4 shadow-sm" data-dismiss="modal">Cerrar ventana</button>
            </div>

        </div>
    </div>
</div>

<!-- ==============================================
     MOTOR JS DE NOTIFICACIONES (Encapsulado)
=============================================== -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        cargarNotificaciones();
    });

    function cargarNotificaciones() {
        fetch('<?= url("/notificaciones/obtener") ?>') 
        .then(res => res.json())
        .then(data => {
            const countBadge = document.getElementById('notifCount');
            const notifBox = document.getElementById('modalNotifBox');
            
            if(!data || !data.data) return;

            // 1. Mostrar/Ocultar el globito de conteo
            if (data.count > 0) {
                countBadge.innerText = data.count;
                countBadge.style.display = 'inline-block';
            } else {
                countBadge.style.display = 'none';
            }

            // 2. Armar la interfaz dentro del Modal
            if (data.data.length === 0) {
                notifBox.innerHTML = `
                    <div class="text-center py-5 text-muted">
                        <i class="fa fa-bell-slash fa-4x mb-3 text-light"></i>
                        <h5 class="font-weight-bold">Estás al día</h5>
                        <p>No tienes notificaciones nuevas por el momento.</p>
                    </div>`;
            } else {
                let html = '<div class="list-group list-group-flush">';
                
                data.data.forEach(n => {
                    // Estilos dinámicos para el Modal
                    const bgClass = n.leida ? 'bg-white' : 'bg-light';
                    const borderClass = n.leida ? '' : 'border-left border-warning';
                    const iconColor = n.leida ? 'text-secondary' : 'text-warning';
                    const fwClass = n.leida ? 'font-weight-normal text-secondary' : 'font-weight-bold text-dark';
                    
                    // Mostramos la fecha completa ya que ahora hay espacio
                    const fechaCompleta = new Date(n.fechaCreacion).toLocaleString('es-ES', { 
                        day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' 
                    });

                    html += `
                        <div class="list-group-item list-group-item-action py-4 ${bgClass}" style="border-bottom: 1px solid #eaeaea; ${n.leida ? '' : 'border-left: 4px solid #ffc107 !important;'}">
                            <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                                <h5 class="mb-0 ${fwClass}" style="font-size: 1.1rem;">
                                    <i class="fa fa-info-circle ${iconColor} mr-2"></i>${n.titulo}
                                </h5>
                                <small class="text-muted font-weight-bold"><i class="fa fa-clock mr-1"></i>${fechaCompleta}</small>
                            </div>
                            <p class="mb-0 ml-4 pl-1" style="font-size: 0.95rem; color: #555; line-height: 1.5;">${n.mensaje}</p>
                        </div>
                    `;
                });
                
                html += '</div>';
                notifBox.innerHTML = html;
            }
        })
        .catch(error => console.error("Error cargando notificaciones:", error));
    }

    function marcarNotificacionesLeidas() {
        const countBadge = document.getElementById('notifCount');
        
        // Si el globo está visible (hay notificaciones sin leer), avisamos al backend
        if (countBadge.style.display !== 'none') {
            fetch('<?= url("/notificaciones/leer") ?>', { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    countBadge.style.display = 'none';
                    countBadge.innerText = '0';
                    // Recargamos el contenido del Modal para quitar los estilos de "no leído"
                    setTimeout(cargarNotificaciones, 400); 
                }
            })
            .catch(error => console.error("Error marcando como leídas:", error));
        }
    }
</script>
<?php endif; ?>