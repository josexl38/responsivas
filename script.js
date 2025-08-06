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
    }
}

window.onload = function() {
    // Asegura que la sección de botones esté visible al cargar la página
    const buttonsSection = document.getElementById('buttons');
    if (buttonsSection) {
        buttonsSection.classList.remove('hidden');
    }
};

// Funcionalidad del menú desplegable
document.addEventListener('DOMContentLoaded', function() {
    const dropdownBtn = document.querySelector('.dropdown-btn');
    const dropdownContent = document.querySelector('.dropdown-content');

    dropdownBtn.addEventListener('click', function() {
        dropdownContent.classList.toggle('hidden');
    });

    window.addEventListener('click', function(e) {
        if (!dropdownBtn.contains(e.target)) {
            dropdownContent.classList.add('hidden');
        }
    });
});

