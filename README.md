Mod Crew Bio Awards
====================
Nova2 Modification (Anodyne Productions)

Created by Moriel Schottlender (mooeypoo@gmail.com)

-

## Description
This modification adds a the award images and popover descriptions (reasons) to the crew bio pages.

## Important Note
Just like any other modification for Nova, please take great care when editing controllers. Especially when updating or upgrading the mod, make sure you COMPLETELY delete the associated functions and then COMPLETELY replace them with the new functions.
If your controller file was previously edited, be careful not overriding the changes you already made to it.

## Installation

1. Open [your domain]/application/controllers/personnel.php controller, and copy the entire function character() { } segment into the one in your domain. For your convenience, the function begins and ends with

```
	/**********************/
	/**** CREW BIO MOD ****/
	/**********************/
```

2. **IF YOUR VIEW FILES WERE NOT PREVIOUSLY EDITED**, Upload the [mod]/application/views folder to [your domain]/application/views/

### NOTE (Narrow fix)
Anodyne released a "narrow" fix for the crew bios. If you use this fix, you can still use this mod -- just upload the file 'personnel_character-narrow.php' from the applications/views/_base_override/main/pages/ folder to your domain, and then rename the file to remove the -narrow.
In other words, there are two options for the view files in the mod folder -- the regular view, and the narrow view, and you can use either one.

### NOTE (Previously Edited Files)
If you already edited these files for another mod, you will have to be careful manually managing this extension into the existing files. I only recommend you do that if YOU REALLY KNOW WHAT YOU'RE DOING! 

I have marked all the changed/additions in the view file with HTML/PHP comments, so you should just find where they are and plug them into your previously-edited file.

Credits and help
================
I apologize in advance, I'm a student and I have a full time job, so I don't have as much time as I'd like to help and correct bugs. However, it does matter to me, so please take the time to report whatever bug you find in the "issues" on github.

Alternatively, you can go to the Anodyne Productions forum and catch me up there.

Enjoy!

~mooeypoo
mooeypoo@gmail.com