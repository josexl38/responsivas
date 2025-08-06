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
            if (this.value.length > 0 && this.value.length < 2) {
                this.setCustomValidity('La nómina debe tener al menos 2 dígitos');
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
    
    if (nomina.length < 2) {
        showToast('La nómina debe tener al menos 2 dígitos', 'error');
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
    // Inicializar búsqueda avanzada
    initializeAdvancedSearch();
    
    // Observar cambios en los formularios para aplicar mejoras
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                const target = mutation.target;
                if (target.id === 'formulario_buscar' && !target.classList.contains('hidden')) {
                    mejorarBusqueda();
                    initializeAdvancedSearch();
                }
            }
        });
    });
    
    const formularios = document.querySelectorAll('[id^="formulario_"]');
    formularios.forEach(form => {
        observer.observe(form, { attributes: true });
    });
});

// Sistema de búsqueda avanzada
function initializeAdvancedSearch() {
    // Crear filtros dinámicos para diferentes tipos de búsqueda
    const searchForm = document.querySelector('#formulario_buscar form');
    if (searchForm && !searchForm.classList.contains('advanced-initialized')) {
        searchForm.classList.add('advanced-initialized');
        enhanceSearchForm(searchForm);
    }
}

function enhanceSearchForm(form) {
    // Conectar los campos visibles con los campos ocultos del formulario
    const visibleNomina = document.querySelector('#buscar_nomina');
    const visibleApellidos = document.querySelector('#buscar_apellidos');
    const hiddenNomina = form.querySelector('input[name="buscar_nomina"]');
    const hiddenApellidos = form.querySelector('input[name="buscar_apellidos"]');
    
    if (visibleNomina && hiddenNomina) {
        visibleNomina.addEventListener('input', function() {
            hiddenNomina.value = this.value;
        });
    }
    
    if (visibleApellidos && hiddenApellidos) {
        visibleApellidos.addEventListener('input', function() {
            hiddenApellidos.value = this.value;
        });
    }
    
    // Manejar el envío del formulario desde los botones externos
    const searchBtn = document.querySelector('.btn-search');
    if (searchBtn) {
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Validar que al menos un campo tenga contenido
            const nominaValue = visibleNomina ? visibleNomina.value.trim() : '';
            const apellidosValue = visibleApellidos ? visibleApellidos.value.trim() : '';
            
            if (!nominaValue && !apellidosValue) {
                showToast('Por favor ingrese una nómina o apellidos para buscar', 'error');
                return;
            }
            
            // Actualizar campos ocultos y enviar formulario
            if (hiddenNomina) hiddenNomina.value = nominaValue;
            if (hiddenApellidos) hiddenApellidos.value = apellidosValue;
            
            form.submit();
        });
    }
    
    // Manejar botón de limpiar
    const clearBtn = document.querySelector('.btn-clear');
    if (clearBtn) {
        clearBtn.addEventListener('click', function(e) {
            if (this.textContent.includes('Limpiar')) {
                e.preventDefault();
                if (visibleNomina) visibleNomina.value = '';
                if (visibleApellidos) visibleApellidos.value = '';
                if (hiddenNomina) hiddenNomina.value = '';
                if (hiddenApellidos) hiddenApellidos.value = '';
                showToast('Campos limpiados', 'success', 1500);
            }
        });
    }
    
    // Agregar funcionalidad de limpieza automática entre campos
    const inputs = [visibleNomina, visibleApellidos].filter(input => input);
    inputs.forEach((input, index) => {
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                // Limpiar otros campos cuando se escribe en uno
                inputs.forEach((otherInput, otherIndex) => {
                    if (index !== otherIndex) {
                        otherInput.value = '';
                        // También limpiar el campo oculto correspondiente
                        if (otherInput === visibleNomina && hiddenNomina) {
                            hiddenNomina.value = '';
                        } else if (otherInput === visibleApellidos && hiddenApellidos) {
                            hiddenApellidos.value = '';
                        }
                    }
                });
            }
        });
    });
    
    // Agregar validación en tiempo real
    if (visibleNomina) {
        visibleNomina.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value && this.value.length < 2) {
                this.setCustomValidity('Ingrese al menos 2 dígitos');
            } else {
                this.setCustomValidity('');
            }
        });
    }
    
    // Mejorar búsqueda por apellidos
    if (visibleApellidos) {
        visibleApellidos.addEventListener('input', function() {
            // Capitalizar primera letra de cada palabra
            this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());
            if (this.value && this.value.length < 2) {
                this.setCustomValidity('Ingrese al menos 2 caracteres');
            } else {
                this.setCustomValidity('');
            }
        });
    }
}

