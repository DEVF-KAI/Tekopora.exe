<?php
$title = 'Foro Ciudadano - TekoPorã Bolivia';
ob_start(); 
?>

<div class="container-fluid py-4" style="background-color: #f4f6f9; border-bottom: 1px solid #e9ecef;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="font-weight-bold mb-1" style="color: #1A6A6D; font-family: 'Times New Roman', Times, serif;">
                    <i class="fa fa-comments mr-2"></i>Foro Ciudadano
                </h2>
                <p class="text-muted mb-0" style="font-size: 1.1rem;">Participa en la comunidad, reporta incidencias y acumula Karma.</p>
            </div>
            <div class="col-md-4 text-right mt-3 mt-md-0">
                <div class="input-group shadow-sm border-0 rounded-pill overflow-hidden">
                    <input type="text" class="form-control border-0 bg-white px-4" placeholder="Buscar discusiones...">
                    <div class="input-group-append">
                        <button class="btn text-white px-4" style="background-color: #217F82;" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            
            <?php if (isset($_SESSION['usuario'])): ?>
            <div class="card shadow-sm mb-4 border-0 rounded-lg">
                <div class="card-body p-4">
                    <form action="<?= url('/foro/store') ?>" method="POST" enctype="multipart/form-data">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle text-white d-flex justify-content-center align-items-center mr-3 shadow-sm" style="width: 45px; height: 45px; background: linear-gradient(135deg, #217F82 0%, #1A6A6D 100%);">
                                <i class="fa fa-pencil-alt"></i>
                            </div>
                            <input type="text" name="titulo" class="form-control border-0 bg-light font-weight-bold post-input" placeholder="Título de tu reporte o debate..." required>
                        </div>
                        <textarea name="contenido" class="form-control border-0 bg-light mb-3 post-input" rows="3" placeholder="Describe los detalles aquí..." required></textarea>
                        
                        <div class="d-flex justify-content-between align-items-center bg-white mt-3 pt-2 border-top">
                            <div class="file-upload-wrapper">
                                <input type="file" id="file-upload" name="imagen" accept="image/*" class="d-none">
                                <label for="file-upload" class="btn btn-outline-secondary btn-sm rounded-pill mb-0 px-3 cursor-pointer file-upload-label">
                                    <i class="fa fa-camera mr-1"></i> <span id="file-name">Adjuntar foto</span>
                                </label>
                            </div>
                            <button type="submit" class="btn text-dark rounded-pill px-4 font-weight-bold shadow-sm publish-btn" style="background-color: #ffc107;">
                                <i class="fa fa-paper-plane mr-1"></i> Publicar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($posts)): ?>
                <?php foreach($posts as $post): ?>
                <div class="card shadow-sm mb-4 border-0 rounded-lg post-card">
                    <div class="card-body d-flex p-0">
                        
                        <div class="d-flex flex-column align-items-center p-3 bg-light rounded-left border-right" style="width: 65px; min-width: 65px;">
                            <button onclick="votar(<?= $post['id'] ?>, 1)" class="btn btn-link text-muted p-0 vote-btn" title="Votar positivo">
                                <i class="fa fa-arrow-up fa-lg"></i>
                            </button>
                            
                            <span id="votos-<?= $post['id'] ?>" class="my-2 font-weight-bold" style="color: #1A6A6D; font-size: 1.1rem;">
                                <?= $post['votos'] ?>
                            </span>
                            
                            <button onclick="votar(<?= $post['id'] ?>, -1)" class="btn btn-link text-muted p-0 vote-btn" title="Votar negativo">
                                <i class="fa fa-arrow-down fa-lg"></i>
                            </button>
                        </div>

                        <div class="p-4 w-100">
                            <div class="d-flex align-items-center mb-3">
                                <span class="font-weight-bold text-dark mr-2">u/<?= htmlspecialchars($post['autor']) ?></span>
                                <span class="badge badge-warning text-dark px-2 py-1 mr-2 shadow-sm" style="font-size: 0.7rem;">
                                    Karma: <?= $post['karma_autor'] ?>
                                </span>
                                <span class="text-muted small ml-auto"><i class="fa fa-clock mr-1"></i><?= $post['fecha'] ?></span>
                            </div>

                            <h4 class="font-weight-bold mb-3" style="color: #1A6A6D;"><?= htmlspecialchars($post['titulo']) ?></h4>
                            <p class="text-dark" style="font-size: 1rem; line-height: 1.6;"><?= nl2br(htmlspecialchars($post['contenido'])) ?></p>

                            <?php if(!empty($post['imagen_url'])): ?>
                                <div class="mt-3 mb-4 rounded-lg overflow-hidden border shadow-sm text-center" style="background-color: #f8f9fa;">
                                    <img src="<?= asset($post['imagen_url']) ?>" alt="Imagen del reporte" class="img-fluid" style="max-height: 400px; object-fit: contain; width: 100%;">
                                </div>
                            <?php endif; ?>

                            <div class="d-flex pt-2 mt-2 border-top">
                                <button class="btn btn-sm btn-light text-muted font-weight-bold mr-2 action-btn rounded-pill px-3" data-toggle="collapse" data-target="#comentarios-<?= $post['id'] ?>">
                                    <i class="fa fa-comment-alt mr-1"></i> <?= $post['num_comentarios'] ?> Comentarios
                                </button>
                                
                               <?php if (isset($_SESSION['usuario']) && in_array($_SESSION['usuario']['rol'], ['Administrador', 'Moderador Turismo', 'Moderador Obras'])): ?>
                                      <a href="<?= url('/foro/eliminar?codigo=' . $post['codigoPublicacion']) ?>" onclick="return confirm('¿Seguro que deseas eliminar esto?');" class="btn btn-sm btn-outline-danger font-weight-bold ml-auto rounded-pill px-3">
                                      <i class="fa fa-trash-alt mr-1"></i> Eliminar
                                      </a>
                                    <?php endif; ?>
                            </div>

                            <div class="collapse mt-3" id="comentarios-<?= $post['id'] ?>">
                                <div class="card card-body bg-light border-0">
                                    
                                    <?php if(!empty($post['comentarios'])): ?>
                                        <?php foreach($post['comentarios'] as $comentario): ?>
                                            <div class="mb-3 border-bottom pb-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span class="font-weight-bold small text-primary">u/<?= htmlspecialchars($comentario['autor']) ?></span>
                                                        <span class="text-muted ml-2" style="font-size: 0.75rem;"><i class="fa fa-clock mr-1"></i> <?= date('d/m/Y H:i', strtotime($comentario['fecha'])) ?></span>
                                                    </div>
                                                    
                                                    <?php if (isset($_SESSION['usuario']) && in_array($_SESSION['usuario']['rol'], ['Administrador', 'Moderador Turismo', 'Moderador Obras'])): ?>
                                                        <a href="<?= url('/foro/eliminar_comentario?id=' . $comentario['idComentario']) ?>" 
                                                           onclick="return confirm('¿Borrar este comentario?');" 
                                                           class="text-danger ml-2" title="Eliminar comentario">
                                                            <i class="fa fa-times"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="mb-0 small text-dark mt-1"><?= nl2br(htmlspecialchars($comentario['contenido'])) ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-muted small text-center mb-3">No hay comentarios aún. ¡Sé el primero!</p>
                                    <?php endif; ?>

                                    <?php if(isset($_SESSION['usuario'])): ?>
                                    <form action="<?= url('/foro/comentar') ?>" method="POST">
                                        <input type="hidden" name="idPublicacion" value="<?= $post['id'] ?>">
                                        <div class="input-group input-group-sm mt-2">
                                            <input type="text" name="contenido" class="form-control rounded-left" placeholder="Escribe un comentario..." required>
                                            <div class="input-group-append">
                                                <button class="btn text-white" style="background-color: #217F82;" type="submit">Enviar</button>
                                            </div>
                                        </div>
                                    </form>
                                    <?php else: ?>
                                        <div class="alert alert-warning small py-1 mt-2 text-center">Inicia sesión para comentar</div>
                                    <?php endif; ?>

                                </div>
                            </div>
                            </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        </div>
