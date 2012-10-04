Ext.define("spectragram.view.Main", {
    extend: 'Ext.tab.Panel',// Panel : un panel de vues.
    xtype:"mainview",
    id:'mainview',
    requires: [
    'Ext.TitleBar',
    'Ext.Video'
    ],
    config: {
        tabBarPosition: 'bottom',

        items: [
        {
            xtype:"homeview"
        },
        {
            xtype:"tweetlist"
        },
        {
            xtype:"searchform"
        },
        {
            xtype:'contactview'
        }
        ]
    }
});