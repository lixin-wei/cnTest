// fork getUserMedia for multiple browser versions, for the future
// when more browsers support MediaRecorder

navigator.getUserMedia = ( navigator.getUserMedia ||
navigator.webkitGetUserMedia ||
navigator.mozGetUserMedia ||
navigator.msGetUserMedia);

// set up basic variables for app

var canvas = document.querySelector('.visualizer');
var $recordingList = $("#recordingList");

// URL shim
window.URL = window.URL || window.webkitURL;

// audio context + .createScriptProcessor shim
var audioContext = new AudioContext;
if (audioContext.createScriptProcessor === null)
    audioContext.createScriptProcessor = audioContext.createJavaScriptNode;


// disable stop button while not recording

stop.disabled = true;

// visualiser setup - create web audio api context and canvas

var audioCtx = new (window.AudioContext || webkitAudioContext)();
var canvasCtx = canvas.getContext("2d");
var microphone = null;
//main block for doing the audio recording

if (navigator.getUserMedia) {
    console.log('getUserMedia supported.');

    var constraints = { audio: true };
    var chunks = [];

    var onSuccess = function(stream) {
        visualize(stream);
        function saveRecording(blob, encoding) {
            console.log("start saving");
            //处理log
            var finalLog = "Answer log:\r\n";
            Object.keys(dataPack.ans).forEach(function (key) {
                // do something with obj[key]
                finalLog += key + " : " + dataPack.ans[key] + "\r\n";
            });
            finalLog += "\r\nPage switching event log:\r\n";
            for(var i=0 ; i<dataPack.log.length ; ++i) {
                var x = dataPack.log[i];
                var hh = parseInt(x.time);
                var ss = hh%60; hh=parseInt(hh/60);
                var mm = hh%60; hh=parseInt(hh/60);
                finalLog += hh + ":" + mm + ":" + ss + "  ---->  " + x.page + "\r\n";
            }
            var url1 = URL.createObjectURL(blob);
            var url2 = "data:application/octet-stream," + encodeURIComponent(finalLog);
            console.log(url1);
            console.log(url2);
            $("#file1").attr("href", url1).attr("download", dataPack.name + "-" + dataPack.part + "-" + (+new Date()) + ".mp3");
            $("#file2").attr("href", url2).attr("download", dataPack.name + "-" + dataPack.part + "-" + (+new Date()) + ".txt");
            var fd = new FormData();
            fd.append('realName', dataPack.name);
            fd.append("ans", JSON.stringify(dataPack.ans));
            fd.append("log", JSON.stringify(dataPack.log));
            fd.append("part", dataPack.part);
            fd.append('fileToUpload', blob);
            window.postData = fd;
            $("#uploadButton").removeAttr("disabled").text("Upload");
        }
        microphone = audioContext.createMediaStreamSource(stream);
        window.audioRecorder = new WebAudioRecorder(microphone, {
            workerDir: "../js/",     // must end with slash
            encoding: "mp3"
        });
        window.audioRecorder.setOptions({
            timeLimit: 3600*5,
            mp3: {
                bitRate: 64
            }
        });
        window.audioRecorder.onComplete = function(recorder, blob) {
            saveRecording(blob, recorder.encoding);
        };
    };

    var onError = function(err) {
        console.log('The following error occured: ' + err);
    };

    navigator.getUserMedia(constraints, onSuccess, onError);
} else {
    console.log('getUserMedia not supported on your browser!');
}

function visualize(stream) {
    var source = audioCtx.createMediaStreamSource(stream);

    var analyser = audioCtx.createAnalyser();
    analyser.fftSize = 2048;
    var bufferLength = analyser.frequencyBinCount;
    var dataArray = new Uint8Array(bufferLength);

    source.connect(analyser);
    //analyser.connect(audioCtx.destination);

    draw();

    function draw() {
        WIDTH = canvas.width;
        HEIGHT = canvas.height;
        requestAnimationFrame(draw);
        analyser.getByteTimeDomainData(dataArray);

        canvasCtx.fillStyle = 'rgba(255, 255, 255, 0.7)';
        canvasCtx.fillRect(0, 0, WIDTH, HEIGHT);

        canvasCtx.lineWidth = 2;
        canvasCtx.strokeStyle = 'rgb(0, 0, 0)';

        canvasCtx.beginPath();

        var sliceWidth = WIDTH * 1.0 / bufferLength;
        var x = 0;


        for(var i = 0; i < bufferLength; i++) {

            var v = dataArray[i] / 128.0;
            var y = v * HEIGHT/2;

            if(i === 0) {
                canvasCtx.moveTo(x, y);
            } else {
                canvasCtx.lineTo(x, y);
            }

            x += sliceWidth;
        }
        canvasCtx.lineTo(canvas.width, canvas.height/2);
        canvasCtx.stroke();
        canvasCtx.closePath();
        // //显示时间
        // if(window.audioRecorder && window.audioRecorder.isRecording()) {
        //     var recordingTime = window.audioRecorder.recordingTime();
        //     var hh = parseInt(recordingTime / 3600); recordingTime -= hh*3600;
        //     var mm = parseInt(recordingTime / 60); recordingTime -= mm*60;
        //     var ss = parseInt(recordingTime);
        //     canvasCtx.save();
        //     canvasCtx.fillStyle = "rgba(0,0,0,0.5)";
        //     canvasCtx.font = '15pt serif';
        //     canvasCtx.textAlign = "center";
        //     canvasCtx.textBaseline = "middle";
        //     canvasCtx.fillText(hh + ":" + mm + ":" + ss, WIDTH/2, HEIGHT/2);
        //     canvasCtx.restore();
        // }
    }
}

