import config
import csv
import time
timestr = time.strftime(" %Y%m%d-%H%M%S")
db = config.db
count=0
starting_year = 2009  # enter starting year
ending_year = 2015  # enter ending year
elementName = "us-gaap_ContractsRevenue"  # enter elementName
termName = "Revenues"  # enter TermName
filename1 = "issue4" + "output" + timestr + ".csv"
with open(filename1, 'w') as csvfile1:
    fieldnames = ['stockSymbol', 'FY','FQ']
    writer = csv.DictWriter(csvfile1, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer.writeheader()
    resultsfinal = []
    secNormlist = []
    results2 = db.TermResults.find({"termName": termName,'FY': {'$lte': ending_year, '$gte': starting_year}})
    for finalrow in results2:
        resultsfinal.append((finalrow["stockSymbol"],finalrow["FY"],finalrow["FQ"]))
    print(resultsfinal)
    facts = db.SECNormalizedFacts.find({"elementName": elementName,'FY': {'$lte': ending_year, '$gte': starting_year}})
    for fact in facts:
        secNormlist.append((fact["stockSymbol"],fact["FY"],fact["FQ"]))
    unmatched_item = set(secNormlist) - set(resultsfinal)# prints all filings in secNormlist but not in termresults
    print(unmatched_item)
    for  key in unmatched_item:
        datarow= {"stockSymbol": key[0], "FY": key[1], "FQ": key[2]}
        count+=1
        print(count)
        writer.writerow(datarow)