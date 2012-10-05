/**
 * app/store/Searches.js
 */
 Ext.define("spectragram.store.Searches",{
  extend:"Ext.data.Store",
  requires:['spectragram.model.Search'],
  config:{
    model:"spectragram.model.Search"
  }
});