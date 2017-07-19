# CMS Wahl-O-Mat: Features of the CMS

## Overview/Überblick

The CMS Wahl-O-Mat (CMS Compass) will be an interactive version of the CMS
Matrix from the CMS Gardener's Guide. Also the database of this application
will be used to generate the CMS Matrix in new editions of the CMS
Gardener's Guide.

This document contains descriptions of all features of the CMS which are
available in the CMS Wahl-O-Mat for comparing the CMS. There are also
serveral other documents describing other aspects for the design of the
CMS-Wahl-O-Mat.

Suggestions for additional features to compare or improvments of the
descriptions of the features are welcome.

Der CMS Wahl-O-Mat soll eine interaktive Version der CMS Matrix aus der
CMS-Gartenfiebel bereitstellen. Außerdem soll die Datenbank des
CMS-Wahl-O-Mat zur Generierung der CMS-Matrix für zukünftige Auflagen der
CMS Gartenfiebel verwendet werden.

Dieses Dokument enthält Beschreibungen aller Features der CMS, die über
den Wahl-O-Mat verglichen werden können. Außerdem sind verschiedene andere
Beschreibungen, die andere Aspekte des Wahl-O-Mat betreffen.

Vorschläge für weitere Features oder Verbesserungen der Beschreibungen von
Features sind immer willkommen.

## Audience/Zielgruppen

This section contains the description of the target audience groups for
which we provide the CMS Wahl-O-Mat and the CMS Matrix.
The following listing provides a template for the description of a target
audience group.

Dieser Abschnitt enthält die Beschreibung der diversen Zielgruppen, für
die wir den CMS-Wahl-O-Mat und die CMS-Matrix zur Verfügung stellen.
Das folgende Listing ist eine Vorlage für die Beschreibung einer
Zielgruppe.

	## Name of Group | Name der Gruppe

	Description | Beschreibung der Gruppe

	### Features/Merkmale:
	* List of | Auflistung der
	* Features which are of interest | der für die
	* for the audience group | Zielgruppe interessanten Features


## Features/Merkmale

This section contains the descriptions of all features which are available
in the CMS-Wahl-O-Mat and in the CMS-Matrix for comparing the CMS.
The features are grouped into categories.

Dieser Abschnitt enthält die Beschreibungen aller Features, über die in
der CMS-Matrix und im Wahl-O-Mat die CMS verglichen werden können.
Die Features sind in Kategorien organisiert.

The Categories/Die Kategorien:

- **Authentication and authorisation** | **Authentifizierung und Authorisierung**:
  Support for single sign on services etc. (e.g. LDAP, Sibboleth) |
   Unterstützung
  für Single-Sign-On etc. (z.B. LDAP, Sibboleth)

- **Content editing** | **Bearbeitung von Inhalten**: Functions for editing and
  creating content | Funktionalität zum Bearbeiten und Erstellen von Inhalten

- **Database** | **Datenbanksystem**: Supported database management systems |
   Unterstützte Datenbankmanagmentsysteme

- **Extensibility** | **Erweiterbarkeit**:

- **General** | **Allgemeine Markmale**: General information about the CMS, e.g.
  License, currrent release etc. | Allgemeine Informationen über das CMS, z.B.
  Lizenz, aktuelles Release etc.

- **Management**: Functions for managing sites and content | Funktionen zum
  Verwalten von Sites und Inhalten

- **Miscellaneous** | **Verschiedenes**: Everything which does not fit into
  another category | Alles was in keine andere Kategorie passt

- **Support** | **Nachhaltigkeit**:

- **Security** | **Sicherheit**:

- **Standards Compliance** | **Standardkonformität**:

- **Technical** | **Technische Merkmale**: Technical properties like programming
  language, required operating system etc. | Technische Merkmale, z.B.
  Programmiersprache, benötigtes Betriebssystem etc.

- **Usability**:

### Authentication and authorisation/Authentifizierung und Authorisierung

- **Granular privileges** | **Granuliertes Rollen-/Rechtemanagement**

- **LDAP authentication** | **LDAP-Authentifizierung**: Is it possible to
  retrieve User information from an LDAP server instead of the database of the
  system? | Können Informationen über die Benutzer von einem LDAP-Server statt
  aus der Systemeigenen Datenbank bezogen werden?

- **User registration with double opt-in** |
  **Benutzerregistierung mit Double-opt-in**

### Content editing/Bearbeitung von Inhalten

