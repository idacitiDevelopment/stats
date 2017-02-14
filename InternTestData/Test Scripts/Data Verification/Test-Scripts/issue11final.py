import config
import csv
import time
import itertools
import dateutil.relativedelta
from itertools import cycle, islice, dropwhile
import datetime
import os
import pandas as pd
db = config.db
count = 0
s= 20090000 #for Mongo Query
e= 20161231
starting = 2008 #argument for starting year
ending = 2016 #argument for ending year
ending_for_range = ending + 1
timestr = time.strftime(" %Y%m%d-%H%M%S")
filename = "splitFYFQ" + ".csv"
fieldnames = ['entityId','companyName','stockSymbol','cik','count_of_NULL']
with open(filename, 'w') as csvfile:
    for n in range(starting, ending_for_range):
        fieldnames.extend([str(n) + 'Q1', str(n) + 'Q2', str(n) + 'Q3', str(n) + "FY"])
    writer = csv.DictWriter(csvfile, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer.writeheader()
    company =[] #stores stockSymbols of all companies in entityReferences
    eref= db.EntityReferences.find()
    for stocksymbol in eref:
        company.append(stocksymbol["stockSymbol"])
    ss= [ 'ahgp','pson','aapl','fb']  #test list
    for symbol in company: #replace company by ss to run test list of stockSymbols
        filing = db.SECFilings.find({"stockSymbol": symbol,'filingPeriod': {'$lte': e, '$gte': s}}).sort("filingDate",1)
        datarow ={} #intializing datarow dictionary to store all data from SECFilings
        for document in filing:
            try:
                filingperiod = str(document["filingPeriod"])
                monthday = (filingperiod[4:])
                yearpart = int((filingperiod[:4]))
                fiscalyearend = str(document["fiscalYearEnd"])
                months = int((fiscalyearend[:2]))
                monthpart = int((filingperiod[4:-2]))
            except ValueError:
                continue
            datarow.update({'entityId': document["entityId"], 'companyName': document["companyName"], 'stockSymbol': symbol,'cik':document["cik"]})
            monthsCycle = [12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1] #order of months in descending order for creating cyclic list
            OrderedMonths = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12] #ordered months
            if months > 5:
                if months in OrderedMonths:
                    print(months)
                    cycled = cycle(monthsCycle)
                    skipped = dropwhile(lambda x: x != months, cycled) #skip all months until current fiscalyearend's Month
                    sliced = islice(skipped, None, 15) #create a cyclic list from the current starting Month
                    result = list(sliced)  # create a list from iterator
                    #print(result)
                    az0 = result[9] #array to access the list items
                    az1 = result[10]
                    az2 = result[11]
                    bz0 = result[6]
                    bz1 = result[7]
                    bz2 = result[8]
                    cz0 = result[3]
                    cz1 = result[4]
                    cz2 = result[5]
                    dz0 = result[0]
                    dz1 = result[1]
                    dz2 = result[2]
                    Q1 = [az0, az1, az2]
                    Q2 = [bz0, bz1, bz2]
                    Q3 = [cz0, cz1, cz2]
                    FY = [dz0, dz1, dz2] #Q4 is FY

                    if monthpart in Q1:
                        d = datetime.datetime.strptime(filingperiod, "%Y%m%d")
                        resul1 = d.year
                        concatenatedFYFQ = str(resul1) + "Q1"
                        datarow.update({concatenatedFYFQ: document["stockSymbol"]})

                    elif monthpart in Q2:
                        d = datetime.datetime.strptime(filingperiod, "%Y%m%d")
                        resul2 = d.year
                        concatenatedFYFQ = str(resul2) + "Q2"
                        datarow.update({concatenatedFYFQ: document["stockSymbol"]})

                    elif monthpart in Q3:
                        d = datetime.datetime.strptime(filingperiod, "%Y%m%d")
                        resul3 = d.year
                        concatenatedFYFQ = str(resul3) + "Q3"
                        datarow.update({concatenatedFYFQ: document["stockSymbol"]})

                    elif monthpart in FY:
                        d = datetime.datetime.strptime(filingperiod, "%Y%m%d")
                        resul4 = d.year
                        concatenatedFYFQ = str(resul4) + "FY"
                        datarow.update({concatenatedFYFQ: document["stockSymbol"]})

            else: #for FYE less than 5
                if months in OrderedMonths:
                    cycled = cycle(monthsCycle)
                    skipped = dropwhile(lambda x: x != months, cycled)
                    sliced = islice(skipped, None, 15)
                    result = list(sliced)  # create a list from iterator
                    az0 = result[9]
                    az1 = result[10]
                    az2 = result[11]
                    bz0 = result[6]
                    bz1 = result[7]
                    bz2 = result[8]
                    cz0 = result[3]
                    cz1 = result[4]
                    cz2 = result[5]
                    dz0 = result[0]
                    dz1 = result[1]
                    dz2 = result[2]
                    Q1 = [az0, az1, az2]
                    Q2 = [bz0, bz1, bz2]
                    Q3 = [cz0, cz1, cz2]
                    FY = [dz0, dz1, dz2]
                    if monthpart in Q1:
                        d = datetime.datetime.strptime(filingperiod, "%Y%m%d")
                        d1 = d - dateutil.relativedelta.relativedelta(months=3)
                        resul1 = int(d1.year)
                        concatenatedFYFQ = str(resul1) + "Q1"
                        datarow.update({concatenatedFYFQ: document["stockSymbol"]})

                    elif monthpart in Q2:
                        d = datetime.datetime.strptime(filingperiod, "%Y%m%d")
                        d2 = d - dateutil.relativedelta.relativedelta(months=3)
                        resul2 = int(d2.year)
                        concatenatedFYFQ = str(resul2) + "Q2"
                        datarow.update({concatenatedFYFQ: document["stockSymbol"]})

                    elif monthpart in Q3:
                        d = datetime.datetime.strptime(filingperiod, "%Y%m%d")
                        d3 = d - dateutil.relativedelta.relativedelta(months=3)
                        resul3 = int(d3.year)
                        concatenatedFYFQ = str(resul3) + "Q3"
                        datarow.update({concatenatedFYFQ:  document["stockSymbol"]})

                    elif monthpart in FY:
                        d = datetime.datetime.strptime(filingperiod, "%Y%m%d")
                        resul4 = int(d.year) - 1
                        concatenatedFYFQ = str(resul4) + "FY"
                        datarow.update({concatenatedFYFQ : document["stockSymbol"]})
        writer.writerow(datarow)

