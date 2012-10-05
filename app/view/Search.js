/**
 * Search form
 */
 Ext.define("spectragram.view.Search",{
  xtype:'searchform',
  extend:"Ext.form.Panel",
  requires:["Ext.form.FieldSet"],

  config:{
    iconCls:'search',
    title:'search',
    items:[
    {
      xtype:'fieldset',
      title:'Twitter search',
      instructions:'Search for a word on twitter',
      ui:'searchbar',

      items:[
      {
        xtype:'searchfield',
        "label":"search",
        name:'query',
        placeHolder:'@elvis, #phone',
        required:'true'
      },
      {
        xtype:"button",
        id:'searchFormSendButton',
        name:"submit",
        text:'search',
        ui:"confirm"
      }
      ]
    }
    ]
  }
});