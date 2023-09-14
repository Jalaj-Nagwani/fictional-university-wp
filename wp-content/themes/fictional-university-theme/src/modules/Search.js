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


        var a = $.getJSON(universityData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val());
        var b = $.getJSON(universityData.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val());

        $.when(a, b).then((posts, pages) => {
            var combinedResults = posts[0].concat(pages[0]);
            this.resultsDiv.html(`
            <h2 class="search-overlay__section-title">General Information</h2>
            ${combinedResults.length ? '<ul class="link-list min-list">' : '<p>No Results</p>'}
                ${combinedResults.map(item => `<li><a href="${item.link}">${item.title.rendered}</a> ${item.type == 'post' ? `by ${item.author_name}` : ''}</li>`).join('')}
            ${combinedResults.length ? '</ul>' : ''}
            `);
            this.isSpinnerVisible = false;

        }, () => {
            this.resultsDiv.html("<p>Unexpected Error. Please try after some time. </p>")
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