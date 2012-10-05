Ext.define("spectragram.view.Main", {
    extend: 'Ext.tab.Panel',// Panel : un panel de vues.
    xtype:"mainview",
    id:'mainview',
    requires: [
    'Ext.TitleBar'
    ],
    config: {
        tabBarPosition: 'bottom',

        items: [
        {
            xtype:"searchform"
        },
        {
            xtype:"tweetlist"
        }
        ]
    }
});