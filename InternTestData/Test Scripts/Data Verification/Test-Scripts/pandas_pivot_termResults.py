import pandas as pd
import numpy as np
df= pd.read_csv("outputTermResults_PLS_TotalDebt 20170127-093725.csv")
custom_dict = {'Q1':0, 'Q2':1, 'Q3':2,'Q4':3, 'FY':4}
df['rank'] = df['FQ'].map(custom_dict)
df.sort(columns=['stockSymbol','termName','FY','rank'],inplace=True)
df.drop("rank", axis=1, inplace=True)
df["FYFQ"]=df["FY"].map(str) + df["FQ"]
df.drop("FY", axis=1, inplace=True)
df.drop("FQ", axis=1, inplace=True)
df.drop("expression", axis=1, inplace=True)
df.drop("elementName", axis=1, inplace=True)
#print(df)
table = pd.pivot_table(df,values='value', index=['termId','entityId','stockSymbol','termName'], columns='FYFQ')
print(table)
table.to_csv("pivot_issue13.csv", index=True)
