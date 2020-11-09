class Search {
    constructor() {
        this.userInput = document.querySelector('#game-search');
        this.searchButton = document.querySelector('.query_button');
        this.titleElement = document.querySelector('#game_title');
        this.imageElement = document.querySelector('#game_image');

        this.searchButton.addEventListener("click", () => {
            this.getGames();
        })
    }

    // On réécupère d'abord la liste des jeux de l'API

    getGames() {
        const research = this.userInput.value.toLowerCase().replace(/ /g, '-');
        const getData = async () => {
            const response = await fetch("https://api.rawg.io/api/games/" + research + "?key=2d3f2baa156044ab91295ba0e044da14");
            const games = await response.json();

            this.showResults(games);
        }
        getData();
    }

    // on vérifie si le jeu recherché est dans la liste retournée

    showResults(games) {
        console.log(games);
        console.log(games.background_image);
        this.titleElement.innerHTML = games.name;
        this.imageElement.setAttribute('src', games.background_image);
    }
}

let search = new Search();