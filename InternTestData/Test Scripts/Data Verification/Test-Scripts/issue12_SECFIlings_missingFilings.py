import config
import csv
import time
import datetime
import os
import pandas as pd
from itertools import cycle, islice, dropwhile
starting = 2009 #argument for starting year
ending = 2016 #argument for ending year
s= 20090000 #for Mongo Query
ending_for_range = ending + 1
db = config.db
count = 0
stockSymbol=[]
eref = db.EntityReferences.find({})
for document in eref:
    stockSymbol.append(document["stockSymbol"])
filename1='preliminary_output_issue12'+'.csv'
fieldnames = ['entityId','companyName','stockSymbol']
with open(filename1, 'w') as csvfile1:
    for n in range(starting, ending_for_range):
        fieldnames.extend([str(n) + 'Q1', str(n) + 'Q2', str(n) + 'Q3', str(n) + "FY"])
    writer = csv.DictWriter(csvfile1, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer.writeheader()
    for symbol in stockSymbol:
        datarow={}
        SECfiling = db.SECFilings.find({"stockSymbol": symbol, 'FY': {'$lte': ending, '$gte': starting}}).sort("FY", 1)
        for filings in SECfiling:
            datarow.update({"entityId":filings["entityId"],"companyName":filings["companyName"],"stockSymbol":filings["stockSymbol"]})
            concatenatedFYFQ = str(filings["FY"]) + str(filings["FQ"])
            datarow.update({concatenatedFYFQ: "exists"})
            count+=1
            print(count)
        writer.writerow(datarow)
filename1='outputp_issue12'+'.csv'
fieldnames = ['entityId','companyName','stockSymbol','No_of_filings','count_of_NULL',"startFYFQ","endFYFQ"]
with open(filename1, 'w') as csvfile2:
    for n in range(starting, ending_for_range):
        fieldnames.extend([str(n) + 'Q1', str(n) + 'Q2', str(n) + 'Q3', str(n) + "FY"])
    writer2 = csv.DictWriter(csvfile2, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer2.writeheader()
    with open('output_issue12.csv', 'r') as csvfile3:
        reader = csv.DictReader(csvfile3)
        headers = reader.fieldnames
        for row in reader:
            FYFQ_from_csv = []
            for n in range(starting, ending_for_range):
                # print(row["2009Q1"])
                FYFQ_from_csv.append(row[str(n) + 'Q1'])
                FYFQ_from_csv.append(row[str(n) + 'Q2'])
                FYFQ_from_csv.append(row[str(n) + 'Q3'])
                FYFQ_from_csv.append(row[str(n) + 'FY'])
            skippedheader = dropwhile(lambda x: x != "2009Q1", headers)
            # print(skippedheader)
            slicedheader = islice(skippedheader, None, 36)
            resultheader = list(slicedheader)
            skipped = dropwhile(lambda x: x != "exists", FYFQ_from_csv)
            skipped1 = dropwhile(lambda x: x == None, FYFQ_from_csv)  # removes leading elements from list which are not stockSymbols
            sliced = islice(skipped, None, 38)
            result = list(sliced) #the list wgich has skipped NULLS before the starting FYFQ
            sliced1 = islice(FYFQ_from_csv, None, 36)
            result1 = list(sliced1)
            try:
                while not result[-1]:  # removes trailing elements from list which are not "exists"
                    result.pop()
                No_of_filings = len(result)  # counting no of filings
                countofNULL = result.count('')  # counting no of NULLS
                # print(result.count(''))
                row.update({"No_of_filings": No_of_filings, "count_of_NULL": countofNULL})
                a = [i for i, x in enumerate(result1) if x != '']   #to find start and end fyfq index
                b = a[0] #first not NULL element is startFYFQ
                c = a[-1] #lst not NULL element is endFYFQ
                row.update({"startFYFQ": resultheader[b], "endFYFQ": resultheader[c]})
                startandendFYFQlist=resultheader[b:c]
                print(startandendFYFQlist)
                for value in startandendFYFQlist: #to find NULL filings and replace them
                    if row[value] == '':
                        row.update({value: "missing"})
                        print(value, row[value])
                writer2.writerow(row)
            except IndexError:
                continue
os.remove('preliminary_output_issue12.csv')
print("program executed successfully")