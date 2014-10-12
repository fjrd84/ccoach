/**
 * Created by jdonado on 12/10/14.
 */

function startGame(){
    //alert("Game is starting! :D");
    $.get( "ajax/getQuestions.php", { "choices[]": ["Jon", "Susan"] }, function( data ) {
        //$( ".result" ).html( data );
        //alert( "Load was performed." );
        processData(JSON.parse(JSON.stringify(eval("(" + data + ")"))));
    });
}

function processData(data){
    console.log(data);
    $(".loading").remove();
}