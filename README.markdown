tm2ta is a simple script to try to convert TextAdept themes to TextMate themes.
If you do not know what TextAdept is, I am surprised you're reading this.
If you still care, TextAdept is a minimalist text editor that is insanely extensible with Lua, and (this is its homepage)[http://textadept.caladbolg.net].

# Install

Well, there is an easy way and a hard way to do this.

### The Easy Way

Clone this repository. Every time you want to convert a file, do this:

    php -q tm2ta.php -f theme.tmTheme

Could get annoying though.

### The Hard Way

Clone this repository, or just download the script. Just get tm2ta.php on your machine.
This command could help:

    curl http://github.com/jdp/tm2ta/raw/master/tm2ta.php > tm2ta.php

Now, go to a directory where you can put executables.
On my machine, `~/bin` works but I had to add that to my shell config file.
Now make a symbolic link to the `tm2ta.php` file:

    ln -s /path/to/tm2ta.php tm2ta

Make it executable:

    chmod u+x tm2ta

Now you can invoke it just by using the `tm2ta` command.
