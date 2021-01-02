class UserParameters {

    constructor() {
        
        this.submitButton = document.querySelector('.update_rank_button');
        this.memberRadio = document.querySelector('#member');
        this.reviewerRadio = document.querySelector('#reviewer');
        this.editorRadio = document.querySelector('#editor');
        this.adminRadio = document.querySelector('#admin');
        this.boxes = document.getElementsByClassName('box');
        this.alertContainer = document.querySelector('disabled_demand');
        
        this.memberRadio.addEventListener('click', () => {
            this.checkRadio(this.memberRadio.value);
        });

        this.reviewerRadio.addEventListener('click', () => {
            this.checkRadio(this.reviewerRadio.value);
        });

        this.editorRadio.addEventListener('click', () => {
            this.checkRadio(this.editorRadio.value);
        });

        this.adminRadio.addEventListener('click', () => {
            this.checkRadio(this.adminRadio.value);
        });

        

    }

    checkRadio(choice) {

        this.submitButton.removeAttribute('disabled');

    }

}

let userParameters = new UserParameters;