// htdocs_public/js/main.js

document.addEventListener('DOMContentLoaded', () => {
    const lugarModal = document.getElementById('lugarModal');
    const lugarForm = document.getElementById('lugarForm');
    const modalTitle = document.getElementById('lugarModalLabel');
    const btnGuardar = document.getElementById('btnGuardar');
    const btnCrearLugar = document.getElementById('btnCrearLugar');

    const controllerUrl = '/PB-MAPS-PROJECT/app/Controllers/LugarController.php';

    function resetForm() {
        lugarForm.reset();
        document.getElementById('id_lugar').value = '';
        modalTitle.textContent = 'Crear Nuevo Lugar Turístico';
        btnGuardar.textContent = 'Guardar Cambios';
        
        // CORRECCIÓN 1: Usamos un atributo personalizado para controlar el modo
        lugarForm.dataset.method = 'POST'; 
        
        // Restauramos el action para que la creación funcione normal (si usas POST normal)
        lugarForm.setAttribute('action', controllerUrl);
    }

    if (btnCrearLugar) {
        btnCrearLugar.addEventListener('click', resetForm);
    }

    // 2. Manejar la apertura del Modal para EDITAR
    document.querySelectorAll('.btn-edit-lugar').forEach(button => {
        button.addEventListener('click', async (e) => {
            const id = e.currentTarget.getAttribute('data-id');
            resetForm(); 

            try {
                const response = await fetch(`${controllerUrl}?action=get&id=${id}`);
                const result = await response.json();

                if (result.status === 200 && result.lugar) {
                    const lugar = result.lugar; 
                    
                    // Llenar formulario
                    document.getElementById('id_lugar').value = lugar.id;
                    document.getElementById('nombre_lugar').value = lugar.nombre; 
                    document.getElementById('descripcion').value = lugar.descripcion; 
                    document.getElementById('url_imagen').value = lugar.url_imagen || ''; 

                    // Ajustar Modal
                    modalTitle.textContent = `Editar Lugar: ${lugar.nombre}`;
                    btnGuardar.textContent = 'Actualizar Cambios';
                    
                    // CORRECCIÓN 2: Marcamos el formulario como PUT usando dataset
                    lugarForm.dataset.method = 'PUT'; 
                    lugarForm.removeAttribute('action'); 

                    const modal = new bootstrap.Modal(lugarModal);
                    modal.show();
                } else {
                    alert(`Error al obtener datos: ${result.message}`);
                }
            } catch (error) {
                console.error('Error al intentar obtener datos:', error);
                alert('Error al conectar con el servidor.');
            }
        });
    });

    // 3. Manejar el ENVÍO del Formulario
    lugarForm.addEventListener('submit', async (e) => {
        
        // CORRECCIÓN 3: Verificamos nuestro atributo personalizado
        if (lugarForm.dataset.method === 'PUT') {
            e.preventDefault(); // ¡Ahora sí se detendrá el envío normal!
            
            const idLugar = document.getElementById('id_lugar').value;

            const formData = {
                id_lugar: idLugar,
                nombre: document.getElementById('nombre_lugar').value,
                descripcion: document.getElementById('descripcion').value,
                url_imagen: document.getElementById('url_imagen').value,
            };

            try {
                const response = await fetch(controllerUrl, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData) 
                });

                const result = await response.json();

                if (response.ok) { 
                    alert(`¡Éxito! ${result.message}`);
                    // Ocultar modal y recargar
                    const modalInstance = bootstrap.Modal.getInstance(lugarModal);
                    if (modalInstance) modalInstance.hide();
                    window.location.href = `${window.location.pathname}?success=lugar_actualizado`;
                } else { 
                    alert(`Error (${result.status}): ${result.message}`);
                }

            } catch (error) {
                console.error('Error al enviar actualización:', error);
                alert('Ocurrió un error de red al intentar actualizar.');
            }
        }
        // Si no es PUT, dejamos que el formulario se envíe normalmente como POST (Crear)
    });

    // 4. Manejar Eliminación (Se mantiene igual)
    document.querySelectorAll('.btn-delete-lugar').forEach(button => {
        button.addEventListener('click', async (e) => {
            const id = e.currentTarget.getAttribute('data-id');
            if (confirm('¿Estás seguro de eliminar este lugar?')) {
                try {
                    const response = await fetch(`${controllerUrl}?id=${id}`, { method: 'DELETE' });
                    const result = await response.json();
                    if (response.ok) {
                        alert(result.message);
                        window.location.reload();
                    } else {
                        alert('Error al eliminar');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        });
    });
});