// Función para mejorar la visualización de tablas
function optimizeTableDisplay() {
    const tables = document.querySelectorAll('table');
    tables.forEach(table => {
        // Envolver tabla en contenedor si no existe
        if (!table.parentElement.classList.contains('table-container')) {
            const container = document.createElement('div');
            container.className = 'table-container';
            table.parentNode.insertBefore(container, table);
            container.appendChild(table);
        }
        
        // Mejorar enlaces de acción
        const actionCells = table.querySelectorAll('td');
        actionCells.forEach(cell => {
            const links = cell.querySelectorAll('a');
            if (links.length > 1) {
                const linksContainer = document.createElement('div');
                linksContainer.className = 'action-links';
                
                links.forEach(link => {
                    if (link.textContent.toLowerCase().includes('editar')) {
                        link.classList.add('edit');
                    } else if (link.textContent.toLowerCase().includes('eliminar')) {
                        link.classList.add('delete');
                    }
                    linksContainer.appendChild(link);
                });
                
                cell.innerHTML = '';
                cell.appendChild(linksContainer);
            }
        });
    });
}

// Función para agregar información de resultados
function addResultsInfo(count, searchTerm = '') {
    const existingInfo = document.querySelector('.results-info');
    if (existingInfo) {
        existingInfo.remove();
    }
    
    const info = document.createElement('div');
    info.className = 'results-info';
    
    let message = `Se encontraron ${count} resultado${count !== 1 ? 's' : ''}`;
    if (searchTerm) {
        message += ` para "${searchTerm}"`;
    }
    
    info.textContent = message;
    
    const table = document.querySelector('table');
    if (table) {
        table.parentNode.insertBefore(info, table);
    }
}

// Ejecutar optimizaciones cuando se cargan resultados
window.addEventListener('load', function() {
    optimizeTableDisplay();
    
    // Contar resultados si hay tabla
    const table = document.querySelector('table');
    if (table) {
        const rows = table.querySelectorAll('tbody tr, tr:not(:first-child)');
        if (rows.length > 0) {
            addResultsInfo(rows.length);
        }
    }
});

// Función para exportar datos a CSV
function exportToCSV(filename = 'datos_exportados.csv') {
    const table = document.querySelector('table');
    if (!table) {
        showToast('No hay datos para exportar', 'error');
        return;
    }
    
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const rowData = [];
        cols.forEach(col => {
            // Limpiar el texto y escapar comillas
            let text = col.textContent.trim().replace(/"/g, '""');
            rowData.push(`"${text}"`);
        });
        csv.push(rowData.join(','));
    });
    
    // Crear y descargar archivo
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    
    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        showToast('Datos exportados exitosamente', 'success');
    }
}

// Agregar botón de exportación automáticamente
function addExportButton() {
    const table = document.querySelector('table');
    if (table && !document.querySelector('.export-btn')) {
        const exportBtn = document.createElement('button');
        exportBtn.textContent = 'Exportar a CSV';
        exportBtn.className = 'btn-search export-btn';
        exportBtn.style.marginLeft = '10px';
        exportBtn.onclick = () => exportToCSV();
        
        // Buscar un lugar apropiado para insertar el botón
        const buttonsContainer = document.querySelector('.filter-buttons') || 
                                document.querySelector('button').parentNode;
        if (buttonsContainer) {
            buttonsContainer.appendChild(exportBtn);
        }
    }
}