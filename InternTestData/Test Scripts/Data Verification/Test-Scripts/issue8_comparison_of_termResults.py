import config
import csv
import time
timestr = time.strftime(" %Y%m%d-%H%M%S")
db = config.db
count = 0
original = [] #original list before termRule change
duplicate = [] #list after termRule change
with open('original.csv', 'r') as csvfile1:  #read csv file before termRule change
    reader1 = csv.DictReader(csvfile1)
    for orig in reader1:
        original.append((orig["termId"], orig["entityId"], orig["FY"] , orig["FQ"], orig["termName"], orig["rank"], orig["expression"], orig["elementName"], orig["value"]))
with open('dup.csv', 'r') as csvfile2: #read csv file after termRule change
    reader2 = csv.DictReader(csvfile2)
    for dup in reader2:
        duplicate.append(( dup["termId"],dup["entityId"], dup["FY"],dup["FQ"], dup["termName"], dup["rank"], dup["expression"], dup["elementName"],dup["value"]))
filename1 = "issue8" + "output_files_changed" + timestr + ".csv" #output csv
with open(filename1, 'w') as csvfile1:
    fieldnames = ["termId","entityId","FY","FQ","termName","rank","expression","elementName","value"]
    writer = csv.DictWriter(csvfile1, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer.writeheader()
    changed_items = list(set(duplicate) - set(original))# difference between secNormalizedFacts dict and TermResults dict #comparison
    for rows in changed_items:
        print(rows)
        datarow = {"termId": rows[0], "entityId": rows[1], "FY": rows[2], "FQ": rows[3],"termName": rows[4], "rank": rows[5], "expression": rows[6],"elementName": rows[7], "value": rows[8]}
        writer.writerow(datarow)