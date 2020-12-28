class LoginValidation {

    constructor() {

        this.usernameElement = document.querySelector('.username_input');
        this.passElement = document.querySelector('.password_input');
        this.loginButton = document.querySelector('.login_button');

        this.usernameRegex = /^[A-Za-zéèçàâäêëîïôöòûüùñ_0-9][\s?\-?a-zéèçàâäêëîïôöòûüùñ_0-9]+?$/
        this.emailRegex = /^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
        this.passwordRegex = /(?=.*[a-z]+)(?=.*[0-9]+)(?=.*[A-Z]+)/

        document.addEventListener('keyup', (KeyboardEvent) => {
            this.checkInputs(KeyboardEvent);
        })

        this.passElement.addEventListener('focusout', () => {
            this.passElement.className = 'password_input_neutral';
        })
    }

    checkInputs(event) {

        switch (document.activeElement) {

            case this.usernameElement:
                if (this.usernameElement.value.length < 1) {
                    this.usernameElement.className = 'username_input_invalid';
                }
                    else {
                        this.usernameElement.className = 'username_input_valid';
                        this.enableLogInButton();
                    }
            break;

            case this.passElement:
                if (this.passElement.value.length < 6) {
                    this.passElement.className = 'password_input_invalid';
                }   else {
                        this.passElement.className = 'password_input_valid';
                        this.enableLogInButton();
                    }
            break;
        }
    }

    enableLogInButton(element) {

        if (this.usernameElement.className === 'username_input_valid' && this.passElement.className === 'password_input_valid') {
            this.loginButton.removeAttribute('disabled');
        }

    }

}

let loginValidation = new LoginValidation();