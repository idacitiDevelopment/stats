import config
import csv
import time
db = config.db
timestr = time.strftime("%Y%m%d-%H%M%S")
filename = "demotedfactsExists" + timestr + ".csv"
count = 0
with open(filename, 'w') as csvfile:
    fieldnames = ['entityId', 'cik', 'stockSymbol', 'FY', 'FQ', 'value', 'elementName', 'filingDate', 'demotedValues', 'demotedFilingDate']
    writer = csv.DictWriter(csvfile, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer.writeheader()
    SECFacts = db.SECNormalizedFacts.find({"demotedFacts": {"$exists": "true"}})
    for facts in SECFacts:
        dfvalue=[]
        fdate =[]  #filingDate
        for demoted in facts["demotedFacts"]:
            dfvalue.append((demoted["value"]))
            fdate.append((demoted["filingDate"]))
        ent = "'" + facts["entityId"]
        datarow= {'entityId': ent, 'cik': facts["cik"], 'stockSymbol': facts["stockSymbol"], 'FY': facts["FY"], 'FQ': facts["FQ"], 'value': facts["value"], 'elementName': facts["elementName"], 'filingDate':facts["filingDate"], 'demotedValues': dfvalue, 'demotedFilingDate': fdate}
        writer.writerow(datarow)
        count+=1
        print(count)
print("Completed Program Successfully")
