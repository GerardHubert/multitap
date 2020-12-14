class UserManagement {
    constructor() {
        this.updateUsernameButton = document.querySelector('.update_username_button');
        this.updateEmailButton = document.querySelector('.update_email_button');
        this.updatePassButton = document.querySelector('.update_pass_button');
        this.updateRankButton = document.querySelector('.update_rank_button');
        this.deleteAccountButton = document.querySelector('.delete_account_button');

        this.updateUsernameButton.addEventListener('click', () =>
            this.updateUsernameForm());

        this.updateEmailButton.addEventListener('click', () =>
            this.updateEmailForm());

        this.updatePassButton.addEventListener('click', () =>
            this.updatePassForm());

        this.updateRankButton.addEventListener('click', () =>
            this.updateEmailForm());

        this.deleteAccountButton.addEventListener('click', () =>
            this.deleteAccountForm());
    }
}

let userManagement = new UserManagement();