<?php
/**
 * Etherpads French language file
 *
 * package ElggPad
 */

$french = array(

	/**
	 * Menu items and titles
	 */

	'etherpad' => "Pads",
	'etherpad:owner' => "Pads de %s",
	'etherpad:friends' => "Pads de vos abonnements",
	'etherpad:all' => "Tous les pads du site",
	'etherpad:add' => "Créer un pad",
	'etherpad:timeslider' => 'Historique',
	'etherpad:fullscreen' => 'Plein écran',
	'etherpad:none' => "Aucun pad n'a été créé pour l'instant.",
	'etherpad:status:closed' => "Fermé",
	'etherpad:infos:closed' => "&nbsp; %s par %s",

	'etherpad:group' => 'Pads du groupe',
	'groups:enablepads' => 'Activer les pads pour le groupe',
	'etherpad:toggle_comment' => "Afficher/masquer les commentaires",
	'etherpad:infos' => "Informations",
	'etherpad:lastedited' => "Dernière édition %s",
	'etherpad:revisions' => "%s révisions",
	'etherpad:contributors' => "Ont contribué à ce pad :",
	'etherpad:convert:markdown_wiki' => "Convertir ce pad en page wiki",
	'etherpad:convert:markdown_blog' => "Convertir ce pad en article de blog",
	'etherpad:create:info' => "{5a **Les pads sont temporaires :**
Les pads sont des **brouillons collaboratifs**, ils ne doivent pas être utilisés comme article définitif.

Au bout de trois mois, si le pad n'a pas été utilisé depuis plus d'un mois, il est converti en texte. Tout l'historique des modifications est supprimé.
}",

	/**
	 * River
	 */
	'river:create:object:etherpad' => "%s a créé le pad %s",
	'river:update:object:etherpad' => "%s a mis à jour le pad %s",
	'river:comment:object:etherpad' => "%s a commenté le pad %s",

	'item:object:etherpad' => 'Pads',

	/**
	 * Status messages
	 */

	'etherpad:saved' => "Le pad a été enregistré.",
	'etherpad:delete:success' => "Le pad a été supprimé.",
	'etherpad:delete:failure' => "Le pad ne peux pas être supprimé.",
	'etherpad:Empty or No Response from the server' => "Le serveur de pads semble être à l'arrêt. Contactez un membre du groupe !dev pour plus d'informations...",

	/**
	 * Edit page
	 */

	'etherpad:title' => "Titre",
	'etherpad:description' => "Description",
	'etherpad:tags' => "Tags",
	'etherpad:access_id' => "Accès en lecture",
	'etherpad:write_access_id' => "Accès en écriture",

	/**
	 * Admin settings
	 */

	'etherpad:etherpadhost' => "Adresse de du serveur de l'etherpad :",
	'etherpad:etherpadkey' => "Clé API de l'etherpad lite :",
	'etherpad:showchat' => "Afficher le chat ?",
	'etherpad:linenumbers' => "Afficher les numéros de ligne ?",
	'etherpad:showcontrols' => "Afficher les contrôles ?",
	'etherpad:monospace' => "Utiliser une font de caractère monospace ?",
	'etherpad:showcomments' => "Afficher les commentaires ?",
	'etherpad:newpadtext' => "Text dans un nouveau pad :",
	'etherpad:pad:message' => 'Le nouveau pad a été créé.',
	'etherpad:cron:mail:subject' => 'Erreurs lors du cron etherpad',

	/**
	 * Widget
	 */
	'etherpad:profile:numbertodisplay' => "Nombre de pads à afficher",
	'etherpad:profile:widgetdesc' => "Afficher les derniers pads",

);

add_translation('fr', $french);
