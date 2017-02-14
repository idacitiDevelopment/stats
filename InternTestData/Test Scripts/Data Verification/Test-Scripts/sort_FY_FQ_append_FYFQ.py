import config

import pandas as pd
db = config.db
b = pd.read_csv("outputTermResults_PLS_TotalDebt 20170127-093725.csv")
custom_dict = {'Q1':0, 'Q2':1, 'Q3':2,'Q4':3, 'FY':4}
b['rank'] = b['FQ'].map(custom_dict)
b.sort(columns=['stockSymbol','termName','FY','rank'],inplace=True)
b.drop("rank", axis=1, inplace=True)
b.drop("expression", axis=1, inplace=True)
b.drop("elementName", axis=1, inplace=True)

b["FYFQ"]=b["FY"].map(str) + b["FQ"]
b.to_csv("sort_append_TermResults.csv", index=False)