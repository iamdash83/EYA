#!/bin/bash
LOG="/etc/transmission-daemon/filebot-process.log"
TR_COMPLETE=$TR_TORRENT_DIR/$TR_TORRENT_NAME
echo "---------------------------------------" >> $LOG
echo $TR_TORRENT_NAME " finished @ " $TR_TIME_LOCALTIME " in " $TR_TORRENT_DIR >> $LOG
if [[ $TR_TORRENT_NAME == *American*Dad* ]]
then
  echo "This is American Dad, skipping" >> $LOG
  exit 0
fi
/usr/bin/filebot -script fn:amc --output "/media/raid/videos" --log-file /etc/transmission-daemon/post-trans-amc.log --action copy --conflict skip -non-strict --def artwork=n music=n  "seriesFormat=TV/{n}/Season {s.pad(2)}/{n} - {s00e00} - {t}"  "movieFormat={fn =~ /3D/ ? '3D Films' : 'Films'}/{fn}" "ut_dir=$TR_TORRENT_DIR/$TR_TORRENT_NAME" "ut_kind=multi" "ut_title=$TR_TORRENT_NAME" >> $LOG
echo "Removing from transmission id " $TR_TORRENT_ID >> $LOG
transmission-remote -t $TR_TORRENT_ID --remove >> $LOG
echo "---------------------------------------" >> $LOG
