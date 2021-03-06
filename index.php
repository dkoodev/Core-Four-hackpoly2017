<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>HelloMundo</title>
  <link rel="icon" href="img/bubble.png">
  <link rel="stylesheet" type="text/css" href="main.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script scr="http://code.jquery.com/jquery-latest.min.js"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
  <script src="../build/tracking-min.js"></script>
  <script src="../build/data/face-min.js"></script>
  <script src='https://code.responsivevoice.org/responsivevoice.js'></script>

  <style>
  video, canvas {
    position: absolute;
  }
  </style>
</head>
<body>
  <div ng-app="myApp" ng-controller="myCtrl"> 

<div id="message"></div>

  <div class="demo-frame">
    <div class="demo-container">
      <video id="video" width="320" height="240" preload autoplay loop muted></video>
      <canvas id="canvas" width="320" height="240"></canvas>
    </div>
  </div>

  <h1 id="speechlog"><span id="speechtitle">speech log<span></h1>
  <div id="transcript" style="overflow-y: scroll;"></div>

  <div id="langContainer">
  <h3 class="direction">starting language:</h3>
    <select id="lang_select" value="en" onchange="partialReset()">
      <option value="en">English</option>
      <option value="zh-CN">Chinese (Simplified)</option>
      <option value="es">Spanish</option>
      <option value="fr">French</option>
      <option value="de">German</option>
      <option value="hi">Hindi</option>
      <option value="la">Latin</option>
      <option value="el">Greek</option>
      <option value="ja">Japanese</option>
      <option value="ko">Korea</option>
      <option value="ru">Russian</option>
    </select>
  <h3 class="direction">target language:</h3>
    <select id="lang_select_target" value="es">
      <option value="en">English</option>
      <option value="zh-CN">Chinese (Simplified)</option>
      <option value="es">Spanish</option>
      <option value="fr">French</option>
      <option value="de">German</option>
      <option value="hi">Hindi</option>
      <option value="la">Latin</option>
      <option value="el">Greek</option>
      <option value="ja">Japanese</option>
      <option value="ko">Korea</option>
      <option value="ru">Russian</option>
    </select>
  </div>




  <span class="textbubble" id="speech"></span>
  <span class="textbubble" id="interim"></span>

</div>

<img class="logo" src="img/mainlogo.png"/>
</body>


<script>

var target;
var real_obj;

var speakers = {"en": "US English Female", "ko": "Korean Female", "zh-CN": "Chinese Female", "es": "Spanish Female", "fr": "French Female", "de": "Deutsch Female", "hi": "Hindi Female", "la": "Latin Female", "el": "Greek Female", "ja": "Japanese Female", "ru": "Russian Female"};

function httpGetAsync(theUrl, callback)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() { 
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
            callback(xmlHttp.responseText);
    }
    xmlHttp.open("GET", theUrl, true); // true for asynchronous 
    xmlHttp.send(null);
    real_obj += "";
    setTimeout(function() {
      var temp = speakers[target];
      responsiveVoice.speak(document.getElementById('speech').innerHTML, temp);
    }, 500);
}

function saveConvo(obj) {
    var div2 = document.createElement("div");
    div2.innerHTML = real_obj;
    div2.className = "transcriptTranslation";
    document.getElementById("transcript").appendChild(div2);
}

function setTranscription(final_transcript){
  transcription = document.getElementById('speech');
  obj = JSON.parse(final_transcript);
  real_obj = obj['data']['translations'][0]['translatedText'];
  real_obj = real_obj.replace('&#39;', '');
  console.log(real_obj.length-1);
  transcription.innerHTML = real_obj;
  console.log(real_obj);
  saveConvo(real_obj);
}

// { "data": { "translations": [ { "translatedText": "'" } ] } }

  var rectW;
  setInterval(function() {
    // Create the event
    var event = new CustomEvent("name-of-event", { "detail": "Example of an event" });
    // Dispatch/Trigger/Fire the event
    document.dispatchEvent(event);
  }, 100);

  var x;
  var y;

  var language = "en";

  // $(function () {
  //       $("#lang_select").change(function () {
  //           var selectedText = $(this).find("option:selected").text();
  //           var selectedValue = $(this).val();
  //           alert("Selected Text: " + selectedText + " Value: " + selectedValue);
  //       });
  //   });


    window.onload = function() {
      var video = document.getElementById('video');
      var canvas = document.getElementById('canvas');
      var context = canvas.getContext('2d');
      var tracker = new tracking.ObjectTracker("face");
      tracker.setInitialScale(4);
      tracker.setStepSize(2);
      tracker.setEdgesDensity(0.1);
      tracking.track('#video', tracker, { camera: true });
      tracker.on('track', function(event) {
        context.clearRect(0, 0, canvas.width, canvas.height);
        event.data.forEach(function(rect) {
          x = rect.x;
          y = rect.y;
        });
      });
    };
    // Add an event listener
    document.addEventListener("name-of-event", function(e) {
      var div = document.getElementsByClassName('textbubble')[0];
      var trueX = (3.35 * x + 600);
      var trueY = (3.35 * y + 220);
      trueX += "px";
      trueY += "px";
      $(".textbubble").animate({top: trueY, left: trueX}, 50);
      // document.body.appendChild(div);
    });

      if (!(window.webkitSpeechRecognition) && !(window.speechRecognition)) {
        upgrade();
      } else {
        var recognizing,
        transcription = document.getElementById('speech'),
        interim_span = document.getElementById('interim');

        interim_span.style.opacity = '0.5';


        function reset() {
          recognizing = false;
          speech.lang = language;
          interim_span.innerHTML = '';
          transcription.innerHTML = '';
          speech.start();
          recognizing = true;
        }

        function partialReset() {
          speech.stop();
          recognizing = false;
          language = document.getElementById("lang_select").value;
          speech.lang = language;
          speech.start();
          recognizing = true;
        }

        var speech = new webkitSpeechRecognition() || speechRecognition();

        speech.continuous = true;
        speech.interimResults = true;
        speech.lang = language; // check google web speech example source for more lanuages
        speech.start(); //enables recognition on default

        speech.onstart = function() {
            // When recognition begins
            recognizing = true;
        };



        speech.onresult = function(event) {
          // When recognition produces result
          var interim_transcript = '';
          var final_transcript = '';

          // main for loop for final and interim results
          for (var i = event.resultIndex; i < event.results.length; ++i) {
            var source = document.getElementById("lang_select").value;
            target = document.getElementById("lang_select_target").value;
            if (event.results[i].isFinal) {
              document.getElementById('interim').innerHTML = "";
              final_transcript += event.results[i][0].transcript;
              var query = final_transcript;
              query=encodeURIComponent(query.trim());
              var x = 'https://translation.googleapis.com/language/translate/v2?key=AIzaSyCa-LCaNth2vVBexvnQtja_wvXNp5rozhQ&source='+source+'&target='+target+ '&q=' + query+'\'';
              console.log(x);
              if(target != source) {
                httpGetAsync(x, setTranscription);
              }

            } else {
              document.getElementById('speech').innerHTML = "";
              interim_transcript += event.results[i][0].transcript;
              interim_span.innerHTML = interim_transcript;
            }
          }


          if(final_transcript != "") {

            var div = document.createElement("div");
            div.innerHTML = final_transcript;
            div.className = "transcriptInner";
            document.getElementById("transcript").appendChild(div);
            
          }
        };

        speech.onerror = function(event) {
            // Either 'No-speech' or 'Network connection error'
            console.error(event.error);
        };

        speech.onend = function() {
            // When recognition ends
            speech.lang = language;
            reset();
        };
      }
  </script>

</html>