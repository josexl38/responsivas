// Sistema de validación y mejoras para el proyecto PHP
document.addEventListener('DOMContentLoaded', function() {
    
    // Sistema de notificaciones toast
    function showToast(message, type = 'success') {
        // Remover toast existente
        const existingToast = document.querySelector('.toast');
        if (existingToast) {
            existingToast.remove();
        }
        
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        // Mostrar toast
        setTimeout(() => toast.classList.add('show'), 100);
        
        // Ocultar toast después de 4 segundos
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }
    
    // Validación de nómina (solo números, mínimo 4 dígitos)
    function validateNomina(input) {
        const value = input.value.trim();
        const isValid = /^\d{2,}$/.test(value);
        
        if (value === '') {
            input.classList.remove('field-valid', 'field-invalid');
            removeValidationMessage(input);
            return true; // Campo vacío es válido para búsqueda
        }
        
        if (isValid) {
            input.classList.add('field-valid');
            input.classList.remove('field-invalid');
            showValidationMessage(input, 'Nómina válida', 'success');
        } else {
            input.classList.add('field-invalid');
            input.classList.remove('field-valid');
            showValidationMessage(input, 'La nómina debe contener solo números y mínimo 2 dígitos', 'error');
        }
        
        return isValid || value === '';
    }
    
    // Validación de email
    function validateEmail(input) {
        const value = input.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isValid = emailRegex.test(value);
        
        if (value === '') {
            input.classList.remove('field-valid', 'field-invalid');
            removeValidationMessage(input);
            return true;
        }
        
        if (isValid) {
            input.classList.add('field-valid');
            input.classList.remove('field-invalid');
            showValidationMessage(input, 'Email válido', 'success');
        } else {
            input.classList.add('field-invalid');
            input.classList.remove('field-valid');
            showValidationMessage(input, 'Formato de email inválido', 'error');
        }
        
        return isValid || value === '';
    }
    
    // Capitalizar apellidos automáticamente
    function capitalizeNames(input) {
        const value = input.value;
        const capitalized = value.split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
            .join(' ');
        
        if (value !== capitalized) {
            input.value = capitalized;
        }
    }
    
    // Sanitización de caracteres peligrosos
    function sanitizeInput(input) {
        let value = input.value;
        // Remover caracteres potencialmente peligrosos
        value = value.replace(/[<>'"&]/g, '');
        input.value = value;
    }
    
    // Mostrar mensaje de validación
    function showValidationMessage(input, message, type) {
        removeValidationMessage(input);
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `validation-message validation-${type}`;
        messageDiv.textContent = message;
        
        input.parentNode.appendChild(messageDiv);
    }
    
    // Remover mensaje de validación
    function removeValidationMessage(input) {
        const existingMessage = input.parentNode.querySelector('.validation-message');
        if (existingMessage) {
            existingMessage.remove();
        }
    }
    
    // Aplicar validaciones a campos específicos
    const nominaInputs = document.querySelectorAll('input[name="nomina"], input[name="nomina_buscar"]');
    nominaInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Solo permitir números
            this.value = this.value.replace(/\D/g, '');
            validateNomina(this);
        });
        
        input.addEventListener('blur', function() {
            validateNomina(this);
        });
    });
    
    // Validación de emails
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('input', function() {
            sanitizeInput(this);
            validateEmail(this);
        });
        
        input.addEventListener('blur', function() {
            validateEmail(this);
        });
    });
    
    // Capitalización automática de nombres y apellidos
    const nameInputs = document.querySelectorAll('input[name="nombre"], input[name="apellidos"], input[name="apellidos_buscar"]');
    nameInputs.forEach(input => {
        input.addEventListener('input', function() {
            sanitizeInput(this);
            capitalizeNames(this);
        });
    });
    
    // Números de serie en mayúsculas
    const serialInputs = document.querySelectorAll('input[name*="serie"]');
    serialInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            sanitizeInput(this);
        });
    });
    
    // Sanitización general para otros campos de texto
    const textInputs = document.querySelectorAll('input[type="text"]:not([name="nomina"]):not([name="nombre"]):not([name="apellidos"]):not([name*="serie"])');
    textInputs.forEach(input => {
        input.addEventListener('input', function() {
            sanitizeInput(this);
        });
    });
    
    // Manejo de formularios con loading y validación
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
            
            // Validar campos requeridos
            const requiredFields = form.querySelectorAll('input[required], select[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('field-invalid');
                    showValidationMessage(field, 'Este campo es requerido', 'error');
                    isValid = false;
                } else {
                    field.classList.remove('field-invalid');
                    removeValidationMessage(field);
                }
            });
            
            // Validar nóminas específicamente
            const nominaFields = form.querySelectorAll('input[name="nomina"]');
            nominaFields.forEach(field => {
                if (field.value && !validateNomina(field)) {
                    isValid = false;
                }
            });
            
            // Validar emails específicamente
            const emailFields = form.querySelectorAll('input[type="email"]');
            emailFields.forEach(field => {
                if (field.value && !validateEmail(field)) {
                    isValid = false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showToast('Por favor corrige los errores en el formulario', 'error');
                return false;
            }
            
            // Mostrar loading en botón
            if (submitBtn) {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                
                // Restaurar botón después de 5 segundos (por si hay error)
                setTimeout(() => {
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                }, 5000);
            }
        });
    });
    
    // Búsqueda avanzada - limpiar campos mutuamente excluyentes
    const nominaBuscar = document.querySelector('input[name="nomina_buscar"]');
    const apellidosBuscar = document.querySelector('input[name="apellidos_buscar"]');
    
    if (nominaBuscar && apellidosBuscar) {
        nominaBuscar.addEventListener('input', function() {
            if (this.value.trim()) {
                apellidosBuscar.value = '';
                apellidosBuscar.classList.remove('field-valid', 'field-invalid');
                removeValidationMessage(apellidosBuscar);
            }
        });
        
        apellidosBuscar.addEventListener('input', function() {
            if (this.value.trim()) {
                nominaBuscar.value = '';
                nominaBuscar.classList.remove('field-valid', 'field-invalid');
                removeValidationMessage(nominaBuscar);
            }
        });
    }
    
    // Exportar resultados a CSV
    // Función de exportar removida según solicitud del usuario
    
    // Contador de resultados
    function updateResultsCounter() {
        const tables = document.querySelectorAll('table tbody');
        tables.forEach(table => {
            const rows = table.querySelectorAll('tr');
            if (rows.length > 0) {
                const counter = document.createElement('div');
                counter.className = 'search-info';
                counter.innerHTML = `📊 Se encontraron <strong>${rows.length}</strong> resultado(s)`;
                
                // Insertar contador antes de la tabla
                table.closest('table').parentNode.insertBefore(counter, table.closest('table'));
            }
        });
    }
    
    // Ejecutar contador si hay resultados
    updateResultsCounter();
    
    // Animaciones de entrada
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('slide-in');
    });
    
    // Mejorar dropdowns
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(dropdown => {
        const content = dropdown.querySelector('.dropdown-content');
        if (content) {
            dropdown.addEventListener('mouseenter', () => {
                content.style.display = 'block';
            });
            
            dropdown.addEventListener('mouseleave', () => {
                content.style.display = 'none';
            });
        }
    });
    
    // Confirmación para eliminaciones
    const deleteLinks = document.querySelectorAll('a[href*="eliminar"]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    });
    
    // Mostrar mensaje de bienvenida
    if (document.querySelector('h1')) {
        setTimeout(() => {
            showToast('Sistema de gestión cargado correctamente', 'success');
        }, 1000);
    }
    
    console.log('Sistema de validación y mejoras cargado correctamente');

// Función esencial para mostrar/ocultar formularios
function mostrarFormulario(tipo) {
    // Ocultar todos los formularios
    const formularios = [
        'formulario_inicio',
        'formulario_usuario', 
        'formulario_equipo',
        'formulario_hardware',
        'formulario_software',
        'formulario_buscar',
        'formulario_responsivas',
        'formulario_responsivas_software',
        'formulario_responsivas_hardware'
    ];
    
    formularios.forEach(function(id) {
        const elemento = document.getElementById(id);
        if (elemento) {
            elemento.classList.add('hidden');
        }
    });
    
    // Mostrar el formulario seleccionado
    const formularioSeleccionado = document.getElementById('formulario_' + tipo);
    if (formularioSeleccionado) {
        formularioSeleccionado.classList.remove('hidden');
        
        // Enfocar el primer campo del formulario
        const primerInput = formularioSeleccionado.querySelector('input, select');
        if (primerInput) {
            setTimeout(() => primerInput.focus(), 100);
        }
    }
    
    // Manejar búsqueda especial
    if (tipo === 'buscar') {
        const buscarForm = document.querySelector('#formulario_buscar form');
        if (buscarForm) {
            buscarForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const nomina = document.getElementById('buscar_nomina').value.trim();
                const apellidos = document.getElementById('buscar_apellidos').value.trim();
                
                if (!nomina && !apellidos) {
                    showToast('Por favor ingrese una nómina o apellidos para buscar', 'error');
                    return;
                }
                
                // Actualizar campos ocultos
                document.getElementById('hidden_nomina').value = nomina;
                document.getElementById('hidden_apellidos').value = apellidos;
                
                // Construir URL de búsqueda
                let url = 'procesar_datos.php?';
                if (nomina) {
                    url += 'buscar_nomina=' + encodeURIComponent(nomina);
                } else if (apellidos) {
                    url += 'buscar_apellidos=' + encodeURIComponent(apellidos);
                }
                
                // Redirigir a la búsqueda
                window.location.href = url;
            });
        }
        
        // Configurar botones de búsqueda
        const btnBuscar = document.querySelector('.btn-search');
        const btnLimpiar = document.querySelector('.btn-clear');
        
        if (btnBuscar) {
            btnBuscar.addEventListener('click', function(e) {
                e.preventDefault();
                buscarForm.dispatchEvent(new Event('submit'));
            });
        }
        
        if (btnLimpiar) {
            btnLimpiar.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('buscar_nomina').value = '';
                document.getElementById('buscar_apellidos').value = '';
                
                // Limpiar validaciones
                const inputs = document.querySelectorAll('#formulario_buscar input');
                inputs.forEach(input => {
                    input.classList.remove('field-valid', 'field-invalid');
                    removeValidationMessage(input);
                });
            });
        }
    }
}

// Hacer la función global para que funcione con onclick
window.mostrarFormulario = mostrarFormulario;
});

// Funciones globales para uso en PHP
window.showToast = function(message, type = 'success') {
    const existingToast = document.querySelector('.toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.add('show'), 100);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 4000);
};