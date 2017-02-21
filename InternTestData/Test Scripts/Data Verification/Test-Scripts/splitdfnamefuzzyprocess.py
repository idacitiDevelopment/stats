from fuzzywuzzy import process
import pandas as pd 
import numpy as np


tax=pd.read_csv('ClosestMatch.csv')

tax['name'] = tax['name'].map(lambda x: x.lstrip('[').rstrip(']'))
#tax['name'] =  df['name'].astype(str) + ',' 
name= tax['name']

new=[]
for i in range(0, len(tax)):
    #li=[]
    #li.append(a[i])
    l =name[i]
    split=l.split(',') #converts series to list per row
    
    #abc=s.tolist()
    #use= s.split()
    var=process.extractOne(tax.iloc[i]['Company Name'], split)
    #aa = process.extract(tax.iloc[i]['Company Name'], split, limit=2) #limit top 2 
    print(var)
    new.append(var)
se = pd.Series(new)
tax['match'] = se.values
tax.to_csv("stringmatchnew.csv")



#example
choices = ["Atlanta Falcons", "New York Jets", "New York Giants", "Dallas Cowboys"]
process.extract("new york jets", choices, limit=2)

 process.extractOne("cowboys", choices)
    