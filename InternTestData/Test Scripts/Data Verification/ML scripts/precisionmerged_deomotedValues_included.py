import numpy as np
import pandas as pd
import matplotlib
import matplotlib.pyplot as plt
import scipy.stats as stats
import seaborn as sns
import os
import random
from numpy.random import permutation
df = pd.read_csv('Precision_merged1.csv')
#nearest neighbour
df['correct_recent'] = np.where(df['correctedValue']==df['value'], 1 ,np.where(df['demotedValues']==df['correctedValue'],0,2) ) #chenge demoted value equal to corrected value
#print(df.head())
df = df[np.isfinite(df['Multiple'])]
random_indices = permutation(df.index)
test_cutoff = np.math.floor(len(df)/3)
test = df.loc[random_indices[1:test_cutoff]]
print(test_cutoff)
train = df.loc[random_indices[test_cutoff:]]
x_columns=['demotedValues', 'value', 'Multiple']
y_column=['correct_recent'] ## Correct value is Value
from sklearn.neighbors import KNeighborsRegressor
knn = KNeighborsRegressor(n_neighbors=3) ## select nearest 5 neighbors
knn.fit(train[x_columns], train[y_column])
## Create column in the test sample from the prediction
test['predictions'] = knn.predict(test[x_columns])
#print(train.info())
test['actual'] = test[y_column]
sns.lmplot('predictions', 'actual', data=test, fit_reg=False)
from sklearn import metrics
print(pd.crosstab(test.predictions, test.actual, margins=True))
#print(pd)
#print(test)
#df.to_csv('Precision_merged5.csv')