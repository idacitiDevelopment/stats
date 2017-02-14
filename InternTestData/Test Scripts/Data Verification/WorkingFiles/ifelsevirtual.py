import config
import csv
import time
db = config.db
count = 0
b = 0
timestr = time.strftime("%Y%m%d-%H%M%S")
filename = "VirtualParent1" + timestr + ".csv"
fieldnames = ['_id', 'cik', 'stockSymbol', 'termName', 'value', 'resolvedExpression', 'Message']
dfv = " "
with open(filename, 'w') as csvfile:
    for n in range(10):
        fieldnames.extend(["DFValue" + str(n), "dimName" + str(n), "memberName" + str(n)])
    writer = csv.DictWriter(csvfile, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer.writeheader()
    virtualFacts = db.TermResults.find({"rank": {"$gt": 100}})
    x = False
    for document in virtualFacts:
        if "resolvedExpression" in document:
            x = True
            hasduplicate = False
            knownValue = dict()
            no = 0
            error = "exception, no dimensional data"
            datarow = {'_id': document["_id"], 'cik': document["cik"], 'stockSymbol': document["stockSymbol"], 'termName': document["termName"], 'resolvedExpression': document["resolvedExpression"], 'value': document["value"]}
            errorrow = {'_id': document["_id"], 'cik': document["cik"], 'stockSymbol': document["stockSymbol"], 'termName': document["termName"], 'value': document["value"], 'resolvedExpression': document["resolvedExpression"], 'Message': error}
            if "dimensionalFacts" in document:
                for fact in document['dimensionalFacts']:
                    if no < 10:
                        a = fact["value"]
                        datarow.update({"DFValue" + str(no): fact["value"]})
                        deal = []
                        seal = []
                        feel = []
                        for dimension in fact['dimensions']:
                            deal.append(dimension["dimName"] + ";")
                            seal.append(dimension["memberName"] + ";")
                        datarow.update({"dimName" + str(no): deal})
                        datarow.update({"memberName" + str(no): seal})
                        no = no + 1
                        if a in knownValue:
                            hasduplicate = True
                        else:
                            knownValue[a] = True
                        for a in knownValue:
                            feel.append(a)
                        datarow.update({'Message': "Double counting on" + str(feel)})
                if hasduplicate:
                    count = count + 1
                    writer.writerow(datarow)
            else:
                writer.writerow(errorrow)
        else:
            print("Doc has no Resolved expression ")
            writer.writerow({'_id': document["_id"], 'cik': document["cik"], 'stockSymbol': document["stockSymbol"], 'termName': document["termName"], 'value': document["value"], 'resolvedExpression': "No resolved expression data"})
print(count)
print("completed program successfully")