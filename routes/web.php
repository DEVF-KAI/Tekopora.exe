<?php
return [
    // ==========================================
    // PÁGINAS PÚBLICAS Y HERRAMIENTAS
    // ==========================================
    ['method' => 'GET',  'path' => '/',                 'target' => 'HomeController@index'],
    ['method' => 'GET',  'path' => '/destinos',         'target' => 'DestinosController@destinos'],
    ['method' => 'GET',  'path' => '/herramientas',     'target' => 'HerramientasController@herramientas'],
    ['method' => 'GET',  'path' => '/visualizacion',    'target' => 'VisualizacionController@visualizacion'],
    ['method' => 'GET',  'path' => '/aperturas',        'target' => 'AperturasController@aperturas'],
    ['method' => 'GET',  'path' => '/perfil-ciudadano', 'target' => 'AnalisisPersonalController@perfil'],

    // ==========================================
    // AUTENTICACIÓN (LOGIN / REGISTER / LOGOUT)
    // ==========================================
    ['method' => 'GET',  'path' => '/login',            'target' => 'AuthController@login'],
    ['method' => 'POST', 'path' => '/login',            'target' => 'AuthController@authenticate'],
    ['method' => 'GET',  'path' => '/logout',           'target' => 'AuthController@logout'],
    ['method' => 'GET',  'path' => '/register',         'target' => 'RegisterController@register'],
    ['method' => 'POST', 'path' => '/register',         'target' => 'RegisterController@store'],

    // ==========================================
    // VERIFICACIÓN CON GOOGLE (OAuth 2.0)
    // ==========================================
    ['method' => 'GET',  'path' => '/auth/google',          'target' => 'GoogleAuthController@redirectToGoogle'],
    ['method' => 'GET',  'path' => '/auth/google/callback', 'target' => 'GoogleAuthController@handleGoogleCallback'],

    // ==========================================
    // VERIFICACIÓN DE IDENTIDAD (CÓDIGO POR EMAIL)
    // ==========================================
    ['method' => 'GET',  'path' => '/verificar',            'target' => 'VerificationController@index'],
    ['method' => 'GET',  'path' => '/verificar/enviar',     'target' => 'VerificationController@enviar'],
    ['method' => 'POST', 'path' => '/verificar/validar',    'target' => 'VerificationController@validar'],

    // ==========================================
    // RECUPERACIÓN DE CONTRASEÑA
    // ==========================================
    ['method' => 'GET',  'path' => '/recuperar',            'target' => 'PasswordResetController@index'],
    ['method' => 'POST', 'path' => '/recuperar/enviar',     'target' => 'PasswordResetController@enviarCodigo'],
    ['method' => 'GET',  'path' => '/recuperar/verificar',  'target' => 'PasswordResetController@mostrarVerificacion'],
    ['method' => 'POST', 'path' => '/recuperar/verificar',  'target' => 'PasswordResetController@verificarCodigo'],
    ['method' => 'GET',  'path' => '/recuperar/nueva',      'target' => 'PasswordResetController@mostrarNueva'],
    ['method' => 'POST', 'path' => '/recuperar/actualizar', 'target' => 'PasswordResetController@actualizarPassword'],

    // ==========================================
    // ADMINISTRACIÓN Y USUARIOS
    // ==========================================
    ['method' => 'GET',  'path' => '/adminpanel',       'target' => 'AdminPanelController@index'],
    ['method' => 'GET',  'path' => '/admin/suspender',  'target' => 'AdminPanelController@suspenderUsuario'],
    ['method' => 'GET',  'path' => '/admin/activar',    'target' => 'AdminPanelController@activarUsuario'],
    ['method' => 'GET',  'path' => '/usuarios/bloqueados',          'target' => 'UsuarioController@index'],
    ['method' => 'GET',  'path' => '/usuarios/bloquear',            'target' => 'UsuarioController@bloquear'],
    ['method' => 'GET',  'path' => '/usuarios/desbloquear',         'target' => 'UsuarioController@desbloquear'],
    ['method' => 'GET',  'path' => '/usuarios/obtener-bloqueados',  'target' => 'UsuarioController@obtenerBloqueados'],
    ['method' => 'GET',  'path' => '/admin/editar',     'target' => 'AdminPanelController@editarUsuario'],
    ['method' => 'POST', 'path' => '/admin/actualizar', 'target' => 'AdminPanelController@actualizarUsuario'],

    // ==========================================
    // PROYECTOS Y OBRAS
    // ==========================================
    ['method' => 'GET',  'path' => '/empresas',                'target' => 'EmpresasController@empresas'],
    ['method' => 'GET',  'path' => '/empresas/add',            'target' => 'EmpresasAddController@empresasadd'],
    ['method' => 'POST', 'path' => '/empresas/store',          'target' => 'EmpresasAddController@store'],
    ['method' => 'GET',  'path' => '/proyectos',             'target' => 'ProyectosController@proyectos'],
    ['method' => 'GET',  'path' => '/proyectosadd',          'target' => 'ProyectosaddController@proyectosadd'],
    ['method' => 'POST', 'path' => '/proyectosadd',          'target' => 'ProyectosaddController@store'],
    ['method' => 'GET',  'path' => '/mis-obras',             'target' => 'ProyectosController@misObras'],
    ['method' => 'POST', 'path' => '/proyectos/reportar',    'target' => 'ProyectosController@guardarReporte'],
    ['method' => 'GET',  'path' => '/proyectos/detalle',     'target' => 'ProyectosController@detalleProyecto'],
    ['method' => 'POST', 'path' => '/proyectos/evaluar',     'target' => 'ProyectosController@evaluarEmpresa'],
    ['method' => 'POST', 'path' => '/proyectos/subir_foto',  'target' => 'ProyectosController@subirFotoAdicional'],

    // ==========================================
    // FORO Y COMENTARIOS
    // ==========================================
    ['method' => 'GET',  'path' => '/foro',                     'target' => 'ForoController@index'],
    ['method' => 'POST', 'path' => '/foro/store',               'target' => 'ForoController@store'],
    ['method' => 'GET',  'path' => '/foro/eliminar',            'target' => 'ForoController@eliminar'],
    ['method' => 'POST', 'path' => '/foro/comentar',            'target' => 'ForoController@comentar'],
    ['method' => 'POST', 'path' => '/foro/votar',               'target' => 'ForoController@votar'],
    ['method' => 'GET',  'path' => '/foro/eliminar_comentario', 'target' => 'ForoController@eliminarComentario'],
    
    // ==========================================
    // TURISMO
    // ==========================================
    ['method' => 'GET',  'path' => '/turismo',                      'target' => 'TurismoController@turismo'],
    ['method' => 'GET',  'path' => '/validar-sitios',               'target' => 'TurismoController@validarSitios'],
    ['method' => 'POST', 'path' => '/turismo/procesar-validacion',  'target' => 'TurismoController@procesarValidacion'],
    ['method' => 'GET',  'path' => '/turismo/crear',                'target' => 'TurismoController@crear'],
    ['method' => 'POST', 'path' => '/turismo/guardar',              'target' => 'TurismoController@store'],
    ['method' => 'GET',  'path' => '/turismo/revision',             'target' => 'TurismoController@panelRevision'],
    ['method' => 'POST', 'path' => '/turismo/procesar',             'target' => 'TurismoController@procesarPropuesta'],
    ['method' => 'GET',  'path'  => '/turismo/mis-propuestas',       'target' => 'TurismoController@misPropuestas'],
    ['method' => 'GET',  'path' => '/municipios',                    'target' => 'MunicipiosController@municipios'],

    // ==========================================
    // REPORTES (SOLO ADMIN)
    // ==========================================
    ['method' => 'GET',  'path' => '/reportes/auditoria',  'target' => 'ReportesController@generarAuditoria'],
    ['method' => 'POST', 'path' => '/reportes/auditoria',  'target' => 'ReportesController@generarAuditoria'],

    // ==========================================
    // CHATBOT 
    // ==========================================
    ['method' => 'GET',  'path' => '/chatboy',             'target' => 'ChatboyController@index'],

    // ==========================================
    // NOTIFICACIONES
    // ==========================================
    ['method' => 'GET',  'path' => '/notificaciones/obtener', 'target' => 'NotificacionController@obtener'],
    ['method' => 'POST', 'path' => '/notificaciones/leer',    'target' => 'NotificacionController@marcarLeidas'],
];