import config
import csv
import time
db = config.db
count = 0
no = 0
timestr = time.strftime("%Y%m%d-%H%M%S")
filename = "VirtualParent1" + timestr + ".csv"
fieldnames = ['_id', 'cik', 'stockSymbol', 'termName', 'value', 'resolvedExpression', 'exception']
dfv = " "
with open(filename, 'w') as csvfile:
    for n in range(7):
        dfv = dfv +"DFValue" + str(n)
        fieldnames.extend(["DFValue" + str(n), "dimName" +str(n), "memberName"+str(n)])
    writer = csv.DictWriter(csvfile, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer.writeheader()
    virtualFacts = db.TermResults.find({"rank": {"$gt": 100}})
    x = False

    for document in virtualFacts:
        if "resolvedExpression" in document:
            x = True
            hasduplicate = False
            knownValue = dict()
            error = "exception, no dimensional data"
            datarow = {'_id': document["_id"], 'cik': document["cik"],'stockSymbol': document["stockSymbol"],'termName': document["termName"],'resolvedExpression': document["resolvedExpression"], 'value': document["value"]}
            errorrow = {'_id': document["_id"], 'cik': document["cik"], 'stockSymbol': document["stockSymbol"],'termName': document["termName"], 'value': document["value"],'resolvedExpression': document["resolvedExpression"], 'exception': error}
            try:
                 for fact in document['dimensionalFacts']:
                    a = fact["value"]
                    if a in knownValue:
                        hasduplicate = True
                        try:
                            for dimension in fact['dimensions']:
                                d = dimension["dimName"]
                                mn = dimension["memberName"]
                                if a is not None:
                                    datarow.update({"DFValue" + str(no): fact["value"]})
                                    no = no +1
                                else:
                                    break
                        except ValueError:
                            print("valueerror")
                    else:
                        knownValue[a] = True
                 try:
                    if hasduplicate == True:
                        writer.writerow(datarow)
                        count = count + 1
                 except (KeyError):
                    err = "error"
                    writer.writerow(errorrow)
            except (KeyError):
                writer.writerow(errorrow)
        else:
            print("Doc has no Resolved expression ")
            writer.writerow({'_id': document["_id"], 'cik': document["cik"], 'stockSymbol': document["stockSymbol"],'termName': document["termName"], 'value': document["value"],'resolvedExpression': "No resolved expression data"})

print(count)
print("completed program successfully")