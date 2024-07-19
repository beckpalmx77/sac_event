<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Image Map</title>
    <style>
        .responsive-image {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
<img src="img/sac10year_456.png" usemap="#image-map" class="responsive-image" id="image-map-image">

<map name="image-map">
    <map name="image-map">
        <area target="_blank" alt="1" title="1" href="http://171.100.56.194:8999/sac_event/" coords="1629,2729,323,2356" shape="rect">
    </map>
</map>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.rwdImageMaps/1.6/jquery.rwdImageMaps.min.js"></script>
<script>
    $(document).ready(function(e) {
        $('img[usemap]').rwdImageMaps();
    });
</script>
</body>
</html>
