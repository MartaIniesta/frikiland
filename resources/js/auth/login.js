document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.container');
    const registerBtn = document.querySelector('.register-btn');
    const loginBtn = document.querySelector('.login-btn');

    if (!container || !registerBtn || !loginBtn) return;

    registerBtn.addEventListener('click', () => {
        container.classList.add('active');
    });

    loginBtn.addEventListener('click', () => {
        container.classList.remove('active');
    });
});

/*==== MUESTRA LA CONTRASEÑA ====*/
function togglePassword() {
    const passwordInput = document.getElementById('password');
    if (!passwordInput) return;

    passwordInput.type =
        passwordInput.type === 'password' ? 'text' : 'password';
}

window.togglePassword = togglePassword;
