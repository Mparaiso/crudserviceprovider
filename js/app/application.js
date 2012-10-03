define(function(require){

  //point d'entrée de l'application
  var PhotoService = require('./services/photoservice');
  
  var body = document.body;
  var image = document.createElement('image');
  image.style.width="100%";
  var button = document.createElement('button');
  button.innerHTML = "CLICK here";
  button.type = "button";
  var debug = document.createElement("p");

  //body.appendChild(button);
  //body.appendChild(image);
  //body.appendChild(debug);

  var Application = function(){
    var photoService = new PhotoService();
    var photoServiceConfig = photoService.getDefaultConfig();
    button.addEventListener('touchend',function(event){
      photoService.getPhotoUrl(
      function success(uri){
        alert('photo gotten');
        image.src = uri;
        debug.innerHTML = uri;
      },
      function error(message){
        alert(message);
      }
      );
    });
  };
  /** protottype **/
  Application.prototype = {
    pause : function(event){
      alert(event);
    },
    resume : function(event){
      alert(event);
    },
    online  : function(event){
      alert(event);
    },
    offline : function(event){
      alert(event);
    },
    backbutton : function(event){
      alert(event);
    }
  };
  return Application;
});