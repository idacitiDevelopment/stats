import config
import csv
import time
timestr = time.strftime(" %Y%m%d-%H%M%S")
db = config.db
count =0
dict1 ={}
new_dict = {}
filename = "output_issue9"+".csv"
with open(filename, 'w') as csvfile:
    fieldnames = ['entityId', 'stockSymbol']
    writer = csv.DictWriter(csvfile, fieldnames=fieldnames, delimiter=',', lineterminator='\n')
    writer.writeheader()
    with open('outputTermResults_Pinterest 20160725-150217.csv', 'r') as csvfile: #open a csv which has the fields entityId and stockSymbol
        reader = csv.DictReader(csvfile)
        for row in reader:
            dict1.update({row["entityId"]: row["stockSymbol"]})
            count+=1
            #print(count)
            for k, v in dict1.items():
                new_dict.setdefault(k, []).append(v)               #appending values for similar keys
        for key,values in new_dict.items():
            #print("The items in dict for the entityId " + key,values)
            if len(set(values)) >1:       #if entityId has more than 1 StockSymbol
                #print("The entityId "+ str(key) +" with multiple stockSymbols "+ str(values))
                count+=1
                print(count)
                datarow = {'entityId': key,'stockSymbol': set(values)}    #using set(values) to consider only unique values
                writer.writerow(datarow)