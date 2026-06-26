<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color:#263238; font-weight:700;">
            <i class="fa fa-users"></i> Gestión de Usuarios
        </h2>

        <a href="<?= url('register') ?>" class="btn btn-create">
            <i class="fa fa-plus"></i> Nuevo Usuario
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-5">
                    <div class="input-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fa fa-search text-success"></i>
                        </span>
                        <input type="text" id="inputBusquedaCI" class="form-control border-start-0"
                            placeholder="Ingrese CI para búsqueda instantánea..." style="box-shadow: none;">
                    </div>
                    <small class="text-muted ml-2">Búsqueda optimizada por Hash Map $O(1)$</small>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover ali n-middle text-center">

                    <thead class="table-header">
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>CI</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($usuarios as $u): ?>

                            <tr id="fila-<?= $u['codigoUsuario'] ?>">

                                <td class="fw-bold"><?= htmlspecialchars($u['nombre'] . ' ' . $u['appPaterno']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= htmlspecialchars($u['ci']) ?></td>
                                <td><?= htmlspecialchars($u['telefono']) ?></td>

                                <td>
                                    <span class="badge badge-role">
                                        <?= $u['rol'] ?? 'Sin rol' ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if ($u['estado'] == 'Activo'): ?>
                                        <span class="badge badge-active">Activo</span>
                                    <?php else: ?>
                                        <span class="badge badge-inactive"><?= $u['estado'] ?></span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if (isset($u['rol']) && $u['rol'] !== 'Administrador'): ?>
                                        <a href="<?= url('admin/editar?codigo=' . $u['codigoUsuario']) ?>"
                                            class="btn btn-sm btn-edit">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <?php if ($u['estado'] == 'Activo'): ?>
                                            <a href="<?= url('admin/suspender?codigo=' . $u['codigoUsuario']) ?>"
                                                class="btn btn-sm btn-danger">
                                                <i class="fa fa-ban"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= url('admin/activar?codigo=' . $u['codigoUsuario']) ?>"
                                                class="btn btn-sm btn-success">
                                                <i class="fa fa-check"></i>
                                            </a>
                                        <?php endif; ?>

                                    <?php else: ?>
                                        <span class="text-muted" style="font-size: 0.85em; font-style: italic;">Protegido</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<style>
    body {
        background: #F5F7F6;
    }

    /* Botón crear */
    .btn-create {
        background: #2E7D6B;
        color: white;
        border-radius: 8px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-create:hover {
        background: #25675a;
        color: white;
    }

    /* Cabecera tabla */
    .table-header {
        background: #2A6F97;
        color: white;
    }

    /* Badge rol */
    .badge-role {
        background: #F2B705;
        color: #263238;
        padding: 6px 10px;
        border-radius: 8px;
    }

    /* Estado activo */
    .badge-active {
        background: #2E7D6B;
        color: white;
        padding: 6px 10px;
        border-radius: 8px;
    }

    /* Estado inactivo */
    .badge-inactive {
        background: #C62828;
        color: white;
        padding: 6px 10px;
        border-radius: 8px;
    }

    /* Botón editar */
    .btn-edit {
        background: #F2B705;
        color: #263238;
    }

    .btn-edit:hover {
        background: #d89f04;
    }

    /* Card */
    .card {
        border-radius: 12px;
    }

    /* Hover filas */
    .table-hover tbody tr:hover {
        background: rgba(46, 125, 107, 0.08);
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Materia prima desde PHP
    const usuariosRaw = <?= json_encode($usuarios) ?>;

    // 2. CONSTRUCCIÓN DE LA TABLA HASH (Hash Map)
    // La llave es el CI, el valor es el ID de la fila en el DOM
    const usuariosHash = {};

    usuariosRaw.forEach(u => {
        // Mapeamos el CI directamente al ID del elemento visual
        usuariosHash[u.ci] = `fila-${u.codigoUsuario}`;
    });

    // 3. LÓGICA DEL BUSCADOR
    const inputBusqueda = document.getElementById('inputBusquedaCI');

    inputBusqueda.addEventListener('input', function(e) {
        const ciIngresado = e.target.value.trim();
        const filas = document.querySelectorAll('tbody tr');

        // Si el buscador está vacío, mostramos todo
        if (ciIngresado === "") {
            filas.forEach(f => f.style.display = "");
            return;
        }

        // --- ALGORITMO DE BÚSQUEDA ---
        
        // Paso A: Ocultamos todo (reset)
        filas.forEach(f => f.style.display = "none");

        // Paso B: Búsqueda O(1) - Acceso Directo por Hash
        if (usuariosHash[ciIngresado]) {
            const idFilaEncontrada = usuariosHash[ciIngresado];
            document.getElementById(idFilaEncontrada).style.display = "";
            
            // Efecto visual de "Encontrado" (opcional)
            document.getElementById(idFilaEncontrada).style.backgroundColor = "rgba(46, 125, 107, 0.1)";
            setTimeout(() => {
                document.getElementById(idFilaEncontrada).style.backgroundColor = "";
            }, 1000);

        } else {
            // Paso C: Fallback para búsqueda parcial (mientras el usuario escribe)
            // Solo se activa si no hay coincidencia exacta de CI
            for (let ci in usuariosHash) {
                if (ci.startsWith(ciIngresado)) {
                    document.getElementById(usuariosHash[ci]).style.display = "";
                }
            }
        }
    });
});
</script>