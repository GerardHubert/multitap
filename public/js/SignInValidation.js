class SignInValidation {

    constructor() {

        this.usernameInput = document.querySelector('.username_input');
        this.emailInput = document.querySelector('.email_input');
        this.passwordInput = document.querySelector('.password_input');
        this.passwordConfirm = document.querySelector('.password_confirm_input');
        this.inscriptionButton = document.querySelector('.inscription_button');

        this.usernameRegex = /^[A-Za-zéèçàâäêëîïôöòûüùñ_0-9]?[\s?\-?a-zéèçàâäêëîïôöòûüùñ_0-9]+?$/;
        this.emailRegex = /^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
        this.passwordRegex = /(?=.*[a-z]+)(?=.*[0-9]+)(?=.*[A-Z]+)/;

        document.addEventListener('keyup', (KeyboardEvent) => {
            this.checkInputs(KeyboardEvent);
            this.enableButton();
        })
    }

    checkInputs(event) {
        
        switch (document.activeElement) {
            case this.usernameInput:
                if (this.usernameRegex.test(this.usernameInput.value) === false) {
                    this.usernameInput.className = 'username_input_invalid';
                } else if (this.usernameRegex.test(this.usernameInputValue) === true) {
                    this.usernameInput.className = 'username_input_valid';
                }
            break;
            case this.emailInput:
                if (this.emailRegex.test(this.emailInput.value) === false) {
                    this.emailInput.className = 'email_input_invalid';
                } else if (this.emailRegex.test(this.emailInput.value) === true) {
                    this.emailInput.className = 'email_input_valid';
                }
            break;
            case this.passwordInput:
                if (this.passwordRegex.test(this.passwordInput.value) === false || this.passwordInput.value.length < 6) {
                    this.passwordInput.className = 'password_input_invalid';
                } else if (this.passwordRegex.test(this.passwordInput.value) === true) {
                    this.passwordInput.className = 'password_input_valid';
                }
            break;
            case this.passwordConfirm:
                if (this.passwordConfirm.value !== this.passwordInput.value) {
                    this.passwordConfirm.className = 'password_confirm_input_invalid';
                }   else if (this.passwordConfirm.value === this.passwordInput.value) {
                    this.passwordConfirm.className = 'password_confirm_input_valid';
                }
            break;
        }
    }

    enableButton() {

        if (this.usernameInput.className === 'username_input_valid' && this.emailInput.className === 'email_input_valid' && this.passwordInput.className === 'password_input_valid' && this.passwordConfirm.className === 'password_confirm_input_valid' && this.passwordInput.value === this.passwordConfirm.value) {
            this.inscriptionButton.removeAttribute('disabled');
        } else {
            this.inscriptionButton.setAttribute('disabled', '');
        }
        
    }
}

let signInValidation = new SignInValidation();