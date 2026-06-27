document.addEventListener('DOMContentLoaded', function() {
    
    // 1. INICIALIZAR LÓGICA DE "VER MÁS..."
    setTimeout(() => {
        const textos = document.querySelectorAll('.content-truncate');
        textos.forEach(texto => {
            if (texto.scrollHeight > texto.clientHeight + 2) { 
                const idPost = texto.id.split('-')[1];
                const btn = document.getElementById('btn-more-' + idPost);
                if(btn) btn.classList.remove('d-none');
            }
        });
    }, 200);

    // 2. MOSTRAR NOMBRE DEL ARCHIVO AL SUBIR IMAGEN
    const fileInput = document.getElementById('file-upload');
    if(fileInput) {
        fileInput.addEventListener('change', function(e) {
            if(e.target.files.length > 0) {
                const fileNameElem = document.getElementById('file-name');
                fileNameElem.textContent = e.target.files[0].name;
                fileNameElem.classList.add('text-teko');
            }
        });
    }

    // 3. BÚSQUEDA Y FILTRADO EN TIEMPO REAL (Lógica corregida)
    const buscadorInput = document.getElementById('buscadorForo');
    const filtroCategoria = document.getElementById('filtroCategoria');
    const publicaciones = document.querySelectorAll('.post-card');
    const msgNoResultados = document.getElementById('noResultados');

    function filtrarPublicaciones() {
        const textoBusqueda = buscadorInput.value.toLowerCase().trim();
        // AHORA COMPARAMOS CONTRA EL VALUE, QUE ES IDENTICO A LA BD
        const valorCategoria = filtroCategoria.value; 
        
        let postsVisibles = 0;

        publicaciones.forEach(post => {
            const tituloYContenido = post.getAttribute('data-titulo');
            const categoriaPost = post.getAttribute('data-categoria'); // Ej: "Turismo, Arte y Cultura"

            const coincideTexto = tituloYContenido.includes(textoBusqueda);
            const coincideCategoria = (valorCategoria === 'todas') || (categoriaPost === valorCategoria);

            if (coincideTexto && coincideCategoria) {
                post.style.display = 'flex'; 
                postsVisibles++;
            } else {
                post.style.display = 'none';
            }
        });

        msgNoResultados.style.display = postsVisibles === 0 ? 'block' : 'none';
    }

    if(buscadorInput && filtroCategoria) {
        buscadorInput.addEventListener('input', filtrarPublicaciones);
        filtroCategoria.addEventListener('change', filtrarPublicaciones);
    }
});

// 4. FUNCIÓN PARA EXPANDIR/COLAPSAR TEXTO 
function toggleText(id) {
    const texto = document.getElementById('content-' + id);
    const btn = document.getElementById('btn-more-' + id);
    
    if (texto.classList.contains('expanded')) {
        texto.classList.remove('expanded');
        btn.innerText = 'Ver más...';
    } else {
        texto.classList.add('expanded');
        btn.innerText = 'Ver menos';
    }
}

// 5. SISTEMA DE VOTACIÓN UNIVERSAL AJAX
// public/js/foro.js

function votar(tipoEntidad, idEntidad, tipoVoto) {
    // 🟢 CORRECCIÓN: La ruta ahora debe empezar con '/' directamente, sin la carpeta de XAMPP
    const url = '/foro/votar';

    const data = {
        tipoEntidad: tipoEntidad,
        idEntidad: idEntidad,
        tipoVoto: tipoVoto
    };

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualiza el número de votos en la pantalla
            const contador = document.getElementById(`votos-${tipoEntidad}-${idEntidad}`);
            if (contador) {
                contador.innerText = data.votos;
            }
        } else {
            // Si el backend lanza un error controlado
            console.error("Error al votar:", data.error);
            alert(data.error || "Ocurrió un error al procesar tu voto.");
        }
    })
    .catch(error => {
        // Si la URL está mal o el JSON se rompe
        console.error("Fallo crítico en la petición de voto:", error);
    });
}