### Database/Datenbanksystem

- **Supported free software databases** |
  **Unterstütze Datenbanken (freie Software)**: Which Database Management
  Systems that are avaiable under the terms of a free software license are
  (offically) supported? | Welche unter den Bebindungen einer freien Software
  Lizenz erhältlichen Datenbankmangementsysteme werden (offiziell) unterstützt?
  Examples | Beispiele: MySQL, MariaDB, PostgreSQL, SQLite

- **Commercial databases** | **Kommerzielle Datenbanken**: Which commercial
  Database Managment Systems are (offically) supported? | Welche kommerziellen
  Datenbankmanagementsysteme werden (offiziell) unterstützt? Beispiele |
  Examples: Oracle, DB2, MSSQLServer

- **Multiple databases** | **Multi-DB-fähig**:

### Design

- **Style wizard**

- **Free designs/themes/skins/templates** |
  **frei verfügbare Designs/Themes/Skins/Templates**:

- **Commercial designs/themes/skins/templates** |
  **Kommerziell angebotene Designs/Themes/Skins/Templates**:

- **Configurable page layout** | **konfigurierbares Seitenlayout**:

### Extensibility/Erweiterbarkei

- **Total extensions (compatible with current major release)** |
  **Anzahl Erweiterungen (kompatibel mit aktuellem Major Release)**:

- **REST-API**:

- **JSON-API**:

- **XML-RPC-API**:

- **Web-DAV**:

### General/Allgemeine Markmale

- **Programming language** | **Programmiersprache**: Which is the primary
  programming language in which the CMS is written? | In welcher
  Programmiersprache werden die primären Teile des CMS geschrieben?

- **License** | **Lizenz**: Under the terms of which license(s) is the CMS
   available? | Unter den Bebingungen welcher Lizenz(en) ist das CMS erhältlich?
   Examples | Beispiele: GPLv2 or later, GPLv3 or later, LGPLv2,
   Apache License, BSD License

- **Initial release** | **Erstmals veröffentlicht**: When was the first release
  of the CMS published? | Wann wurde das erste Release des CMS veröffentlicht?

- **Free software as of** | **Freie Software seit**: Since when is the CMS
  available as free software? | Seit wann ist das CMS als freie Software
  erhältlich?

- **Current major release issue date**/
  **Veröffentlichungsdatum aktueller Major Release**: When was the current
  version published? | Wann wurde die aktuelle Version veröffentlicht?

- **Next major release (announced)** | **Nächster Major Release (geplant)**:
  When is the scheduled publication date for the next major release? | Wann
  ist die Veröffentlichung des nächsten Version geplant?

- **LTS-Support**

- **Number of core developers** | **Anzahl Core-Entwickler**:

- **Developers** | **Entwickler/innen**

- **Registered users** | **Anmeldete User**

- **Downloads**

- **Admin interface languages** | **verfügbare Sprachen**: Which languages are
  available for the backend? | In welchen Sprachenist die Verwaltungsoberfläche
  verfügbar?

- **Certification program** | **Zertifierungsprogramm**

### Management

- **Multi-site capability** | **Multisitefähig**

- **Approval workflow** | **Freigabeworkflow**: Is it possible to configure
  the CMS in a way so that content has the be approved by certain party
  before the content becomes visible on the public page? | Kann das CMS so
  konfiguriert werden, dass Inhalte vor der endgültigen Veröffentlichung
  freigeben werden müssen?s

- **Link management** | **Linkverwaltung**

- **Filtertable table of contents** | **Filterbare Inhaltsübersichten**

### Misccellaneous/Verschiedenes

- **Tagging** | **Verschlagwortung**

- **Related content** | **Verwandte Inhalte**

- **Comments** | **Kommentare**

- **A/V media** | **A/V-Medien**

- **Slideshow**

- **Newsletter**

- **(multi-user) Blog**

- **Forum/Bulletin board** | **Forum**

- **Print view (PDF generator)** | **Druckansicht (PDF-Generator)**

- **Send by e-mail** | **Inhalt per E-Mail senden**

- **Calendar** | **Kalendar**

- **configurable user profiles** | **konfigurierbare Nutzerprofile**

- **Form construction kit** | **Formularbaukasten**

- **Social media plugins**

- **Feeds**

- **FAQ**

- **Glossary** | **Glossar**

- **Shop function** | **Shopfunktion**

- **Formula editor** | **Formeleditor**

