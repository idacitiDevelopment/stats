import matplotlib
import pandas as pd
import ggplot
from ggplot import *
import matplotlib.pyplot as plt
import numpy as np
from itertools import *
#enter industry name

industryName = "Chemical & Allied Products"

#read csv
df = pd.read_csv('variance21.csv')
df.FY.value_counts()
#for FY greater than 2008
df = df[df.FY > 2008]
df['FY'] = df['FY'].apply(str)
df['FYFQ'] = df[['FY', 'FQ']].apply(lambda x: ''.join(x), axis=1)
df['Q'] = df['FQ'].replace({'Q1': 0, 'Q2': 1, 'Q3': 2, 'Q4': 3, 'FY': 4})
df1 = df.sort_values(['industry','stockSymbol', 'FY', 'Q'], ascending=[True, True, True, True])
#assign integer values for FYFQ
df1['qseq'] = df['FYFQ'].replace(
    {'2009Q4': 0, '2009FY': 1, '2010Q1': 2, '2010Q2': 3, '2010Q3': 4, '2010Q4': 5, '2010FY': 6, '2011Q1': 7,
     '2011Q2': 8, '2011Q3': 9, '2011Q4': 10, '2011FY': 11, '2012Q1': 12, '2012Q2': 13, '2012Q3': 14, '2012Q4': 15,
     '2012FY': 16, '2013Q1': 17, '2013Q2': 18, '2013Q3': 19, '2013Q4': 20, '2013FY': 21, '2014Q1': 22, '2014Q2': 23,
     '2014Q3': 24, '2014Q4': 25, '2014FY': 26, '2015Q1': 27, '2015Q2': 28, '2015Q3': 29, '2015Q4': 30, '2015FY': 31,
     '2016Q1': 32, '2016Q2': 33})
#ignoring other values from results
df1 = df1[df.FYFQ != "2009Q1"]
df1 = df1[df.FYFQ != "2009Q2"]
df1 = df1[df.FYFQ != "2009Q3"]
df1 = df1[df.FYFQ != "2016FY"]
#converting  array to int
df1['qseq'] = df1['qseq'].astype(int)


#Sorting by industry and then stockSymbol
df1.sort_values(['industry','stockSymbol'], inplace = True)

#filter documents by industry
df1 = df1[df1.industry == industryName ]

#iterating over array
for index, row in df1.iterrows():
    print(row["stockSymbol"])

#dictionary
stock = set()
for m in df1["stockSymbol"]:
    stock.update(g for g in m.split("|"))
for symbol in stock:
    df1[symbol] = [ symbol in title.split("|") for title in df1["stockSymbol"]]

#counting unique stock symbols
count =len(df1['stockSymbol'].unique())
print("unique stock symbols=" + str(count))
print(df1)
#subplot
fig, axes = plt.subplots(nrows=count, ncols=1, figsize=(40, 25), tight_layout=False)

bins = np.arange(36)
#subplot for each stocksymbol
for ax, stSymbol in zip(axes.ravel(), stock):
    ax.hist(df1[df1[stSymbol] == 1].qseq, bins=bins, histtype='stepfilled', normed=True, color='r', alpha=.3, ec='none')
    ax.hist(df1.qseq, bins=bins, histtype='stepfilled', ec='None', normed=True, zorder=0, color='#cccccc')
    my_xticks = ('2009Q4', '2009FY', '2010Q1', '2010Q2', '2010Q3', '2010Q4', '2010FY', '2011Q1',
     '2011Q2', '2011Q3', '2011Q4', '2011FY', '2012Q1', '2012Q2', '2012Q3', '2012Q4',
     '2012FY', '2013Q1', '2013Q2', '2013Q3', '2013Q4', '2013FY', '2014Q1', '2014Q2',
     '2014Q3', '2014Q4', '2014FY', '2015Q1', '2015Q2', '2015Q3', '2015Q4', '2015FY',
     '2016Q1', '2016Q2')
    ax.xaxis.set_ticks(np.arange(34))
    #assigning labels to x axis
    ax.set_xticklabels(my_xticks,rotation='45')
    ax.set_yticks([])
    ax.set_xlabel('FYFQ')
    ax.set_ylabel('value')
    ax.annotate(stSymbol,xy=(0,4e-2), horizontalalignment='left', verticalalignment='top',fontsize=12)

