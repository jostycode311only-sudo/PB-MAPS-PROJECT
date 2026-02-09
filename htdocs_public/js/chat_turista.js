const API_URL = '../../app/Controllers/ChatAPI.php';
const receptorId = document.getElementById('receptorId').value;
let chatInterval = null;

document.addEventListener('DOMContentLoaded', () => {
    // 1. Cargar mensajes iniciales
    cargarMensajes();
    
    // 2. Iniciar polling (actualización automática)
    chatInterval = setInterval(cargarMensajes, 3000);

    // 3. Configurar envío
    document.getElementById('formChatTurista').addEventListener('submit', (e) => {
        e.preventDefault();
        enviarMensaje();
    });

    // Poner título (opcional, mejora UX)
    document.getElementById('chatTitle').textContent = "Chat con la Agencia";
});

function cargarMensajes() {
    fetch(`${API_URL}?action=leer&contacto_id=${receptorId}`)
        .then(response => response.json())
        .then(data => {
            const box = document.getElementById('cajaMensajes');
            
            if (data.success && data.mensajes) {
                // Guardamos la posición del scroll antes de actualizar
                const isScrolledToBottom = box.scrollHeight - box.clientHeight <= box.scrollTop + 1;

                box.innerHTML = ''; // Limpiar para redibujar (en prod se optimizaría)
                
                if (data.mensajes.length === 0) {
                    box.innerHTML = '<div class="text-center p-4 text-muted">Aún no hay mensajes. ¡Di hola!</div>';
                    return;
                }

                data.mensajes.forEach(msg => {
                    const div = document.createElement('div');
                    // Aquí la lógica se invierte respecto al admin:
                    // Si el emisor soy yo (turista), es 'sent'. Si no, es 'received'.
                    // Como no tenemos el ID del turista en JS fácil, usamos la misma lógica del receptor:
                    // Si emisor_id == receptorId (la agencia), entonces es RECEIVED.
                    
                    const tipo = (msg.emisor_id == receptorId) ? 'received' : 'sent';
                    
                    div.className = `message ${tipo}`;
                    div.innerHTML = `
                        ${msg.mensaje}
                        <span class="message-time">${new Date(msg.fecha_envio).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                    `;
                    box.appendChild(div);
                });

                // Auto-scroll solo si estaba abajo o es la primera carga
                box.scrollTop = box.scrollHeight;
            }
        })
        .catch(err => console.error(err));
}

function enviarMensaje() {
    const input = document.getElementById('inputMensaje');
    const mensaje = input.value.trim();

    if (!mensaje) return;

    fetch(`${API_URL}?action=enviar`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            receptor_id: receptorId,
            mensaje: mensaje
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            input.value = ''; // Limpiar
            cargarMensajes(); // Recargar inmediato
        }
    });
}