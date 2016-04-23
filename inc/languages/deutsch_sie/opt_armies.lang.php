<?php

// temporary workaround till translation is done

require_once MYBB_ROOT . 'inc/languages/english/opt_armies.lang.php';

// german

// general use
$l['opt_armies_title'] = 'OPT Armies';
$l['opt_armies_uid_unknown'] = 'unbekannt';

// ACP
$l['opt_armies_can_manage_armies'] = 'Kann Armeen verwalten?';

$l['opt_armies_registration_open_title'] = 'Registrierung geöffnet?';
$l['opt_armies_registration_open_description'] = 'Benutzer können nur bei geöffneter Registrierung Armeen beitreten.';
$l['opt_armies_registration_random_only_title'] = 'Nur zufällig ausgewählten Armeen beitreten?';
$l['opt_armies_registration_random_only_description'] = 'Wenn aktiviert, dann kann die Armee bei der Registrierung nicht ausgewählt werden, stattdessen wird der Benutzer einer zufällig ausgewählten (offenen) Armee zugewiesen.';
$l['opt_armies_max_member_difference_title'] = 'Maximaler Teamunterschied?';
$l['opt_armies_max_member_difference_description'] = 'Wieviele Mitglieder darf eine offene Armee mehr haben als die kleinste geöffnete Armee?';

$l['opt_armies_list_armies'] = 'Armeenliste';
$l['opt_armies_list_armies_description'] = 'Aktuell definierte Armeen';
$l['opt_armies_add_army'] = 'Armee hinzufügen';
$l['opt_armies_add_army_description'] = 'Neue Armee anlegen, nur die Basisinformationen werden hier abgefragt.';
$l['opt_armies_army_shortcut'] = 'Abkürzung';
$l['opt_armies_army_name'] = 'Armeenname';
$l['opt_armies_army_nation'] = 'Nation';
$l['opt_armies_army_leader'] = 'Anführer';
$l['opt_armies_army_icon'] = 'Armeenbild';
$l['opt_armies_edit_army'] = 'Armee bearbeiten';
$l['opt_armies_edit_army_description'] = 'Die Basisinformationen einer Armee bearbeiten.';
$l['opt_armies_delete_army'] = 'Armee löschen';
$l['opt_armies_delete_army_question'] = 'Willst Du wirklich diese Armee löschen? Das beinhaltet auch alle Armeengruppen!';
$l['opt_armies_update_order'] = 'Ansichtsortierung aktualisieren';
$l['opt_armies_no_armies'] = 'Keine Armeen in der Datenbank gefunden';
$l['opt_armies_table_armies'] = 'Verfügbare Armeen';
$l['opt_armies_error_no_army_shortcut'] = 'Die Abkürzung der Armee fehlt.';
$l['opt_armies_error_no_army_name'] = 'Der Name der Armee fehlt.';
$l['opt_armies_error_no_leader'] = 'Der Anführer der Armee fehlt.';
$l['opt_armies_army_added'] = 'Armee hinzugefügt';
$l['opt_armies_army_edited'] = 'Armee bearbeitet';
$l['opt_armies_config_army'] = 'Armee konfigurieren';
$l['opt_armies_config_army_description'] = 'Armeenstruktur konfigurieren (d.h. z.B. "Squads", inklusive einer Hierarchie)';
$l['opt_armies_army_displayorder'] = 'Anzeigereihenfolge';
$l['opt_armies_army_primary_group'] = 'Primäre Armeengruppe';
$l['opt_armies_army_default_group'] = 'Standard-Armeengruppe (für neue Rekruten)';
$l['opt_armies_army_hco_group'] = 'Heeresleitungsgruppe';
$l['opt_armies_army_co_group'] = 'Offiziersgruppe';
$l['opt_armies_army_welcome_pm_template'] = 'Vorlage für die Willkommens PM. Es können die folgenden Platzhalter verwendet werden:
<ul>
<li>{username} - der Benutzername</li>
<li>{army_name} - der Armeenname</li>
<li>{army_leader} - der Name des Anführers der Armee</li>
</ul>';
$l['opt_armies_welcome_pm_subject'] = 'Willkommen Soldat!';
$l['opt_armies_army_welcome_pm_template_default'] = 'Willkommen Rekrut {username} zur {army_name}!

Melde Dich in der Ausbildungskompanie (URL here) und informiere Dich dort, welches Deine nächsten Schritte sind.


Mit freundlichen Grüßen,

