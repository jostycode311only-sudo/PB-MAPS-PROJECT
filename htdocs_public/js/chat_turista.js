// htdocs_public/js/chat_turista.js

const API_URL = 'api_chat.php'; 

// Obtenemos el ID del operador desde el input oculto en el HTML
const receptorInput = document.getElementById('receptorId');
const receptorId = receptorInput ? receptorInput.value : 0;
let chatInterval = null;

document.addEventListener('DOMContentLoaded', () => {
    // 1. Verificar si tenemos un ID válido
    if (!receptorId || receptorId == 0) {
        console.error("Error: No se encontró el ID del operador.");
        const caja = document.getElementById('cajaMensajes');
        if(caja) caja.innerHTML = '<div class="alert alert-danger">Error: Operador no válido.</div>';
        return;
    }

    console.log("Iniciando chat con Operador ID:", receptorId);

    // 2. Cargar mensajes inmediatamente
    cargarMensajes();
    
    // 3. Activar actualización automática (cada 3 segundos)
    chatInterval = setInterval(cargarMensajes, 3000);

    // 4. Configurar el formulario de envío
    const form = document.getElementById('formChatTurista');
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault(); // Evitar recarga de página
            enviarMensaje();
        });
    }

    // Actualizar título visualmente
    const titulo = document.getElementById('chatTitle');
    if(titulo) titulo.textContent = "Chat con la Agencia";
});

function cargarMensajes() {
    // Petición al archivo puente
    fetch(`${API_URL}?action=leer&contacto_id=${receptorId}`)
        .then(response => {
            // Si el archivo puente no existe o falla, lanzará error aquí
            if (!response.ok) {
                throw new Error(`Error de conexión: ${response.status}`);
            }
            return response.text(); // Leemos como texto primero para ver si hay errores de PHP
        })
        .then(text => {
            try {
                return JSON.parse(text); // Intentamos convertir a JSON
            } catch (e) {
                console.error("Respuesta inválida del servidor:", text);
                throw new Error("Error técnico en el servidor.");
            }
        })
        .then(data => {
            const box = document.getElementById('cajaMensajes');
            if (!box) return;

            // Quitar spinner de carga si existe
            if(box.querySelector('.spinner-border')) box.innerHTML = '';

            if (data.success && data.mensajes) {
                // Limpiamos la caja (estrategia simple)
                box.innerHTML = ''; 
                
                if (data.mensajes.length === 0) {
                    box.innerHTML = '<div class="text-center p-4 text-muted">Aún no hay mensajes. ¡Di hola!</div>';
                    return;
                }

                // Dibujar cada mensaje
                data.mensajes.forEach(msg => {
                    const div = document.createElement('div');
                    // Si el emisor es la agencia, es recibido. Si soy yo, es enviado.
                    const tipo = (msg.emisor_id == receptorId) ? 'received' : 'sent';
                    
                    div.className = `message ${tipo}`;
                    div.innerHTML = `
                        ${msg.mensaje}
                        <span class="message-time">
                            ${new Date(msg.fecha_envio).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                        </span>
                    `;
                    box.appendChild(div);
                });
                
                // Bajar el scroll al final
                box.scrollTop = box.scrollHeight;
            } else {
                console.warn("API Error:", data.error);
            }
        })
        .catch(err => {
            console.error("Error al cargar mensajes:", err);
            // No mostramos alerta al usuario en cada ciclo para no molestar, solo en consola
        });
}

function enviarMensaje() {
    const input = document.getElementById('inputMensaje');
    const mensaje = input.value.trim();

    if (!mensaje) return;

    // Desactivar botón para evitar doble clic
    const btn = document.querySelector('#formChatTurista button');
    if(btn) btn.disabled = true;

    fetch(`${API_URL}?action=enviar`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            receptor_id: receptorId,
            mensaje: mensaje
        })
    })
    .then(response => response.text())
    .then(text => {
        try {
            return JSON.parse(text);
        } catch (e) {
            throw new Error("Respuesta inválida al enviar.");
        }
    })
    .then(data => {
        if (data.success) {
            input.value = ''; // Limpiar input
            cargarMensajes(); // Recargar chat inmediatamente
        } else {
            alert("Error al enviar: " + (data.error || "Desconocido"));
        }
    })
    .catch(err => {
        console.error(err);
        alert("Error de conexión al enviar mensaje.");
    })
    .finally(() => {
        // Reactivar botón
        if(btn) btn.disabled = false;
    });
}