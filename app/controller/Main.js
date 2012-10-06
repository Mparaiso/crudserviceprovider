/**
 * app/controller/Main.js
 * controller principal
 */
 Ext.define('spectragram.controller.Main', {
    extend: 'Ext.app.Controller',
    
    config: {
        refs: {
            mainView:"mainview",
            tweetList: "tweetlist list",
            searchForm:"searchform",
            searchField:"searchform searchfield",
            searchFormSendButton:"#searchFormSendButton" //récupere l'id d'un bouton
        },
        control: {
            "tweetList": {
                itemtap: 'showPost' //itemtap : event showpost : handler
            },
            "searchform button":{
                tap:"onSearchFormSubmit" // ajouter un handler sur le bouton référencé
            }
        }
    },
    /** handler **/
    onSearchFormSubmit:function(widget,event,options){
        var query = widget.up('panel').getValues().query;//@note @sencha obtenir les valeurs d'un Ext.form.Panel
        var store = Ext.getStore("Searches");
        //var index = store.find('query',query);
        var model = store.add({query:query});
        store.sync();
        var tweetList = this.getTweetList();
        var tweets = model[0].tweets();
        tweetList.setStore(tweets);
        tweets.load();
        this.getMainView().animateActiveItem( 1, {type: 'slide', direction: 'right'} );
        
    },
    onStoreLoad: function(records, operation, success){
        //var store = Ext.getStore("Searches");

    },
    showPost:function(list,index,element,record){
        list.up("tweetlist").push({ // le controlleur a access à toute les vues de cette façon
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
    //called when the Application is launched, remove if not needed
    // launch: function(app) {
    // }
});