import config
import csv
import time
import sys
db = config.db
#enter value
maximum = 0.30
timestr = time.strftime(" %Y%m%d-%H%M%S")
filename = "outputTermResults" + timestr + ".csv"
#csv writing
with open(filename, 'w') as csvfile:
    fieldnames = ['termId', 'entityId', 'FY', 'FQ', 'stockSymbol', 'value', 'ChangePcntPoP', 'ChangePcntPoPExp']
    writer = csv.DictWriter(csvfile, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer.writeheader()
    #csv read (opening the csv file which contains the list of terms and entities)
    with open('RohithTestFile.csv', 'r') as csvfile:
        reader = csv.DictReader(csvfile)
        term=[]
        entity=[]
        for row in reader:
            #Check
            if row["entityId"]:
                entity.append(row["entityId"])
            if row["termId"]:
                term.append(row["termId"])
        print(term)
        print(entity)
        for i in term:
            for j in entity:
                termresults = db.TermResults.find({"termId": i, "entityId": j})
                for result in termresults:
                    ent = "'" + j
                    datarow = {"termId": i, "entityId": ent, "FY": result["FY"],"FQ": result["FQ"], "stockSymbol": result["stockSymbol"], "value": result["value"]}
                    try:
                        if abs(result["changePcntPoP"]) >= maximum:
                            datarow.update({"ChangePcntPoP": result["changePcntPoP"],'ChangePcntPoPExp': result["changePcntPoPExp"]})
                            writer.writerow(datarow)
                    except KeyError:
                        error = " No ChangePcntPoP"
                        pass


