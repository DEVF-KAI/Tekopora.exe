<div class="container mt-4 mb-5 layout-anim-enter">

    <!-- CABECERA -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-0 title-gradient">
                <i class="fa fa-users me-2"></i> Gestión de Usuarios
            </h2>
            <p class="text-muted small mt-1 mb-0">Control total sobre los accesos y roles de la plataforma.</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- NUEVO BOTÓN DE REPORTE -->
            <a href="<?= url('/reportes/auditoria?area=usuarios') ?>" class="btn btn-danger shadow-sm px-3 py-2" target="_blank" style="border-radius: 50px; font-weight: bold;">
                <i class="fa fa-file-pdf me-1"></i> Auditoría
            </a>

            <a href="<?= url('register') ?>" class="btn btn-create-modern shadow-lg">
                <i class="fa fa-plus me-2"></i> <span>Nuevo Usuario</span>
            </a>
        </div>
    </div>

    <!-- BUSCADORES Y FILTROS MODERNOS -->
    <div class="row mb-4 search-container align-items-center">
        <!-- Buscador por Email -->
        <div class="col-lg-5 col-md-6 mb-3 mb-md-0">
            <div class="input-group search-glass rounded-pill overflow-hidden p-1 shadow-sm">
                <span class="input-group-text bg-transparent border-0 ps-4">
                    <i class="fa fa-envelope text-primary-custom"></i>
                </span>
                <input type="text" id="inputBusquedaEmail" class="form-control bg-transparent border-0 shadow-none"
                    placeholder="Buscar ciudadano por correo...">
            </div>
        </div>

        <!-- Filtro por Rol -->
        <div class="col-lg-4 col-md-6">
            <div class="input-group search-glass rounded-pill overflow-hidden p-1 shadow-sm">
                <span class="input-group-text bg-transparent border-0 ps-4">
                    <i class="fa fa-filter text-primary-custom"></i>
                </span>
                <select id="filtroRol" class="form-select bg-transparent border-0 shadow-none text-secondary fw-bold" style="cursor:pointer;">
                    <option value="">Todos los Roles</option>
                    <option value="Administrador">Administrador</option>
                    <option value="Moderador Turismo">Moderador Turismo</option>
                    <option value="Moderador Obra">Moderador Obra</option>
                    <option value="Personal Alcaldia">Personal Alcaldía</option>
                    <option value="Ciudadano">Ciudadano</option>
                    <option value="Sin rol">Sin rol asignado</option>
                </select>
            </div>
        </div>
    </div>

    <!-- TABLA MODERNA (FLOTANTE) -->
    <div class="table-responsive px-2 pb-5">
        <table class="table table-borderless table-spaced align-middle text-center mb-0 w-100">
            <thead>
                <tr class="header-row text-muted text-uppercase small fw-bold">
                    <th class="text-start ps-4 pb-3" style="width: 32%;">Ciudadano e Insignia</th>
                    <th class="pb-3">Contacto</th>
                    <th class="pb-3">Identidad</th>
                    <th class="pb-3">Privilegio</th>
                    <th class="pb-3">Estatus</th>
                    <th class="pe-4 pb-3">Panel</th>
                </tr>
            </thead>

            <tbody id="tablaUsuariosBody">
                <?php 
                $delay = 0; 
                foreach ($usuarios as $u): 
                    $rolUser = $u['rol'] ?? 'Sin rol';
                    $karmaUser = $u['karmatotal'] ?? $u['karma'] ?? 0;
                    
                    // 1. LÓGICA DE TEXTO DE KARMA (Etiqueta debajo del nombre)
                    if ($karmaUser < 0) {
                        $textoKarma = 'Riesgo'; $badgeIcon = 'fa-exclamation-triangle'; $badgeClass = 'insignia-riesgo';
                    } elseif ($karmaUser < 50) {
                        $textoKarma = 'Novato'; $badgeIcon = 'fa-leaf'; $badgeClass = 'insignia-novato';
                    } elseif ($karmaUser < 200) {
                        $textoKarma = 'Activo'; $badgeIcon = 'fa-fire'; $badgeClass = 'insignia-activo';
                    } elseif ($karmaUser < 500) {
                        $textoKarma = 'Destacado'; $badgeIcon = 'fa-star'; $badgeClass = 'insignia-destacado';
                    } else {
                        $textoKarma = 'Leyenda'; $badgeIcon = 'fa-trophy'; $badgeClass = 'insignia-leyenda';
                    }

                    // 2. LÓGICA DE IMAGEN (Foto de Perfil / Navbar)
                    $imgInsignia = 'cobre.png'; // Default

                    if ($rolUser === 'Administrador') {
                        $imgInsignia = 'superadmin.png';
                        $textoKarma = 'Supremo'; // Toque especial para el admin
                        $badgeIcon = 'fa-crown';
                        $badgeClass = 'insignia-leyenda';
                    } elseif (in_array($rolUser, ['Moderador Turismo', 'Moderador Obra', 'Personal Alcaldia'])) {
                        $imgInsignia = 'constructora.png';
                    } else {
                        if ($karmaUser >= 500) { $imgInsignia = 'morado.png'; }
                        elseif ($karmaUser >= 300) { $imgInsignia = 'oro.png'; }
                        elseif ($karmaUser >= 100) { $imgInsignia = 'azul.png'; }
                        elseif ($karmaUser >= 50) { $imgInsignia = 'plata.png'; }
                        else { $imgInsignia = 'cobre.png'; }
                    }
                ?>
                    <tr id="fila-<?= htmlspecialchars($u['codigoUsuario'] ?? '0') ?>" class="custom-row fade-in-row" style="animation-delay: <?= $delay ?>s;">

                        <!-- Nombre e Insignia (IMAGEN REEMPLAZA AL AVATAR) -->
                        <td class="text-start ps-4 py-3">
                            <div class="d-flex align-items-center">
                                
                                <!-- IMAGEN GRANDE DE INSIGNIA -->
                                <div class="me-3 flex-shrink-0">
                                    <img src="<?= asset('imgs/' . $imgInsignia) ?>" alt="Insignia" 
                                         style="width: 55px; height: 55px; object-fit: contain; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.15));" 
                                         title="<?= $karmaUser ?> pts de Karma">
                                </div>
                                
                                <!-- Textos -->
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-1 fw-bold text-dark" style="line-height: 1.2;">
                                        <?= htmlspecialchars(trim(($u['nombre'] ?? '') . ' ' . ($u['appPaterno'] ?? ''))) ?>
                                    </h6>
                                    
                                    <!-- ETIQUETA "NOVATO" RECUPERADA -->
                                    <div class="mb-1">
                                        <span class="badge badge-karma border <?= $badgeClass ?>">
                                            <i class="fa <?= $badgeIcon ?>"></i> <?= $textoKarma ?>
                                        </span>
                                    </div>

                                    <small class="text-muted m-0 p-0" style="font-size: 0.70rem; line-height: 1;">
                                        Cód: <?= htmlspecialchars($u['codigoUsuario'] ?? 'N/A') ?>
                                    </small>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Correo -->
                        <td class="py-3 text-muted small fw-bold">
                            <i class="fa fa-envelope me-1 opacity-50"></i> <?= htmlspecialchars($u['email'] ?? 'Sin correo') ?>
                        </td>
                        
                        <!-- CI / Google -->
                        <td class="py-3">
                            <?php if(empty($u['ci'])): ?>
                                <span class="badge-modern badge-google">
                                    <img src="https://www.google.com/favicon.ico" width="14" class="me-1"> Auth
                                </span>
                            <?php else: ?>
                                <span class="fw-bold text-secondary" style="letter-spacing: 1px;">
                                    <?= htmlspecialchars($u['ci']) ?>
                                </span>
                            <?php endif; ?>
                        </td>

                        <!-- Rol con Gradientes Universales -->
                        <td class="py-3">
                            <?php 
                                $rol = $u['rol'] ?? 'Sin rol';
                                if ($rol === 'Administrador') {
                                    $rolClass = 'bg-gradient-admin'; $icon = 'fa-star';
                                } elseif ($rol === 'Moderador Turismo') {
                                    $rolClass = 'bg-gradient-turismo'; $icon = 'fa-map-marker';
                                } elseif ($rol === 'Moderador Obra') {
                                    $rolClass = 'bg-gradient-obra'; $icon = 'fa-building';
                                } elseif ($rol === 'Personal Alcaldia') {
                                    $rolClass = 'bg-gradient-alcaldia'; $icon = 'fa-university';
                                } elseif ($rol === 'Ciudadano') {
                                    $rolClass = 'bg-gradient-ciudadano'; $icon = 'fa-user';
                                } else {
                                    $rolClass = 'bg-gradient-default'; $icon = 'fa-id-badge';
                                }
                            ?>
                            <span class="badge rounded-pill text-white px-3 py-2 fw-bold shadow-sm <?= $rolClass ?>">
                                <i class="fa <?= $icon ?> me-1 opacity-75"></i> <?= $rol ?>
                            </span>
                        </td>

                        <!-- Estado -->
                        <td class="py-3">
                            <?php if (($u['estado'] ?? '') == 'Activo'): ?>
                                <div class="status-indicator status-active">
                                    <span class="dot"></span> Activo
                                </div>
                            <?php else: ?>
                                <div class="status-indicator status-inactive">
                                    <span class="dot"></span> <?= htmlspecialchars($u['estado'] ?? 'Inactivo') ?>
                                </div>
                            <?php endif; ?>
                        </td>

                        <!-- Acciones -->
                        <td class="pe-4 py-3">
                            <div class="d-flex justify-content-center gap-2">
                                <?php if (isset($u['rol']) && $u['rol'] !== 'Administrador'): ?>
                                    
                                    <?php if (($u['estado'] ?? '') == 'Activo'): ?>
                                        <a href="<?= url('admin/editar?codigo=' . ($u['codigoUsuario'] ?? '')) ?>"
                                            class="action-btn btn-edit shadow-sm" title="Modificar">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (($u['estado'] ?? '') == 'Activo'): ?>
                                        <a href="<?= url('admin/suspender?codigo=' . ($u['codigoUsuario'] ?? '')) ?>"
                                            class="action-btn btn-suspend shadow-sm" title="Suspender acceso">
                                            <i class="fa fa-lock"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= url('admin/activar?codigo=' . ($u['codigoUsuario'] ?? '')) ?>"
                                            class="action-btn btn-activate shadow-sm" title="Restaurar acceso">
                                            <i class="fa fa-unlock"></i>
                                        </a>
                                    <?php endif; ?>

                                <?php else: ?>
                                    <span class="shield-badge" title="Cuenta de sistema protegida">
                                        <i class="fa fa-shield"></i>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php 
                    $delay += 0.05; 
                endforeach; 
                ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* ==========================================
       DISEÑO COMPLEJO Y ANIMACIONES 2026
       ========================================== */
    body { background-color: #f0f4f8; font-family: 'Inter', system-ui, sans-serif; }

    .title-gradient {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .text-primary-custom { color: #217F82; }

    .btn-create-modern {
        background: linear-gradient(135deg, #217F82, #2A6F97);
        color: white; border-radius: 50px; font-weight: 700;
        padding: 12px 28px; border: none; text-decoration: none;
        transition: all 0.4s ease; display: inline-flex; align-items: center;
    }
    .btn-create-modern:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 15px 25px rgba(33, 127, 130, 0.4); color: white;
    }

    .search-glass {
        background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.5); transition: all 0.3s ease;
    }
    .search-glass:focus-within {
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 8px 25px rgba(33, 127, 130, 0.15) !important;
        transform: translateY(-2px);
    }
    .form-control:focus, .form-select:focus { outline: none; box-shadow: none; }

    .table-spaced { border-collapse: separate; border-spacing: 0 14px; }

    .custom-row {
        background: #ffffff; transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
    }
    .custom-row td { border: none !important; }
    .custom-row td:first-child { border-top-left-radius: 16px; border-bottom-left-radius: 16px; }
    .custom-row td:last-child { border-top-right-radius: 16px; border-bottom-right-radius: 16px; }
    
    .custom-row:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(33, 127, 130, 0.12);
        z-index: 10; position: relative;
    }

    /* Insignias de Karma Estilo Etiqueta Textual */
    .badge-karma {
        font-size: 0.70rem; padding: 4px 10px; border-radius: 6px;
        font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .insignia-riesgo { background-color: #ffebee !important; color: #c62828 !important; border-color: #ffcdd2 !important; }
    .insignia-novato { background-color: #f5f5f5 !important; color: #757575 !important; border-color: #e0e0e0 !important; }
    .insignia-activo { background-color: #e8f5e9 !important; color: #2e7d32 !important; border-color: #c8e6c9 !important; }
    .insignia-destacado { background-color: #e3f2fd !important; color: #1565c0 !important; border-color: #bbdefb !important; }
    .insignia-leyenda { background-color: #fff8e1 !important; color: #f57f17 !important; border-color: #ffecb3 !important; }

    /* Gradientes de Roles */
    .bg-gradient-admin { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); } 
    .bg-gradient-turismo { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); } 
    .bg-gradient-obra { background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%); } 
    .bg-gradient-alcaldia { background: linear-gradient(135deg, #cb2d3e 0%, #ef473a 100%); } 
    .bg-gradient-ciudadano { background: linear-gradient(135deg, #20bf55 0%, #01baef 100%); } 
    .bg-gradient-default { background: linear-gradient(135deg, #434343 0%, #000000 100%); } 
    
    .badge-google {
        background: white; border: 1px solid #e0e0e0; color: #555;
        border-radius: 50px; padding: 5px 12px; font-size: 0.8rem;
        font-weight: 600; box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }

    .status-indicator {
        display: inline-flex; align-items: center; padding: 6px 14px;
        border-radius: 50px; font-size: 0.85rem; font-weight: 700;
    }
    .status-indicator .dot { width: 8px; height: 8px; border-radius: 50%; margin-right: 8px; }
    .status-active { background: rgba(32, 191, 85, 0.1); color: #1b9c45; }
    .status-active .dot { background: #20bf55; box-shadow: 0 0 8px #20bf55; }
    .status-inactive { background: rgba(231, 76, 60, 0.1); color: #c0392b; }
    .status-inactive .dot { background: #e74c3c; }

    .action-btn {
        width: 38px; height: 38px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none; transition: all 0.3s ease;
    }
    .btn-edit { background: #f8f9fa; color: #3498db; border: 1px solid #e1e8ed; }
    .btn-edit:hover { background: #3498db; color: white; transform: scale(1.1); box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4); }
    
    .btn-suspend { background: #fff0f0; color: #e74c3c; border: 1px solid #fce4e4; }
    .btn-suspend:hover { background: #e74c3c; color: white; transform: scale(1.1); box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4); }
    
    .btn-activate { background: #f0fff4; color: #2ecc71; border: 1px solid #e4fceb; }
    .btn-activate:hover { background: #2ecc71; color: white; transform: scale(1.1); box-shadow: 0 5px 15px rgba(46, 204, 113, 0.4); }

    .shield-badge {
        color: #bdc3c7; background: #f8f9fa; padding: 8px 12px; 
        border-radius: 10px; border: 1px dashed #dcdde1;
    }

    .fade-in-row { opacity: 0; transform: translateY(20px); animation: rowEntrance 0.6s ease-out forwards; }
    @keyframes rowEntrance { to { opacity: 1; transform: translateY(0); } }
    
    .highlight-glow { animation: targetPulse 1.2s ease-out forwards !important; }
    @keyframes targetPulse {
        0% { box-shadow: 0 0 0 0 rgba(33, 127, 130, 0.5); transform: scale(1); z-index: 20; position: relative;}
        50% { box-shadow: 0 0 30px 10px rgba(33, 127, 130, 0.2); transform: scale(1.02); z-index: 20; position: relative;}
        100% { box-shadow: 0 5px 15px rgba(0,0,0,0.03); transform: scale(1); z-index: auto;}
    }
</style>

<script>
(function() {
    let usuariosRaw = [];
    
    try {
        usuariosRaw = <?= json_encode($usuarios ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE) ?: '[]' ?>;
    } catch (e) {
        console.error("Error cargando datos:", e);
    }

    const datosMemoria = usuariosRaw.map(u => {
        if (!u || !u.codigoUsuario) return null;
        return {
            idDOM: 'fila-' + u.codigoUsuario,
            email: (u.email || '').toLowerCase(),
            rol: (u.rol || 'Sin rol')
        };
    }).filter(u => u !== null);

    const inputEmail = document.getElementById('inputBusquedaEmail');
    const selectRol = document.getElementById('filtroRol');

    function aplicarFiltros() {
        if (!inputEmail || !selectRol) return;

        const textoEmail = inputEmail.value.toLowerCase().trim();
        const textoRol = selectRol.value;

        datosMemoria.forEach(dato => {
            const fila = document.getElementById(dato.idDOM);
            if (!fila) return;

            const coincideEmail = (textoEmail === "" || dato.email.includes(textoEmail));
            const coincideRol = (textoRol === "" || dato.rol === textoRol || (textoRol === 'Personal Alcaldia' && dato.rol === 'Personal Alcaldía'));

            if (coincideEmail && coincideRol) {
                fila.style.display = "";
            } else {
                fila.style.display = "none";
            }
        });
    }

    if (inputEmail) {
        inputEmail.addEventListener('input', aplicarFiltros);
        inputEmail.addEventListener('keyup', aplicarFiltros);
    }
    if (selectRol) {
        selectRol.addEventListener('change', aplicarFiltros);
    }
})();
</script>