{army_leader}';
$l['opt_armies_error_no_pm'] = 'Fehlende PM Vorlage';
$l['opt_armies_table_groups'] = 'Armeengruppen';
$l['opt_armies_army_is_locked'] = 'Ist die Armee geschlossen?';
$l['opt_armies_army_is_locked_2'] = 'Benutzer können geschlossenen Armeen nicht beitreten.';
$l['opt_armies_army_is_invite_only'] = 'Ist die Armee "nur auf Einladung" verfügbar?';
$l['opt_armies_army_is_invite_only_2'] = 'Benutzer müssen in die Armee eingeladen werden wenn diese Option gesetzt ist.';
$l['opt_armies_army_locked'] = 'Die Armee ist geschlossen';
$l['opt_armies_army_invite_only'] = 'Die Armee ist "nur auf Einladung"';
$l['opt_armies_configure_army'] = 'Armee konfigurieren';
$l['opt_armies_configure_army_groups'] = 'Armeengruppen konfigurieren';
$l['opt_armies_this_army'] = 'Details über diese Armee';
$l['opt_armies_group_name'] = 'Forumgruppen Name';
$l['opt_armies_group_shortcut'] = '"Tag" der Gruppe';
$l['opt_armies_parent_group_name'] = 'Name der übergeordneten Gruppe (Achtung: keine Schleifen erzeugen!)';
$l['opt_armies_group_leader'] = 'Gruppenführer';
$l['opt_armies_no_groups'] = 'Keine Gruppen für diese Armee in der Datenbank gefunden';
$l['opt_armies_add_army_group'] = 'Armeegruppe hinzufügen';
$l['opt_armies_add_army_group_description'] = 'Neue Untergruppe für diese Armee anlegen.';
$l['opt_armies_edit_group'] = 'Eine Armeengruppe bearbeiten';
$l['opt_armies_no_parent_group'] = 'Keine übergeordnete Gruppe (nur die Armee ist übergeordnet)';
$l['opt_armies_add_group'] = 'Eine Armeengruppe hinzufügen';
$l['opt_armies_add_group_description'] = 'Eine Armeengruppe hinzufügen, einschließlich Gruppenführer und Forengruppe.';
$l['opt_armies_edit_group'] = 'Diese Armeengruppe bearbeiten';
$l['opt_armies_edit_group_description'] = 'Den Gruppenführer und die Forengruppe dieser Armeengruppe bearbeiten.';
$l['opt_armies_delete_group'] = 'Diese Armeengruppe löschen';
$l['opt_armies_group_added'] = 'Eine Armeengruppe wurde hinzugefügt.';
$l['opt_armies_group_edited'] = 'Eine Armeengruppe wurde bearbeitet.';
$l['opt_armies_error_agrid_same_as_pagrid'] = 'Die Forengruppen und die übergeordnete Forengruppe dürfen nicht übereinstimmen...';
$l['opt_armies_error_no_group_shortcut'] = 'Die Abkürzung (Tag) der Gruppe fehlt.';
$l['opt_armies_error_no_group_leader'] = 'Der Anführer der Gruppe fehlt.';
$l['opt_armies_no_group_selected'] = ' << keine Gruppe ausgewählt >> ';
$l['opt_armies_error_no_group_selected'] = 'Fehlende Forengruppe für die Armeengruppe.';
$l['opt_armies_error_no_unknown_user'] = 'Unbekannter Benutzer "{username}"';
$l['opt_armies_error_invalid_parent_group'] = 'Ungültige übergeordnete Forengruppe ausgewählt. Die Forengruppe muss einer Armeengruppe zugeordnet sein.';
$l['opt_armies_delete_group_question'] = 'Willst Du wirklich diese Gruppe löschen? Damit werden auch alle untergeordneten Gruppen gelöscht!';
$l['opt_armies_add_user'] = 'Einen Benutzer zu dieser Armee hinzufügen';
$l['opt_armies_add_user_description'] = 'Du kannst jeden Benutzer zu der Armee hinzufügen, bedenkte gut, was Du da tust.';
$l['opt_armies_error_no_username'] = 'Fehlender Benutzername';
$l['opt_armies_user_added'] = 'Der Benutzer "{username}" wurde zur Armme "{army_name}" hinzugefügt.';


// misc pages
$l['opt_armies_page_title'] = 'OPT Armies';
$l['ok'] = 'Ok';
$l['cancel'] = 'Abbruch';
$l['error'] = 'Fehler';


