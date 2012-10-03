Ext.define("spectragram.view.Main", {
    extend: 'Ext.tab.Panel',
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
            xtype:"blogview"
        },
        {
            xtype:'contactview'
        }
        ]
    }
});