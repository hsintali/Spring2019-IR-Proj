import os, sys, json
import lucene
from org.apache.lucene.analysis.standard import StandardAnalyzer
from org.apache.lucene.document import Document
from org.apache.lucene.document import Field
from org.apache.lucene.document import TextField, StoredField
from org.apache.lucene.index import IndexWriter, IndexWriterConfig
from org.apache.lucene.store import SimpleFSDirectory
from java.nio.file import Paths

def index(docDirPath = "data", indexDirPath = "index"):
    lucene.initVM()
    indexDir = SimpleFSDirectory(Paths.get(indexDirPath))
    analyzer = StandardAnalyzer()
    writerConfig = IndexWriterConfig(analyzer)
    writerConfig.setOpenMode(IndexWriterConfig.OpenMode.CREATE)
    index_writer = IndexWriter(indexDir, writerConfig)
    files = os.listdir(docDirPath)
    for file in files:
        if not os.path.isdir(file):
            f = open(docDirPath + "/" + file)
            iter_f = iter(f)
            for line in iter_f:
                document = Document()
                data = json.loads(line)
                document.add(Field("user_id", data["user_id"], TextField.TYPE_STORED))
                document.add(Field("text", data["text"], TextField.TYPE_STORED))
                if data["urls"] != "None" and len(data["urls"]) != 0:
                    document.add(Field("title", data["urls"]["title"], TextField.TYPE_STORED))
                else:
                    document.add(Field("title", "None", TextField.TYPE_STORED))
                if data["hashtags"] != "None":
                    hashtags = ""
                    for tag in data['hashtags'].values():
                        hashtags += tag["text"] + ", "
                    document.add(Field("hashtags", hashtags[0:-2], TextField.TYPE_STORED))
                else:
                    document.add(Field("hashtags", "None", TextField.TYPE_STORED))
                if data["user_mentions"] != "None":
                    user_mentions = ""
                    for user in data['user_mentions'].values():
                        user_mentions += user + ", "
                    document.add(Field("user_mentions", user_mentions[0:-2], TextField.TYPE_STORED))
                else:
                    document.add(Field("user_mentions", "None", TextField.TYPE_STORED))
                if data["place"] != "None":
                    document.add(Field("place", data["place"]["place_name"], TextField.TYPE_STORED))
                    document.add(Field("cords_x", str(data["place"]["1"]["x"]), StoredField.TYPE))
                    document.add(Field("cords_y", str(data["place"]["1"]["y"]), StoredField.TYPE))
                else:
                    document.add(Field("place", "None", TextField.TYPE_STORED))
                    document.add(Field("cords_x", "None", StoredField.TYPE))
                    document.add(Field("cords_y", "None", StoredField.TYPE))
                document.add(Field("time", data["time"], StoredField.TYPE))
                text = data['tweet'].split(',')
                for i in range(len(text)):
                    if("'lang':" in text[i]):
                        lang = text[i].split(': ')[1]
                        document.add(Field("lang", lang[1:-1], StoredField.TYPE))
                        break
                index_writer.addDocument(document)
            f.close()
    index_writer.close()

def main():
    if len(sys.argv) == 2:
        index(sys.argv[1])
    elif len(sys.argv) == 3:
        index(sys.argv[1], sys.argv[2])
    else:
        index()

if __name__ == "__main__":
    main()