// ...selectarmy...
$l['opt_armies_select_army'] = 'Wähle eine Armee aus';
$l['opt_armies_page_list_army'] = 'Armee';
$l['opt_armies_registration_closed'] = 'Die Registrierung ist zur Zeit geschlossen. Bitte siehe im Forum nach, wann sie wieder geöffnet wird.';
$l['opt_armies_registration_random_only'] = 'Es ist nur die zufällige Registrierung erlaubt, bitte benutze den "Einer zufälligen Armee beitreten" Link.';
$l['opt_armies_select_army_welcome'] = 'Trete einer Armee bei!';
$l['opt_armies_select_army_description'] = 'Hallo Soldat!
<p>
Hier kannst Du auswählen, welcher Armee Du beitreten möchtest.
</p>
<p>
Es sind nicht immer alle Armeen verfügbar, eventuell hat eine Armee deutlich mehr Mitglieder als eine andere Armee und ist damit temporär geschlossen.
Zudem sind einige Armee nur auf Einladung verfügbar.
</p>';
$l['opt_armies_random_army_title'] = 'Einer zufälligen Armee beitreten!';
$l['opt_armies_random_army_description'] = 'Wähle diese Option aus um einer zufälligen Armee beizutreten:';
$l['opt_armies_available_armies'] = 'Wähle Deine Armee';
$l['opt_armies_select_army_options'] = 'Trete dieser Armee bei';
$l['opt_armies_error_already_in_army'] = 'Du bist bereits Mitglied der Armee "{army_name}"!';
$l['opt_armies_no_icon'] = 'Kein Bild vorhanden';
$l['opt_armies_army_status'] = 'Status';
$l['opt_armies_army_status_open'] = 'offen';
$l['opt_armies_army_status_locked'] = 'geschlossen';
$l['opt_armies_army_status_temp_locked'] = 'temporär geschlossen (z.B. weil die Armee zuviele Mitglieder hat)';
$l['opt_armies_army_status_invite_only'] = 'nur auf Einladung';
$l['opt_armies_army_status_random'] = 'nur zufällige Armeenauswahl aktiv';
$l['opt_armies_select_army_option_join'] = 'Jetzt beitreten!';
$l['opt_armies_select_army_option_none'] = 'Beitritt nicht möglich';

// ...joinarmy...
$l['opt_armies_join_army'] = 'Der Armee "{army_name}" beitreten';
$l['opt_armies_join_army_confirmation'] = 'Bestätige den Beitritt';
$l['opt_armies_join_army_confirmation_text'] = 'Bist du dir sicher, dass Du dieser Armee beitreten willst? Nach der Bestätigung kannst Du ohne Hilfe keiner anderen Armee beitreten!';
$l['opt_armies_army_joined'] = 'Du bist erfolgreich der Armee "{armyname}" beigetreten!';
$l['opt_armies_confirm_join_request'] = 'Bestätige den Beitritt';
$l['opt_armies_cancel_join_request'] = 'Zurückziehen';
$l['opt_armies_join_request_canceled'] = 'Du hast deine Beitrittsanfrage zurückgezogen.';
$l['opt_armies_join_request_done'] = 'Du bist nun ein Rekrut der Armee "{army_name}".';

// ...showarmies...
$l['opt_armies_show_armies'] = 'Zeige Armeen';
$l['opt_armies_show_armies_description'] = 'Dies ist eine Übersicht aller Armeen mit deren Strukturen und Mitgliedern.';
$l['opt_armies_group_shortcut'] = 'Tag';
$l['opt_armies_group'] = 'Gruppe';
$l['opt_armies_group_leader'] = 'Gruppenführer';
$l['opt_armies_group_XOs'] = 'Gruppenassistenten';
$l['opt_armies_group_members'] = 'Gruppenmitglieder';
$l['opt_armies_army_is_locked'] = 'geschlossen<br>(kein Beitritt möglich)';
$l['opt_armies_army_is_invite_only'] = 'nur auf Einladung<br>(Ein Admin muss dich einladen)';
$l['opt_armies_army_is_open'] = 'offen';
$l['opt_armies_manage_group'] = 'Du kannst diese Gruppe verwalten';
$l['opt_armies_manage_all'] = 'alle auswählen';
$l['opt_armies_member_action_menu'] = 'Mitglieder verwalten';
$l['opt_armies_member_changerank'] = 'Ränge der Mitglieder ändern';
$l['opt_armies_no_option_selected'] = '&lt;&lt;wähle eine Option&gt;&gt;';
$l['opt_armies_member_transfer'] = 'Mitglieder versetzen';
$l['opt_armies_manage_XOs'] = 'verwalten';
$l['opt_armies_manage_CO'] = 'verwalten';
// $l['opt_armies_make_officer'] = 'make officer';
// $l['opt_armies_make_HCO'] = 'make HCO';
$l['opt_armies_kick_member'] = 'Mitglied aus der Armee entfernen';
$l['opt_armies_army_members'] = 'Mitglieder';

