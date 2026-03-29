// htdocs_public/js/chat_admin.js

const API_URL = '../../app/Controllers/ChatAPI.php';
let chatInterval = null;
let currentContactId = 0;

document.addEventListener('DOMContentLoaded', () => {
    cargarContactos();

    // Evento enviar mensaje
    document.getElementById('formEnviarMensaje').addEventListener('submit', (e) => {
        e.preventDefault();
        enviarMensaje();
    });
});

// 1. Cargar lista de personas con las que he hablado
function cargarContactos() {
    fetch(`${API_URL}?action=contactos`)
        .then(response => response.json())
        .then(data => {
            const lista = document.getElementById('listaContactos');
            lista.innerHTML = ''; // Limpiar

            if (data.success && data.contactos.length > 0) {
                data.contactos.forEach(usuario => {
                    const div = document.createElement('div');
                    div.className = `contact-item ${currentContactId == usuario.id ? 'active' : ''}`;
                    div.innerHTML = `
                        <span class="contact-name">${usuario.nombre_usuario}</span>
                        <span class="contact-role">${usuario.rol}</span>
                    `;
                    div.onclick = () => seleccionarContacto(usuario.id, usuario.nombre_usuario);
                    lista.appendChild(div);
                });
            } else {
                lista.innerHTML = '<div class="p-3 text-muted">No hay chats recientes.</div>';
            }
        })
        .catch(err => console.error('Error cargando contactos:', err));
}

// 2. Seleccionar un usuario para chatear
function seleccionarContacto(id, nombre) {
    currentContactId = id;
    document.getElementById('chatHeaderName').textContent = nombre;
    document.getElementById('inputMensaje').disabled = false;
    document.getElementById('btnEnviar').disabled = false;
    
    // Resaltar visualmente
    cargarContactos(); 
    
    // Cargar mensajes inmediatamente
    cargarMensajes();

    // Iniciar polling (actualización automática) si no existe
    if (chatInterval) clearInterval(chatInterval);
    chatInterval = setInterval(cargarMensajes, 3000); // Cada 3 segundos
}

// 3. Cargar historial de conversación
function cargarMensajes() {
    if (!currentContactId) return;

    fetch(`${API_URL}?action=leer&contacto_id=${currentContactId}`)
        .then(response => response.json())
        .then(data => {
            const box = document.getElementById('cajaMensajes');
            box.innerHTML = ''; // Limpiar (Idealmente deberíamos solo añadir los nuevos, pero por ahora limpiamos)

            if (data.success && data.mensajes) {
                data.mensajes.forEach(msg => {
                    const div = document.createElement('div');
                    // Si el emisor soy yo (el admin logueado), clase 'sent', si no 'received'
                    // Nota: Necesitamos saber mi propio ID. Un truco es verificar el nombre.
                    // Pero mejor: En CSS, 'sent' es para mis mensajes.
                    // Asumimos que el backend devuelve quién envió.
                    
                    // IMPORTANTE: Para saber si es 'sent' o 'received', comparamos el emisor_id
                    // Como el JS no sabe mi ID de sesión fácilmente sin inyectarlo en PHP,
                    // usaremos una lógica simple: si emisor_id == currentContactId, es RECIBIDO.
                    const tipo = (msg.emisor_id == currentContactId) ? 'received' : 'sent';
                    
                    div.className = `message ${tipo}`;
                    div.innerHTML = `
                        ${msg.mensaje}
                        <span class="message-time">${new Date(msg.fecha_envio).toLocaleTimeString()}</span>
                    `;
                    box.appendChild(div);
                });
                // Auto scroll al final
                box.scrollTop = box.scrollHeight;
            }
        });
}

// 4. Enviar Mensaje
function enviarMensaje() {
    const input = document.getElementById('inputMensaje');
    const mensaje = input.value.trim();

    if (!mensaje || !currentContactId) return;

    fetch(`${API_URL}?action=enviar`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            receptor_id: currentContactId,
            mensaje: mensaje
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            input.value = ''; // Limpiar input
            cargarMensajes(); // Recargar chat
        } else {
            alert('Error al enviar');
        }
    });
}