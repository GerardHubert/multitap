class Games {
    constructor() {
        this.gameTitle = document.querySelector('.game_title');
        this.developers = document.querySelector('.developers');
        this.out = document.querySelector('.out');
        this.genres = document.querySelector('.genre');
        this.platForms = document.querySelector('.platforms');
        this.apiGameIdElement = document.getElementById('api_game_id');
        this.url = "https://api.rawg.io/api/";
        this.apiKey = "?key=2d3f2baa156044ab91295ba0e044da14";
        this.apiGameId = this.getGameId();
        this.getGamesDetails();
    }

    getGameId() {
        return this.apiGameIdElement.innerHTML;
    }

    getGamesDetails() {
        const getData = async () => {
            const response = await fetch(this.url + 'games/' + this.apiGameId + this.apiKey);
            const details = await response.json();

            this.displayDetails(details);
        }
        getData();
    }

    displayDetails(data) {
        this.gameTitle.innerHTML = data.name;

        const devList = [];
        data.developers.forEach(dev => devList.push(dev.name));
        this.developers.innerHTML = devList.join();

        this.out.innerHTML = data.released;

        const genresList = [];
        data.genres.forEach(genre => genresList.push(genre.name));
        this.genres.innerHTML = genresList.join();

        const platFormsList = [];
        data.platforms.forEach(machine => platFormsList.push(machine.platform.name));
        this.platForms.innerHTML = platFormsList.join();
    }
}

let games = new Games();