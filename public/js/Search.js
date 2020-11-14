class Search {
    constructor() {
        this.userInput = document.querySelector('#game-search');
        this.searchButton = document.querySelector('.query_button');
        this.resultsContainer = document.querySelector('.search_results');
        this.resultsContainerOn = document.querySelector('.search_results_on');
        this.checkboxes = document.getElementsByClassName('box');
        this.queryMessageElement = document.getElementById('query_message');
        this.reviewForm = document.querySelector('.review_form');

        this.searchButton.addEventListener("click", () => {
            this.search();
        })
    }

    resetSearch() {
        const children = this.resultsContainer.children;
        Array.from(children).forEach(element => element.remove());
    }

    search() {

        const boxes = [];
        for (let box of this.checkboxes) {
            if (box.checked) {
                boxes.push(box);
            }
        }

        switch (!this.userInput.value) {
            case true:
                this.queryMessageElement.innerHTML = 'Merci de saisir le nom d\'un jeu';
                this.queryMessageElement.style.display = 'block';
            break;
            case false:
                if (boxes.length !== 1) {
                this.queryMessageElement.innerHTML = 'Le choix de la machine est obligatoire. Merci de cocher une case, et une seule';
                this.queryMessageElement.style.display = 'block';
                }
                    else {
                        this.queryMessageElement.style.display = 'none';
                        this.resetSearch();
                        this.getGames(boxes[0].value);
                    }
            break;
        }
    }

    getGames(platformId) {
        
        const getData = async () => {
            const response = await fetch("https://api.rawg.io/api/games?key=2d3f2baa156044ab91295ba0e044da14&search="
                + this.userInput.value
                + '&platforms='
                + platformId
                + '&exclude_additions'
                + 'page_size=10');
            const games = await response.json();
            this.showResults(games.results);
        }
        getData();
    }

    showResults(games) {
        
        if (games.length !== 0) {

            //this.resultsContainer.className = 'search_results_on'
            this.resultsContainer.style.display = 'flex';

            for (let game of games) {
                
                let resultCard = document.createElement('div');
                resultCard.setAttribute('class', 'result_thumbnail');

                let titleResult = document.createElement('h4');
                titleResult.setAttribute('class', 'game_title');
                titleResult.innerHTML = game.name;
                resultCard.appendChild(titleResult);

                let imageResult = document.createElement('img');
                imageResult.setAttribute('class', 'game_image');
                imageResult.src = game.background_image;
                resultCard.appendChild(imageResult);

                let idResult = document.createElement('input');
                idResult.setAttribute('name', 'gameId');
                idResult.setAttribute('type', 'hidden');
                idResult.setAttribute('value', game.id);
                resultCard.appendChild(idResult);

                this.resultsContainer.appendChild(resultCard);
            }
        }
            else {
                this.queryMessageElement.innerHTML = 'Aucun jeu trouvé';
                this.queryMessageElement.style.display = 'block';
            }
    }
}

let search = new Search();