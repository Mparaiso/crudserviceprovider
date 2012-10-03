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
    "<h1>Welcome to home</h1>",
    "<p>this is the home page</p>",
    "<p>this is another paragraph</p>",
    "<p>the text of this page is centered</p>",
    "<p>the text of this page is gray</p>"
    ].join("")
  }
});