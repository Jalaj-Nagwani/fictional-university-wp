import $ from "jquery";

class Like {

    constructor() {
        this.events();

    }

    events() {
        $(".like-box").on("click", this.ourClickDispatcher.bind(this));
    }

    ourClickDispatcher(e) {

        var currentLikeBox = $(e.target).closest(".like-box");

        if (currentLikeBox.attr("data-exists") == "yes") {  // Tip :- If we want to pull in the fresh updated value we must use x.attr instead of x.data
            this.deleteLike(currentLikeBox);
        }
        else {
            this.createLike(currentLikeBox);
        }
    }

    createLike(currentLikeBox) {
        $.ajax({
            url: universityData.root_url + '/wp-json/university/v1/manage-like',
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-NONCE', universityData.nonce);
            },
            type: 'POST',
            data: {
                'professorID': currentLikeBox.data("professor")
            },
            success: (response) => {
                currentLikeBox.attr('data-exists', 'yes');
                var likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10);  // To convert the html into a number we used the parseInt function
                likeCount++;  // Incrementing the Like Count by 1
                currentLikeBox.find(".like-count").html(likeCount);  // Updating the like-box
                currentLikeBox.attr('data-like', response);
                console.log(response);
            },
            error: (error) => {
                console.log(error);
            },
        });
    }

    deleteLike(currentLikeBox) {
        $.ajax({
            url: universityData.root_url + '/wp-json/university/v1/manage-like',
            type: 'DELETE',
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-NONCE', universityData.nonce);
            },
            data: {
                'like': currentLikeBox.data("like")
            },
            success: (response) => {
                // alert("Success of deleteLike");
                currentLikeBox.attr('data-exists', 'no');
                var likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10);  // To convert the html into a number we used the parseInt function
                likeCount--;  // Incrementing the Like Count by 1
                currentLikeBox.find(".like-count").html(likeCount);  // Updating the like-box
                currentLikeBox.attr('data-like', '');
                console.log(response);
            },
            error: (error) => {
                // alert("Error of deleteLike");
                console.log(error);
            },
        });
    }
}

export default Like