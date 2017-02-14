import matplotlib
import pandas as pd
import ggplot
from ggplot import *
import matplotlib.pyplot as plt
import numpy as np
from itertools import *
df = pd.read_csv('variance21.csv',encoding = "ISO-8859-1")
#len(df['stockSymbol'].unique())
df.FY.value_counts()
df = df[df.FY > 2008]    #reading rows with FY greater than 2008
df['FY'] = df['FY'].apply(str)    
df['FYFQ'] = df[['FY', 'FQ']].apply(lambda x: ''.join(x), axis=1)    #merge FY FQ
df['Q'] = df['FQ'].replace({'Q1': 0, 'Q2': 1, 'Q3': 2, 'Q4': 3, 'FY': 4})     #replacing FQ with integers
df1 = df.sort_values(['industry','stockSymbol', 'FY', 'Q'], ascending=[True, True, True, True])      
df1.head()
#print(df1.head())
df1['qseq'] = df['FYFQ'].replace(      
    {'2009Q4': 0, '2009FY': 1, '2010Q1': 2, '2010Q2': 3, '2010Q3': 4, '2010Q4': 5, '2010FY': 6, '2011Q1': 7,
     '2011Q2': 8, '2011Q3': 9, '2011Q4': 10, '2011FY': 11, '2012Q1': 12, '2012Q2': 13, '2012Q3': 14, '2012Q4': 15,
     '2012FY': 16, '2013Q1': 17, '2013Q2': 18, '2013Q3': 19, '2013Q4': 20, '2013FY': 21, '2014Q1': 22, '2014Q2': 23,
     '2014Q3': 24, '2014Q4': 25, '2014FY': 26, '2015Q1': 27, '2015Q2': 28, '2015Q3': 29, '2015Q4': 30, '2015FY': 31,
     '2016Q1': 32, '2016Q2': 33})             #replacing FYFQ with integers
df1['qseq'].value_counts()
print(df1)
df1 = df1[df.FYFQ != "2009Q1"]
df1 = df1[df.FYFQ != "2009Q2"]
df1 = df1[df.FYFQ != "2009Q3"]   #ignoring these dates
df1 = df1[df.FYFQ != "2016FY"]
df1['qseq'] = df1['qseq'].astype(int)
#df2 = df1[df['termName'] == "Net Income"][['stockSymbol', 'FYFQ', 'qseq', 'value']]
#df2.info()
#print(df2)
#scatterplot
plt.scatter(df1['qseq'], df1['value'])            #scatter plot
plt.show()
#nicermodel
plt.figure(figsize=(10,10))
plt.scatter(df1["qseq"], df1["value"], lw=0, alpha=0.2,color='red')
plt.xlabel("FYFQ")
plt.ylabel("value")
plt.title("spread of value over FY",fontsize='15')
decade_mean = df1.groupby("industry").value.mean()         
#decade_std = df1.groupby("industry").value.std()
#decade_var = df1.groupby("industry").value.var()
print(decade_mean)
plt.plot( decade_mean.values, 'o-',
        color='r', lw=3, label='mean')      #plot value vs qseq
plt.scatter(df1["qseq"], df1["value"], alpha=0.2, lw=0, color='k')
plt.xlabel("Year")
plt.ylabel("value")
plt.legend(frameon=False)
#creating separate columns for industry
indset = set()
for m in df1["industry"]:
    indset.update(g for g in m.split("|"))
indset = sorted(indset)
#make a column for each industry
for ind in indset:
    df1[ind] = [ ind in title.split("|") for title in df1["industry"]]   
#print(df1.head())
#create a 4x6 grid of plots for 24 industries
fig, axes = plt.subplots(nrows=6, ncols=4, figsize=(75, 30), tight_layout=False)

bins = np.arange(40)   

for ax, inds in zip(axes.ravel(), indset):
    ax.hist(df1[df1[inds] == 1].qseq, bins=bins, histtype='stepfilled', normed=True, color='r', alpha=.3, ec='none')
    ax.hist(df1.qseq, bins=bins, histtype='stepfilled', ec='None', normed=True, zorder=0, color='#cccccc')
    my_xticks = ('2009Q4', '2009FY', '2010Q1', '2010Q2', '2010Q3', '2010Q4', '2010FY', '2011Q1',
     '2011Q2', '2011Q3', '2011Q4', '2011FY', '2012Q1', '2012Q2', '2012Q3', '2012Q4',
     '2012FY', '2013Q1', '2013Q2', '2013Q3', '2013Q4', '2013FY', '2014Q1', '2014Q2',
     '2014Q3', '2014Q4', '2014FY', '2015Q1', '2015Q2', '2015Q3', '2015Q4', '2015FY',
     '2016Q1', '2016Q2')
    #plt.xticks(np.arange(40), my_xticks,rotation='vertical')
    ax.xaxis.set_ticks(np.arange(34))
    ax.set_xticklabels(my_xticks,rotation='45')
    ax.set_yticks([])
    #ax.yaxis.set_ticks(np.arange(0,))
    #remove_border(ax, left=False)
    ax.set_xlabel('FYFQ')
    ax.set_ylabel('value')
    ax.annotate(inds,xy=(0,4e-2), horizontalalignment='left', verticalalignment='top',fontsize=12)
    ax.annotate(inds,xy=(0,4e-2), horizontalalignment='left', verticalalignment='top',fontsize=12)
    print(ind)


