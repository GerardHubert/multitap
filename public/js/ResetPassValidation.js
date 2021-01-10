class ResetPassValidation {

    constructor() {

        this.passwordInput = document.querySelector('.password_input');
        this.confirmPasswordInput = document.querySelector('.password_confirm_input');
        this.submitButton = document.querySelector('.update_pass_button');

        this.passwordRegex = /(?=.*[a-z]+)(?=.*[0-9]+)(?=.*[A-Z]+)/;

        document.addEventListener('keyup', (KeyboardEvent) => {
            this.checkInputs(KeyboardEvent);
            this.enableButton();
        })
    }

    checkInputs(event) {
        
        switch (document.activeElement) {
            
            case this.passwordInput:
                if (this.passwordRegex.test(this.passwordInput.value) === false || this.passwordInput.value.length < 6) {
                    this.passwordInput.className = 'password_input_invalid';
                } else if (this.passwordRegex.test(this.passwordInput.value) === true) {
                    this.passwordInput.className = 'password_input_valid';
                }
            break;
            case this.confirmPasswordInput:
                if (this.confirmPasswordInput.value !== this.passwordInput.value) {
                    this.confirmPasswordInput.className = 'password_confirm_input_invalid';
                }   else if (this.confirmPasswordInput.value === this.passwordInput.value) {
                    this.confirmPasswordInput.className = 'password_confirm_input_valid';
                }
            break;
        }
    }

    enableButton() {

        if (this.passwordInput.className === 'password_input_valid' && this.confirmPasswordInput.className === 'password_confirm_input_valid' && this.passwordInput.value === this.confirmPasswordInput.value) {
            this.submitButton.removeAttribute('disabled');
        } else {
            this.submitButton.setAttribute('disabled', '');
        }
        
    }
}

let resetPassValidation = new ResetPassValidation();