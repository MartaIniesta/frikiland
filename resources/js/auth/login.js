document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.container');
    const registerBtn = document.querySelector('.register-btn');
    const loginBtn = document.querySelector('.login-btn');

    if (container && registerBtn && loginBtn) {
        registerBtn.addEventListener('click', () => {
            container.classList.add('active');
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove('active');
        });
    }
});

function togglePassword(button) {
    const wrapper = button.closest('.input-wrapper');
    if (!wrapper) return;

    const input = wrapper.querySelector('input');
    const icon = button.querySelector('i');

    if (!input || !icon) return;

    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';

    icon.classList.toggle('bx-hide', !isPassword);
    icon.classList.toggle('bx-show', isPassword);
}

window.togglePassword = togglePassword;