// ...randomarmy...
$l['opt_armies_all_armies_closed'] = 'Alle Armeen sind derzeit geschlossen, daher ist leider kein Beitritt möglich.';
$l['opt_armies_join_random_army'] = 'Einer zufälligen Armee beitreten';
$l['opt_armies_join_random_army_confirmation_text'] = 'Bist du dir sicher, dass du einer zufällige ausgewählten Armee beitreten möchtest? Eine nachträgliche Änderung kann ohne Hilfestellung nicht erfolgen!';

// ...managegroup...
$l['opt_armies_manage_group_title'] = 'Gruppe verwalten';
$l['opt_armies_error_unknown_manage_action'] = 'Unbekannte Gruppenverwaltungsaktion.';
$l['opt_armies_error_unknown_member_action'] = 'Unbekannte Mitgliederaktion.';
$l['opt_armies_manage_group_leader_title'] = 'Anführer der Gruppe verwalten';
$l['opt_armies_select_group_leader'] = 'Wähle den neuen Anführer der Gruppe:';
$l['opt_armies_new_group_leader'] = '{username} ist nun der neue Anführer der Gruppe "{groupname}".';
$l['opt_armies_manage_group_assistants_title'] = 'Verwalte die Gruppenassistenten';
$l['opt_armies_select_group_assistants'] = 'Wähle die neuen Gruppenassistenten:';
$l['opt_armies_group_assistants_updated'] = 'Die Gruppenassistenden der Gruppe "{groupname}" wurden aktualisiert.';
$l['opt_armies_cancel_manage'] = 'Operation abgebrochen.';
$l['opt_armies_kick_group_members_title'] = 'Mitglieder aus der Armee entfernen';
$l['opt_armies_kick_group_members'] = 'Mitglieder entfernen';
$l['opt_armies_group_members_kicked'] = 'Es wurden einige Mitglider aus der Armee "{armyname}" entfernt.';
$l['opt_armies_transfer_group_members_title'] = 'Versetze Mitglieder zu einer anderen Armeengruppe. Aktuelle Gruppe';
$l['opt_armies_transfer_group_members'] = 'Zu versetzende Mitglieder auswählen:';
$l['opt_armies_transfer_group_members_select_target'] = 'Wähle die neue Armeengruppe für diese Mitglieder:';
$l['opt_armies_group_members_transfered'] = 'Es wurden einige Mitglieder von der Gruppe "{groupname_old}" zur Gruppe "{groupname_new}" versetzt.';
$l['opt_armies_changerank_group_members_title'] = 'Mitglieder befördern/ oder degradieren';
$l['opt_armies_changerank_group_members'] = 'Die zu befördernden/degradierenden Mitglieder auswählen:';
$l['opt_armies_changerank_group_members_select_rank'] = 'Neuen Rang auswählen. Der alte Rang spielt dabei keine Rolle, also sei vorsichtig, was du da tust. Degradierte Heeresleiter werden zu Rekruten zurück gestuft!:';
$l['opt_armies_group_members_rank_changed'] = 'Die Ränge einiger Mitglider der Gruppe "{groupname}" wurden geändert.';
$l['opt_armies_rank_group_enlisted'] = 'Mannschaften';
$l['opt_armies_rank_group_officers'] = 'Offiziere';
$l['opt_armies_rank_group_HCOs'] = 'Heeresleitung';
$l['opt_armies_rank_group_civilian'] = 'Zivilisten';
$l['opt_armies_error_invalid_member_selection'] = 'Ungültige Mitgliederauswahl. Es kann niemand mit dem gleichen oder höheren Rang degradiert oder befördert werden als man selber innehat.';

$l['opt_armies_error_no_members_selected'] = 'Keine Mitglieder ausgewählt!';
$l['opt_armies_cannot_promote_recuits_directly'] = 'Rekruten müssen erst in eine reguläre Gruppe versetzt werden, sonst können sie nicht befördert werden. Benutzer=';
$l['opt_armies_demoting_officer_workaround'] = 'Ein Offizier soll zu einem Mannschaftsdienstgrad degradiert werden, ist jedoch nicht Mitglied einer regulären Gruppe, daher wird er wieder zum Rekruten. Benutzer=';
$l['opt_armies_demoting_HCO_workaround'] = 'Die Degradierung eines Heeresleiters macht diesen automatisch wieder zu einem Rekruten! Benutzer=';
$l['opt_armies_promoting_enlisted_workaround'] = 'Ein Soldat mit Mannschaftsdienstgrad soll zum Offizier befördert werden, ist aber in keiner regulären Gruppe. Er wird daher wieder zum Rekruten! Benutzer=';
$l['opt_armies_return_to_showarmies'] = 'Hier klicken um zur Armeenansichtsseite zurück zu kehren.';


// german

?>