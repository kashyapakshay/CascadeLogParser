import re

class CascadeLogParser:
	def __init__(self, logFileName):
		self.logFileName = logFileName

	def _parseString(self, str):
		"""Parse a given string/line of the error log and extract the information
			into key-value pairs.

			Returns:
				dictionary: A dictionary containing information in the string
				as key-value pairs.
		"""

		# Replace recurring spaces with just one space
		str = re.sub(' +', ' ', str) 

		# Split the line into 5 parts (which have 4 spaces between them). 
		# Cascade logs are formatted as: 
		# <date> <time> <Message Type> <Exception Name(?)> <Message>
		tokens = str.split(' ', 4)

		user = "None"
		mId = "None"
		mType = "None"

		# Some messages start with "{User: <>, id: <>, type: <>}"
		# Detect and extract this information
		if(tokens[4].startswith("{")):
			infoStr = tokens[4][1:tokens[4].find("}")]
			tokens[4] = tokens[4].replace('{'+infoStr+'} ', '')
			infoStrTokens = infoStr.split(',')

			user = infoStrTokens[0].replace('User: ', '').strip()
			mId = infoStrTokens[1].replace('id: ', '').strip()
			mType = infoStrTokens[2].replace('type: ', '').strip()

			#user = infoStrTokens[0]
			#mId = infoStrTokens[1]
			#mType = infoStrTokens[2]

		# Put all the information we've got into the dictionary/map
		infoMap = { 
			"date": tokens[0],
			"time": (tokens[1].split(','))[0],
			"type": tokens[2],
			"exception": tokens[3],
			"user": user,
			"id": mId,
			"aType": mType,
			"message": tokens[4].replace('"', '\\"')
		}

		return infoMap

	def parse(self, blob):
		prevMap = None
		for line in blob.splitlines():
			if line[0].isdigit():
				if prevMap is not None:
					yield prevMap

				prevMap = self._parseString(line)
			else:
				if prevMap is not None:
					prevMap['message'] += line.replace('\t', ' ')
						.replace('"', '\\"') + "<br />"

	def createJSON(self, jsonFileName):
		logContent = ''

		with open(self.logFileName, 'r') as log: 
			blob = log.read()

		logContent = '{'
		logContent += '"logName": "' + self.logFileName + '", "logContent": ['

		for logMap in self.parse(blob):
			logContent += '''{
				"date": "''' + logMap["date"] + '''",
				"time": "''' + logMap["time"] + '''",
				"type": "''' + logMap["type"] + '''",
				"exception": "''' + logMap["exception"] + '''",
				"user": "''' + logMap["user"] + '''",
				"id": "''' + logMap["id"] + '''",
				"aType": "''' + logMap["aType"] + '''",
				"message": "''' + logMap["message"] + '''"
			},'''

		logContent = logContent.rstrip(',')

		logContent += ']'
		logContent += '}'

		with open(jsonFileName, 'w') as jsonLog: 
			jsonLog.write(logContent)

if __name__ == "__main__":
	clp = CascadeLogParser("cascade.log")
	clp.createJSON("cascade.json")