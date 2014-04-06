Plex-EYA
========

EYA, Easy YTS Adder, is a PHP based web interface which pulls the latest releases from the YTS APIs,
cross references them with films you have in your Plex Library (so that you don't re-download them),
and allows a one click interface to add the files to Transmission and displays the current download status.  
Following the download, a simple Filebot script can be run, which processes the downloaded media and
organises nicely for Plex to pick up.

Currently runs on Mac OSX and Linux(Ubuntu 12.04 tested)

The service requires Plex(Plex.tv), Transmission(transmissionbt.com), PHP, mySQL and Filebot(filebot.net).

Known Issues:
============
1. Occasionally, the search for IMDB will yield an incorrect value, resulting in EYA
not detecting that the movie is in your Plex Library.  Ideas on how to fix this are welcome.  
Known films: Run(2013)
2. Search functionality is currently in development and may not work as intended.

Installation Instructions
==============================================
1. Download the repository and place in your web server directory( e.g. htdocs/EYA/)
2. Create a mySQL user & database for EYA to use (take note of the password created)
3. If not already installed, download Transmission(transmissionbt.com) and set up on your device
4. Enable Remote/RPC, instructions vary but can be found online.  It is recommended that you enable authentication on your remote.
5. Open sample.config.inc.php and rename it to config.inc.php, then enter configuration for your set up. (mySQL database/username/password, Transmission RPC username/password and any other configuration you wish to change)
6. If you wish not to use Filebot for organising your downloads, skip to step 9.
7. Install Filebot(filebot.net) for your device.
7. A sample Filebot script is included in the Repository. This is the file I use personally and no guarantees are made. Full Filebot information is found on their website(filebot.net)
8. Configure Transmission to execute a script when a download completes. (instructions differ per platform)
9. Navigate to the install directory in a web browser (e.g. http://localhost/EYA/install)
10. Follow the on screen instructions, if you do not have a separate Plex Library for 3D films, leave that option empty
11. EYA will begin its install and traverse through your Plex Library
12. Once Complete, it is advisable that you remove the install directory
13. Navigate to the EYA directory to begin using EYA.

Operating Instructions
==============================================
By this point I hope you have already followed the instructions above carefully, and that EYA is successfully installed on your system. If you have not installed EYA as explained above, please follow the Installation Instructions

EYA displays the latest 50 movies to be released by torrent website YTS, through the use of their API(http://yts.im/api)
Any films, currently found to your Plex Library will appear green, and will be un-clickable
Films that do not appear green are available for download.
By clicking on a film, EYA will add this file to Transmission, and update the view accordingly.
EYA will show the current status of the download, including the percentage downloaded.
The green bar will begin to fill, as the percentage increases.
Once the download is complete, and you have set up Filebot, the files should be copied across to your Plex Library.


==============================================
I hope I have covered everything in here, if there are any issues please contact us using the issues page.

Jay and Chris
