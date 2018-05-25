$(function(){
    $depart = document.getElementById("depart").value;
    $arrivee = document.getElementById("arrivee").value;
    $btnSearch = document.getElementById("btnSearch").value;

    function myFunction() {console.log("ok");

        window.location = "http://localhost/papoteCar/public/travelSearch/" + $arrivee + "/" + $depart;
    };
});