
Admin Rainbow Headline
======================

 - Version: 1.8
 - Author: Nils Werner (nils.werner@gmail.com)
 - Build Date: 2011-02-02
 - Requirements: Symphony 2.2

About
-----

Have you ever had two or more installations of Symphony open in several tabs? Have you ever had problems differentiating those tabs, making changes to one of those sites that were supposed to go into another one?

Admin Rainbow Headline comes to the rescue. It lets you set any RGB-Color for the headline (the dark grey block at the top of Symphonys backend), making it easier to differentiate them.

Installation
------------

1. Upload the 'admin_rainbow_headline' folder in this archive to your Symphony 'extensions' folder.

2. Enable it by selecting the "Admin Rainbow Headline" entry, choose Enable from the with-selected menu, then click Apply.

3. You can now set the headline color in your preferences.

Changelog
---------

**1.0**

- Initial release

**1.1**

- Added generation of favicon based on chosen color.

**1.2**

- Added support for color chooser field popup. Get it from http://github.com/MrBlank/symphony_color_chooser/tree/ to enable the popup.

**1.3**

- Switched favicon format from "image/vnd.microsoft.icon" using inofficial imagebmp()-functions to "image/png". Internet Explorer will not display the favicon.

**1.4**

- Added support for translations.

**1.5**

- Foreground colors are now luminance-aware. This means if you pick a bright color, the text will switch to black to be properly readable.

**1.6**

- Added compatibility with the new version info integrated into Symphony 2.1

**1.7**

- Added a check for GD library. Before, if it wasn't installed, the whole backend would simply break.

**1.8**

- Ensured compability to Symphony 2.2
