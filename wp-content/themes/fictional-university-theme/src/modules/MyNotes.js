import $ from "jquery";

class MyNotes {
    constructor() {
        this.createbtn = $(".submit-note");
        this.parentElmnt = $("#my-notes");
        this.events()
    }

    events() {
        this.parentElmnt.on("click", ".delete-note", this.deleteNote);
        this.parentElmnt.on("click", ".edit-note", this.editNote.bind(this));
        this.parentElmnt.on("click", ".update-note", this.updateNote.bind(this));
        this.createbtn.on("click", this.createNote);
    }

    createNote() {
        var ourNewPost = {
            'title': $('.new-note-title').val(),
            'content': $('.new-note-body').val(),
            'status': 'publish'
        }

        // console.log(ourUpdatedPost);

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-NONCE', universityData.nonce);
            },
            url: universityData.root_url + "/wp-json/wp/v2/note/",
            type: 'POST',
            data: ourNewPost,
            success: (response) => {
                $('.new-note-title', '.new-note-body').val("");
                $(`
                
                <li data-id="${response.id}">
                    <input readonly class="note-title-field" value="${response.title.raw}">
                    <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
                    <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
                    <textarea readonly class="note-body-field">${response.content.raw}</textarea>
                    <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
                </li>
                
                `).prependTo("#my-notes").hide().slideDown();

                // console.log(response);
            },
            error: (error) => {
                console.log(error);
                if (error.responseText == "You have reached the limit"){
                    $(".note-limit-message").addClass("active");
                }
            },
        });
    }

    deleteNote(e) {

        var thisNote = $(e.target).parents("li");

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-NONCE', universityData.nonce);
            },
            url: universityData.root_url + "/wp-json/wp/v2/note/" + thisNote.data('id'),
            type: 'DELETE',
            success: (response) => {
                thisNote.slideUp();
                // console.log(response);
                console.log(response.userNoteCount);
                if (response.userNoteCount <= 5){
                    $(".note-limit-message").removeClass("active");
                }
            },
            error: (error) => {
                console.log(error);
            },
        });
    }


    updateNote(e) {

        var thisNote = $(e.target).parents("li");
        var ourUpdatedPost = {
            'title': thisNote.find('.note-title-field').val(),
            'content': thisNote.find('.note-body-field').val()
        }

        // console.log(ourUpdatedPost);

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-NONCE', universityData.nonce);
            },
            url: universityData.root_url + "/wp-json/wp/v2/note/" + thisNote.data('id'),
            type: 'POST',
            data: ourUpdatedPost,
            success: (response) => {
                this.makeNoteReadonly(thisNote);
                // console.log(response);
            },
            error: (error) => {
                console.log(error);
            },
        });
    }


    editNote(e) {

        var thisNote = $(e.target).parents("li");
        if (thisNote.data('state') == 'editable') {
            this.makeNoteReadonly(thisNote);
        }
        else {
            this.makeNoteEditable(thisNote);
        }
    }

    makeNoteEditable(thisNote) {
        thisNote.find('.edit-note').html('<i class="fa fa-times" aria-hidden="true"></i>  Cancel');
        thisNote.find(".note-title-field, .note-body-field").removeAttr('readonly').addClass('note-active-field');
        thisNote.find(".update-note").addClass("update-note--visible");
        thisNote.data('state', 'editable');
    }

    makeNoteReadonly(thisNote) {
        thisNote.find('.edit-note').html('<i class="fa fa-pencil" aria-hidden="true"></i>  Edit');
        thisNote.find(".note-title-field, .note-body-field").attr('readonly', "readonly").removeClass('note-active-field');
        thisNote.find(".update-note").removeClass("update-note--visible");
        thisNote.data('state', 'cancel');
    }
}

export default MyNotes