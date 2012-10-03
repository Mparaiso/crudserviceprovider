#DEVNOTES
@note  @android utiliser phonegap , android, eclipse , adb ,android x86 , virtualbox 
###Installer , configurer android x86
@note @phonegap installer android x86
+ téléchargement : http://www.android-x86.org/
+ installation via virtualbox :
  http://www.android-x86.org/documents/virtualboxhowto
+ http://www.android-x86.org/documents/virtualboxhowto
+ http://software.intel.com/en-us/blogs/2012/03/06/hands-on-notesbuild-android-x86-ics-4-virtualbox-from-google-virtualbox-target-and-intel-kernel

#### android 4 virtualbox : activer le reseau :
http://maketecheasier.com/install-android-4-0-ice-cream-sandwich-in-virtualbox/2012/03/02
<code>
su
dhcpcd eth0
setprop net.dns1 8.8.4.4
</code>
####utiliser adb
http://www.keyables.com/2011/12/using-adb-to-install-apps-on-android.html
<code>
adb connect localhost:5555
adb install term.apk
</code>
####tester une app via eclipse
http://stackoverflow.com/questions/11424435/connected-to-my-android-virtualbox-installation-with-adb-now-how-do-i-install-a

####SENCHA TOUCH
+ Installation
  + télécharger le SDK http://www.sencha.com/products/touch/download/2.0.1.1
  + télécharger le build tool

+ Générer un projet
  + dans le dossier du SDK , tapper la commande
  sencha generate app -n appname -d répertoire

#####Bootstrap
le script app.js est appelé par le loader situé dans sdk/microloader/development.js

#####Namespaces
Les namespaces définis suivent l'architecture du répertoire courant :
+ exemple "myapp.view.Home" se situe dans app/view/Home.js

#####Views
Pour créer une vue , 3 choses : 
+ déclarer la vue dans app.js
(paramètre views )
+ donner un xtype à la vue ( exemple "homeview") dans les options de définition
+ ajouter à la vue en temps qu'item de la vue principale

#####Styles
Pour ajouter une classe à un composant
+ dans sa définition , dans config ajouter le parametre ***cls*** avec le nome de la classe
+ créer le style associé quelque part

#####Formulaires
+ les champs de formulaires peuvent etre contenus dans des vues ou dans des fieldsets (xtype:'fieldset')
+ exemple de formulaire : app/view/Contact.js

#####Listes 
+ voir app/view/Blog.js pour un exemple de définition de liste

#####Ajax
+ voir app/view/Blog.js pour un exemple ajax
