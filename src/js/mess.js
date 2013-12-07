$(document).ready(

    function() {

        $('#mess_container').hide();

        $("#add_btn").bind("click", function() {

            var name_val = $('#name_txt').val();
            var message_val = $('#message_ta').val();
            var pid = $('#mess_inputs').val();
            // make ajax call to logout
            $.ajax({
                type: "GET",
                url: "functions.php",
                data: {
                    function: "add",
                    name: name_val,
                    message: message_val,
                    pid: pid
                }
            }).done(function(data) {
                alert(data);
            });

        });

        // Change producer
        $("a.producer").click(function () {
            console.log('hello')
            changeProducer(parseInt($(this).data('producer_id'), 10));
            return false;
        });
    }
)

/*
 * This script is running to get the data for the producers
 */
// Called when we click on a producer link - gets the id for the producer

function changeProducer(pid) {

    //console.log("pid --> " +pid);

    // Clear and update the hidden stuff
    $("#mess_inputs").val(pid);
    $("#mess_p_mess").text("");

    // get all the stuff for the producers
    // make ajax call to functions.php with teh data
    $.ajax({
        type: "GET",
        url: "functions.php",
        data: {
            function: "producers",
            pid: pid
        }
    }).done(function(data) { // called when the AJAX call is ready
        console.log(data);
        var j = JSON.parse(data);

        $("#mess_p_headline").text("Meddelande till " + j.name + ", " + j.city);

        if (j.url !== "") {

            $("#mess_p_kontakt").text("Länk till deras hemsida " + j.url);
        } else {
            $("#mess_p_kontakt").text("Producenten har ingen webbsida");
        }

        if (j.imageURL !== "") {
            $("#p_img_link").attr("href", j.imageURL);
            $("#p_img").attr("src", j.imageURL);
        } else {
            $("#p_img_link").attr("href", "#");
            $("#p_img").attr("src", "img/noimg.jpg");
        }
    });

    // get all the messages for a producer
    $.ajax({
        type: "GET",
        url: "functions.php",
        data: {
            function: "getAllMessagesForProducer",
            pid: pid
        }
    }).done(function(data) {
        var messages = JSON.parse(data);
        console.log(messages);

        if (messages !== false) {
            messages.forEach(function(message) {
                $("#mess_p_mess").append("<p class='message_container'>" + message.message + "<br />Skrivet av: " + message.name + "</p>");
            });
        }
    });

    // show the div if its unvisible
    $("#mess_container").show("slow");

}
