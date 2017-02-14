from pymongo import MongoClient
import csv
connectioninfo = 'mongodb://184.172.208.146:27017/'
client = MongoClient(connectioninfo)
db = client.idaciti
filename = "output" + ".csv"
count = 0
with open(filename, 'w') as csvfile:
    fieldnames = ['termName', 'entityId','FY','FQ','value', 'Description','Expression', 'otherExpression']
    writer = csv.DictWriter(csvfile, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer.writeheader()
    cursor = db.TermResults.find({"otherTermResults": {"$exists": "true"}, "termId": "949201"})
    for document in cursor:
        found = False
        des = 'The main value is ' + str(document["value"]) + ' and the other value are '
        for otherResults in document['otherTermResults']:
            if document["value"] < otherResults["value"]:
                found = True
                des = des + ' ' + str(otherResults["value"])
        if found:
            print("Found bad data")
            print(document["termName"])
            print(document["entityId"])
            print(document["FY"])
            print(document["FQ"])
            print(document["value"])
            print(otherResults["value"])
            print(des)
            ent = "'" + document["entityId"]
            writer.writerow({'termName': document["termName"], 'entityId': ent, 'FY': document["FY"], 'FQ': document["FQ"], 'value': document["value"], 'Description': des, 'Expression': document["expression"], 'otherExpression': otherResults["expression"]})
        else:
            print("data is ok")
