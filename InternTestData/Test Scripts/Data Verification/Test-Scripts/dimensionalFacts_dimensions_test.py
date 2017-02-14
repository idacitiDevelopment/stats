import csv
import time
import config
db = config.db
timestr = time.strftime("%Y%m%d-%H%M%S")
filename = "dimensionalFacts" + timestr + ".csv"
count = 0
with open(filename, 'w') as csvfile:
    fieldnames = ['entityId', 'cik', 'stockSymbol', 'FY', 'FQ', 'value', 'dimensionalFacts.dimensions.memberName', 'dimensionalFacts.value']
    writer = csv.DictWriter(csvfile, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer.writeheader()
    SECFacts = db.TermResults.find({"dimensionalFacts": {"$exists": "true"},"termName": "Revenues"})
    datarow={}
    for facts in SECFacts:
        datarow.update({'entityId': facts["entityId"], 'cik': facts["cik"], 'stockSymbol': facts["stockSymbol"], 'FY': facts["FY"],'FQ': facts["FQ"], 'value': facts["value"]})
        dfvalue = []
        memname = []
        for dimensional in facts["dimensionalFacts"]:
            dfvalue.append((str(dimensional["value"])+ ";"))
            #fdate.append((dimensional["dimensionalFacts.dimensions"]))
            for dimension in dimensional["dimensions"]:
                memname.append((dimension["memberName"]+";"))
            datarow.update({'dimensionalFacts.value': dfvalue, 'dimensionalFacts.dimensions.memberName': memname})
        writer.writerow(datarow)
        count+=1
        print(count)
print("Completed Program Successfully")