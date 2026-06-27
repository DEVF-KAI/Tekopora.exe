<!-- Botón Flotante Neumórfico -->
<a href="chatboy" 
   id="btn-abrir-chat" 
   class="btn-flotante-teko"
   title="Hablar con TEKO">
    <div class="icon-container">
        <i class="fas fa-comment-dots"></i>
    </div>
    <span class="text-container">Hablar con TEKO</span>
</a>

<style>
    .btn-flotante-teko {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: linear-gradient(135deg, #1A6A6D 0%, #2A8C8F 100%);
        color: white;
        padding: 10px 22px 10px 10px;
        border-radius: 50px;
        text-decoration: none;
        z-index: 99999;
        display: flex;
        align-items: center;
        font-weight: 600;
        box-shadow: 0 10px 25px rgba(26, 106, 109, 0.4);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: 2px solid rgba(255,255,255,0.2);
    }

    .btn-flotante-teko:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 15px 35px rgba(26, 106, 109, 0.5);
        color: white;
        text-decoration: none;
    }

    .btn-flotante-teko .icon-container {
        background: white;
        color: #1A6A6D;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        font-size: 1.1rem;
    }

    .btn-flotante-teko .text-container {
        letter-spacing: 0.5px;
    }
</style>