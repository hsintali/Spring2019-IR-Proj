import sys
import lucene
from java.nio.file import Paths
from org.apache.lucene.analysis.standard import StandardAnalyzer
from org.apache.lucene.index import DirectoryReader
from org.apache.lucene.queryparser.classic import QueryParser
from org.apache.lucene.store import SimpleFSDirectory
from org.apache.lucene.search import IndexSearcher

def search(queryString = "", topK = 5, specificField = None, indexDirPath = "index"):
    if queryString == "":
        return

    badChars = ['~','`','!','@','#','$','%','^','&','*','(',')','_','-','+','=','{','[','}',']','|',':',';',"'",'<','>',',','.','?',"'",'"','\\','/','\n','\t','\b','\f','\r']
    for i in badChars :
        queryString = queryString.replace(i, '')
    queryString = queryString.split(" ")
    #print("Query:", queryString, "in top", topK, end='')

    lucene.initVM()
    indexDir = SimpleFSDirectory(Paths.get(indexDirPath))
    reader = DirectoryReader.open(indexDir)
    searcher = IndexSearcher(reader)
    analyzer = StandardAnalyzer()

    queryToParser = ""
    keys = ["user_id", "text", "title", "hashtags", "user_mentions", "place"]
    if specificField is not None and specificField in keys:
        #print(" for field:", specificField)
        for word in queryString:
            queryToParser += specificField + ":" + word + "^1.0"
    else:
        #print(" for all fields")
        for word in queryString:
            queryToParser += "user_id:" + word + "^2.0" + \
                             "text:" + word + "^1.0" + \
                             "title:" + word + "^1.2" + \
                             "hashtags:" + word + "^1.5" + \
                             "user_mentions:" + word + "^1.2" + \
                             "place:" + word + "^1.5"

    query = QueryParser("text", analyzer).parse(queryToParser)
    hits = searcher.search(query, int(9999))
    #print("Total matching documents:", hits.totalHits)
    #print("Maximum score:", hits.getMaxScore(), "\n")

    count = 0
    tweet = '{'
    for hit in hits.scoreDocs:
        doc = searcher.doc(hit.doc)
        if doc.getField('lang').stringValue() != "en":
            continue
        count += 1
        if count > int(topK):
            break
        if count > 1:
            tweet += ', ' 
        tweet += '"' + str(count) + '": '
        tweet += '{"user_id": "' + doc.getField('user_id').stringValue().encode('ascii', 'ignore').decode('ascii') + '"'
       
        text = doc.getField('text').stringValue().encode('ascii', 'ignore').decode('ascii')
        text = text.replace('"', '')
        tweet += ', "text": "' + text + '"'

        title = doc.getField('title').stringValue().encode('ascii', 'ignore').decode('ascii')
        title = title.replace('"', '')
        tweet += ', "title": "' + title + '"'

        tweet += ', "hashtags": "' + doc.getField('hashtags').stringValue().encode('ascii', 'ignore').decode('ascii') + '"'
        tweet += ', "user_mentions": "' + doc.getField('user_mentions').stringValue().encode('ascii', 'ignore').decode('ascii') + '"'
        tweet += ', "place": "' + doc.getField('place').stringValue().encode('ascii', 'ignore').decode('ascii') + '"'
        tweet += ', "cords_x": "' + doc.getField('cords_x').stringValue().encode('ascii', 'ignore').decode('ascii') + '"'
        tweet += ', "cords_y": "' + doc.getField('cords_y').stringValue().encode('ascii', 'ignore').decode('ascii') + '"'
        tweet += ', "time": "' + doc.getField('time').stringValue().encode('ascii', 'ignore').decode('ascii') + '"'
        tweet += ', "Score": "' + str(round(hit.score, 2)).encode('ascii', 'ignore').decode('ascii') + '"}'
    tweet += '}'
    print(tweet, end='')
    reader.close()

def main():
    if len(sys.argv) == 2:
        search(sys.argv[1])
    elif len(sys.argv) == 3:
        search(sys.argv[1], sys.argv[2])
    elif len(sys.argv) == 4:
        search(sys.argv[1], sys.argv[2], sys.argv[3])
    elif len(sys.argv) == 5:
        search(sys.argv[1], sys.argv[2], sys.argv[3], sys.argv[4])

if __name__ == "__main__":
    main()
