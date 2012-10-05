/**
 * app/model/Search.js
 * définit le model Search[keyword]
 */
 Ext.define('spectragram.model.Search', {
  extend: 'Ext.data.Model',
  requires:[
  'spectragram.model.Tweet',
  'Ext.data.identifier.Uuid'
  ],
  config: {
    identifier: 'uuid',
    fields: [
    {name: "id", type:'int'},
    {name: 'query', type: 'string'}
    ],
    hasMany: {
      model: "spectragram.model.Tweet",
      name : 'tweets',
      filterProperty: 'query',
      store: {
        pageSize       : 20,
        remoteFilter   : true,
        clearOnPageLoad: false
      }
    },
    proxy: {
      type: 'localstorage',
      id  : 'twitter-searches'
    }
  }
});