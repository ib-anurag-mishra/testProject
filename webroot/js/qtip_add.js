$(document).ready(function() {


    $(document).on('mouseover', 'a[title]', function(event) {

        $(this).qtip({
            position: {
                at: 'top left', //target
                my: 'bottom right' //tooltip


            },
            overwrite: false,
            show: {
                event: event.type,
                ready: true
            }


        });

    });

    $(document).on('mouseover', 'span[title]', function(event) {

        $(this).qtip({
            position: {
                at: 'top right', //target
                my: 'bottom right' //tooltip


            },
            overwrite: false,
            show: {
                event: event.type,
                ready: true
            }


        });

    });


    $(document).on('mouseover', '#qtip[title]', function(event) {

        $(this).qtip({
            position: {
                at: 'left bottom', //target
                my: 'right top' //tooltip


            },
            overwrite: false,
            show: {
                event: event.type,
                ready: true
            }


        });

    });

    $(document).on('mouseover', 'span .dload', function(event) {

        $(this).qtip({
            position: {
                at: 'top right', //target
                my: 'bottom left' //tooltip


            },
            overwrite: false,
            show: {
                event: event.type,
                ready: true
            }


        });

    });


    $(document).on('mouseover', '.genres-page .album-title a[title]', function(event) {

        $(this).qtip({
            position: {
                at: 'top left', //target
                my: 'bottom right' //tooltip


            },
            overwrite: false,
            show: {
                event: event.type,
                ready: true
            }
        });

    });



});
