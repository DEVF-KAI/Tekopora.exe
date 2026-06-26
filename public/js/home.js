document.addEventListener("DOMContentLoaded", () => {
    
    // 1. INICIALIZAR AOS (Animaciones de Scroll)
    if (typeof AOS !== 'undefined') {
        AOS.init({ duration: 1000, once: true, offset: 100 });
    }

    // 2. INICIALIZAR TARJETAS TILT 3D
    if (typeof VanillaTilt !== 'undefined') {
        const tiltCards = document.querySelectorAll(".js-tilt");
        if(tiltCards.length > 0) {
            VanillaTilt.init(tiltCards, {
                max: 15, speed: 400, glare: true, "max-glare": 0.2, scale: 1.05
            });
        }
    }

    // 3. EL BAILE DE LUCES (Particles.js)
    if(document.getElementById('particles-js')) {
        particlesJS("particles-js", {
            "particles": {
                "number": { "value": 80, "density": { "enable": true, "value_area": 800 } },
                "color": { "value": "#48c6ef" },
                "shape": { "type": "circle" },
                "opacity": { "value": 0.5, "random": true },
                "size": { "value": 3, "random": true },
                "line_linked": { "enable": true, "distance": 150, "color": "#48c6ef", "opacity": 0.4, "width": 1 },
                "move": { "enable": true, "speed": 2, "direction": "none", "random": false, "straight": false, "out_mode": "out", "bounce": false }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": { "enable": true, "mode": "grab" },
                    "onclick": { "enable": true, "mode": "push" },
                    "resize": true
                },
                "modes": {
                    "grab": { "distance": 140, "line_linked": { "opacity": 1 } },
                    "push": { "particles_nb": 4 }
                }
            },
            "retina_detect": true
        });
    }
});