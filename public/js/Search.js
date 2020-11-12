class Search {
    constructor() {
        this.userInput = document.querySelector('#game-search');
        this.searchButton = document.querySelector('.query_button');
        this.resultsContainer = document.querySelector('.search_results');
        this.checkboxes = document.getElementsByClassName('box');
        this.queryMessageElement = document.getElementById('query_message');
        this.reviewForm = document.querySelector('.review_form');

        // Création des éléments pour insertion des résultats

        this.searchButton.addEventListener("click", () => {
            this.deletePreviousSearch();
        })
    }

    deletePreviousSearch() {
        if (this.resultsContainer.childNodes) {
            const children = this.resultsContainer.childNodes;
            console.log(children)
            };
        
        this.search();
    }

    search() {
        /**
         * On vérifie d'abord la requete
         * champs de recherche et 1 seule checkbox obligatoires
         */

        // on récupère un nom de plateform exploitable dans la requete (par les checkboxes)
        const boxes = [];
        for (let box of this.checkboxes) {
            if (box.checked) {
                boxes.push(box);
            }
        }

        if (boxes.length !== 1) {
            this.queryMessageElement.style.display = 'block';
        } else {
            this.queryMessageElement.style.display = 'none';
            this.getGames(boxes[0].value);
        }
    }

    getGames(platformId) {
        //const research = this.userInput.value.toLowerCase().replace(/ /g, '-');
        const getData = async () => {
            const response = await fetch("https://api.rawg.io/api/games?key=2d3f2baa156044ab91295ba0e044da14&search="
                + this.userInput.value
                + '&platforms='
                + platformId
                + '&exclude_additions');
            const games = await response.json();
            this.showResults(games.results);
        }
        getData();
    }

    showResults(games) {

        if (games.length !== 0) {

            this.resultsContainer.className = 'search_results_on';
            
            for (let game of games) {
                
                let resultCard = document.createElement('div')
                resultCard.setAttribute('class', 'result_thumbnail');

                let titleResult = document.createElement('h4');
                titleResult.setAttribute('class', 'game_title');
                titleResult.innerHTML = game.name;
                resultCard.appendChild(titleResult);

                let imageResult = document.createElement('img');
                imageResult.setAttribute('class', 'game_image');
                imageResult.src = game.background_image;
                resultCard.appendChild(imageResult);

                this.resultsContainer.appendChild(resultCard);
            }
        }
            else {
                alert('aucun resultat');
            }
    }
}

let search = new Search();