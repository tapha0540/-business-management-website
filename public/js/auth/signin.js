
const toggleFormLinks = document.querySelectorAll('.toggle-form');
const loginForm = document.getElementById('login-form');
const signupForm = document.getElementById('signup-form');
const signupErrorMsg = document.getElementById('signup-error-msg');
const loginErrorMsg = document.getElementById('login-error-msg');

const toggleForms = (e) => {
  e.preventDefault();
  loginForm.classList.toggle('d-none');
  signupForm.classList.toggle('d-none');
}

toggleFormLinks.forEach(link => {
  link.addEventListener('click', toggleForms);
})
signupForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    signupErrorMsg.textContent = '';
;
    if (signupForm['signup-password'].value !==  signupForm['signup-confirm-password'].value) {
        signupErrorMsg.textContent = 'Les mots de passe ne correspondent pas.';
        return;
    }
    const formData = {
        first_name: signupForm['first-name'].value,
        last_name: signupForm['last-name'].value,
        email: signupForm['signup-email'].value,
        password: signupForm['signup-password'].value,
    }; 
    const response = await fetchApi('http://localhost:8081/routes/auth/signup.php', 'POST', formData);
    if (response.success) {
        window.location.href = '/html/dashboard.html'; 
    } else {
        signupErrorMsg.textContent = response.message || 'Erreur lors de l\'inscription.';
    }
});

loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    loginErrorMsg.textContent = '';
    const formData = {
        email: loginForm.email.value,
        password: loginForm.password.value,
    }; 
    const response = await fetchApi('http://localhost:8081/routes/auth/login.php', 'POST', formData);
    if (response.success) {
        window.location.href = '/html/dashboard.html';
    } else {
        loginErrorMsg.textContent = response.message || 'Erreur lors de la connexion.';
    }
});
