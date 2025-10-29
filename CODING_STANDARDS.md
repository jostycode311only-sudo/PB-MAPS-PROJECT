1. Introducción: Objetivo del Estándar 🎯
El objetivo principal de este documento es establecer un conjunto de reglas, convenciones y buenas prácticas de codificación para el desarrollo del proyecto PB-MAPS. Al utilizar Bootstrap, PHP (puro) y MySQL/XAMPP, la consistencia en el código es crucial para la mantenibilidad y la colaboración.

2. Estructura de Carpetas del Proyecto 📂
La estructura se basa en el patrón Modelo-Vista-Controlador (MVC), manteniendo una clara separación entre los activos públicos (public/) y la lógica del servidor (app/).

PB-MAPS-PROJECT/
├── app/                       # Lógica de Backend (Controladores y Modelos PHP)
│   ├── Controllers/           # Lógica de la aplicación (Manejo de rutas y datos)
│   └── Models/                # Clases para la interacción con la Base de Datos (CRUD)
├── public/                    # RAÍZ ACCESIBLE POR EL SERVIDOR (htdocs/ de XAMPP)
│   ├── css/                   # Hojas de estilo personalizadas (main.css)
│   ├── js/                    # Archivos JavaScript personalizados (main.js)
│   ├── images/                # Activos de imágenes
│   ├── admin/                 # Vistas del Panel de Administración (dashboard.html, etc.)
│   └── index.html             # Vista principal (Página pública)
├── CODING_STANDARDS.md        # Este documento
└── .gitignore                 # Excluye archivos como credenciales y temporales


3. Estándares para HTML y CSS 🎨
El foco está en la limpieza del marcado, el orden y el uso de indentación de 4 espacios.

Estándar,Correcto ✅,Incorrecto ❌
Indentación,4 espacios para anidar etiquetas.,Uso de tabs o inconsistencia (mezclar 2 y 4 espacios).
Comentarios,Usar comentarios de bloque en mayúsculas para separar secciones principales.,Dejar bloques largos sin identificar o usar comentarios vagos.
,`,`
Nombres de Clases (CSS),kebab-case para clases personalizadas y archivos (minúsculas y guiones).,cardHotelInfo o Clase_Principal.
,.pb-maps-header,.PB_MAPS_Header
Semántica,"Usar etiquetas semánticas (<header>, <nav>, <main>, <section>).",Usar solo <div> para estructurar todo el contenido.


4. Estándares para PHP (Puro) 🐘
Adoptaremos el estándar PSR-12 (Extended Coding Style) como base para garantizar la legibilidad del backend.

Estándar	Correcto ✅	Incorrecto ❌
Convención de Nombres	Clases: PascalCase (ej. LugarTuristicoModel.php). Variables/Métodos: camelCase (ej. $datosLugar, crearNuevoLugar()).	$datos_lugar, CrearNuevoLugar() o lugarturistico_model.php.
Separación de Lógica (MVC)	El código PHP que accede a la base de datos debe residir en app/. La vista (.html) no debe contener lógica PHP.	Mezclar código: Incluir $mysqli->query(...) dentro de dashboard.html.
Seguridad	Usar Sentencias Preparadas (Prepared Statements) en todas las consultas con datos de usuario.	Concatenar variables de usuario directamente en la consulta SQL (ej. INSERT INTO ... VALUES ('$nombre')).
Manejo de Errores	Usar try-catch para manejar errores críticos (ej. conexión a MySQL).	Dejar que los errores de conexión o SQL se muestren en el frontend (die() o mensajes de error directos).


5. Estándares para Bootstrap (La Capa de Presentación) 📐
Bootstrap se usa para la apariencia y la responsividad.

Estándar,Objetivo,Aplicación en PB-MAPS
Uso de CDN,Instalar CSS en el <head> y JavaScript al final del <body>.,Asegurar que el bootstrap.bundle.min.js esté justo antes de la etiqueta </body>.
Grid System,"Usar el sistema de Grid para toda la disposición de contenido (tarjetas, listados).",Usar la estructura row row-cols-md-3 para que los elementos se organicen automáticamente en 3 columnas en pantallas medianas o grandes.
Clases de Color,"Usar las clases de color y contexto de Bootstrap (ej. primary, danger, warning).","Acciones CRUD: btn-danger (Eliminar), btn-warning (Editar), btn-success (Crear)."
Sobrescribir CSS,Las clases personalizadas (main.css) deben ser mínimas y solo para ajustes estéticos.,"EVITAR: Sobrescribir estilos base como h1 o p. HACER: Usar clases utilitarias de Bootstrap (text-center, my-5)."

6. Buenas Prácticas en XAMPP y Base de Datos (MySQL)
Área,Buena Práctica,Aplicación en PB-MAPS
XAMPP Folder,El proyecto debe residir dentro de la carpeta htdocs/ de XAMPP para que Apache pueda servir los archivos.,Acceso local al proyecto vía localhost/PB-MAPS-PROJECT.
Variables de Entorno,"NUNCA guardar credenciales de base de datos (usuario, contraseña) directamente en archivos que puedan ser públicos o subidos al repositorio.",Crear un archivo de configuración separado (ej. config/db.php) y añadirlo a .gitignore.
Nomenclatura SQL,Usar minúsculas y guiones bajos (snake_case) en tablas y columnas.,"Tablas: usuario, agencia_operadora. Columnas: nombre_usuario, fecha_creacion."
Cotejamiento,Usar utf8mb4_general_ci para soporte completo del idioma español.,"Asegura que acentos, tildes y la letra 'ñ' se almacenen y busquen correctamente."

7. Pasos Finales: Validación Práctica
Para la validación del estándar, el módulo a implementar y revisar será el Formulario de Registro de un Lugar Turístico (el Modal en dashboard.html).

Implementación: Aplicar los estándares de HTML (indentación, comentarios de bloque, semántica) y Bootstrap (clases de formulario y botones).

Code Review: Realizar una revisión cruzada para asegurar el cumplimiento de la nomenclatura y las buenas prácticas definidas en este documento.
