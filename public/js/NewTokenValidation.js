class NewTokenValidation {

    constructor() {

        this.usernameInput = document.querySelector('.username_input');
        this.emailInput = document.querySelector('.email_input');
        this.submitButton = document.querySelector('.inscription_button');
        
        this.usernameRegex = /^[A-Za-zéèçàâäêëîïôöòûüùñ_0-9]?[\s?\-?a-zéèçàâäêëîïôöòûüùñ_0-9]+?$/;
        this.emailRegex = /^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;

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
        }
    }

    enableButton() {

        if (this.usernameInput.className === 'username_input_valid' && this.emailInput.className === 'email_input_valid') {
            this.submitButton.removeAttribute('disabled');
        } else {
            this.submitButton.setAttribute('disabled', '');
        }
        
    }
}

let newTokenValidation = new NewTokenValidation();