import config
import csv
import time
import datetime
db = config.db
timestr = time.strftime("%Y%m%d-%H%M%S")
filename = "SECFiling_10K_10KA_DiffeentFiscalPeriodVsFiscalYear" + timestr + ".csv"
with open(filename, 'w') as csvfile:
    fieldnames = ['_id', 'entityId', 'stockSymbol', 'fiscalYearEnd','filingPeriod','formType','BadData']
    writer = csv.DictWriter(csvfile, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer.writeheader()
    countbadfiling = 0
    count = 0
    data =None
    SECFiling = db.SECFilings.find({"formType": {"$regex": "10-K"}})
    badfiling = False
    for document in SECFiling:
        a = str(document["filingPeriod"])
        b = (a[4:])
        try:
            d2 = datetime.datetime.strptime(b, '%m%d').date()
            x = str(document["fiscalYearEnd"])
            d1 = datetime.datetime.strptime(x, '%m%d').date()
        except ValueError:
            error = "This entry has Bad data"
            fyr = "'" + str(document["fiscalYearEnd"])
            ent = "'" + document["entityId"]
            writer.writerow({'_id': document["_id"], 'entityId': ent, 'stockSymbol': document["stockSymbol"],'formType': document["formType"], 'fiscalYearEnd': fyr, 'filingPeriod': a, 'BadData': error})
            print(error)
        else:
            result = abs((d2 - d1).days)
            count = count + 1
            print(count)
            if x != b:
                if result > 5:
                    badfiling = True
                    ent = "'" + document["entityId"]
                    fyr = "'" + x
                    writer.writerow({'_id': document["_id"], 'entityId': ent, 'stockSymbol': document["stockSymbol"],
                                     'formType': document["formType"], 'fiscalYearEnd': fyr, 'filingPeriod': a})
                    countbadfiling = countbadfiling + 1
            else:
                x = b
        print(countbadfiling)