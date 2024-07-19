<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .responsive {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>


<img src="../img/sac_event_img.png" alt="Nature" class="responsive" usemap="#image-map">

<map name="image-map">
    <area target="_blank" alt="1" title="1" href="1" coords="53,3038,378,3536" shape="rect">
    <area target="_blank" alt="2" title="2" href="2" coords="434,3041,750,3531" shape="rect">
    <area target="_blank" alt="3" title="3" href="3" coords="1125,3545,787,3034" shape="rect">
    <area target="_blank" alt="4" title="4" href="4" coords="1165,3033,1496,3540" shape="rect">
    <area target="_blank" alt="5" title="5" href="5" coords="1537,3041,1867,3536" shape="rect">
    <area target="_blank" alt="6" title="6" href="6" coords="1893,6312,33,5703" shape="rect">
</map>

<div class="tick"
     data-did-init="handleTickInit">
    <div data-repeat="true"
         data-layout="horizontal center fit"
         data-transform="preset(d, h, m, s) -> delay">
        <div class="tick-group">
            <div data-key="value"
                 data-repeat="true"
                 data-transform="pad(00) -> split -> delay">
                <span data-view="flip"></span>
            </div>
        </div>
    </div>
</div>

</body>
</html>
