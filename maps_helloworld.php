<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
        width: 100vw;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script>
      //<?php 
      //echo "var latlng = { lat: 10, lng: -10 };";
      //?>
      //var url = window.location.href;
      function GetUrlPara(pos)
  　　{
        var url = document.location.href.toString();
        var arrUrl = url.split("?");
        var value = arrUrl[1].split("&");
        var result = value[pos].split("=");
        return parseInt(result[1]);
  　　}
      var latlng = { lat: GetUrlPara(1), lng: GetUrlPara(0)};
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
          center: latlng,
          zoom: 4
        });

      var marker = new google.maps.Marker({
            position: latlng, //marker的放置位置
            map: map //這邊的map指的是第四行的map變數
        });
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCZQaKT0Bb5P1gRlqNP9Jo3ljAUvioiGaM&callback=initMap"
    async defer></script>
  </body>
</html>
