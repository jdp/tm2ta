tm2ta is a simple script to try to convert TextMate themes to TextAdept themes.
If you do not know what TextAdept is, I am surprised you're reading this.
If you still care, TextAdept is a minimalist text editor that is insanely extensible with Lua, and [this is its homepage](http://textadept.caladbolg.net).

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

Now, `cd` to a directory where you can put executables.
On my machine, `~/bin` works but I had to add that to my shell config file.
Now make a symbolic link to the `tm2ta.php` file:

    ln -s /path/to/tm2ta.php tm2ta

Make it executable:

    chmod u+x tm2ta

Now you can invoke it just by using the `tm2ta` command.

# Usage

tm2ta does not make a whole theme for you.
TextAdept's theming system is still too messy for that.
What it does do is extract the properties from unbelievably ugly tmTheme XML and format them as Lua.

### Basic Usage

The most basic usage is just specifying an input file with the -f switch.
It will print the Lua code to stdout.

    tm2ta -f theme.tmTheme

Here's the output of the [Argonaut](http://www.tmthemes.com/theme/Argonaut/) TextMate theme:

    style_comment = style { italic = true, fore = color('00','A6','FF') }
    style_string = style { fore = color('64','97','C5') }
    style_constant = style { bold = true, fore = color('A4','ED','2D') }
    style_preprocessor = style { fore = color('7B','9A','00') }
    style_function = style { bold = true, fore = color('FF','CA','00') }

### Getting Fancy

If you want to output somewhere other than stdout, just specify the outfile with the -o switch.

    tm2ta -f theme.tmTheme -o lexer.lua

That it pretty much it.

# Problems

* TextAdept's theming system is messy. You'll still have to change stuff like caret and indent guide colors manually.
* I don't use Macs. I don't use TextMate. If I left out any properties, add them to the `$ident_map` variable and send a pull request.
