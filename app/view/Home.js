/**
 * HOME
 */
 Ext.define('spectragram.view.Home',{
  extend:'Ext.Panel',
  xtype:'homeview',
  config:{
    title:'Home',
    cls:'home',
    iconCls:'home',
    scrollable:true,
    styleHtmlContent:true,
    html:[
    "<h1>Welcome to Twitter Client</h1>",
    "<p>You can search for tweets",
    "about topics you want , search keywords with the # symbol,",
    "and search users with the @ symbol</p>",
    "<p><b>Exemples: #mobile, #computer, #cloud, @johndoe, @janedone, ...</b></p>"
    ].join("")
  }
});