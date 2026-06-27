<?php
$title = 'Foro Ciudadano - TekoPorã Bolivia';

// 1. Inyectamos los archivos estáticos SOLO para esta vista
$extraCss = '<link rel="stylesheet" href="' . asset('css/foro.css') . '">';
$extraJs = '<script src="' . asset('js/foro.js') . '"></script>';

ob_start(); 
?>

<!-- Cabecera y Buscador Dinámico -->
<div class="foro-header py-4 shadow-sm">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="foro-title mb-1 font-weight-bold">
                    <i class="fa fa-users mr-2"></i>Comunidad Paceña
                </h2>
                <p class="text-muted mb-0">Comparte, reporta y debate sobre nuestro municipio.</p>
            </div>
            <div class="col-md-6 mt-3 mt-md-0">
                <div class="input-group shadow-sm border-0 rounded-pill bg-white overflow-hidden p-1">
                    <select id="filtroCategoria" class="custom-select border-0 bg-transparent text-muted font-weight-bold" style="max-width: 160px; box-shadow: none;">
                        <option value="todas">Todas las categorías</option>
                        <option value="Avance de Obras Públicas">Obras Públicas</option>
                        <option value="Denuncias y Reportes Ciudadanos">Reportes</option>
                        <option value="Turismo, Arte y Cultura">Turismo</option>
                        <option value="Movilidad y Tráfico Urbano">Movilidad</option>
                        <option value="Medio Ambiente y Áreas Verdes">Medio Ambiente</option>
                        <option value="General">General</option>
                    </select>
                    <div class="input-group-prepend">
                        <span class="input-group-text border-0 bg-transparent text-muted"><i class="fa fa-search"></i></span>
                    </div>
                    <input type="text" id="buscadorForo" class="form-control border-0 shadow-none bg-transparent" placeholder="Buscar discusiones o palabras clave...">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <!-- Crear Publicación -->
            <?php if (isset($_SESSION['usuario'])): ?>
            <div class="card shadow-sm mb-5 border-0 rounded-lg create-post-card">
                <div class="card-body p-4">
                    <form action="<?= url('/foro/store') ?>" method="POST" enctype="multipart/form-data">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-circle shadow-sm mr-3">
                                <span><?= strtoupper(substr($_SESSION['usuario']['nombre'], 0, 1)) ?></span>
                            </div>
                            <input type="text" name="titulo" class="form-control border-0 bg-light font-weight-bold post-input" placeholder="¿Qué está pasando en La Paz, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>?" required>
                        </div>
                        
                        <div class="mb-3">
                            <select name="idCategoria_FK" class="form-control border-0 bg-light text-muted post-input" required>
                                <option value="" disabled selected>Elige una categoría para tu post...</option>
                                <option value="2">Avance de Obras Públicas</option>
                                <option value="4">Denuncias y Reportes Ciudadanos</option>
                                <option value="3">Turismo, Arte y Cultura</option>
                                <option value="5">Movilidad y Tráfico Urbano</option>
                                <option value="6">Medio Ambiente y Áreas Verdes</option>
                                <option value="1">General</option>
                            </select>
                        </div>

                        <textarea name="contenido" class="form-control border-0 bg-light mb-3 post-input" rows="3" placeholder="Añade más detalles a tu publicación..." required></textarea>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <div class="file-upload-wrapper">
                                <input type="file" id="file-upload" name="imagen" accept="image/*" class="d-none">
                                <label for="file-upload" class="btn btn-light text-muted btn-sm rounded-pill mb-0 px-3 cursor-pointer action-btn">
                                    <i class="fa fa-camera mr-2 text-teko"></i> <span id="file-name" class="font-weight-bold">Subir Foto</span>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-publish rounded-pill px-4 shadow-sm">
                                <i class="fa fa-paper-plane mr-1"></i> Publicar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <!-- Contenedor de Publicaciones -->
            <div id="contenedorPublicaciones">
                <?php if (!empty($posts)): ?>
                    <?php foreach($posts as $post): ?>
                    
                    <div class="card shadow-sm mb-4 border-0 rounded-lg post-card" 
                         data-titulo="<?= strtolower(htmlspecialchars($post['titulo'] . ' ' . $post['contenido'])) ?>"
                         data-categoria="<?= htmlspecialchars($post['nombre_categoria'] ?? 'General') ?>">
                        
                        <div class="card-body d-flex p-0">
                            <!-- Sidebar de Votos -->
                            <div class="vote-sidebar d-flex flex-column align-items-center p-3 bg-light rounded-left border-right">
                                <button onclick="votar('publicacion', <?= $post['id'] ?>, 1)" class="btn vote-btn p-0 mb-1" title="Votar positivo">
                                    <i class="fa fa-caret-up fa-2x"></i>
                                </button>
                                <span id="votos-publicacion-<?= $post['id'] ?>" class="vote-count font-weight-bold">
                                    <?= $post['votos'] ?? 0 ?>
                                </span>
                                <button onclick="votar('publicacion', <?= $post['id'] ?>, -1)" class="btn vote-btn p-0 mt-1" title="Votar negativo">
                                    <i class="fa fa-caret-down fa-2x"></i>
                                </button>
                            </div>

                            <div class="p-4 w-100">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge badge-teko-category mr-2 shadow-sm">
                                        <i class="fa fa-tag mr-1"></i> <?= htmlspecialchars($post['nombre_categoria'] ?? 'General') ?>
                                    </span>
                                    <span class="font-weight-bold text-dark mr-2">u/<?= htmlspecialchars($post['autor'] ?? 'Anónimo') ?></span>
                                    <span class="badge bg-light text-muted px-2 py-1 mr-2 karma-badge border">
                                        ⭐ <?= $post['karma_autor'] ?? 0 ?>
                                    </span>
                                    <span class="text-muted small ml-auto"><i class="fa fa-clock mr-1"></i><?= $post['fecha'] ?></span>
                                </div>

                                <h4 class="post-title font-weight-bold mb-2"><?= htmlspecialchars($post['titulo']) ?></h4>
                                
                                <div class="post-content-wrapper mb-2">
                                    <p class="post-text text-dark content-truncate" id="content-<?= $post['id'] ?>">
                                        <?= nl2br(htmlspecialchars($post['contenido'])) ?>
                                    </p>
                                    <a href="javascript:void(0);" class="ver-mas-btn text-teko font-weight-bold d-none" onclick="toggleText(<?= $post['id'] ?>)" id="btn-more-<?= $post['id'] ?>">Ver más...</a>
                                </div>

                                <?php if(!empty($post['imagen_url'])): ?>
                                    <div class="post-image-container mt-3 mb-3 rounded-lg overflow-hidden shadow-sm">
                                        <img src="<?= asset($post['imagen_url']) ?>" alt="Imagen del reporte" class="img-fluid w-100">
                                    </div>
                                <?php endif; ?>

                                <div class="d-flex align-items-center pt-2 mt-3 border-top">
                                    <button class="btn btn-sm btn-light text-muted font-weight-bold action-btn rounded-pill px-3" data-toggle="collapse" data-target="#comentarios-<?= $post['id'] ?>">
                                        <i class="fa fa-comment-alt mr-1"></i> <?= $post['num_comentarios'] ?? 0 ?> Comentarios
                                    </button>
                                    
                                    <!-- 🔥 CORRECCIÓN: BOTÓN ELIMINAR POST (SOLO ADMIN Y MODERADOR TURISMO) 🔥 -->
                                    <?php if (isset($_SESSION['usuario']) && in_array($_SESSION['usuario']['rol'], ['Administrador', 'Moderador Turismo'])): ?>
                                          <a href="<?= url('/foro/eliminar?codigo=' . $post['codigoPublicacion']) ?>" onclick="return confirm('¿Seguro que deseas eliminar esto?');" class="btn btn-sm btn-outline-danger font-weight-bold ml-auto rounded-pill px-3 delete-btn">
                                          <i class="fa fa-trash-alt mr-1"></i> Eliminar
                                          </a>
                                    <?php endif; ?>
                                </div>

                                <!-- Sección de Comentarios -->
                                <div class="collapse mt-3" id="comentarios-<?= $post['id'] ?>">
                                    <div class="card card-body bg-light border-0 comments-section p-3 rounded-lg">
                                        <?php if(isset($_SESSION['usuario'])): ?>
                                        <form action="<?= url('/foro/comentar') ?>" method="POST" class="mb-4">
                                            <input type="hidden" name="idPublicacion_FK" value="<?= $post['id'] ?>">
                                            <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white">
                                                <input type="text" name="contenido" class="form-control border-0 px-4 shadow-none" placeholder="Escribe tu opinión..." required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-publish px-4" type="submit"><i class="fa fa-paper-plane"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                        <?php endif; ?>

                                        <?php if(!empty($post['comentarios'])): ?>
                                            <?php foreach($post['comentarios'] as $comentario): ?>
                                                <div class="mb-3 bg-white p-3 rounded shadow-sm comment-item border-left border-info">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <div>
                                                            <span class="font-weight-bold text-dark">u/<?= htmlspecialchars($comentario['autor'] ?? 'Anónimo') ?></span>
                                                            <span class="text-muted ml-2" style="font-size: 0.75rem;"><i class="fa fa-clock mr-1"></i> <?= date('d/m/Y H:i', strtotime($comentario['fecha'])) ?></span>
                                                        </div>
                                                        
                                                        <div class="d-flex align-items-center">
                                                            <!-- 🔥 CORRECCIÓN: BOTÓN ELIMINAR COMENTARIO (SOLO ADMIN Y MODERADOR TURISMO) 🔥 -->
                                                            <?php if (isset($_SESSION['usuario']) && in_array($_SESSION['usuario']['rol'], ['Administrador', 'Moderador Turismo'])): ?>
                                                                <a href="<?= url('/foro/eliminarComentario?id=' . $comentario['idComentario']) ?>" onclick="return confirm('¿Eliminar este comentario permanentemente?');" class="text-danger small font-weight-bold mr-3" title="Eliminar comentario">
                                                                    <i class="fa fa-trash-alt"></i> Eliminar
                                                                </a>
                                                            <?php endif; ?>

                                                            <div class="comment-vote-controls bg-light rounded-pill px-2 py-1">
                                                                <button onclick="votar('comentario', <?= $comentario['idComentario'] ?>, 1)" class="btn btn-link text-muted p-0 m-0"><i class="fa fa-caret-up"></i></button>
                                                                <span id="votos-comentario-<?= $comentario['idComentario'] ?>" class="mx-2 font-weight-bold text-teko small"><?= $comentario['votos'] ?? 0 ?></span>
                                                                <button onclick="votar('comentario', <?= $comentario['idComentario'] ?>, -1)" class="btn btn-link text-muted p-0 m-0"><i class="fa fa-caret-down"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="mb-0 text-dark" style="font-size: 0.95rem;"><?= nl2br(htmlspecialchars($comentario['contenido'])) ?></p>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div id="noResultados" class="text-center py-5" style="display: none;">
                <i class="fa fa-search fa-3x text-muted mb-3 opacity-50"></i>
                <h5 class="text-muted">No encontramos resultados</h5>
                <p class="text-muted">Intenta buscar con otras palabras o cambia la categoría.</p>
            </div>

        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layouts/app_layout.php'; 
?>