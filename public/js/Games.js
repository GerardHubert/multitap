class Games {
    constructor() {
        this.pageTitle = document.querySelector('#page_title');
        this.gameTitle = document.querySelector('.game_title');
        this.developers = document.querySelector('.developers');
        this.out = document.querySelector('.out');
        this.genres = document.querySelector('.genre');
        this.platForms = document.querySelector('.platforms');
        this.videoLink = document.querySelector('#video_link');
        this.video = document.querySelector('#video');
        this.apiGameIdElement = document.querySelector('.api_game_id');
        this.headImage = document.querySelector('#headband');
        this.asideImage = document.querySelector('#additional_image');
        this.reviewsList = document.querySelector('#reviews_list');
        this.latestReviews = document.querySelector('#cards');
        this.url = "https://api.rawg.io/api/";
        this.apiKey = "?key=2d3f2baa156044ab91295ba0e044da14";
        this.getGameId();
    }

    getGameId() {

        switch (this.pageTitle.innerHTML) {

            case "Multitap : Accueil":
                const reviews = Array.from(this.latestReviews.children);
                let review = 0;

                for (reviews[review]; review < reviews.length; review++) {
                    let gameId = reviews[review].querySelector('.api_game_id').innerHTML;
                    let image = reviews[review].querySelector('.thumbnail_image_home');

                    let getData = async () => {
                        let response = await fetch(this.url + 'games/' + gameId + this.apiKey);
                        let details = await response.json();
                        
                        image.src = details.background_image;
                    }
                    getData()
                }
            break;

            case "Multitap : Toutes les reviews":
                const children = Array.from(this.reviewsList.children);
                let card = 0;

                for (children[card]; card < children.length-1; card++) {
                    let id = children[card].querySelector('.api_game_id').value;
                    let image = children[card].querySelector('.reviewsListImage');

                    let getData = async () => {
                        let response = await fetch(this.url + 'games/' + id + this.apiKey);
                        let details = await response.json();

                        image.src = details.background_image;
                    }
                    getData()
                }
            break;

            case "Multitap : Review":
                let apiGameId = this.apiGameIdElement.innerHTML;
                this.getGamesDetails(apiGameId);
            break;

        }
        
    }

    getGamesDetails(apiGameId) {
        const getData = async () => {
            const response = await fetch(this.url + 'games/' + apiGameId + this.apiKey);
            const details = await response.json();

            this.displayDetails(details);
        }
        getData();
    }

    displayDetails(data) {

        this.gameTitle.innerHTML = data.name;

        this.headImage.src = data.background_image;

        this.asideImage.src = data.background_image_additional;

        if (data.developers.length === 0) {
            this.developers.innerHTML = 'Développeur(s) inconnu';
        }
            else {
                const devList = [];
                data.developers.forEach(dev => devList.push(dev.name));
                this.developers.innerHTML = devList.join();
            }

        this.out.innerHTML = data.released;

        const genresList = [];
        data.genres.forEach(genre => genresList.push(genre.name));
        this.genres.innerHTML = genresList.join();

        const platFormsList = [];
        data.platforms.forEach(machine => platFormsList.push(machine.platform.name));
        this.platForms.innerHTML = platFormsList.join();

        switch (data.clip === null) {
            case true:
                this.video.style.display = 'none';
                const noVideoImage = document.createElement('img');
                noVideoImage.setAttribute('class', 'no_video');
                noVideoImage.src = 'images/novideo.png';
                noVideoImage.alt = 'aucune vidéo disponible';
                document.getElementById('game_info').appendChild(noVideoImage);
            break;

            case false:
                this.video.src = "https://youtube.com/embed/" + data.clip.video + "?rel=0";
            break;
        }        

    }
}

let games = new Games();