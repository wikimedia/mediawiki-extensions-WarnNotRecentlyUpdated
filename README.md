WarnNotRecentlyUpdated extension checks the last modified date of content pages
and print a warning if the page is older than a specific delay.

The intent is to be used for manuals/checklists/documentation for teams where
information is known to rot quickly, and where there is a very good chance
information older than a certain age can be legacy or are dangerous to trust like:

  - network/devops/SRE/operations/systems/infrastructure
  - legal
  - fiscal

To install this extension to your MediaWiki installation:

	cd extensions
	git clone https://gerrit.wikimedia.org/r/mediawiki/extensions/WarnNotRecentlyUpdated.git

Then add the required settings in LocalSettings.php. Documentation is available at
https://www.mediawiki.org/wiki/Extension:WarnNotRecentlyUpdated

To see the extension in action you can browse the Nasqueron Operations grimoire
located at https://agora.nasqueron.org/Operations_grimoire and find older content.
