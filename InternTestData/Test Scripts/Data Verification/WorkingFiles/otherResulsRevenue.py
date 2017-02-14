import config
import csv
import time
db = config.db
timestr = time.strftime("%Y%m%d-%H%M%S")
filename = "otherResultsRevenue" + timestr + ".csv"
count = 0
with open(filename, 'w') as csvfile:
    fieldnames = ['termName', 'entityId','FY','FQ','value', 'Description','Expression', 'otherExpression']
    writer = csv.DictWriter(csvfile, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer.writeheader()
    collection = db.TermRules.find({"name": {"$regex": "Revenue"}})
    for pipe in collection:
        print(pipe["name"])
        print(pipe["termId"])
        print(collection.count())
        cursor = db.TermResults.find({"otherTermResults": {"$exists": "true"}, "termId": pipe["termId"]})
        for document in cursor:
                found = False
                xyz = " "
                des = 'The main value is ' + str(document["value"]) + ' and the other value are '
                for otherResults in document['otherTermResults']:
                    if document["value"] < otherResults["value"]:
                        found = True
                        des = des + ' ' + str(otherResults["value"])
                        xyz = str(otherResults["expression"])
                if found:
                    print("Found bad data")
                    print(document["termName"])
                    print(document["entityId"])
                    print(document["FY"])
                    print(document["FQ"])
                    print(document["value"])
                    #print(otherResults["value"])
                    print(des)
                    ent = "'" + document["entityId"]
                    writer.writerow({'termName': document["termName"], 'entityId': ent, 'FY': document["FY"], 'FQ': document["FQ"], 'value': document["value"], 'Description': des, 'Expression': document["expression"],'otherExpression': xyz})

                else:
                     print("data is ok")


