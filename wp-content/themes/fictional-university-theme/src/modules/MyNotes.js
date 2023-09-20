import $ from "jquery";

class MyNotes{
    constructor(){
        this.deletebtn = $(".delete-note");
        this.editbtn = $(".edit-note");
        this.events()
    }

    events(){
        this.deletebtn.on("click", this.deleteNote);
        this.editbtn.on("click", this.editNote);
    }

    deleteNote(e){

        var thisNote = $(e.target).parents("li");

        $.ajax({
            beforeSend : (xhr) => {
                xhr.setRequestHeader('X-WP-NONCE', universityData.nonce);
            },
            url : universityData.root_url + "/wp-json/wp/v2/note/" + thisNote.data('id'),
            type : 'DELETE',
            success : (response) => {
                thisNote.slideUp();
                console.log("Congrats");
                console.log(response);
            },
            error : (error) => {
                console.log(error);
            },
        });
    }


    editNote(e){

        var thisNote = $(e.target).parents("li");
        thisNote.find(".note-title-field, .note-body-field").removeAttr('readonly').addClass('note-active-field');
        thisNote.find(".update-note").addClass("update-note--visible");

        $.ajax({
            beforeSend : (xhr) => {
                xhr.setRequestHeader('X-WP-NONCE', universityData.nonce);
            },
            url : universityData.root_url + "/wp-json/wp/v2/note/" + thisNote.data('id'),
            type : 'GET',
            success : (response) => {
                console.log("Congrats");
                console.log(response);
            },
            error : (error) => {
                console.log(error);
            },
        });
    }
}

export default MyNotes