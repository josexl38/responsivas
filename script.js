function mostrarFormulario(formulario) {
    // Oculta todos los formularios dentro del contenedor de formularios
    const formularios = document.querySelectorAll('.box > div');
    formularios.forEach(form => {
        form.classList.add('hidden');
    });

    // Muestra el formulario seleccionado
    const formularioMostrar = document.getElementById(`formulario_${formulario}`);
    if (formularioMostrar) {
        formularioMostrar.classList.remove('hidden');
        formularioMostrar.classList.add('slide-in');
    }
}

window.onload = function() {
    // Asegura que la sección de botones esté visible al cargar la página
    const buttonsSection = document.getElementById('buttons');
    if (buttonsSection) {
        buttonsSection.classList.remove('hidden');
    }
    
    // Inicializar validaciones
    initializeValidation();
    
    // Agregar clases required a campos obligatorios
    addRequiredLabels();
};

// Funcionalidad del menú desplegable
document.addEventListener('DOMContentLoaded', function() {
    const dropdownBtn = document.querySelector('.dropdown-btn');
    const dropdownContent = document.querySelector('.dropdown-content');

    if (dropdownBtn && dropdownContent) {
        dropdownBtn.addEventListener('click', function() {
            dropdownContent.classList.toggle('hidden');
        });

        window.addEventListener('click', function(e) {
            if (!dropdownBtn.contains(e.target)) {
                dropdownContent.classList.add('hidden');
            }
        });
    }
});

// Sistema de notificaciones toast
function showToast(message, type = 'success', duration = 3000) {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    // Mostrar toast
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Ocultar toast
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, duration);
}

// Validación en tiempo real
function initializeValidation() {
    // Validación de nómina (solo números)
    const nominaInputs = document.querySelectorAll('input[name="nomina"]');
    nominaInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 0 && this.value.length < 4) {
                this.setCustomValidity('La nómina debe tener al menos 4 dígitos');
            } else {
                this.setCustomValidity('');
            }
        });
    });
    
    // Validación de email
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.setCustomValidity('Por favor ingrese un email válido');
                showToast('Email no válido', 'error', 2000);
            } else {
                this.setCustomValidity('');
            }
        });
    });
    
    // Validación de campos de texto (no vacíos, sin caracteres especiales peligrosos)
    const textInputs = document.querySelectorAll('input[type="text"]:not([name="nomina"])');
    textInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Remover caracteres potencialmente peligrosos
            this.value = this.value.replace(/[<>'"]/g, '');
        });
        
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && this.value.trim().length < 2) {
                this.setCustomValidity('Este campo debe tener al menos 2 caracteres');
            } else {
                this.setCustomValidity('');
            }
        });
    });
    
    // Validación de número de serie (formato específico)
    const serieInputs = document.querySelectorAll('input[name="numero_serie"]');
    serieInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
        
        input.addEventListener('blur', function() {
            if (this.value && this.value.length < 5) {
                this.setCustomValidity('El número de serie debe tener al menos 5 caracteres');
            } else {
                this.setCustomValidity('');
            }
        });
    });
}

// Agregar asteriscos a campos requeridos
function addRequiredLabels() {
    const requiredInputs = document.querySelectorAll('input[required], select[required]');
    requiredInputs.forEach(input => {
        const label = document.querySelector(`label[for="${input.id}"]`);
        if (label && !label.classList.contains('required')) {
            label.classList.add('required');
        }
    });
}

// Mejorar el envío de formularios
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML += '<span class="loading-spinner"></span>';
                
                // Re-habilitar el botón después de 5 segundos por seguridad
                setTimeout(() => {
                    submitButton.disabled = false;
                    const spinner = submitButton.querySelector('.loading-spinner');
                    if (spinner) {
                        spinner.remove();
                    }
                }, 5000);
            }
        });
    });
});

// Función para confirmar eliminaciones
function confirmarEliminacion(tipo, identificador) {
    const mensaje = `¿Está seguro de que desea eliminar este ${tipo}?\n\nEsta acción no se puede deshacer.`;
    return confirm(mensaje);
}

// Función para validar antes de generar PDFs
function validarGeneracionPDF(nomina) {
    if (!nomina || nomina.trim() === '') {
        showToast('Por favor ingrese un número de nómina', 'error');
        return false;
    }
    
    if (nomina.length < 4) {
        showToast('La nómina debe tener al menos 4 dígitos', 'error');
        return false;
    }
    
    return true;
}

// Mejorar la experiencia de búsqueda
function mejorarBusqueda() {
    const buscarForm = document.querySelector('#formulario_buscar form');
    if (buscarForm) {
        const nominaInput = buscarForm.querySelector('input[name="buscar_nomina"]');
        const apellidosInput = buscarForm.querySelector('input[name="buscar_apellidos"]');
        
        if (nominaInput && apellidosInput) {
            // Limpiar el otro campo cuando se escribe en uno
            nominaInput.addEventListener('input', function() {
                if (this.value) {
                    apellidosInput.value = '';
                }
            });
            
            apellidosInput.addEventListener('input', function() {
                if (this.value) {
                    nominaInput.value = '';
                }
            });
        }
    }
}

// Inicializar mejoras de búsqueda cuando se muestra el formulario
document.addEventListener('DOMContentLoaded', function() {
    // Observar cambios en los formularios para aplicar mejoras
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                const target = mutation.target;
                if (target.id === 'formulario_buscar' && !target.classList.contains('hidden')) {
                    mejorarBusqueda();
                }
            }
        });
    });
    
    const formularios = document.querySelectorAll('[id^="formulario_"]');
    formularios.forEach(form => {
        observer.observe(form, { attributes: true });
    });
});
