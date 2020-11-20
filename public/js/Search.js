class Search {
    constructor() {
        this.userInput = document.querySelector('#game-search');
        this.searchButton = document.querySelector('.query_button');
        this.resultsContainer = document.querySelector('.search_results');
        this.resultsContainerOn = document.querySelector('.search_results_on');
        this.checkboxes = document.getElementsByClassName('box');
        this.queryMessageElement = document.getElementById('query_message');
        this.reviewForm = document.querySelector('.review_form');
        this.gameTitleArea = document.querySelector('.form_game_title');
        this.gameIdArea = document.querySelector('.form_game_id');
        this.gameImage = document.querySelector('.form_image_url');
        this.usernameElement = document.querySelector('#username');

        this.searchButton.addEventListener("click", () => {
            this.search();
        })

    }

    resetSearch() {
        const children = this.resultsContainer.children;
        Array.from(children).forEach(element => element.remove());
    }

    search() {
        let radioChecked = null;
        for (let radio of this.checkboxes) {
            if (radio.checked) {
                radioChecked = radio;
            }
        }

        switch (!this.userInput.value) {
            case true:
                this.queryMessageElement.innerHTML = 'Merci de saisir le nom d\'un jeu';
                this.queryMessageElement.style.display = 'block';
            break;
            case false:
                if (radioChecked === null) {
                this.queryMessageElement.innerHTML = 'Le choix de la machine est obligatoire. Merci de cocher une case, et une seule';
                this.queryMessageElement.style.display = 'block';
                }
                    else {
                        this.queryMessageElement.style.display = 'none';
                        this.resetSearch();
                        this.getGames(radioChecked.value);
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
            const query = new Array();
            this.showResults(games.results);
        }
        getData();
    }

    showResults(games) {
        
        if (games.length !== 0) {

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
                idResult.setAttribute('class', 'gameId');
                idResult.setAttribute('type', 'hidden');
                idResult.setAttribute('value', game.id);
                resultCard.appendChild(idResult);

                this.resultsContainer.appendChild(resultCard);

                this.chosenGame();
            }
        }
            else {
                this.queryMessageElement.innerHTML = 'Aucun jeu trouvé';
                this.queryMessageElement.style.display = 'block';
            }
    }

    chosenGame() {
       
        const children = Array.from(this.resultsContainer.children);
        const chosenGame = new Array();

        children.forEach(child => child.addEventListener('click', () => {
            chosenGame.push(chosenGame['title'] = child.querySelector('.game_title').innerHTML);
            chosenGame.push(chosenGame['id'] = child.querySelector('.gameId').value);
            chosenGame.push(chosenGame['image'] = child.querySelector('.game_image').src)
            this.displayEditor(chosenGame);
        }));
    }

    displayEditor(chosenGame) {

        this.resultsContainer.style.display = 'none';
        this.reviewForm.style.display = 'flex';
        this.gameTitleArea.value = chosenGame['title'];
        this.gameIdArea.setAttribute('value', chosenGame['id']);
        this.gameImage.value = chosenGame['image'];
        document.querySelector('.form_reviewer').value = this.usernameElement.innerHTML;
        
        // bouton back to results = retour aux résultats de la recherche

        document.querySelector('.back_to_results').addEventListener('click', () => {
            this.userInput.value = query['search'];
            this.search().boxes.push(query['checkbox']);
        })
    }
}

let search = new Search();