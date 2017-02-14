import pandas
import numpy
io = pandas.read_csv('variance 20160727-095506.csv')
io = io.sort_values('industry')
io['mean'] = io.groupby('industry')['value'].transform(numpy.mean)
io['sqdiff']= abs(io['value']-io['mean'])**2
io['variance']=io.groupby('industry')['sqdiff'].transform(numpy.mean)
del io['sqdiff']
io.to_csv('variance21.csv', index=False)
print(io)