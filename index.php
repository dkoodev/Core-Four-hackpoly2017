<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Bubble Bot</title>
  <link rel="stylesheet" href="assets/demo.css">
  <link rel="stylesheet" type="text/css" href="main.css">
  <script src="../build/tracking-min.js"></script>
  <script src="../build/data/face-min.js"></script>
   <script src="../node_modules/dat.gui/build/dat.gui.min.js"></script>
  <script src="assets/stats.min.js"></script>
  <script src="../build/data/mouth.js"></script>

  <style>
  video, canvas {
    position: absolute;
  }
  </style>
</head>
<body>

  <div class="demo-frame">
    <div class="demo-container">
      <video id="video" width="320" height="240" preload autoplay loop muted></video>
      <canvas id="canvas" width="320" height="240"></canvas>
    </div>
  </div>

  <div class="textbubble">Hello</div>

  <script>
  setInterval(function() {
    // Create the event
    var event = new CustomEvent("name-of-event", { "detail": "Example of an event" });
    // Dispatch/Trigger/Fire the event
    document.dispatchEvent(event);
  }, 5);

  var x;
  var y;

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

      var trueX = 2.2 * x + 500;
      var trueY = 2.2 * y + 200;
      div.innerHTML = "Hello!";
      div.style.top = trueY + "px";
      div.style.left = trueX + "px";
      document.body.appendChild(div);
    });

  </script>

</body>
</html>