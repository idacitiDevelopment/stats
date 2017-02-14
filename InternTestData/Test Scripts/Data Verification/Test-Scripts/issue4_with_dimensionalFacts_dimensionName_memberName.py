import config
import csv
import time
timestr = time.strftime(" %Y%m%d-%H%M%S")
db = config.db
starting_year = 2009 #enter starting year
ending_year = 2015   #enter ending year
elementName = "us-gaap_RevenueOilAndGasServices"    #enter elementName
termName = "Revenues"   #enter TermName
ending_year_forrange = ending_year + 1
filename1 = "issue4" + "output" + timestr + ".csv"
with open(filename1, 'w') as csvfile1:
    fieldnames = ['entityId', 'stockSymbol', 'FY', 'FQ', 'elementName','memberName']
    writer = csv.DictWriter(csvfile1, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer.writeheader()
    resultslist = [] #list for termResults
    entityreflist = [] #list for entityReferences
    secNormlist = []   #List for SECNormalizedFacts
    results=db.TermResults.find({"termName": termName,"FY": {"$gte": starting_year, "$lte": ending_year}}).distinct("stockSymbol") #termName
    for document in results:
        resultslist.append(document)
    eref = db.EntityReferences.find({}).distinct("stockSymbol")
    for entity in eref:
        entityreflist.append(entity)
    facts = db.SECNormalizedFacts.find({"elementName": elementName, "FY": {"$gte": starting_year, "$lte": ending_year},"dimensionalFacts.dimensions.memberName": "us-gaap_PredecessorMember"}).distinct("stockSymbol")
    for fact in facts:
        secNormlist.append(fact)
    difference_between_entityref_termresults = list(set(entityreflist) - set(resultslist))# getting list of stockSymbols not resolved in termResults
    union_stocksymbols = set(difference_between_entityref_termresults).intersection(secNormlist) #getting list of stockSymbols that are missing in termresults but are resolved in SECNormalized facts
    print(union_stocksymbols)
    for symbols in union_stocksymbols:
        FYFQCursor = db.SECNormalizedFacts.find({"elementName": elementName, "stockSymbol": symbols, "FY": {"$gte": starting_year, "$lte": ending_year}, "dimensionalFacts.dimensions.memberName":"us-gaap_PredecessorMember"})  #new cursor to get the data of the stockSymbols which are resolved in SECNormalizedFacts
        for row in FYFQCursor:
            for dimensionalFact in row["dimensionalFacts"]:
                for memName in dimensionalFact["dimensions"]:
                    datarow = {'entityId': row["entityId"], 'stockSymbol': row["stockSymbol"], 'FY': row["FY"], 'FQ': row["FQ"],'elementName': row["elementName"],'memberName': memName["memberName"]}
                    writer.writerow(datarow)
print("Completed Program successfully")