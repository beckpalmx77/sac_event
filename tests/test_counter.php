<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Responsive Flip Countdown Clock In jQuery</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<style>
    body {
        background: url('../img/sac_event_img.png') no-repeat center center fixed;
        background-size: cover;
        font-family: sans-serif;
    }

.flipper {
  color: #333;
  display: block;
  font-size: 50px;
  line-height: 100%;
  padding: 0;
  margin: 0;
  height: 1.7em;
}
.flipper.flipper-invisible {
  font-size: 0px !important;
}

.flipper-group {
  position: relative;
  white-space: nowrap;
  display: block;
  float: left;
  padding: 0;
  margin: 0;
}
.flipper-group label {
  position: absolute;
  color: #fff;
  font-size: 20%;
  top: 100%;
  line-height: 1em;
  left: 50%;
  -webkit-transform: translate(-50%, 0);
          transform: translate(-50%, 0);
  text-align: center;
  padding-top: .5em;
}

.flipper-digit {
  white-space: nowrap;
  position: relative;
  padding: 0;
  margin: 0;
  display: inline-block;
  float: left;
  height: 1.2em;
  overflow-y: hidden;
}
.flipper-digit span {
  font-size: 25%;
}

.flipper-delimiter {
  white-space: nowrap;
  display: block;
  float: left;
  padding: 0;
  margin: 0;
  color: #fff;
  min-width: .1em;
  white-space: nowrap;
  display: block;
  padding-top: 0.1em;
  padding-bottom: 0.1em;
  line-height: 1em;
}

.digit-face {
  display: block;
  visibility: hidden;
  position: relative;
  border-radius: 0.1em;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 8;
  padding-top: 0.1em;
  padding-bottom: 0.1em;
  padding-left: 0.1em;
  padding-right: 0.1em;
  box-sizing: border-box;
  text-align: center;
}

.digit-next {
  display: block;
  position: relative;
  border-radius: 0.1em;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 8;
  height: 1.2em;
  background: #fff;
  padding-top: 0.1em;
  padding-bottom: 0.1em;
  padding-left: 0.1em;
  padding-right: 0.1em;
  box-sizing: border-box;
  text-align: center;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
}

.digit-top {
  z-index: 10;
  top: 0;
  left: 0;
  right: 0;
  height: 50%;
  -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
  pointer-events: none;
  overflow: hidden;
  position: absolute;
  background: #fff;
  padding-top: 0.1em;
  padding-bottom: 0;
  padding-left: 0.1em;
  padding-right: 0.1em;
  border-top-left-radius: 0.1em;
  border-top-right-radius: 0.1em;
  box-sizing: border-box;
  text-align: center;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
  transition: background 0s linear, -webkit-transform 0s linear;
  transition: transform 0s linear, background 0s linear;
  transition: transform 0s linear, background 0s linear, -webkit-transform 0s linear;
  -webkit-transform-origin: 0 0.6em 0 !important;
          transform-origin: 0 0.6em 0 !important;
  -webkit-transform-style: preserve-3d !important;
          transform-style: preserve-3d !important;
  z-index: 20;
}
.digit-top.r {
  transition: background 0.2s linear, -webkit-transform 0.2s linear;
  transition: transform 0.2s linear, background 0.2s linear;
  transition: transform 0.2s linear, background 0.2s linear, -webkit-transform 0.2s linear;
  -webkit-transform: rotateX(90deg);
          transform: rotateX(90deg);
  background: #cccccc;
}

.digit-top2 {
  visibility: hidden;
  position: absolute;
  height: 50%;
  left: 0;
  right: 0;
  background: #cccccc;
  transition: -webkit-transform 0.2s linear;
  transition: transform 0.2s linear;
  transition: transform 0.2s linear, -webkit-transform 0.2s linear;
  line-height: 0em !important;
  top: 50% !important;
  bottom: auto !important;
  padding-top: 0;
  padding-bottom: 0.1em;
  padding-left: 0.1em;
  padding-right: 0.1em;
  border-bottom-left-radius: 0.1em;
  border-bottom-right-radius: 0.1em;
  overflow: hidden;
  text-align: center;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
  transition: background 0s linear, -webkit-transform 0s linear;
  transition: transform 0s linear, background 0s linear;
  transition: transform 0s linear, background 0s linear, -webkit-transform 0s linear;
  -webkit-transform: rotateX(-90deg);
          transform: rotateX(-90deg);
  -webkit-transform-style: preserve-3d !important;
          transform-style: preserve-3d !important;
  -webkit-transform-origin: 0 0 0 !important;
          transform-origin: 0 0 0 !important;
  z-index: 20;
}
.digit-top2.r {
  visibility: visible;
  transition: background 0.2s linear 0.2s, -webkit-transform 0.2s linear 0.2s;
  transition: transform 0.2s linear 0.2s, background 0.2s linear 0.2s;
  transition: transform 0.2s linear 0.2s, background 0.2s linear 0.2s, -webkit-transform 0.2s linear 0.2s;
  -webkit-transform: rotateX(0deg);
          transform: rotateX(0deg);
  background: #fff;
}

.digit-bottom {
  -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
  pointer-events: none;
  position: absolute;
  overflow: hidden;
  background: #fff;
  height: 50%;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 9;
  line-height: 0em;
  padding-top: 0;
  padding-bottom: 0.1em;
  padding-left: 0.1em;
  padding-right: 0.1em;
  border-bottom-left-radius: 0.1em;
  border-bottom-right-radius: 0.1em;
  box-sizing: border-box;
  text-align: center;
  transition: none;
}
.digit-bottom.r {
  transition: background 0.2s linear;
  background: #cccccc;
}

.flipper-digit:after {
  content: "";
  position: absolute;
  height: 2px;
  background: rgba(0, 0, 0, 0.5);
  top: 50%;
  display: block;
  z-index: 30;
  left: 0;
  right: 0;
}

.flipper-dark {
  color: #fff;
}
.flipper-dark .flipper-delimiter {
  color: #333;
}
.flipper-dark .digit-next {
  background: #333;
}
.flipper-dark .digit-top {
  background: #333;
}
.flipper-dark .digit-top.r {
  background: black;
}
.flipper-dark .digit-top2 {
  background: black;
}
.flipper-dark .digit-top2.r {
  background: #333;
}
.flipper-dark .digit-bottom {
  background: #333;
}

.flipper-dark-labels .flipper-group label {
  color: #333;
}

    </style>
</head>

<body>
<div class="container pt-5 pb-5">
<h1 class="text-light"></h1>
<div class="jquery-script-ads" style="margin:50px auto"><script type="text/javascript"><!--
google_ad_client = "ca-pub-2783044520727903";
/* jQuery_demo */
google_ad_slot = "2780937993";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="https://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>
<div class="flipper" data-reverse="true" data-datetime="2022-01-01 00:00:00" data-template="ddd|HH|ii|ss" data-labels="Days|Hours|Minutes|Seconds" id="myFlipper"></div>

</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
<div class="flipper flipper-dark flipper-dark-labels" data-reverse="false" data-template="d|H|i|s" data-labels="Date|Hours|Minutes|Seconds" id="modalFlipper"></div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha384-vk5WoKIaW/vJyUAd9n/wmopsmNhiy+L2Z+SBxGYnUkunIxVxAv/UtMOhba/xskxh" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="../asset/clock/jquery.flipper-responsive.js"></script>
<script>
  jQuery(function ($) {
  $('#myFlipper').flipper('init');
  $('#modalFlipper').flipper('init');
});
</script>
<script type="text/javascript">

  let _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    let ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    let s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>
