// Ext.model
Ext.define('spectragram.model.Tweet', {
  extend: 'Ext.data.Model',
  requires:["spectragram.proxy.Twitter"],

  config: {

    fields: [
    {name: "id",type: "int"},
    {name: "text",type: "string"},
    {name: "from_user_name",type: 'string'},
    {name: "created_at", type:'date'},
    {name: "profile_image_url", type:'url'}
    ],

  proxy: { type: 'twitter'}
  }
});