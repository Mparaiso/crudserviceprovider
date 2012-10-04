/**
 * app/controller/Main.js
 * controller principal
 */
Ext.define('spectragram.controller.Main', {
    extend: 'Ext.app.Controller',
    
    config: {
        refs: {
            main:"#mainview",
            tweets:"tweetlist",
            search:"searchform",
            searchFormSendButton:"#searchFormSendButton" //récupere l'id d'un bouton
        },
        control: {
            "tweetlist list":{
                itemtap: 'showPost' //itemtap : event showpost : handler
            },
            "searchform button":{
                tap:"searchFormAlert" // ajouter un handler sur le bouton référencé
            }
        }
    },
    showPost:function(list,index,element,record){
        //console.dir(element);
        //console.dir(record);
        this.getResults().push({
            xtype:'panel',
            title:record.get('from_user_name'),
            data:record._data,
            tpl:[
            "<img src='{profile_image_url}' style='max-width:200px;'/>",
            '<b>{from_user_name}</b><br/>',
            "<p>{text}</p>"
            ].join(''),
            scrollable:true,
            styleHtmlContent:true
        });
    },
    searchFormAlert:function(){
        //console.log("searchFormAlert");
        //console.dir(this);
        //this.config.refs.main.animateActiveItem(1);
        //console.dir(this.getMain());
        this.getMain().animateActiveItem(1,{type: 'slide', direction: 'right'});
    }
    //called when the Application is launched, remove if not needed
    // launch: function(app) {

    // }
});