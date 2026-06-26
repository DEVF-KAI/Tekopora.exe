document.addEventListener("DOMContentLoaded", () => {
    
    // 1. Efecto Parallax en el Fondo (El fondo se mueve, la tarjeta no)
    const bg = document.getElementById('parallax-bg');
    if (bg) {
        document.addEventListener('mousemove', (e) => {
            // Calculamos un movimiento suave e inverso a la posición del ratón
            const x = (window.innerWidth - e.pageX * 2) / 90;
            const y = (window.innerHeight - e.pageY * 2) / 90;
            bg.style.transform = `translateX(${x}px) translateY(${y}px)`;
        });
    }

    // 2. Medidor de Fuerza de Contraseña
    const pwdInput = document.getElementById('passwordInput');
    const strengthBar = document.getElementById('strengthBar');
    
    const reqLength = document.getElementById('reqLength');
    const reqUpper = document.getElementById('reqUpper');
    const reqNumber = document.getElementById('reqNumber');
    const reqSymbol = document.getElementById('reqSymbol');

    if (pwdInput) {
        pwdInput.addEventListener('input', function() {
            const val = pwdInput.value;
            let score = 0;

            if (val.length >= 8) { score++; reqLength.className = 'pwd-req-item valid'; reqLength.querySelector('i').className = 'fas fa-check-circle'; } 
            else { reqLength.className = 'pwd-req-item invalid'; reqLength.querySelector('i').className = 'fas fa-times-circle'; }

            if (/[A-Z]/.test(val)) { score++; reqUpper.className = 'pwd-req-item valid'; reqUpper.querySelector('i').className = 'fas fa-check-circle'; } 
            else { reqUpper.className = 'pwd-req-item invalid'; reqUpper.querySelector('i').className = 'fas fa-times-circle'; }

            if (/[0-9]/.test(val)) { score++; reqNumber.className = 'pwd-req-item valid'; reqNumber.querySelector('i').className = 'fas fa-check-circle'; } 
            else { reqNumber.className = 'pwd-req-item invalid'; reqNumber.querySelector('i').className = 'fas fa-times-circle'; }

            if (/[^A-Za-z0-9]/.test(val)) { score++; reqSymbol.className = 'pwd-req-item valid'; reqSymbol.querySelector('i').className = 'fas fa-check-circle'; } 
            else { reqSymbol.className = 'pwd-req-item invalid'; reqSymbol.querySelector('i').className = 'fas fa-times-circle'; }

            let width = (score / 4) * 100;
            if(val.length === 0) width = 0; 
            
            strengthBar.style.width = width + '%';

            if (score <= 1) strengthBar.style.backgroundColor = '#e74c3c';
            else if (score === 2) strengthBar.style.backgroundColor = '#f39c12';
            else if (score === 3) strengthBar.style.backgroundColor = '#f1c40f';
            else if (score === 4) strengthBar.style.backgroundColor = '#2ecc71';
        });
    }
});