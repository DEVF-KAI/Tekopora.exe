<div class="container py-5">
    <div class="register-card mx-auto">
        <h2 class="text-center mb-4"><i class="fa fa-user-edit"></i> Editar Usuario</h2>
        
        <form action="<?= url('admin/actualizar') ?>" method="POST">
            <input type="hidden" name="codigoUsuario" value="<?= $usuario['codigoUsuario'] ?>">

            <div class="mb-3">
                <label class="form-label small font-weight-bold">NOMBRE COMPLETO</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label small font-weight-bold">CORREO ELECTRÓNICO</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label small font-weight-bold">ASIGNAR ROL</label>
                <select name="rol" class="form-control" required>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['idRol'] ?>" <?= ($r['idRol'] == $rolActual) ? 'selected' : '' ?>>
                            <?= $r['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="<?= url('adminpanel') ?>" class="btn btn-secondary rounded-pill px-4">Cancelar</a>
                <button type="submit" class="btn btn-success rounded-pill px-4">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>