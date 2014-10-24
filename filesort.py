import MySQLdb
from shutil import copyfile
import os
from optparse import OptionParser

# copies all known music files in database to a centralized library folder
# to be run after musicman.py


def log(message, log='filesort.log'):
	with open(log, 'a') as f:
		f.write(message + '\n')

if __name__ == '__main__':
	parser = OptionParser()
	parser.add_option("-l", "--librarypath", dest="librarypath")
	(options, args) = parser.parse_args()

	if options.librarypath == None:
		print "library path is required (-l)"
		exit()

	librarypath = options.librarypath

	db = MySQLdb.connect(host="localhost", # your host, usually localhost
                     user="musicman", # your username
                      passwd="sql", # your password
                      db="musicman") # name of the data base
	cur = db.cursor()

	cur.execute("SELECT * FROM music ORDER BY artist")
	datas = cur.fetchall()
	for path, ext, name, band, album, track, disk in datas:
		track = int(track)
		disk = int(disk)

		name = name.replace('/', '<slash>')

		trackpath = librarypath + '/' + band + '/' + album + '/'
		try:
			if not os.path.exists(trackpath):
				os.makedirs(trackpath)
			if disk != 0:
				trackpath += str(disk) + ' - '
			if track != 0:
				trackpath += str(track) + ' - '
			trackpath += name + '.' + ext

			print path, 'to', trackpath
		
			copyfile(path, trackpath)
		except:
			log(path + ' to ' + trackpath)