filename1='output_issue11'+'.csv'
fieldnames = ['entityId','companyName','stockSymbol','cik','No_of_filings','count_of_NULL','pcnt_count',"startFYFQ","endFYFQ"]
with open(filename1, 'w') as csvfile:
    for n in range(starting, ending_for_range):
        fieldnames.extend([str(n) + 'Q1', str(n) + 'Q2', str(n) + 'Q3', str(n) + "FY"])
    writer = csv.DictWriter(csvfile, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer.writeheader()
    with open('splitFYFQ.csv', 'r') as csvfile1:
        reader = csv.DictReader(csvfile1)
        headers = reader.fieldnames
        for row in reader:
            FYFQ_from_csv = []
            for n in range(starting, ending_for_range):
                #print(row["2009Q1"])
                FYFQ_from_csv.append(row[str(n) + 'Q1'])
                FYFQ_from_csv.append(row[str(n) + 'Q2'])
                FYFQ_from_csv.append(row[str(n) + 'Q3'])
                FYFQ_from_csv.append(row[str(n) + 'FY'])
            skippedheader = dropwhile(lambda x: x != "2008Q1", headers)
            #print(skippedheader)
            slicedheader = islice(skippedheader, None, 36)
            resultheader =list(slicedheader)
            skipped = dropwhile(lambda x: x != row["stockSymbol"], FYFQ_from_csv)
            skipped1 = dropwhile(lambda x: x == None, FYFQ_from_csv)#removes leading elements from list which are not stockSymbols
            sliced = islice(skipped, None, 38)
            result = list(sliced)
            sliced1 = islice(FYFQ_from_csv, None, 36)
            result1 = list(sliced1)
            print(result1)
            #ab = result1.index(row["stockSymbol"])
            #print(ab)
            try:
                #print(result)
                while not result[-1]: #removes trailing elements from list which are not stockSymbols
                    result.pop()
                No_of_filings = len(result) #counting no of filings
                countofNULL = result.count('') #counting no of NULLS
                #print(result.count(''))
                pcnt_count= countofNULL/No_of_filings  #calculating Percentage count
                row.update({"No_of_filings":No_of_filings,"count_of_NULL":countofNULL, "pcnt_count":pcnt_count})
                a = [i for i, x in enumerate(result1) if x != '']
                print(a)
                b = a[0]
                c=a[-1]
                row.update({"startFYFQ": resultheader[b],"endFYFQ":resultheader[c]})
                writer.writerow(row)
            except IndexError:
                continue
starting = 2008 #argument for starting year
ending = 2016 #argument for ending year
ending_for_range = ending + 1
s= 20090000 #for Mongo Query
e= 20161231
full = []
company = []  # stores stockSymbols of all companies in entityReferences
eref = db.EntityReferences.find()
for stocksymbol in eref:
    company.append(stocksymbol["stockSymbol"])
filename3 = "issue11_termResults1"+".csv"
fieldnames2 = ['stockSymbol']
with open(filename3, 'w') as csvfile:
    for n in range(starting, ending_for_range):
        fieldnames2.extend([str(n) + 'Q1', str(n) + 'Q2', str(n) + 'Q3', str(n) +"Q4", str(n) + "FY"])
    writer = csv.DictWriter(csvfile, fieldnames=fieldnames2, delimiter=',', lineterminator='\n')
    writer.writeheader()
    for symbol in company: #replace company by ss to run test list of stockSymbols
        termresults = db.TermResults.find({"termName": "Total Assets", "stockSymbol": symbol})
        datarow = {}
        for result in termresults:
            if result["FY"]>starting and result["FY"]<ending:
                concatenatedFYFQ = str(result["FY"]) + str(result["FQ"])
                datarow.update({'stockSymbol': result['stockSymbol'], concatenatedFYFQ: result["value"]})
        try:
            writer.writerow(datarow)
            count += 1
            print(count)
        except ValueError:
            continue
df1 = pd.read_csv('output_issue11.csv')
df2 = pd.read_csv('issue11_termResults1.csv')
merged = df1.merge(df2, on="stockSymbol", how="outer").fillna("")
merged.to_csv('issue11_final_merged.csv')
#os.remove('splitFYFQ.csv')

filename1='11op'+'.csv'
fieldnames = ['entityId','companyName','stockSymbol','cik','No_of_filings','count_of_NULL','pcnt_count',"startFYFQ","endFYFQ"]
with open(filename1, 'w') as csvfile:
    for n in range(starting, ending_for_range):
        fieldnames.extend([str(n) + 'Q1', str(n) + 'Q2', str(n) + 'Q3', str(n) + "FY"])
    writer = csv.DictWriter(csvfile, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer.writeheader()
    with open('issue11_final_merged.csv', 'r') as csvfile1:
        reader1 = csv.DictReader(csvfile1)
        #headers = reader.fieldnames
        FYFQ_from_csv = []
        for row in reader1:
            datarow={'entityId':row["entityId"],'companyName':row["companyName"],'stockSymbol':row["stockSymbol"],'cik':row["cik"],'No_of_filings':row["No_of_filings"],'count_of_NULL':row["count_of_NULL"],'pcnt_count':row['pcnt_count'],"startFYFQ":row["startFYFQ"],"endFYFQ":row["endFYFQ"]}
            for n in range(starting, ending_for_range):
                if row[str(n) + 'Q1'+'_x']== row["stockSymbol"]:
                    datarow.update({str(n)+'Q1':row[str(n)+'Q1'+'_y']})
                #else:
                 #   datarow.update({str(n) + 'Q1': "No filing"})
                if row[str(n) + 'Q2' + '_x'] == row["stockSymbol"]:
                    datarow.update({str(n) + 'Q2': row[str(n) + 'Q2' + '_y']})
                if row[str(n) + 'Q3' + '_x'] == row["stockSymbol"]:
                    datarow.update({str(n) + 'Q3': row[str(n) + 'Q3' + '_y']})
                if row[str(n) + 'FY' + '_x'] == row["stockSymbol"]:
                    datarow.update({str(n) + 'FY': row[str(n) + 'FY' + '_y']})
            writer.writerow(datarow)
df1 = pd.read_csv('11op.csv')
df1 = df1[df1.count_of_NULL > 0]
df1.to_csv("filter_missingfilings_only.csv")
filename1= 'issue11_missingFilingsfinalresult.csv'
with open(filename1, 'w') as csvfile:
    fieldnames=['stockSymbol','companyName','startFYFQ','endFYFQ','missingFilings']
    writer = csv.DictWriter(csvfile, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer.writeheader()

    with open('filter_missingfilings_only.csv', 'r') as csvfile1:
        reader1 = csv.DictReader(csvfile1)
        headers = reader1.fieldnames
        for row in reader1:
            FYFQ_from_csv = []
            for n in range(starting, ending_for_range):
                FYFQ_from_csv.append(row[str(n) + 'Q1'])
                FYFQ_from_csv.append(row[str(n) + 'Q2'])
                FYFQ_from_csv.append(row[str(n) + 'Q3'])
                FYFQ_from_csv.append(row[str(n) + 'FY'])
            datarow = {'stockSymbol': row["stockSymbol"],'companyName': row["companyName"],'startFYFQ': row["startFYFQ"],'endFYFQ': row["endFYFQ"]}
            skippedheader = dropwhile(lambda x: x != "2008Q1", headers)
            slicedheader = islice(skippedheader, None, 36)
            resultheader = list(slicedheader)
            a= resultheader.index(row["startFYFQ"])
            b = resultheader.index(row["endFYFQ"])
            #print(a)
            slice1 = resultheader[a:b+1]
            #print(slice)
            #print(resultheader)
            slice2=FYFQ_from_csv[a:b+1]
            #print(slice1)
            print(slice2)
            real =[]
            for i, j in enumerate(slice2):
                if j == '':
                    aaaa=1
                    abc=i
                    real.append(slice1[i]+';')

                    print(slice1[i])
            datarow.update({'missingFilings': real})
            writer.writerow(datarow)
print("completed program successfully")