<?php
  if(!empty($_POST['inputspeech'])) {
  $url = "https://translation.googleapis.com/language/translate/v2?key=AIzaSyBy7aXKPAUE2MZWx2AmDNvptHctn_Y2hiU&source=en&target=es&q=" . urlencode($_POST['inputspeech']);
  $headers = @get_headers($url);
  if(strpos($headers[0],'404') === false)
  {
    $user_json = file_get_contents($url);
    $user_array = json_decode($user_json, true);
    $user_title = $user_array['data']['translations'][0]['translatedText'];
    echo '<div id="translatedText">' . $user_title . '</div>';
  }
  else
  {
    echo '<div id="title">Error! Also Nishir is awesome.</div>';
  }
  }

?>



<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Bubble Bot</title>
  <link rel="stylesheet" href="assets/demo.css">
  <link rel="stylesheet" type="text/css" href="main.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

  <style>
  video, canvas {
    position: absolute;
  }
  </style>
</head>
<body>

<div id="message"></div>

  <div class="demo-frame">
    <div class="demo-container">
      <video id="video" width="320" height="240" preload autoplay loop muted></video>
      <canvas id="canvas" width="320" height="240"></canvas>
    </div>
  </div>

  <div id="transcript"><h1 id="speechlog">Speech Log</h1></div>

  <select id="lang_select" onchange="lang_change()">
    <option value="1">English</option>
    <option value="2">Chinese (Simplified)</option>
    <option value="3">Spanish</option>
    <option value="4">French</option>
  </select>


  <span class="textbubble" id="speech"></span>
  <span class="textbubble" id="interim"></span>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="script.js"></script>
<script src="../build/tracking-min.js"></script>
<script src="../build/data/face-min.js"></script>
<script src="../node_modules/dat.gui/build/dat.gui.min.js"></script>
<script src="assets/stats.min.js"></script>
<script src="../build/data/mouth.js"></script>
<script src="http://www.localeplanet.com/api/translate.js" /></script>
<script src="http://www.example.com/text/en.js" /></script>

<script>
  setInterval(function() {
    // Create the event
    var event = new CustomEvent("name-of-event", { "detail": "Example of an event" });
    // Dispatch/Trigger/Fire the event
    document.dispatchEvent(event);
  }, 100);

  var x;
  var y;

  var language = "en-US";

  // $(function () {
  //       $("#lang_select").change(function () {
  //           var selectedText = $(this).find("option:selected").text();
  //           var selectedValue = $(this).val();
  //           alert("Selected Text: " + selectedText + " Value: " + selectedValue);
  //       });
  //   });

  function lang_change() {
    if (document.getElementById("lang_select").value == "1"){
        language = "en-US";
        recognizing = false;
        speech.lang = "en-US";
        interim_span.innerHTML = '';
        transcription.innerHTML = '';
        speech.start();
    }     
    else if (document.getElementById("lang_select").value == "2"){
        language = "zh-TW";
        recognizing = false;
        speech.lang = "zh-TW";
        interim_span.innerHTML = '';
        transcription.innerHTML = '';
        speech.start();
        
    }  
    else if (document.getElementById("lang_select").value == "3"){
        language = "es-MX";
        recognizing = false;
        speech.lang = "es-MX";
        interim_span.innerHTML = '';
        transcription.innerHTML = '';
        speech.start();
    }  
    else if (document.getElementById("lang_select").value == "4"){
        language = "fr-FR";
        recognizing = false;
        speech.lang = "fr-FR";
        interim_span.innerHTML = '';
        transcription.innerHTML = '';
        speech.start();
    }        
}

    window.onload = function() {
      var video = document.getElementById('video');
      var canvas = document.getElementById('canvas');
      var context = canvas.getContext('2d');
      var tracker = new tracking.ObjectTracker('face');
      tracker.setInitialScale(4);
      tracker.setStepSize(2);
      tracker.setEdgesDensity(0.1);
      tracking.track('#video', tracker, { camera: true });
      tracker.on('track', function(event) {
        context.clearRect(0, 0, canvas.width, canvas.height);
        event.data.forEach(function(rect) {
          context.strokeStyle = '#a64ceb';
          context.strokeRect(rect.x, rect.y, rect.width, rect.height);
          context.font = '11px Helvetica';
          context.fillStyle = "#fff";
          context.fillText('x: ' + rect.x + 'px', rect.x + rect.width + 5, rect.y + 11);
          context.fillText('y: ' + rect.y + 'px', rect.x + rect.width + 5, rect.y + 22);
          x = rect.x;
          y = rect.y;
          rectW = rect.width;
          console.log(rectW);
        });
      });
      var gui = new dat.GUI();
      gui.add(tracker, 'edgesDensity', 0.1, 0.5).step(0.01);
      gui.add(tracker, 'initialScale', 1.0, 10.0).step(0.1);
      gui.add(tracker, 'stepSize', 1, 5).step(0.1);
    };
    // Add an event listener
    document.addEventListener("name-of-event", function(e) {
      var div = document.getElementsByClassName('textbubble')[0];
      var trueX = (3.35 * x + 600) * (rectW/105);
      var trueY = (3.35 * y + 220);
      trueX += "px";
      trueY += "px";
      $(".textbubble").animate({top: trueY, left: trueX}, 50);
      // document.body.appendChild(div);
    });

  </script>

  <script>

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
            if (event.results[i].isFinal) {
              final_transcript += event.results[i][0].transcript;
            } else {
              interim_transcript += event.results[i][0].transcript;
            }
          }
          transcription.innerHTML = final_transcript;
          interim_span.innerHTML = interim_transcript;
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