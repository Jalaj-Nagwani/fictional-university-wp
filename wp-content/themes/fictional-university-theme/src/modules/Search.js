import $ from 'jquery';

class Search {

    // 1. Desccribe and create/initiate our object
    constructor() {
        this.addSearchHtml();
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchField = $("#search-term");
        this.searchOverlay = $(".search-overlay");
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false;
        this.previousValue;  // For tracking the change in the search value
        this.typingTimer;
        this.events();
    }


    // 2. events

    events() {
        this.resultsDiv = $("#search-overlay__results")
        this.openButton.on("click", this.openOverlay.bind(this));
        this.closeButton.on("click", this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));
        this.searchField.on("keyup", this.typingLogic.bind(this));
    }

    // 3. methods (functions, action...)


    typingLogic() {

        if (this.searchField.val() != this.previousValue) {

            clearTimeout(this.typingTimer);

            if (!this.searchField.val()) {
                this.resultsDiv.html('');
                this.isSpinnerVisible = false;
            }

            else {
                if (!this.isSpinnerVisible) {
                    this.resultsDiv.html('<div class="spinner-loader"></div>'); // Loading Icon is placed before the setTimeout function
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this), 750);
                this.previousValue = this.searchField.val();
            }
        }
    }


    getResults() {


        $.getJSON(universityData.root_url + '/wp-json/university/v1/search?term=' + this.searchField.val(), (data) => {
            console.log(data);
            this.resultsDiv.html(`
            <div class="row">
                <div class="one-third">
                    <h2 class="search-overlay__section-title">General Information</h2>
                        ${data.general_info.length ? '<ul class="link-list min-list">' : '<p>No Results</p>'}
                            ${data.general_info.map(item => `<li><a href="${item.link}">${item.title}</a> ${item.type == 'post' ? `by ${item.author_name}` : ''}</li>`).join('')}
                        ${data.general_info.length ? '</ul>' : ''}
                </div>
                <div class="one-third">
                    <h2 class="search-overlay__section-title">Programs</h2>
                        ${data.programs.length ? '<ul class="link-list min-list">' : '<p>No Results</p>'}
                            ${data.programs.map(item => `<li><a href="${item.link}">${item.title}</a></li>`).join('')}
                        ${data.programs.length ? '</ul>' : ''}

                    <h2 class="search-overlay__section-title">Professors</h2>
                        ${data.professors.length ? '<ul class="professor-cards">' : '<p>No Results</p>'}
                            ${data.professors.map(item => `
                            
                                <li class="professor-card__list-item">
                                    <a href="${item.link}" class="professor-card">
                                        <img class="professor-card__image" src="${item.image_link}">
                                        <span class="professor-card__name">${item.title}</span>
                                    </a>
                                </li>
                            
                            `).join('')}
                        ${data.professors.length ? '</ul>' : ''}

                </div>
                <div class="one-third">
                    <h2 class="search-overlay__section-title">Campuses</h2>
                        ${data.campuses.length ? '<ul class="link-list min-list">' : '<p>No Results</p>'}
                            ${data.campuses.map(item => `<li><a href="${item.link}">${item.title}</a></li>`).join('')}
                        ${data.campuses.length ? '</ul>' : ''}

                    <h2 class="search-overlay__section-title">Events</h2>
                        ${data.events.length ? '<ul class="link-list min-list">' : '<p>No Results</p>'}
                            ${data.events.map(item => `
                            
                            <div class="event-summary">
                                <a class="event-summary__date t-center" href="${item.link}">
                                    <span class="event-summary__month">${item.month}</span>
                                    <span class="event-summary__day">${item.date}</span>
                                </a>
                                <div class="event-summary__content">
                                    <h5 class="event-summary__title headline headline--tiny"><a href="${item.link}">${item.title}</a></h5>
                                    <p> 
                                    ${item.description}
                                    <a href="${item.link}" class="nu gray">Learn more</a> </p>
                                </div>
                            </div>
                            
                            `).join('')}
                </div>
            </div>
            
            `);

            this.isSpinnerVisible = false;
        });

    }


    keyPressDispatcher(e) {

        if (e.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(':focus')) {
            this.openOverlay();
        }

        else if (e.keyCode == 27 && this.isOverlayOpen) {
            this.closeOverlay();
        }


    }

    openOverlay() {
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass("body-no-scroll");
        this.searchField.val('');
        this.resultsDiv.html('');
        setTimeout(() => this.searchField.focus(), 301);
        this.isOverlayOpen = true;
        console.log("Open");
    }


    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        this.isOverlayOpen = false;
    }


    addSearchHtml() {
        $("body").append(`<div class="search-overlay">
        <div class="search-overlay__top">
            <div class="container">
                <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term" autocomplete="off">
                <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
            </div>
        </div>
    
        <div id="search-overlay__results"></div>
    </div>`);
    }
}

export default Search