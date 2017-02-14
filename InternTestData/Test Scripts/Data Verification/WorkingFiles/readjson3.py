import json
from deepdiff import DeepDiff
from pprint import pprint
data = []
sata =[]
count =0
nos = 0
with open('TermResults.json') as f:
    for line in f:
        data.append(json.loads(line))
with open('TermResults1.json') as f1:
    for line1 in f1:
        sata.append(json.loads(line1))
    real = {}
    datarow = {}
    for orig,dup in zip(data,sata):
        datarow.update({'_id': orig['_id'], 'termName': orig["termName"], 'entityId': orig["entityId"], 'termId': orig["termId"], 'FY': orig["FY"], 'FQ': orig["FQ"], 'value': orig["value"]})
        real.update({'_id': dup['_id'],'termName': dup["termName"], 'entityId': dup["entityId"], 'termId': dup["termId"], 'FY': dup["FY"], 'FQ': dup["FQ"], 'value': dup["value"]})
        count+=1
        nos+=1
        dddif = DeepDiff(datarow, real)
        if dddif != {}:
            pprint(dddif, indent=2)
            print(real)

print(count)
print(nos)

