/**
 * gère l'aquisition de photos de l'application
 */
 define(function(require,exports,module){
  var PhotoService=new Function();
  PhotoService.prototype={
    getDefaultConfig: function(){
      return{
        quality:50,
        destinationType:Camera.DestinationType.DATA_URL,
        sourceType:2
      };
    },
    /**
     * get url
     */
    getPhotoUrl:function(success,fail,config){
      config = config || this.getDefaultConfig();
      config.destinationType = Camera.DestinationType.FILE_URL;
      return this._getPhoto(success,fail,config);
    },
    getPhotoDataUrl:function(success,fail,config){
      config = config || this.getDefaultConfig();
      this._getPhoto(function onSuccess(data){
        var dataUrl = "data:image/jpeg;base64,"+data;
        return success(data);
      },fail,config);
    },
    _getPhoto: function(onSuccess,onFail,config){
      config = config || this.getDefaultConfig();
      try{
        navigator.camera.getPicture(onSuccess,onFail,config);
      }catch(error){
        alert(error);
      }
    }
  };
  return PhotoService;
});