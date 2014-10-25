# musicman

Musicman is a web interface for playing music on a linux server running VLC. Files are indexed in a MySQL table for quick and painless access to files which might not be well organized in storage.

## Setup

1. Turn on the "Remote Control" interface in VLC
2. Put the contents of musicman/www somewhere on your server's web site
3. Create database "musicman" and create user 'musicman' with password 'sql'
4. Run musicman.py to index the contents of your hard drive.

  eg; $ python musicman.py -d /my/music/directory
  
