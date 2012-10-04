/**
 * Tweets
 */
 Ext.define("spectragram.view.Tweets",{ // paramètres de définition
  requires:[
  "Ext.data.proxy.JsonP",
  "Ext.field.Search",
  "Ext.dataview.*",
  ],
  extend:"Ext.NavigationView", // étend cette classe
  layout: 'vbox',
  xtype:'tweetlist', // donner un type à l'objet défini, on peut ensuite appelé l'objet grace à ce nom
  config:{ // configuration de l'objet défini
  title:'Tweets',
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
    title:"Twitter",
    itemTpl:[
    "<img src='{profile_image_url}' style='max-width:100px;' align='left' />",
    '<b>{from_user_name}</b><br/>',
    "{[values.text.slice(0,50)]}[...]"
      ].join(''), // la manière dont l'item de liste sera rendu

      store:{
        autoLoad: true, // chargement automatique des données
        fields:['text',"from_user_name","profile_image_url","created_at","contentSnippet"],
        //@important le proxy doit être déclaré dans le store , sinon pas de requète
      proxy:{ //@note @sencha décrire la façon dont le composant télécharge les données
        type:'jsonp', //appel jsonp puisque qu'on interroge un serveur distant
        url:"https://search.twitter.com/search.json?q=%23apple&lang=en&rpp=30",

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