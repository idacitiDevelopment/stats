import config
import csv
import time
timestr = time.strftime(" %Y%m%d-%H%M%S")
db = config.db
count = 0
timestr = time.strftime(" %Y%m%d-%H%M%S")
filename = "outputTermResults_PLS" + timestr + ".csv"
term=[]
entity=[]
with open(filename, 'w') as csvfile:
    fieldnames = ['termId', 'entityId', 'value', 'stockSymbol', 'termName', 'expression', 'elementName', 'FY', 'FQ']
    writer = csv.DictWriter(csvfile, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer.writeheader()
    #csv read (opening the csv file which contains the list of terms and entities)
    with open('TermsStock.csv', 'r') as csvfile:
        reader = csv.DictReader(csvfile)
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
                ent = "'" + result["entityId"]
                datarow = {"termId": result["termId"], "entityId": ent}
                if abs(result["value"]) >= 0:
                    if "elementName" in result:
                        datarow.update({"value": result["value"], "stockSymbol":result["stockSymbol"], "termName":result["termName"], "expression": result["expression"], 'elementName': result["elementName"], "FY":result["FY"], "FQ":result["FQ"]})
                        writer.writerow(datarow)
                        count+=1
                        print(count)
                    else:
                        errorrow ={"termId": result["termId"], "entityId": ent, "value": result["value"], "stockSymbol":result["stockSymbol"],"termName":result["termName"], "FY":result["FY"], "FQ":result["FQ"]}
                        writer.writerow(errorrow)
                        print("error handled")
print("Completed Program Successfully")