- **Polls, surveys** | **Umfragen**

- **Quiz tool**

- **Sitemap generator**

### Support/Nachhaltigkeit

- **Commerical offers (services, trainings, hosting)** |
  **Kommerzielle Angebote (Dienstleistungen, Schulungen, Hosting)**

- **Manuals** | **Handbücher**: Are there manuals (books written as complete
   monography) available? Wikis and other collections don't qualify. | Sind
  Handbücher (in Form von Monographien) verfügbar? Wikis oder andere Sammlungen
  reichen hier nicht aus.

- **Developer conferences, training programs** |
  **Entwicklerkonferenzen, Fortbildungsprogramme**

- **Public user/developer platform** |
  **öffentliche Plattform für User/Entwickler**

- **Certification program** | **Zertifizierungsprogramm**

- **Aviablable languages** | **Verfügbare Sprachen**

### Security and Safety | Sicherheit

- **Distinct front-end + backend** | **Frontend + Backend getrennt**

- **Captcha/Honeypot**

- **Notification system** | **Benachrichtiungssystem**

- **Response time to security issues** | **Reaktionszeit auf Sicherheitslücken**

- **Sandbox für Entwicklungen**

- **Controllable backup** | **Steuerbares Backup**

- **Protection/anonymisation of user data** | **Anonymisierung von Nutzerdaten**

### Standards Compliance | Standardkonformität

- **UTF-8 support**

- **HTML 5/CSS 3**

- **Valid markup | valides Markup**

- **Front-end templates WCAG compliant** |
  **Frontend (Templates) BITV-/WCAG-konform**

- **Backend WCAG compliant** | **Backend BITV/WCAG konform**: Complies the
  backend at least to the requirements for the conformance level "A"
  of the WCAG 2.0? | Erfüllt das Backend mindestens the Anforderungen für die
  Konformitätsstufe "A" der WCAG 2.0?

- **Configurable URLs** | **konfigurierbare URLs**

- **Meta data editable per page** | **Metadaten pro Seite editierbar**

- **Development repository** | **Entwicklungs-Repo**

### Technical/Technische Merkmale

### Usability

- **Frontend editing for content** / **Bearbeitung von Inhalten im Frontend**

- **Shortcuts, individual dashboard** | **Shortcuts, individuelle Dashboards**

- **Customizable WYSIWYG editor** | **Anpassbarer WYSIWYG editor**

- **Spell checker** | **Rechtschreibprüfung**

- **Versioning, undo changes** |
  **Versionierung, Rückgängigmachen von Änderungen**: Are changes to the
  content registered? Is it possible to view older versions of the content?
  Is it possible to undo changes? | Werden Änderungen an den
  Inhalten aufgezeichnet? Können ältere Versionen des Inhaltes betrachtet
  werden? Können Änderungen rückgängig gemacht werden?

- **Drag & drop content management** | **Drag & Drop-Inhaltverwaltung**

- **Editioral comments** | **redaktionelle Kommentare**

- **Automation possible (e.g teasers)** | **Automatisierung möglich (z.B. Teaser)**

- **Bulk upload of images/documents** | **Massenupload von Bildern/Dokumenten**

- **Automated image editing (e.g. scaling)** |
  **Automatische Bildverarbeitung (z.B. Skalieren)**: Can the CMS do simple
  image editing tasks like scaling images? | Kann das CMS einfach
  Bildbearbeitungsoperationen, z.B. der Skalieren von Bildern, serverseitig
  durchführen?

- **Multiple use of media content** | **Mehrfachverwendung medialer Inhalte**:
  Is it possible to reuse media content like images on different pages? | Können
  mediale Inhalte (z.B. Bilder) auf mehreren Seiten verwendet werden?

- **Free structuring (menus, categories)** |
  **Freie Strukturierung (Menüs, Rubriken)**

- **Scheduled publishing/unpublishing** |
  **Zeitgesteuertes Publizieren/Verbergen**

- **Full text search** | **Volltextsuche**: Does the CMS provide an integrated
  (no additional software needs to be setup) full text search engine  for the
  content managed by the CMS? | Bietet das CMS eine integrierte (es wird keine
  zusätzliche Software eingerichtet werden) Volltextsuche für die verwalteten
  Inhalte?

- **Customziable/faceted search** | **Anpasspare/facettierte Suche**

- **Searchable assets (PDF, meta data)** | **durchsuchbare Inhalte**
