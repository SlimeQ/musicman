import eyed3
from optparse import OptionParser
import os
import MySQLdb
from unidecode import unidecode

def log(message, log='dbbuild.log'):
	with open(log, 'a') as f:
		f.write(message + '\n')

def add(cur, rootdir, song):
	print song
	try:
		audiofile = eyed3.load(song)
		print audiofile.tag
		if audiofile.tag == None or None in [audiofile.tag, audiofile.tag.title, audiofile.tag.artist, audiofile.tag.album]:
			return


		path = song
		extention = song.split('.')[-1]
		title = audiofile.tag.title
		artist = audiofile.tag.artist
		album = audiofile.tag.album

		# print path
		print extention
		print title
		print artist
		print album
		print '-------------------'

		track = audiofile.tag.track_num[0]
		disk = audiofile.tag.track_num[1]

		if track == None:
			track = -1
		if disk == None:
			disk = 0

		# print 'INSERT INTO music values('" + song + '\', \'' + song.split('.')[-1] + '\', \'' + audiofile.tag.title + '\', \'' + audiofile.tag.artist + '\', \'' + audiofile.tag.album + '\', ' + str(track) + ', ' + str(disk) + ')'
		cur.execute("INSERT INTO music values(\"" + path + '", "' + extention + '", "' + title + '", "' + artist + '", "' + album + '", ' + str(track) + ', ' + str(disk) + ')')

	except:
		print 'error'
		log(song)
		return

if __name__ == '__main__':
	parser = OptionParser()
	parser.add_option("-d", "--directory", dest="directory")
	(options, args) = parser.parse_args()

	print options.directory
	if options.directory == None:
		print 'wat? no directory given'
		exit()

	db = MySQLdb.connect(host="localhost", user="musicman", passwd="sql", db="musicman")
	cur = db.cursor() 

	cur.execute("DROP TABLE IF EXISTS music")
	cur.execute("CREATE TABLE music (path TEXT, format TINYTEXT, title TINYTEXT, artist TINYTEXT, album TINYTEXT, track INT, disk INT)")

	print options.directory

	for folder, subs, files in os.walk(options.directory):
		for f in files:
			if f.split('.')[-1] in ['mp3', 'wma', 'wav', "flac", "mp4", "mpeg", "aac", "aiff",]:
				add(cur, options.directory, os.path.join(folder, f))

	cur.execute("SELECT * FROM music")
	for song in cur.fetchall():
		print song

	cur.close()
	db.commit()