</div>

<style>
    .post-card { transition: all 0.2s ease-in-out; border: 1px solid transparent; }
    .post-card:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
    .action-btn:hover { background-color: #e9ecef !important; color: #1A6A6D !important; }
    .vote-btn:hover { color: #217F82 !important; background: transparent; transform: scale(1.2); }
</style>

<script>
function votar(idPublicacion, tipoVoto) {
    // Usamos Fetch API para enviar los datos por debajo de la mesa (sin recargar la página)
    let formData = new URLSearchParams();
    formData.append('idPublicacion', idPublicacion);
    formData.append('tipoVoto', tipoVoto);

    fetch('<?= url("/foro/votar") ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData.toString()
    })
    .then(response => response.json())
    .then(data => {
        if(data.error) {
            alert(data.error); // Ej: "Debes iniciar sesión"
        } else if(data.success) {
            // Magia: Actualizamos el numerito del voto instantáneamente
            document.getElementById('votos-' + idPublicacion).innerText = data.votos;
        }
    })
    .catch(error => console.error('Error:', error));
}

// Script para mostrar el nombre del archivo al subir foto
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file-upload');
    if(fileInput) {
        fileInput.addEventListener('change', function(e) {
            if(e.target.files.length > 0) {
                document.getElementById('file-name').textContent = e.target.files[0].name;
            }
        });
    }
});
</script>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layouts/app_layout.php'; 
?>