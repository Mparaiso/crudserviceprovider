/**
 * Search form
 */
Ext.define("spectragram.view.Search",{
  xtype:'searchform',
  extend:"Ext.form.Panel",

  config:{
    iconCls:'search',
    title:'search',

    items:[
      {
        xtype:'fieldset',
        title:'Twitter search',
        instructions:'Search for a word on twitter',

        items:[
          {
            xtype:'searchfield',
            "label":"search",
            required:'true'
          },
          {
            xtype:"button",
            id:'searchFormSendButton',
            text:'send',
            ui:"confirm"
          }
        ]
      }
    ]
  }
});