import config
import csv
import time
import sys
db = config.db
count = 0
timestr = time.strftime(" %Y%m%d-%H%M%S")
maximum = float(sys.argv[1])
PartnerAccess = sys.argv[2]
arguments = [maximum, PartnerAccess]
print(arguments)
print(sys.argv)
getid = db.PartnerAccess.find({'_id': PartnerAccess})
for document in getid:
    print(document)
    for term in document["terms"]:
        print(term["termId"])
        filename = term["termName"] + timestr + ".csv"
        with open(filename, 'w') as csvfile:
            fieldnames = ['termId', 'entityId', 'FY', 'FQ', 'stockSymbol', 'value', 'ChangePcntPoP', 'ChangePcntPoPExp']
            writer = csv.DictWriter(csvfile, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
            writer.writeheader()
            for entity in document["entities"]:
                count += 1
                print(entity["entityId"])
                print(count)
                termresults = db.TermResults.find({"termId":term["termId"], "entityId": entity["entityId"]})
                for result in termresults:
                    datarow = {"termId":result["termId"], "entityId": result["entityId"],"FY": result["FY"], "FQ": result["FQ"], "stockSymbol": result["stockSymbol"], "value": result["value"]}
                    try:
                        if abs(result["changePcntYoY"]) >= maximum:
                            datarow.update({"ChangePcntPoP": result["changePcntPoP"], 'ChangePcntPoPExp': result["changePcntPoPExp"]})
                            writer.writerow(datarow)
                    except KeyError:
                        error = " No ChangePcntYoY"
                        pass
