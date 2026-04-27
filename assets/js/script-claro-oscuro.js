const boton = document.getElementById('toggle-tema');
const body = document.body;

if (localStorage.getItem('tema') === 'oscuro') {
    body.classList.add('dark-mode');
    boton.textContent = '☀️';
}

boton.addEventListener('click', () => {
    body.classList.toggle('dark-mode');

    if (body.classList.contains('dark-mode')) {
        localStorage.setItem('tema', 'oscuro');
        boton.textContent = '☀️';
    } else {
        localStorage.setItem('tema', 'claro');
        boton.textContent = '🌙';
    }
});
