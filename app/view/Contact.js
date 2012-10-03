/**
 * Contact
 */
 Ext.define("spectragram.view.Contact",{
  requires:[
  "Ext.form.FieldSet",
  "Ext.field.Email"
  ],
  extend:'Ext.form.Panel',
  xtype:"contactview",
  config:{
    "title":"Contact",
    iconCls:"user",
    url:'contact.php',
    items:[
    {
      xtype:'fieldset', //@note @sencha créer une formulaire
      title:'Contact Us',
      instructions:'(email is not required)',
      items:[
      {
        xtype:"textfield",
        "name":"name",
        "label":"Name",
        "required":true
      },
      {
        xtype:"emailfield",
        name:"email",
        label:"Email"
      },
      {
        xtype:"textareafield",
        name:"message",
        "label":"Message"
      }
      ]
    },
    {
      xtype:'button', //@note @sencha créer un bouton
      text:"send",
      cls:'mybutton',
      ui:'confirm',
      handler:function(){ //@note @sencha soumettre le formulaire
        //cherche tout les items associés 
        // à la vue contactview et envoie leur valeurs à
        // l'url définie par le paramètre url
        this.up('contactview').submit();
      }
    }
    ]
  }
});