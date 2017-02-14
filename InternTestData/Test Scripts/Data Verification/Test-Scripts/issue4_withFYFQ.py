import config
import csv
import time
timestr = time.strftime(" %Y%m%d-%H%M%S")
db = config.db
starting_year = 2009 #enter starting year
ending_year = 2015   #enter ending year
elementName = "us-gaap_AssetsCurrent"    #enter elementName
termName = "Total Assets"   #enter TermName
ending_year_forrange = ending_year + 1
quarters= ['Q1','Q2','Q3','Q4','FY']
filename1 = "issue4" + "output" + timestr + ".csv"
with open(filename1, 'w') as csvfile1:
    fieldnames = ['entityId', 'stockSymbol', 'FY', 'FQ', 'elementName']
    writer = csv.DictWriter(csvfile1, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer.writeheader()
    resultslist = [] #list for termResults
    entityreflist = [] #list for entityReferences
    secNormlist = []   #List for SECNormalizedFacts
    for FY in range(starting_year,ending_year_forrange):
        for FQ in quarters:
            results=db.TermResults.find({"termName": termName,"FY": FY,"FQ": FQ}).distinct("stockSymbol") #termName
            for document in results:
                resultslist.append(document)
            eref = db.EntityReferences.find({}).distinct("stockSymbol")
            for entity in eref:
                entityreflist.append(entity)
            facts = db.SECNormalizedFacts.find({"elementName": elementName, "FY": FY, "FQ": FQ}).distinct("stockSymbol")
            for fact in facts:
                secNormlist.append(fact)
    difference_between_entityref_termresults = list(set(entityreflist) - set(resultslist))      # getting list of stockSymbols not resolved in termResults
    union_stocksymbols = set(difference_between_entityref_termresults).intersection(secNormlist)  #getting list of stockSymbols that are missing in termresults but are resolved in SECNormalized facts
    print(union_stocksymbols)
    for symbols in union_stocksymbols:
        FYFQCursor = db.SECNormalizedFacts.find({"elementName": elementName, "stockSymbol": symbols, "FY": {"$gte": starting_year, "$lte": ending_year}})  #new cursor to get the data of the stockSymbols which are resolved in SECNormalizedFacts
        for row in FYFQCursor:
            datarow = {'entityId': row["entityId"], 'stockSymbol': row["stockSymbol"], 'FY': row["FY"], 'FQ': row["FQ"],'elementName': row["elementName"]}
            writer.writerow(datarow)
print("Completed Program successfully")