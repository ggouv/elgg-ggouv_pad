<?php
/**
 * pads French language file
 *
 * package ElggPad
 */

$french = array(

	/**
	 * Menu items and titles
	 */

	'pad' => "Pads",
	'pad:owner' => "Pads de %s",
	'pad:friends' => "Pads de vos abonnements",
	'pad:all' => "Tous les pads du site",
	'pad:add' => "Créer un pad",
	'pad:edit' => "Édition du pad %s",
	'pad:timeslider' => 'Historique',
	'pad:fullscreen' => 'Plein écran',
	'pad:none' => "Aucun pad n'a été créé pour l'instant.",
	'pad:status:closed' => "Fermé",
	'pad:infos:closed' => "&nbsp; %s par %s",

	'pad:group' => 'Pads du groupe',
	'groups:enablepads' => 'Activer les pads pour le groupe',
	'pad:toggle_comment' => "Afficher/masquer les commentaires",
	'pad:toggle_markdown-preview' => "Afficher/masquer la prévisualisation",
	'pad:infos' => "Informations",
	'pad:lastedited' => "Dernière édition %s",
	'pad:revisions' => "%s révisions",
	'pad:contributors' => "Contributeurs :",
	'pad:convert:markdown_wiki' => "Convertir ce pad en page wiki",
	'pad:convert:markdown_blog' => "Convertir ce pad en article de blog",
	'pad:close' => "Fermer ce pad",
	'pad:close:confirm' => "Voulez-vous vraiment fermer ce pad ? Il ne sera plus possible de l'éditer et l'historique sera supprimé. Le texte, les auteurs et le nombre de révisions seront conservés.",
	'pad:create:info' => "{5a **Les pads sont temporaires :**
Les pads sont des **brouillons collaboratifs**, ils ne doivent pas être utilisés comme article définitif.

Au bout de trois mois, si le pad n'a pas été utilisé depuis plus d'un mois, il sera converti en texte. L'historique des modifications sera supprimé. La liste des contributeurs sera conservée.
}",

	/**
	 * River
	 */
	'river:create:object:pad' => "%s a créé le pad %s",
	'river:update:object:pad' => "%s a mis à jour le pad %s",
	'river:comment:object:pad' => "%s a commenté le pad %s",

	'item:object:pad' => 'Pads',

	/**
	 * Status messages
	 */

	'pad:saved' => "Le pad a été enregistré.",
	'pad:delete:success' => "Le pad a été supprimé.",
	'pad:delete:failure' => "Le pad ne peux pas être supprimé.",
	'pad:Empty or No Response from the server' => "Le serveur de pads semble être à l'arrêt. Contactez un membre du groupe !dev pour plus d'informations...",
	'pad:close:success' => "Le pad a été fermé.",
	'pad:close:failure' => "Le pad ne peux pas être fermé.",

	/**
	 * Edit page
	 */

	'pad:title' => "Titre",
	'pad:description' => "Description",
	'pad:tags' => "Tags",
	'pad:access_id' => "Accès en lecture",
	'pad:write_access_id' => "Accès en écriture",

	/**
	 * Admin settings
	 */

	'pad:padhost' => "Adresse de du serveur de l'pad :",
	'pad:padkey' => "Clé API de l'pad lite :",
	'pad:showchat' => "Afficher le chat ?",
	'pad:linenumbers' => "Afficher les numéros de ligne ?",
	'pad:showcontrols' => "Afficher les contrôles ?",
	'pad:monospace' => "Utiliser une font de caractère monospace ?",
	'pad:showcomments' => "Afficher les commentaires ?",
	'pad:newpadtext' => "Text dans un nouveau pad :",
	'pad:pad:message' => 'Le nouveau pad a été créé.',
	'pad:cron:mail:subject' => 'Erreurs lors du cron pad',

	/**
	 * Widget
	 */
	'pad:profile:numbertodisplay' => "Nombre de pads à afficher",
	'pad:profile:widgetdesc' => "Afficher les derniers pads",

);

add_translation('fr', $french);
