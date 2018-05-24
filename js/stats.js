
function radioTitle() {

    var url = 'http://totripto.com:8000/json.xsl';

$.ajax({
   type: 'GET',
    url: url,
    async: true,
    jsonpCallback: 'parseMusic',
    contentType: "application/json",
    dataType: 'jsonp',
    success: function(json) {
        // this is the element we're updating that will hold the track title
       $('#track-title').text(json['/turntable.mp3']['title']);
        // this is the element we're updating that will hold the listeners count
       $('#listeners').text(json['/turntable.mp3']['listeners']);
       // this is the element we're updating that will hold the listeners count
       $('#bitrate').text(json['/turntable.mp3']['bitrate']);
        },
    error: function(e) {
       console.log(e.message);
    }
});

}

$(document).ready(function(){

  setTimeout(function(){radioTitle();}, 2000);
  setInterval(function(){radioTitle();}, 15000); // we're going to update our html elements / player every 15 seconds

});

