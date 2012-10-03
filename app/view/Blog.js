/**
 * Blog
 */
 Ext.define("spectragram.view.Blog",{ // paramètres de définition
  requires:[
  "Ext.data.proxy.JsonP",
  "Ext.field.Search",
  ],
  extend:"Ext.Container", // étend cette classe
  layout: 'vbox',
  xtype:'blogview', // donner un type à l'objet défini, on peut ensuite appelé l'objet grace à ce nom
  config:{ // configuration de l'objet défini
  title:'Blog',
  iconCls:'star',
  items:[
  // {
  //   xtype:'searchfield',
  //   name:'search',
  //   label:"Search",
  //   flex: 1
  // },
  {
    "xtype": 'list',
    fullscreen: true,
    itemTpl:[
    "<img src='{profile_image_url}' style='max-width:100px;' align='left' />",
    '<strong>{from_user_name}</strong>',
    "{text}"
      ].join(''), // la manière dont l'item de liste sera rendu

      store:{
        autoLoad: true, // chargement automatique des données
        fields:['text',"from_user_name","profile_image_url","create_at","contentSnippet"],
        //@important le proxy doit être déclaré dans le store , sinon pas de requète
      proxy:{ //@note @sencha décrire la façon dont le composant télécharge les données
        type:'jsonp', //appel jsonp puisque qu'on interroge un serveur distant
        url:"https://search.twitter.com/search.json?q=apple",

        reader:{
          type:'json',
          rootProperty:"results" // va chercher la racine de la collection à récupérer
        }
      }
    }
  }
  ]
}
}
);